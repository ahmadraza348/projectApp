<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCommentResource;
use App\Models\Task;
use App\Models\TaskComment;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * List comments for a task.
     */
    public function index(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $comments = $task->comments()->with('user')->get();

        return $this->successResponse(TaskCommentResource::collection($comments), 'Comments fetched successfully.');
    }

    /**
     * Add a comment to a task.
     */
    public function store(Request $request, Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $data = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $comment = $task->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        return $this->successResponse(new TaskCommentResource($comment->load('user')), 'Comment added.', 201);
    }

    /**
     * Delete a comment. Only the comment's author or an admin/manager may delete it.
     */
    public function destroy(Request $request, TaskComment $comment): JsonResponse
    {
        $this->authorize('view', $comment->task);

        $canDelete = $comment->user_id === $request->user()->id
            || in_array($request->user()->role, ['admin', 'manager']);

        if (! $canDelete) {
            return $this->errorResponse('You are not allowed to delete this comment.', 403);
        }

        $comment->delete();

        return $this->successResponse(null, 'Comment deleted.');
    }
}
