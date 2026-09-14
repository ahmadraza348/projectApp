<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Task $task, public User $assignedBy)
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return[
           'type' =>'task_assigned',
           'task_id' => $this->task->id,
           'task_title' => $this->task->title,
           'task_link' => route('task.show', $this->task->id),
           'assigned_by' => $this->assignedBy->name,
           'message' => 'You have been assigned a new task: ' . $this->task->title,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'task_assigned',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_link' => route('task.show', $this->task->id),
            'assigned_by' => $this->assignedBy->name,
            'message' => 'You have been assigned a new task: ' . $this->task->title,
        ]);
    }

}
