<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskAttachmentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        $request->validate([
            'attachment'   => ['required', 'array'],
            'attachment.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,docx,txt,zip'],
        ]);

        foreach ($request->file('attachment') as $file) {
            $path = $file->store('task-attachments', 'public');

            $task->attachments()->create([
                'user_id'       => auth()->id(),
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'size'          => $file->getSize(),
            ]);
        }

        return back()->with('success', 'File(s) uploaded.');
    }

    public function destroy(TaskAttachment $attachment)
    {
        $this->authorize('view', $attachment->task);
        abort_unless($attachment->user_id === auth()->id() || in_array(auth()->user()->role, ['admin', 'manager']), 403);
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Attachment deleted.');
    }
}
