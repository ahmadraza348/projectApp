<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_task(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        Passport::actingAs($admin);

        $payload = [
            'project_id' => $project->id,
            'title' => 'Design the login screen',
            'priority' => 'high',
            'status' => 'todo',
        ];

        $response = $this->postJson('/api/v1/tasks', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('data.title', 'Design the login screen');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Design the login screen',
            'project_id' => $project->id,
        ]);
    }

    public function test_create_task_fails_when_priority_is_invalid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        Passport::actingAs($admin);

        $response = $this->postJson('/api/v1/tasks', [
            'project_id' => $project->id,
            'title' => 'Bad priority task',
            'priority' => 'super-urgent', // not in the allowed enum
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('priority');
    }

    public function test_member_cannot_create_task(): void
    {
        // TaskPolicy::create() only allows admin/manager.
        $member = User::factory()->create(['role' => 'member']);
        $project = Project::factory()->create();
        Passport::actingAs($member);

        $response = $this->postJson('/api/v1/tasks', [
            'project_id' => $project->id,
            'title' => 'Should be rejected',
            'priority' => 'low',
        ]);

        $response->assertStatus(403);
    }

    public function test_member_can_only_view_their_own_task(): void
    {
        // TaskPolicy::view() restricts members to tasks assigned to them.
        $member = User::factory()->create(['role' => 'member']);
        $someoneElse = User::factory()->create(['role' => 'member']);
        $othersTask = Task::factory()->create(['member_id' => $someoneElse->id]);
        Passport::actingAs($member);

        $response = $this->getJson("/api/v1/tasks/{$othersTask->id}");

        $response->assertStatus(403);
    }

    public function test_admin_can_update_task_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $task = Task::factory()->create(['status' => 'todo']);
        Passport::actingAs($admin);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/status", [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.status', 'in_progress');
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'in_progress']);
    }

    public function test_admin_can_delete_task(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $task = Task::factory()->create();
        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}