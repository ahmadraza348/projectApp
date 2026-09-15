<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ProjectDeadlineReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Project $project
    ) {
    }

    public function via(object $notifiable): array
    {
        return [
            'database',
            'broadcast',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'project_deadline_reminder',
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'project_link' => route('project.show', $this->project->id),
            'message' => 'Project "' . $this->project->name . '" is due in 3 days.',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'project_deadline_reminder',
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'project_link' => route('project.show', $this->project->id),
            'message' => 'Project "' . $this->project->name . '" is due in 3 days.',
        ]);
    }
}