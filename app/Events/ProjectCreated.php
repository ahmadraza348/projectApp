<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;

class ProjectCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct(public Project $project) {}
}
