@extends('layouts.layout')

@section('content')
<div class="main-content">
  <header class="topbar d-flex align-items-center justify-content-between px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
    <h5 class="mb-0 d-none d-lg-block">Dashboard</h5>
    <div class="d-flex align-items-center gap-3">     
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
          <div class="avatar-circle">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
          </div>
          <span class="d-none d-md-inline small">{{ auth()->user()->name ?? 'User' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('user.profile') }}">My Profile</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item text-danger">Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <div class="page-content">

    <!-- Welcome -->
    <div class="mb-4">
      <h4 class="fw-bold mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }} 👋</h4>
      <p class="text-muted mb-0">Here's what's happening across your projects today.</p>
    </div>

    <!-- Stat cards -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="stat-card p-3 h-100">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small">Total Projects</div>
              <div class="fs-4 fw-bold">{{ number_format($data['total_projects'] ?? 0) }}</div>
            </div>
            <div class="stat-icon" style="background:#eef2ff;color:#4f46e5;"><i class="bi bi-folder2-open"></i></div>
          </div>
          <div class="small text-success mt-2"><i class="bi bi-arrow-up-short"></i> {{ $data['active_projects_this_week'] ?? 0 }} active this week</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card p-3 h-100">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small">Total Tasks</div>
              <div class="fs-4 fw-bold">{{ number_format($data['total_tasks'] ?? 0) }}</div>
            </div>
            <div class="stat-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-list-check"></i></div>
          </div>
          <div class="small text-muted mt-2">{{ number_format($data['completed_tasks'] ?? 0) }} completed total</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card p-3 h-100">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small">Overdue Tasks</div>
              <div class="fs-4 fw-bold">{{ number_format($data['overdue_tasks_count'] ?? 0) }}</div>
            </div>
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-exclamation-triangle"></i></div>
          </div>
          <div class="small text-danger mt-2">Needs attention</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card p-3 h-100">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small">Hours Logged</div>
              <div class="fs-4 fw-bold">{{ number_format($data['hours_logged'] ?? 0) }}h</div>
            </div>
            <div class="stat-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-clock-history"></i></div>
          </div>
          <div class="small text-muted mt-2">Across all tasks</div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <!-- Recent Projects -->
      <div class="col-lg-7">
        <div class="card h-100">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="section-title fw-bold">Recent Projects</span>
            <a href="{{ route('project.index') }}" class="small text-decoration-none">View all</a>
          </div>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Project</th>
                  <th>Status</th>
                  <th>Progress</th>
                  <th>Due Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($data['projects'] as $project)
                @php
                $totalTasks = $project->tasks_count ?? 0;
                $completedTasks = $project->completed_tasks_count ?? 0;
                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

                $statusBadge = match($project->status ?? 'planning') {
                'in_progress' => 'badge-in_progress',
                'review' => 'badge-review',
                'complete' => 'badge-completed',
                default => 'badge-todo',
                };

                $progressBarColor = match(true) {
                $percentage >= 100 => 'bg-success',
                $percentage >= 75 => 'bg-warning',
                $percentage >= 30 => 'bg-primary',
                default => 'bg-secondary',
                };
                @endphp
                <tr>
                  <td>
                    <a href="{{ route('project.show', $project->id) }}" class="fw-semibold text-dark text-decoration-none">
                      {{ $project->name }}
                    </a>
                    <div class="text-muted small">{{ $project->category->name ?? $project->department ?? 'General' }}</div>
                  </td>
                  <td>
                    <span class="badge {{ $statusBadge }}">{{ Str::title(str_replace('_', ' ', $project->status ?? 'Planning')) }}</span>
                  </td>
                  <td style="width:140px;">
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar {{ $progressBarColor }}" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="small text-muted">{{ $percentage }}%</span>
                  </td>
                  <td class="small">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted py-4">No recent projects found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- My Tasks Today -->
      <div class="col-lg-5">
        <div class="card h-100">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="section-title fw-bold">My Tasks Today</span>
            <a href="{{ route('task.index') }}" class="small text-decoration-none">Board view</a>
          </div>
          <ul class="list-group list-group-flush">
            @forelse($data['my_tasks'] ?? [] as $task)
            @php
            $priorityBadge = match($task->priority ?? 'medium') {
            'urgent' => 'badge-urgent',
            'high' => 'badge-high',
            'low' => 'badge-low',
            default => 'badge-medium',
            };
            @endphp
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <a href="{{ route('task.show', $task->id) }}" class="fw-semibold text-dark text-decoration-none">
                  {{ $task->title }}
                </a>
                <div class="text-muted small">{{ $task->project->name ?? 'No Project' }}</div>
              </div>
              <span class="badge {{ $priorityBadge }}">{{ ucfirst($task->priority ?? 'Medium') }}</span>
            </li>
            @empty
            <li class="list-group-item text-center text-muted py-4">
              No tasks assigned for today.
            </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection