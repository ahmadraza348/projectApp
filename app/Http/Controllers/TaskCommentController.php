<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskCommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        $data = $request->validate(['body' => ['required', 'string']]);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $data['body'],
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function destroy(TaskComment $comment)
    {
        $this->authorize('view', $comment->task);
        abort_unless($comment->user_id === auth()->id() || in_array(auth()->user()->role, ['admin', 'manager']), 403);
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
