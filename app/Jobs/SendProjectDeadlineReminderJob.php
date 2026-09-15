<?php

namespace App\Jobs;

use App\Mail\ProjectDeadlineReminderMail;
use App\Models\Project;
use App\Notifications\ProjectDeadlineReminderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendProjectDeadlineReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Project $project
    ) {
    }

    public function handle(): void
    {
        $this->project->load('members');

        foreach ($this->project->members as $member) {

            // In-app notification
            $member->notify(
                new ProjectDeadlineReminderNotification(
                    $this->project
                )
            );

            // Email
            Mail::to($member->email)->send(
                new ProjectDeadlineReminderMail(
                    $this->project,
                    $member
                )
            );
        }
    }
}