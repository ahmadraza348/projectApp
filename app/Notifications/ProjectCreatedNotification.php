<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Project;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProjectCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Project $project)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'project_created',
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'project_link' => route('project.show', $this->project->id),
            'message' => 'A new project has been created: ' . $this->project->name,
        ];
    }
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage(
            [
                'type' => 'project_created',
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'project_link' => route('project.show', $this->project->id),
                'message' => 'A new project has been created: ' . $this->project->name,
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
