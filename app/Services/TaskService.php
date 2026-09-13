<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Notifications\ActivityNotification;

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
        $this->notifyProjectParticipants($task, 'A new task was created: ' . $task->title);
        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        $this->notifyProjectParticipants($task, 'Task updated: ' . $task->title);
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

        return $query->latest()->paginate(10)->withQueryString();
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);
        $this->notifyProjectParticipants($task, 'Task status changed: ' . $task->title);
        return $task;
    }

    private function notifyProjectParticipants(Task $task, string $message): void
    {
        $task->loadMissing(['project.members', 'assignee']);
        $users = $task->project->members
            ->concat($task->assignee ? collect([$task->assignee]) : collect())
            ->unique('id')
            ->reject(fn ($user) => $user->id === auth()->id());

        foreach ($users as $user) {
            $user->notify(new ActivityNotification($message, route('task.show', $task)));
        }
    }
}
