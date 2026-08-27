<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskAttachmentResource;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskAttachmentController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * List attachments for a task.
     */
    public function index(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return $this->successResponse(TaskAttachmentResource::collection($task->attachments), 'Attachments fetched successfully.');
    }

    /**
     * Upload one or more attachments to a task.
     */
    public function store(Request $request, Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $request->validate([
            'attachment'   => ['required', 'array'],
            'attachment.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,docx,txt,zip'],
        ]);

        $attachments = collect($request->file('attachment'))->map(function ($file) use ($task, $request) {
            $path = $file->store('task-attachments', 'public');

            return $task->attachments()->create([
                'user_id'       => $request->user()->id,
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'size'          => $file->getSize(),
            ]);
        });

        return $this->successResponse(TaskAttachmentResource::collection($attachments), 'File(s) uploaded.', 201);
    }

    /**
     * Delete an attachment. Only the uploader or an admin/manager may delete it.
     */
    public function destroy(Request $request, TaskAttachment $attachment): JsonResponse
    {
        $this->authorize('view', $attachment->task);

        $canDelete = $attachment->user_id === $request->user()->id
            || in_array($request->user()->role, ['admin', 'manager']);

        if (! $canDelete) {
            return $this->errorResponse('You are not allowed to delete this attachment.', 403);
        }

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return $this->successResponse(null, 'Attachment deleted.');
    }
}
