<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskTimeLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTimeLogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_log_hours_on_a_task(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create();

        $response = $this->actingAs($member)->post(route('task.time-logs.store', $task), [
            'hours' => 2.5,
            'logged_at' => now()->toDateString(),
            'description' => 'Fixed the login bug',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Time logged.');
        $this->assertDatabaseHas('task_time_logs', [
            'task_id' => $task->id,
            'user_id' => $member->id,
            'hours' => 2.5,
        ]);
    }

    public function test_hours_cannot_exceed_24(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create();

        $response = $this->actingAs($member)->post(route('task.time-logs.store', $task), [
            'hours' => 30,
            'logged_at' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('hours');
        $this->assertDatabaseCount('task_time_logs', 0);
    }

    public function test_authenticated_user_can_delete_a_time_log(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $timeLog = TaskTimeLog::factory()->create();

        $response = $this->actingAs($member)->delete(route('task.time-logs.destroy', $timeLog));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Time log removed.');
        $this->assertDatabaseMissing('task_time_logs', ['id' => $timeLog->id]);
    }
}
