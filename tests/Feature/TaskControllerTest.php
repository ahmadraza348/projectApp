<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_view_task_index(): void
    {
        // Unlike Category/Project, the tasks section allows members too —
        // TaskService scopes the list to their own tasks internally.
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get(route('task.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
    }

    public function test_member_cannot_create_task(): void
    {
        // TaskPolicy::create() only allows admin/manager.
        $member = User::factory()->create(['role' => 'member']);
        $project = Project::factory()->create();

        $response = $this->actingAs($member)->post(route('task.store'), [
            'project_id' => $project->id,
            'title' => 'Should be rejected',
            'priority' => 'low',
        ]);

        $response->assertStatus(403);
    }

    public function test_manager_can_create_task(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create();

        $response = $this->actingAs($manager)->post(route('task.store'), [
            'project_id' => $project->id,
            'title' => 'Set up CI pipeline',
            'priority' => 'high',
            'status' => 'todo',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Task created successfully!');
        $this->assertDatabaseHas('tasks', ['title' => 'Set up CI pipeline']);
    }

    public function test_store_fails_when_priority_is_invalid(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create();

        $response = $this->actingAs($manager)->post(route('task.store'), [
            'project_id' => $project->id,
            'title' => 'Bad priority',
            'priority' => 'not-a-real-priority',
        ]);

        $response->assertSessionHasErrors('priority');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_member_cannot_view_a_task_assigned_to_someone_else(): void
    {
        // TaskPolicy::view() restricts members to their own tasks.
        $member = User::factory()->create(['role' => 'member']);
        $someoneElse = User::factory()->create(['role' => 'member']);
        $othersTask = Task::factory()->create(['member_id' => $someoneElse->id]);

        $response = $this->actingAs($member)->get(route('task.show', $othersTask));

        $response->assertStatus(403);
    }

    public function test_member_can_view_their_own_task(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $ownTask = Task::factory()->create(['member_id' => $member->id]);

        $response = $this->actingAs($member)->get(route('task.show', $ownTask));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.show');
    }

    public function test_member_cannot_delete_even_their_own_task(): void
    {
        // TaskPolicy::delete() is admin/manager only — ownership doesn't grant delete rights.
        $member = User::factory()->create(['role' => 'member']);
        $ownTask = Task::factory()->create(['member_id' => $member->id]);

        $response = $this->actingAs($member)->delete(route('task.destroy', $ownTask));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $ownTask->id]);
    }

    public function test_admin_can_delete_task(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $task = Task::factory()->create();

        $response = $this->actingAs($admin)->delete(route('task.destroy', $task));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Task deleted successfully!');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_member_can_update_status_on_their_own_task(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create(['member_id' => $member->id, 'status' => 'todo']);

        $response = $this->actingAs($member)->patch(route('task.update-status', $task), [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'in_progress']);
    }
}