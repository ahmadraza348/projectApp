
<?php

use App\Models\Project;
use App\Jobs\SendProjectDeadlineReminderJob;
use Illuminate\Support\Facades\Schedule;

// Schedule::call(function () {

//     Project::query()
//         ->where('status', '!=', 'complete')
//         ->whereDate('end_date', now()->addDay())
//         ->each(function ($project) {

//             SendProjectDeadlineReminderJob::dispatch($project);

//         });

// })->everyMinute();

Schedule::call(function () {

    Project::query()
        ->where('status', '!=', 'complete')
        ->whereDate('end_date', now()->addDays(3))
        ->each(function ($project) {

            SendProjectDeadlineReminderJob::dispatch($project);

        });

})->dailyAt('09:00');