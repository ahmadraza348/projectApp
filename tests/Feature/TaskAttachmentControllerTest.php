<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskAttachmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_an_attachment(): void
    {
        Storage::fake('public');
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create();

        $file = UploadedFile::fake()->create('spec.pdf', 500, 'application/pdf');

        $response = $this->actingAs($member)->post(route('task.attachments.store', $task), [
            'attachment' => [$file],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'File(s) uploaded.');
        $this->assertDatabaseHas('task_attachments', [
            'task_id' => $task->id,
            'original_name' => 'spec.pdf',
        ]);

        $attachment = TaskAttachment::first();
        Storage::disk('public')->assertExists($attachment->path);
    }

    public function test_upload_rejects_disallowed_file_types(): void
    {
        Storage::fake('public');
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create();

        $file = UploadedFile::fake()->create('script.exe', 100, 'application/x-msdownload');

        $response = $this->actingAs($member)->post(route('task.attachments.store', $task), [
            'attachment' => [$file],
        ]);

        $response->assertSessionHasErrors('attachment.0');
        $this->assertDatabaseCount('task_attachments', 0);
    }

    public function test_authenticated_user_can_delete_an_attachment(): void
    {
        Storage::fake('public');
        $member = User::factory()->create(['role' => 'member']);
        $task = Task::factory()->create();

        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'user_id' => $member->id,
            'original_name' => 'notes.txt',
            'path' => 'task-attachments/notes.txt',
            'size' => 120,
        ]);
        Storage::disk('public')->put($attachment->path, 'dummy content');

        $response = $this->actingAs($member)->delete(route('task.attachments.destroy', $attachment));

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Attachment deleted.');
        $this->assertDatabaseMissing('task_attachments', ['id' => $attachment->id]);
        Storage::disk('public')->assertMissing($attachment->path);
    }
}
