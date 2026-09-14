<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    protected $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $this->service->fetchTasks($request); // already scoped to "own tasks" for members
        $projects = $this->service->getProjectsForSelection();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Task::class); // members can't create

        $projects = $this->service->getProjectsForSelection();
        $selectedProjectId = $request->query('project_id');

        return view('tasks.create', compact('projects', 'selectedProjectId'));
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $this->service->store($request->validated());
        toastr()->success('Task created successfully!');

        return redirect()->route('task.index');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task); // member blocked unless it's their own task

        $task->load(['project', 'assignee', 'comments', 'timeLogs', 'attachments']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $task->load(['project', 'assignee']);
        $projects = $this->service->getProjectsForSelection();

        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $this->service->update($task, $request->validated());

        toastr()->success('Task updated successfully!');
        return redirect()->route('task.index');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task); // members can't delete, even their own

        $this->service->delete($task);

        toastr()->success('Task deleted successfully!');
        return redirect()->route('task.index');
    }

    public function getMembers(Project $project)
    {
        return response()->json($project->members);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('updateStatus', $task);
        $validated = $request->validate(['status' => 'required|in:todo,in_progress,review,completed',]);
        $this->service->updateStatus($task, $validated['status']);
        toastr()->success('Task status updated successfully!');
        return response()->json(['success' => true,]);
    }
}
