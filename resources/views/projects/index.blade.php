@extends("layouts.layout")
@section("content")
<div class="main-content">
  <header class="topbar d-flex align-items-center justify-content-between px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
    <h5 class="mb-0 d-none d-lg-block">Projects</h5>
    <a href="{{ route('project.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Project</a>
  </header>

  <div class="page-content">

    <!-- Filter bar -->
    <form class="filter-bar mb-4" method="GET" action="{{ route('project.index') }}">
      <div class="row g-2 align-items-center">
        <div class="col-md-4">
          <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search projects...">
        </div>
        <div class="col-md-3">
          <select class="form-select" name="category">
            <option value="">All Categories</option>
            @foreach($data['categories'] as $category)
              <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select" name="status">
            <option value="">All Statuses</option>
            <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planning</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
            <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Completed</option>
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-outline-primary"><i class="bi bi-funnel"></i> Filter</button>
        </div>
      </div>
    </form>

    <!-- Projects grid -->
    <div class="row g-3">
      @forelse($data['projects'] as $project)
        @php
          $statusClasses = [
            'planning'    => 'bg-secondary text-white',
            'in_progress' => 'bg-primary text-white',
            'review'      => 'bg-warning text-dark',
            'complete'    => 'bg-success text-white',
          ];
          $badgeClass = $statusClasses[$project->status] ?? 'bg-light text-dark';
        @endphp

        <div class="col-md-6 col-xl-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge bg-light text-dark border">{{ $project->category->name ?? 'Uncategorized' }}</span>
                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
              </div>
              <h6 class="fw-bold mb-1">
                <a href="#" class="text-dark">{{ $project->name }}</a>
              </h6>
              <p class="text-muted small mb-3">{{ Str::limit($project->description, 90) }}</p>
              
              <div class="d-flex justify-content-between small text-muted border-top pt-2 mt-auto">
                <span><i class="bi bi-people"></i> {{ $project->members_count }} member(s)</span>
                <span>
                  <i class="bi bi-calendar"></i> 
                  {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'No Due Date' }}
                </span>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <p class="text-muted mb-0">No projects found.</p>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
      {{ $data['projects']->links() }}
    </div>

  </div>
</div>
@endsection