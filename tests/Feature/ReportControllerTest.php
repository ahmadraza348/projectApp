<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_cannot_access_reports(): void
    {
        // Reports route is restricted to role:admin only.
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)->get(route('report.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_reports_with_aggregated_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Project::factory()->count(2)->create();
        Task::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get(route('report.index'));

        $response->assertStatus(200);
        $response->assertViewIs('reports');
        $response->assertViewHas('data');
    }
}
