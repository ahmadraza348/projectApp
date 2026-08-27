<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

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
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
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
            $query->where('member_id', auth()->id());
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

        return $query->latest()->get();
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);
        return $task;
    }
}
