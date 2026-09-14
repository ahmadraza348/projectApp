<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use App\Notifications\TaskCompletedNotification;

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
        if (!empty($task->member_id)) {

            $assignee = User::find($task->member_id);

            if ($assignee) {
                $assignee->notify(
                    new TaskAssignedNotification(
                        $task,
                        auth()->user()
                    )
                );
            }
        }
        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        if ($task->wasChanged('member_id') && !empty($task->member_id)) {

            $assignee = User::find($task->member_id);

            if ($assignee) {
                $assignee->notify(
                    new TaskAssignedNotification(
                        $task,
                        auth()->user()
                    )
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
        $oldStatus = $task->status;

        $task->update([
            'status' => $status,
        ]);

        // Send notification only when task changes to completed
        if ($oldStatus !== 'completed' && $status === 'completed') {

            $usersToNotify = User::whereIn('role', ['admin', 'manager'])
                ->where('id', '!=', auth()->id())
                ->get();

            foreach ($usersToNotify as $user) {
                $user->notify(
                    new TaskCompletedNotification(
                        $task,
                        auth()->user()
                    )
                );
            }
        }

        return $task;
    }
}
