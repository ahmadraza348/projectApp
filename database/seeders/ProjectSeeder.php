<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::factory(10)->create()->each(function (Project $project) {
            // attach 2-5 random users as project members via the project_user pivot
            $memberIds = User::inRandomOrder()->take(rand(2, 5))->pluck('id');
            $project->members()->sync($memberIds);
        });
    }
}
