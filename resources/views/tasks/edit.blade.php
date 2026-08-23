@extends('layouts.layout')
@section('content')

<div class="main-content">
    <header class="topbar d-flex align-items-center px-3">
        <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('task.index') }}">Tasks</a></li>
                <li class="breadcrumb-item active">Edit Task</li>
            </ol>
        </nav>
    </header>

    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white"><span class="section-title">Edit Task</span></div>
                    <div class="card-body">

                        <form action="{{ route('task.update', $task) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Project</label>
                                <select class="form-select @error('project_id') is-invalid @enderror" name="project_id" id="projectSelect" required>
                                    <option value="">Select project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('project_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Task Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $task->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Assign To</label>
                                    {{-- pre-populated with current assignee, then refreshed by JS if project changes --}}
                                    <select class="form-select @error('member_id') is-invalid @enderror" name="member_id" id="memberSelect">
                                        <option value="">Select project member</option>
                                        @if ($task->assignee)
                                        <option value="{{ $task->assignee->id }}" selected>{{ $task->assignee->name }}</option>
                                        @endif
                                    </select>
                                    @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Priority</label>
                                    <select class="form-select @error('priority') is-invalid @enderror" name="priority">
                                        @foreach (['low', 'medium', 'high', 'urgent'] as $priority)
                                        <option value="{{ $priority }}" {{ old('priority', $task->priority) == $priority ? 'selected' : '' }}>{{ ucfirst($priority) }}</option>
                                        @endforeach
                                    </select>
                                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status">
                                        @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'completed' => 'Completed'] as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $task->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estimated Hours</label>
                                    <input type="number" class="form-control @error('estimated_hours') is-invalid @enderror" name="estimated_hours" min="0" step="0.5" value="{{ old('estimated_hours', $task->estimated_hours) }}">
                                    @error('estimated_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('task.show', $task) }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectSelect = document.getElementById('projectSelect');
        const memberSelect = document.getElementById('memberSelect');
        const currentMemberId = "{{ $task->member_id }}";

        function loadMembers(projectId) {
            const url = "{{ route('task.project.members', ':id') }}".replace(':id', projectId);
            fetch(url)
                .then(res => res.json())
                .then(members => {
                    memberSelect.innerHTML = '<option value="">Select project member</option>';
                    members.forEach(member => {
                        const selected = currentMemberId == member.id ? 'selected' : '';
                        memberSelect.innerHTML += `<option value="${member.id}" ${selected}>${member.name}</option>`;
                    });
                });
        }

        // Refresh member list whenever project changes; keep the pre-filled option on first load
        projectSelect.addEventListener('change', function() {
            loadMembers(this.value);
        });
    });
</script>

@endsection