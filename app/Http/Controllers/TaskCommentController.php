<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $data = $request->validate(['body' => ['required', 'string']]);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $data['body'],
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function destroy(TaskComment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
