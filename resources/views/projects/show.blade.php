@extends('layouts.layout')
@section('content')
<div class="main-content">
  <header class="topbar d-flex align-items-center px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
        <li class="breadcrumb-item active">{{$project->name}}</li>
      </ol>
    </nav>
  </header>

  <div class="page-content">

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
    $statusClasses = [
    'planning' => 'bg-secondary text-white',
    'in_progress' => 'bg-primary text-white',
    'review' => 'bg-warning text-dark',
    'complete' => 'bg-success text-white',
    ];
    $projectBadgeClass = $statusClasses[$project->status] ?? 'bg-light text-dark';
    @endphp

    <!-- Project header -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <h4 class="fw-bold mb-0">{{$project->name}}</h4>
              <span class="badge {{ $projectBadgeClass }}">{{ ucfirst(str_replace('_',' ',$project->status)) }}</span>
            </div>
            <p class="text-muted mb-0">{{$project->description}}</p>
          </div>
          <div class="d-flex gap-2">
            {{-- pre-fills the project on the create form, matching how tasks/create.blade.php reads project_id --}}
            <a href="{{ route('task.create', ['project_id' => $project->id]) }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Task</a>
            <a href="{{route('project.edit', $project)}}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>

            <form action="{{ route('project.destroy', $project) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Are you sure you want to delete this project?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </div>

        <hr>

        <div class="row g-3">
          <div class="col-6 col-md-3">
            <div class="text-muted small">Category</div>
            <div class="fw-semibold">{{$project->category->name}}</div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-muted small">Timeline</div>
            <div class="fw-semibold">{{ $project->start_date?->format('M d, Y') ?? '—' }} – {{ $project->end_date?->format('M d, Y') ?? '—' }}</div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-muted small">Budget</div>
            <div class="fw-semibold">Rs. {{ number_format($project->budget, 2) }}</div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-muted small">Members</div>
            <div class="fw-semibold">{{ $project->members->count() }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <!-- Tasks: now the project's real tasks, loaded via $project->tasks in ProjectController::show() -->
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="section-title">Tasks ({{ $project->tasks->count() }})</span>
            <a href="{{ route('task.index', ['project_id' => $project->id]) }}" class="small">Board view</a>
          </div>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Task</th>
                  <th>Assignee</th>
                  <th>Priority</th>
                  <th>Status</th>
                  <th>Due</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($project->tasks as $task)
                <tr>
                  <td><a href="{{ route('task.show', $task) }}" class="text-dark fw-semibold">{{ $task->title }}</a></td>
                  <td>
                    @if ($task->assignee)
                    <div class="d-flex align-items-center gap-2">
                      <div class="avatar-circle" style="width:28px;height:28px;font-size:.7rem;">{{ strtoupper(substr($task->assignee->name, 0, 2)) }}</div>
                      {{ $task->assignee->name }}
                    </div>
                    @else
                    <span class="text-muted">Unassigned</span>
                    @endif
                  </td>
                  <td><span class="badge badge-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span></td>
                  <td><span class="badge badge-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span></td>
                  <td class="small">{{ $task->due_date?->format('M d') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No tasks yet for this project.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Members -->
      <div class="col-lg-4">
        <div class="card mb-3">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="section-title">Team Members</span>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal"><i class="bi bi-person-plus"></i></button>
          </div>
          <ul class="list-group list-group-flush">
            @forelse($project->members as $item)
            <li class="list-group-item d-flex align-items-center gap-2">
              <div class="avatar-circle">{{ strtoupper(substr($item->name, 0, 2)) }}</div>
              <div class="flex-grow-1">
                <div class="fw-semibold">{{$item->name}}</div>
                <div class="text-muted small">{{$item->role}}</div>
              </div>
            </li>
            @empty
            <li class="list-group-item text-muted small">No members added yet.</li>
            @endforelse
          </ul>
        </div>
        {{-- Hours Logged card intentionally left out — there's no TimeLog model/table in this project yet --}}
      </div>
    </div>

  </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('project.members.add', $project) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Team Member</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">Select user</label>
          @php $existingMemberIds = $project->members->pluck('id'); @endphp
          <select class="form-select" name="user_id" required>
            <option value="">Choose a user...</option>
            @foreach ($formData['users'] as $user)
            @unless($existingMemberIds->contains($user->id))
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endunless
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Member</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection