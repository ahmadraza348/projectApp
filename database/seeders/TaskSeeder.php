<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Project::with('members')->get()->each(function (Project $project) {
            Task::factory(rand(4, 8))->create([
                'project_id' => $project->id,
                // pick the assignee from the project's real members instead of any random user
                'member_id' => $project->members->isNotEmpty() ? $project->members->random()->id : null,
            ])->each(function (Task $task) use ($project) {
                $commenters = $project->members->isNotEmpty() ? $project->members : \App\Models\User::inRandomOrder()->take(2)->get();

                // 0-4 comments per task
                \App\Models\TaskComment::factory(rand(0, 4))->create([
                    'task_id' => $task->id,
                    'user_id' => $commenters->random()->id,
                ]);

                // 0-3 time log entries per task
                \App\Models\TaskTimeLog::factory(rand(0, 3))->create([
                    'task_id' => $task->id,
                    'user_id' => $commenters->random()->id,
                ]);
            });
        });
    }
}
