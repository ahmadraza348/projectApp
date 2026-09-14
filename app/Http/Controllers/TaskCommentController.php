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
        toastr()->success('Comment added.');
        return redirect()->back();
    }

    public function destroy(TaskComment $comment)
    {
        $comment->delete();
        toastr()->success('Comment deleted.');
        return redirect()->back();
    }
}
