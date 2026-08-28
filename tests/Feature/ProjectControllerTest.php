<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_cannot_access_project_index(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get(route('project.index'));

        $response->assertStatus(403);
    }

    public function test_manager_can_view_project_index(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        Project::factory()->count(2)->create();

        $response = $this->actingAs($manager)->get(route('project.index'));

        $response->assertStatus(200);
        $response->assertViewIs('projects.index');
    }

    public function test_store_creates_new_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $payload = [
            'name' => 'Website Revamp',
            'description' => 'Rebuild the marketing site',
            'category_id' => $category->id,
            'status' => 'planning',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'budget' => 5000,
        ];

        $response = $this->actingAs($admin)->post(route('project.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Project created successfully!');
        $this->assertDatabaseHas('projects', [
            'name' => 'Website Revamp',
            'category_id' => $category->id,
        ]);
    }

    public function test_store_fails_when_category_does_not_exist(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('project.store'), [
            'name' => 'No Category Project',
            'category_id' => 999,
            'status' => 'planning',
        ]);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseCount('projects', 0);
    }

    public function test_update_modifies_existing_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $project = Project::factory()->create(['category_id' => $category->id, 'status' => 'planning']);

        $response = $this->actingAs($admin)->put(route('project.update', $project), [
            'name' => $project->name,
            'category_id' => $category->id,
            'status' => 'in_progress',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Project updated successfully!');
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'status' => 'in_progress']);
    }

    public function test_destroy_deletes_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();

        $response = $this->actingAs($admin)->delete(route('project.destroy', $project));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Project Deleted successfully!');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}