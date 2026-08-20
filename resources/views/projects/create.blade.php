@extends('layouts.layout')
@section('content')

<div class="main-content">
  <header class="topbar d-flex align-items-center px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="projects.html">Projects</a></li>
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

            <form action="#" method="POST">
              @csrf

              <div class="mb-3">
                <label class="form-label">Project Name</label>
                <input type="text" class="form-control" name="name" placeholder="e.g. Website Redesign" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4" placeholder="Briefly describe the project scope..."></textarea>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label">Category</label>
                  <select class="form-select" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($data['category'] as $cat)
                    <option value="{{$cat->name}}">{{$cat->name}}</option>
                    @endforeach
                  
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Status</label>
                  <select class="form-select" name="status">
                    <option value="planning">Planning</option>
                    <option value="in_progress">In Progress</option>
                    <option value="review">Review</option>
                    <option value="completed">Completed</option>
                  </select>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-4">
                  <label class="form-label">Start Date</label>
                  <input type="date" class="form-control" name="start_date">
                </div>
                <div class="col-md-4">
                  <label class="form-label">End Date</label>
                  <input type="date" class="form-control" name="end_date">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Budget ($)</label>
                  <input type="number" class="form-control" name="budget" placeholder="0.00" min="0" step="0.01">
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label">Assign Members</label>
                <select class="form-select" name="members[]" multiple size="5">
                    @foreach($data['category'] as $cat)
                    <option value="{{$cat->name}}">{{$cat->name}}</option>
                    @endforeach

                </select>
                <div class="form-text">Hold Ctrl (Windows) / Cmd (Mac) to select multiple members.</div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <a href="projects.html" class="btn btn-outline-secondary">Cancel</a>
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