@extends('layouts.layout')
@section('content')

<div class="main-content">
    <header class="topbar d-flex align-items-center px-3">
        <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('task.index') }}">Tasks</a></li>
                <li class="breadcrumb-item active">New Task</li>
            </ol>
        </nav>
    </header>

    <div class="page-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-white"><span class="section-title">Create New Task</span></div>
                    <div class="card-body">

                        <form action="{{ route('task.store') }}" method="POST">
                            @csrf

                            <!-- Project Selection -->
                            <div class="mb-3">
                                <label class="form-label">Project</label>
                                <select class="form-select @error('project_id') is-invalid @enderror" name="project_id" id="projectSelect" required>
                                    <option value="">Select project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $selectedProjectId ?? '') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Task Title -->
                            <div class="mb-3">
                                <label class="form-label">Task Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="e.g. Design landing page hero" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="What needs to be done...">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assignee & Priority -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Assign To</label>
                                    <select class="form-select @error('member_id') is-invalid @enderror" name="member_id" id="memberSelect">
                                        <option value="">Select project member</option>
                                    </select>
                                    @error('member_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Only members assigned to this project are shown.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Priority</label>
                                    <select class="form-select @error('priority') is-invalid @enderror" name="priority">
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Due Date & Estimated Hours -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date') }}">
                                    @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Estimated Hours</label>
                                    <input type="number" class="form-control @error('estimated_hours') is-invalid @enderror" name="estimated_hours" min="0" step="0.5" value="{{ old('estimated_hours') }}" placeholder="e.g. 8">
                                    @error('estimated_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('task.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Task</button>
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
        const oldMemberId = "{{ old('member_id') }}";

        function loadMembers(projectId) {
            memberSelect.innerHTML = '<option value="">Loading members...</option>';
            if (!projectId) {
                memberSelect.innerHTML = '<option value="">Select project member</option>';
                return;
            }

            // Generate Blade route with placeholder and replace it with real projectId
            const url = "{{ route('task.project.members', ':id') }}".replace(':id', projectId);

            fetch(url)
                .then(response => response.json())
                .then(members => {
                    memberSelect.innerHTML = '<option value="">Select project member</option>';
                    members.forEach(member => {
                        const selected = oldMemberId == member.id ? 'selected' : '';
                        memberSelect.innerHTML += `<option value="${member.id}" ${selected}>${member.name}</option>`;
                    });
                })
                .catch(() => {
                    memberSelect.innerHTML = '<option value="">Error loading members</option>';
                });
        }

        projectSelect.addEventListener('change', function() {
            loadMembers(this.value);
        });

        if (projectSelect.value) {
            loadMembers(projectSelect.value);
        }
    });
</script>

@endsection