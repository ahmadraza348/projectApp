<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskTimeLog;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskTimeLogController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Task $task)
    {
        $this->authorize('view', $task);
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
        $this->authorize('view', $timeLog->task);
        abort_unless($timeLog->user_id === auth()->id() || in_array(auth()->user()->role, ['admin', 'manager']), 403);
        $timeLog->delete();
        return back()->with('success', 'Time log removed.');
    }
}
