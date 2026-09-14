<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectCreatedMail;

class SendProjectCreatedEmail
{

    public function handle(ProjectCreated $event): void
    {
        $project = $event->project;
        $project->load('members');

        foreach ($project->members as $member) {
           Mail::to($member->email)->send(new ProjectCreatedMail($project, $member));
        }
    }
}
