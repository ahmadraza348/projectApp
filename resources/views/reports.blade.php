@extends('layouts.layout')
@section('content')
<div class="main-content">
    <header class="topbar d-flex align-items-center px-3">
        <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
        <h5 class="mb-0 d-none d-lg-block">Reports</h5>
    </header>

    <div class="page-content">

        <!-- Summary stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-primary">{{ $data['total_projects'] ?? 0 }}</div>
                    <div class="small text-muted">Total Projects</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-success">{{ $data['total_tasks'] ?? 0 }}</div>
                    <div class="small text-muted">Total Tasks</div>
                </div>
            </div>

            @php
            $completedTasks = $data['completed_tasks'] ?? 0;
            $totalTasks = $data['total_tasks'] ?? 0;

            $avgCompletion = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;
            @endphp

            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-warning">{{ $avgCompletion }}%</div>
                    <div class="small text-muted">Avg Completion</div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-info">{{ number_format($data['hours_logged'] ?? 0) }}</div>
                    <div class="small text-muted">Hours Logged</div>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <!-- Project progress report -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-white"><span class="section-title">Project Progress</span></div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Tasks</th>
                                    <th>Completed %</th>
                                    <th>Hours Logged</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['projects'] as $project)
                                @php
                                $totalTasks = $project->tasks_count ?? 0;
                                $completedTasks = $project->completed_tasks_count ?? 0;
                                $completionPercentage = $totalTasks > 0
                                ? round(($completedTasks / $totalTasks) * 100)
                                : 0;

                                // Determine dynamic progress bar color class
                                $badgeClass = match (true) {
                                $completionPercentage >= 100 => 'bg-success',
                                $completionPercentage >= 75 => 'bg-warning',
                                $completionPercentage >= 30 => 'bg-primary',
                                default => 'bg-secondary',
                                };
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $project->name }}</td>
                                    <td>{{ $totalTasks }}</td>
                                    <td style="width:160px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar {{ $badgeClass }}" style="width: {{ $completionPercentage }}%"></div>
                                            </div>
                                            <span class="small text-muted me-1">{{ $completionPercentage }}%</span>
                                        </div>
                                    </td>
                                    <td>{{ number_format($project->total_hours ?? 0) }}h</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No projects available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Member workload -->
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <span class="section-title">Member Workload</span>
                    </div>
                    <div class="card-body">
                        @forelse($data['members'] as $member)
                        @php
                        $taskCount = $member->tasks_count ?? 0;
                        $totalHours = number_format($member->total_hours ?? 0);
                        $percentage = $member->workload_percentage ?? 0;

                        // Dynamic progress bar styling based on load
                        $progressColor = match (true) {
                        $percentage >= 80 => 'bg-danger',
                        $percentage >= 60 => 'bg-warning',
                        $percentage >= 40 => 'bg-info',
                        $percentage >= 20 => 'bg-primary',
                        default => 'bg-secondary',
                        };
                        @endphp
                        <div class="{{ $loop->last ? 'mb-0' : 'mb-3' }}">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="fw-medium">{{ $member->name }}</span>
                                <span class="text-muted">{{ $taskCount }} {{ Str::plural('task', $taskCount) }} · {{ $totalHours }}h</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $progressColor }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <small>No team members found</small>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Export as CSV</button>
        </div>

    </div>
</div>
@endsection