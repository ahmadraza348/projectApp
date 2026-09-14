<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectCreatedMail;
use App\Models\Project;
use App\Models\User;

class SendProjectCreatedEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Project $project,
        public User $member
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->member->email)->send(new ProjectCreatedMail($this->project, $this->member));
    }
}
