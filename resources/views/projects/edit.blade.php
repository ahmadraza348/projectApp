@extends('layouts.layout')
@section('content')

<div class="main-content">
  <header class="topbar d-flex align-items-center px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
        <li class="breadcrumb-item active">Edit Project</li>
      </ol>
    </nav>
  </header>

  <div class="page-content">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header bg-white"><span class="section-title">Edit Project: {{ $project->name }}</span></div>
          <div class="card-body">

            <form action="{{ route('project.update', $project) }}" method="POST">
              @csrf
              @method('PUT')

              <!-- Project Name -->
              <div class="mb-3">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $project->name) }}" placeholder="e.g. Website Redesign" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Description -->
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Briefly describe the project scope...">{{ old('description', $project->description) }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Category & Status -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label">Category</label>
                  <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($formData['category'] as $cat)
                      <option value="{{ $cat->id }}" {{ old('category_id', $project->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select class="form-select @error('status') is-invalid @enderror" name="status">
                    @php $currentStatus = old('status', $project->status); @endphp
                    <option value="planning" {{ $currentStatus == 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="in_progress" {{ $currentStatus == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ $currentStatus == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="complete" {{ $currentStatus == 'complete' ? 'selected' : '' }}>Completed</option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Dates & Budget -->
              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label class="form-label">Start Date</label>
                  <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d') ?? $project->start_date) }}">
                  @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label">End Date</label>
                  <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date', optional($project->end_date)->format('Y-m-d') ?? $project->end_date) }}">
                  @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label">Budget ($)</label>
                  <input type="number" class="form-control @error('budget') is-invalid @enderror" name="budget" value="{{ old('budget', $project->budget) }}" placeholder="0.00" min="0" step="0.01">
                  @error('budget')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Member Assignment -->
              <div class="mb-4">
                <label class="form-label">Assign Members</label>
                @php 
                  $selectedMembers = old('members', $project->members->pluck('id')->toArray()); 
                @endphp
                <select class="form-select @error('members') is-invalid @enderror" name="members[]" multiple size="5">
                  @foreach($formData['users'] as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, $selectedMembers) ? 'selected' : '' }}>
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
                <button type="submit" class="btn btn-primary">Update Project</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection