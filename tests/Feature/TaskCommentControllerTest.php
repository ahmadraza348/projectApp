<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_a_comment_to_a_task(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create();

        $response = $this->actingAs($member)->post(route('task.comments.store', $task), [
            'body' => 'This is blocked on the API keys.',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Comment added.');
        $this->assertDatabaseHas('task_comments', [
            'task_id' => $task->id,
            'user_id' => $member->id,
            'body' => 'This is blocked on the API keys.',
        ]);
    }

    public function test_comment_body_is_required(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create();

        $response = $this->actingAs($member)->post(route('task.comments.store', $task), [
            'body' => '',
        ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseCount('task_comments', 0);
    }

    public function test_authenticated_user_can_delete_a_comment(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $comment = TaskComment::factory()->create();

        $response = $this->actingAs($member)->delete(route('task.comments.destroy', $comment));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Comment deleted.');
        $this->assertDatabaseMissing('task_comments', ['id' => $comment->id]);
    }
}
