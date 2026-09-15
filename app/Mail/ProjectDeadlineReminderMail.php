<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectDeadlineReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Project $project,
        public User $member
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Project Deadline Reminder - ' . $this->project->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.projects.deadline-reminder',
        );
    }
}