<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskTimeLogResource;
use App\Models\Task;
use App\Models\TaskTimeLog;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskTimeLogController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * List time logs for a task.
     */
    public function index(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $timeLogs = $task->timeLogs()->with('user')->get();

        return $this->successResponse(TaskTimeLogResource::collection($timeLogs), 'Time logs fetched successfully.');
    }

    /**
     * Log time against a task.
     */
    public function store(Request $request, Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $data = $request->validate([
            'hours'       => ['required', 'numeric', 'min:0.25', 'max:24'],
            'logged_at'   => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $timeLog = $task->timeLogs()->create($data + ['user_id' => $request->user()->id]);

        return $this->successResponse(new TaskTimeLogResource($timeLog->load('user')), 'Time logged.', 201);
    }

    /**
     * Delete a time log. Only the log's author or an admin/manager may delete it.
     */
    public function destroy(Request $request, TaskTimeLog $timeLog): JsonResponse
    {
        $this->authorize('view', $timeLog->task);

        $canDelete = $timeLog->user_id === $request->user()->id
            || in_array($request->user()->role, ['admin', 'manager']);

        if (! $canDelete) {
            return $this->errorResponse('You are not allowed to delete this time log.', 403);
        }

        $timeLog->delete();

        return $this->successResponse(null, 'Time log removed.');
    }
}
