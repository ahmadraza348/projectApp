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

        toastr()->success('Comment added.');
        return redirect()->back();
    }

    public function destroy(TaskComment $comment)
    {
        $this->authorize('view', $comment->task);
        abort_unless($comment->user_id === auth()->id() || in_array(auth()->user()->role, ['admin', 'manager']), 403);
        $comment->delete();
        toastr()->success('Comment deleted.');
        return redirect()->back();
    }
}
