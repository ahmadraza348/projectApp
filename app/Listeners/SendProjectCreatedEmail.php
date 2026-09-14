<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Jobs\SendProjectCreatedEmailJob;

class SendProjectCreatedEmail
{
    public function handle(ProjectCreated $event): void
    {
        $project = $event->project;
        $project->load('members');

        foreach ($project->members as $member) {

            SendProjectCreatedEmailJob::dispatch(
                $project,
                $member
            );
        }
    }
}