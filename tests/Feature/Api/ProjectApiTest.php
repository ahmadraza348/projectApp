<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_projects(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Project::factory()->count(2)->create();
        Passport::actingAs($admin);

        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data.data');
    }

    public function test_admin_can_create_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        Passport::actingAs($admin);

        $payload = [
            'name' => 'Mobile App Launch',
            'description' => 'Ship v1 of the mobile app',
            'category_id' => $category->id,
            'status' => 'planning',
            'budget' => 12000,
        ];

        $response = $this->postJson('/api/v1/projects', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'Mobile App Launch');
        $this->assertDatabaseHas('projects', [
            'name' => 'Mobile App Launch',
            'category_id' => $category->id,
        ]);
    }

    public function test_create_project_fails_when_end_date_before_start_date(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        Passport::actingAs($admin);

        $response = $this->postJson('/api/v1/projects', [
            'name' => 'Bad Dates Project',
            'category_id' => $category->id,
            'status' => 'planning',
            'start_date' => '2026-05-10',
            'end_date' => '2026-05-01',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('end_date');
    }

    public function test_admin_can_view_single_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        Passport::actingAs($admin);

        $response = $this->getJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $project->id);
    }

    public function test_admin_can_update_project_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['status' => 'planning']);
        Passport::actingAs($admin);

        $response = $this->putJson("/api/v1/projects/{$project->id}", [
            'name' => $project->name,
            'category_id' => $project->category_id,
            'status' => 'complete',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'status' => 'complete']);
    }

    public function test_admin_can_delete_project(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();
        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_member_can_add_member_to_project_route_is_blocked_for_members(): void
    {
        // Categories/Projects group is restricted to admin,manager — a member
        // must be rejected before it ever reaches the controller.
        $member = User::factory()->create(['role' => 'member']);
        $project = Project::factory()->create();
        Passport::actingAs($member);

        $response = $this->postJson("/api/v1/projects/{$project->id}/members", [
            'user_id' => $member->id,
        ]);

        $response->assertStatus(403);
    }
}