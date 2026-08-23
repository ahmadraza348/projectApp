@extends('layouts.layout')
@section('content')

<div class="main-content">
  <header class="topbar d-flex align-items-center justify-content-between px-3">
    <div class="d-flex align-items-center gap-2">
      <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
      <h5 class="mb-0 d-none d-lg-block">Tasks</h5>
    </div>
    <a href="{{ route('task.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Task</a>
  </header>

  <div class="page-content">

    <form class="filter-bar mb-4" method="GET" action="{{ route('task.index') }}">
      <div class="row g-2 align-items-center">
        <div class="col-md-3">
          <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search tasks...">
        </div>
        <div class="col-md-3">
          <select class="form-select" name="project_id">
            <option value="">All Projects</option>
            @foreach ($projects as $project)
            <option value="{{ $project->id }}" @selected(request('project_id')==$project->id)>{{ $project->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select" name="priority">
            <option value="">Priority</option>
            @foreach (['low', 'medium', 'high', 'urgent'] as $priority)
            <option value="{{ $priority }}" @selected(request('priority')==$priority)>{{ ucfirst($priority) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-outline-primary"><i class="bi bi-funnel"></i></button>
        </div>
      </div>
    </form>

    @php
    $grouped = $tasks->groupBy('status');
    $columns = [
    'todo' => ['label' => 'To Do', 'icon' => 'circle text-secondary', 'badge' => 'bg-secondary'],
    'in_progress' => ['label' => 'In Progress', 'icon' => 'arrow-repeat text-primary', 'badge' => 'bg-primary'],
    'review' => ['label' => 'Review', 'icon' => 'eye text-warning', 'badge' => 'bg-warning text-dark'],
    'completed' => ['label' => 'Completed', 'icon' => 'check-circle text-success', 'badge' => 'bg-success'],
    ];
    @endphp

    {{-- data-status-url carries a real, admin-prefixed route with a placeholder id the JS swaps in --}}
    <div class="kanban-board" data-status-url="{{ route('task.update-status', ['task' => '__ID__']) }}">
      @foreach ($columns as $status => $meta)
      <div class="kanban-column" data-status="{{ $status }}">
        <div class="kanban-column-header">
          <span><i class="bi bi-{{ $meta['icon'] }} me-1"></i> {{ $meta['label'] }}</span>
          <span class="badge {{ $meta['badge'] }} column-count">{{ $grouped->get($status, collect())->count() }}</span>
        </div>
        <div class="kanban-column-body">
          @foreach ($grouped->get($status, collect()) as $task)
          <div class="task-card" draggable="true" data-task-id="{{ $task->id }}">
            <div class="d-flex justify-content-between mb-2">
              <span class="badge badge-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
              <span class="badge bg-light text-dark border">{{ $task->project->name }}</span>
            </div>
            <a href="{{ route('task.show', $task) }}" class="fw-semibold text-dark d-block mb-2">{{ $task->title }}</a>
            <div class="d-flex justify-content-between align-items-center">
              <div class="avatar-circle" style="width:26px;height:26px;font-size:.65rem;">
                {{ $task->assignee ? strtoupper(substr($task->assignee->name, 0, 1) . substr(strrchr($task->assignee->name, ' '), 1, 1)) : '?' }}
              </div>
              <span class="small text-muted"><i class="bi bi-calendar"></i> {{ $task->due_date?->format('M d') ?? '—' }}</span>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>

    <p class="text-muted small mt-2"><i class="bi bi-info-circle"></i> Drag a card to another column to change its status.</p>

  </div>
</div>

<script>
  document.querySelectorAll('.task-card').forEach(card => {
    card.addEventListener('dragstart', e => e.dataTransfer.setData('text/plain', card.dataset.taskId));
  });

  const board = document.querySelector('.kanban-board');
  const urlTemplate = board.dataset.statusUrl; // e.g. /admin/tasks/__ID__/status

  document.querySelectorAll('.kanban-column-body').forEach(column => {
    column.addEventListener('dragover', e => e.preventDefault());
    column.addEventListener('drop', async e => {
      e.preventDefault();
      const taskId = e.dataTransfer.getData('text/plain');
      const newStatus = column.closest('.kanban-column').dataset.status;
      const url = urlTemplate.replace('__ID__', taskId); // fixed: was a hardcoded /tasks/.. path, missing /admin prefix

      const res = await fetch(url, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          status: newStatus
        }),
      });

      if (res.ok) {
        location.reload();
      } else {
        alert('Could not update status, please try again.'); // was failing silently before
      }
    });
  });
</script>

@endsection