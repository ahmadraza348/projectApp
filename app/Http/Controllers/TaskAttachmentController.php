<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskAttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
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
        toastr()->success('File(s) uploaded successfully');
        return redirect()->back();
    }

    public function destroy(TaskAttachment $attachment)
    {
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        toastr()->success('Attachment deleted successfully');
        return redirect()->back();
    }
}
