<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskCompletedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Task $task, public User $completedBy)
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
        return [
            'type' => 'task_completed',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_link' => route('task.show', $this->task->id),
            'completed_by' => $this->completedBy->name,
            'message' => 'Task "' . $this->task->title . '" has been marked as completed by ' . $this->completedBy->name,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'task_completed',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_link' => route('task.show', $this->task->id),
            'completed_by' => $this->completedBy->name,
            'message' => 'Task "' . $this->task->title . '" has been marked as completed by ' . $this->completedBy->name,
        ]);
    }
 
}
