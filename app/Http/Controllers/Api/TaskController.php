<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    public function __construct(protected TaskService $service) {}

    /**
     * Display a listing of tasks.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $this->service->fetchTasks($request);
        $projects = $this->service->getProjectsForSelection();

        return $this->successResponse([
            'tasks'    => TaskResource::collection($tasks)->response()->getData(true),
            'projects' => $projects,
        ], 'Tasks fetched successfully.');
    }

    /**
     * Store a newly created task.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);

        $task = $this->service->store($request->validated());
        
        // Eager load relations so the newly created task data is complete in the JSON response
        $task->load(['project', 'assignee']);

        return $this->successResponse(
            new TaskResource($task), 
            'Task created successfully.', 
            201
        );
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        // Load deep relationships for the single view page
        $task->load(['project', 'assignee', 'comments.user', 'timeLogs.user', 'attachments']);

        return $this->successResponse(
            new TaskResource($task), 
            'Task details fetched successfully.'
        );
    }

    /**
     * Update the specified task.
     */
    public function update(TaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $updatedTask = $this->service->update($task, $request->validated());
        $updatedTask->load(['project', 'assignee']);

        return $this->successResponse(
            new TaskResource($updatedTask), 
            'Task updated successfully.'
        );
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->service->delete($task);

        return $this->successResponse(null, 'Task deleted successfully.');
    }

    /**
     * Custom endpoint for updating just the status (e.g., Kanban drag & drop).
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        // Lightweight validation just for the status update
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,completed'
        ]);

        $updatedTask = $this->service->updateStatus($task, $validated['status']);
        $updatedTask->load(['project', 'assignee']);

        return $this->successResponse(
            new TaskResource($updatedTask), 
            'Task status updated successfully.'
        );
    }
}