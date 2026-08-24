<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskTimeLog;
use App\Models\User;
use Carbon\Carbon;

class ReportService
{
    public function fetchData(): array
    {
        $userId = auth()->id();
        $startOfWeek = Carbon::now()->startOfWeek();

        return [
            'total_projects'             => Project::count(),
            'active_projects_this_week'  => Project::where('updated_at', '>=', $startOfWeek)->count(),
            'total_tasks'                => Task::count(),
            'completed_tasks'            => Task::where('status', 'completed')->count(),
            'overdue_tasks_count'        => Task::where('status', '!=', 'completed')
                ->where('due_date', '<', Carbon::today())
                ->count(),
            'hours_logged'               => TaskTimeLog::sum('hours'),
            'total_team_members'         => User::count(),

            'projects'                   => Project::with(['category'])
                ->withCount([
                    'tasks',
                    'tasks as completed_tasks_count' => fn($q) => $q->where('status', 'completed')
                ])
                ->withSum('timeLogs as total_hours', 'hours')
                ->latest()
                ->take(5)
                ->get(),

            // Fixed: FIELD() is MySQL-only and crashes on SQLite — CASE works on both
            'my_tasks'                   => Task::with('project')
                ->where('member_id', $userId)
                ->whereDate('due_date', Carbon::today())
                ->orderByRaw("CASE priority
                    WHEN 'urgent' THEN 1
                    WHEN 'high' THEN 2
                    WHEN 'medium' THEN 3
                    WHEN 'low' THEN 4
                    ELSE 5 END")
                ->take(5)
                ->get(),

            'members'                    => User::withCount('tasks')
                ->withSum('timeLogs as total_hours', 'hours')
                ->orderByDesc('total_hours')
                ->get()
                ->map(function ($user) {
                    $hours = $user->total_hours ?? 0;
                    $user->workload_percentage = min(100, round(($hours / 120) * 100));
                    return $user;
                })
        ];
    }
}
