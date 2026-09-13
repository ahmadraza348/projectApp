<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;

class TaskService
{
    public function getProjectsForSelection()
    {
        return Project::select('id', 'name')->latest()->get();
    }

    public function getProjectMembers(int $projectId)
    {
        $project = Project::with('members:id,name,role')->findOrFail($projectId);
        return $project->members;
    }

    public function store(array $data): Task
    {
        $data['status'] = $data['status'] ?? 'todo';
        $task = Task::create($data);

        // Send notification when a task is created with an assignee 
        if (!empty($task->assigned_to)) {
            $assignee = User::find($task->assigned_to);
            if ($assignee) {
                $assignee->notify(
                    new TaskAssignedNotification($task, auth()->user())
                );
            }
        }
        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data); // Notify only when the assignee has changed 
        if ($task->wasChanged('assigned_to') && !empty($task->assigned_to)) {
            $assignee = User::find($task->assigned_to);
            if ($assignee) {
                $assignee->notify(
                    new TaskAssignedNotification($task, auth()->user())
                );
            }
        }
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function fetchTasks(Request $request)
    {
        $query = Task::with(['project', 'assignee']);

        // Members only ever see tasks assigned to them; admin/manager see everything
        if (auth()->user()->role === 'member') {
            $query->where('assigned_to', auth()->id());
        }
        if ($request) {
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
        }

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);
        return $task;
    }
}
