<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ProjectCreatedNotification;

class SendProjectCreatedNotification
{


    public function handle(ProjectCreated $event): void
    {
        $project = $event->project;
        $project->load('members');

          foreach ($project->members as $member) {
            $member->notify(
                new ProjectCreatedNotification($project)
            );
        }

    }
}
