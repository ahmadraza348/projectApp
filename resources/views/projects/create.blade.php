@extends('layouts.layout')
@section('content')

<div class="main-content">
  <header class="topbar d-flex align-items-center px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
        <li class="breadcrumb-item active">New Project</li>
      </ol>
    </nav>
  </header>

  <div class="page-content">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header bg-white"><span class="section-title">Create New Project</span></div>
          <div class="card-body">

            <!-- Fixed syntax error in route() -->
            <form action="{{ route('project.store') }}" method="POST">
              @csrf

              <!-- Project Name -->
              <div class="mb-3">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="e.g. Website Redesign" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Description -->
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Briefly describe the project scope...">{{ old('description') }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Department, Client & Manager -->
              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label class="form-label">Department</label>
                  <select class="form-select @error('department_id') is-invalid @enderror" name="department_id">
                    <option value="">Select department</option>
                    @foreach($data['departments'] as $dept)
                      <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                  </select>
                  @error('department_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label">Client</label>
                  <select class="form-select @error('client_id') is-invalid @enderror" name="client_id">
                    <option value="">Select client</option>
                    @foreach($data['clients'] as $client)
                      <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                  </select>
                  @error('client_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label">Project Manager</label>
                  <select class="form-select @error('project_manager_id') is-invalid @enderror" name="project_manager_id">
                    <option value="">Select manager</option>
                    @foreach($data['users'] as $user)
                      <option value="{{ $user->id }}" {{ old('project_manager_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                  </select>
                  @error('project_manager_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Status & Priority -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select class="form-select @error('status') is-invalid @enderror" name="status">
                    <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ old('status') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
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

              <!-- Dates & Budget -->
              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label class="form-label">Start Date</label>
                  <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}">
                  @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label">Deadline</label>
                  <input type="date" class="form-control @error('deadline') is-invalid @enderror" name="deadline" value="{{ old('deadline') }}">
                  @error('deadline')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label">Budget ($)</label>
                  <input type="number" class="form-control @error('budget') is-invalid @enderror" name="budget" value="{{ old('budget') }}" placeholder="0.00" min="0" step="0.01">
                  @error('budget')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Member Assignment -->
              <div class="mb-4">
                <label class="form-label">Project Lead</label>
                <select class="form-select @error('assigned_user_id') is-invalid @enderror" name="assigned_user_id">
                  <option value="">Select project lead</option>
                  @foreach($data['users'] as $user)
                    <option value="{{ $user->id }}" @selected(old('assigned_user_id') == $user->id)>{{ $user->name }} - {{ Str::upper($user->role) }}</option>
                  @endforeach
                </select>
                @error('assigned_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="mb-4">
                <label class="form-label">Assign Members</label>
                <select class="form-select @error('members') is-invalid @enderror" name="members[]" multiple size="5">
                  @foreach($data['users'] as $user)
                    <!-- Fixed: Sends $user->id instead of $user->name -->
                    <option value="{{ $user->id }}" {{ is_array(old('members')) && in_array($user->id, old('members')) ? 'selected' : '' }}>
                      {{ $user->name }} - {{ Str::upper($user->role) }}
                    </option>
                  @endforeach
                </select>
                @error('members')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Hold Ctrl (Windows) / Cmd (Mac) to select multiple members.</div>
              </div>

              <!-- Action Buttons -->
              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('project.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Project</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection