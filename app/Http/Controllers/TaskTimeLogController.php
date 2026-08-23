<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskTimeLog;
use Illuminate\Http\Request;

class TaskTimeLogController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $data = $request->validate([
            'hours'       => ['required', 'numeric', 'min:0.25', 'max:24'],
            'logged_at'   => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $task->timeLogs()->create($data + ['user_id' => auth()->id()]);

        return back()->with('success', 'Time logged.');
    }

    public function destroy(TaskTimeLog $timeLog)
    {
        $timeLog->delete();
        return back()->with('success', 'Time log removed.');
    }
}
