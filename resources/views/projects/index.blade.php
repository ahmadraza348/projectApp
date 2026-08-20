@extends("layouts.layout")
@section("content")
<div class="main-content">
  <header class="topbar d-flex align-items-center justify-content-between px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
    <h5 class="mb-0 d-none d-lg-block">Projects</h5>
    <a href="{{route('project.create')}}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Project</a>
  </header>

  <div class="page-content">

    <!-- Filter bar -->
    <form class="filter-bar mb-4" method="GET" action="projects.html">
      <div class="row g-2 align-items-center">
        <div class="col-md-4">
          <input type="text" class="form-control" name="search" placeholder="Search projects...">
        </div>
        <div class="col-md-3">
          <select class="form-select" name="category">
            <option value="">All Categories</option>
            <option>Engineering</option>
            <option>Marketing</option>
            <option>Sales</option>
            <option>Operations</option>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select" name="status">
            <option value="">All Statuses</option>
            <option value="planning">Planning</option>
            <option value="in_progress">In Progress</option>
            <option value="review">Review</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-outline-primary"><i class="bi bi-funnel"></i> Filter</button>
        </div>
      </div>
    </form>

    <!-- Projects grid -->
    <div class="row g-3">

      <div class="col-md-6 col-xl-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-light text-dark border">Marketing</span>
              <span class="badge badge-in_progress">In Progress</span>
            </div>
            <h6 class="fw-bold mb-1"><a href="project-show.html" class="text-dark">Website Redesign</a></h6>
            <p class="text-muted small mb-3">Full revamp of the marketing site with a new design system and CMS.</p>
            <div class="progress mb-2"><div class="progress-bar" style="width:65%"></div></div>
            <div class="d-flex justify-content-between small text-muted">
              <span><i class="bi bi-people"></i> 5 members</span>
              <span><i class="bi bi-calendar"></i> Sep 12, 2026</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-light text-dark border">Engineering</span>
              <span class="badge badge-review">Review</span>
            </div>
            <h6 class="fw-bold mb-1"><a href="project-show.html" class="text-dark">Mobile App API</a></h6>
            <p class="text-muted small mb-3">REST API powering the iOS and Android mobile applications.</p>
            <div class="progress mb-2"><div class="progress-bar bg-warning" style="width:85%"></div></div>
            <div class="d-flex justify-content-between small text-muted">
              <span><i class="bi bi-people"></i> 4 members</span>
              <span><i class="bi bi-calendar"></i> Aug 30, 2026</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-light text-dark border">Sales</span>
              <span class="badge badge-todo">Planning</span>
            </div>
            <h6 class="fw-bold mb-1"><a href="project-show.html" class="text-dark">Internal CRM</a></h6>
            <p class="text-muted small mb-3">Custom CRM to replace the spreadsheet-based sales pipeline.</p>
            <div class="progress mb-2"><div class="progress-bar bg-secondary" style="width:15%"></div></div>
            <div class="d-flex justify-content-between small text-muted">
              <span><i class="bi bi-people"></i> 3 members</span>
              <span><i class="bi bi-calendar"></i> Oct 05, 2026</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-light text-dark border">Engineering</span>
              <span class="badge badge-completed">Completed</span>
            </div>
            <h6 class="fw-bold mb-1"><a href="project-show.html" class="text-dark">Payment Gateway</a></h6>
            <p class="text-muted small mb-3">Integration with Stripe and local payment providers.</p>
            <div class="progress mb-2"><div class="progress-bar bg-success" style="width:100%"></div></div>
            <div class="d-flex justify-content-between small text-muted">
              <span><i class="bi bi-people"></i> 6 members</span>
              <span><i class="bi bi-calendar"></i> Aug 01, 2026</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-light text-dark border">Operations</span>
              <span class="badge badge-in_progress">In Progress</span>
            </div>
            <h6 class="fw-bold mb-1"><a href="project-show.html" class="text-dark">Warehouse Inventory System</a></h6>
            <p class="text-muted small mb-3">Barcode-based stock tracking for the main warehouse.</p>
            <div class="progress mb-2"><div class="progress-bar" style="width:40%"></div></div>
            <div class="d-flex justify-content-between small text-muted">
              <span><i class="bi bi-people"></i> 4 members</span>
              <span><i class="bi bi-calendar"></i> Sep 25, 2026</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-xl-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-light text-dark border">Marketing</span>
              <span class="badge badge-todo">Planning</span>
            </div>
            <h6 class="fw-bold mb-1"><a href="project-show.html" class="text-dark">Q4 Campaign</a></h6>
            <p class="text-muted small mb-3">End-of-year marketing campaign across social and email.</p>
            <div class="progress mb-2"><div class="progress-bar bg-secondary" style="width:5%"></div></div>
            <div class="d-flex justify-content-between small text-muted">
              <span><i class="bi bi-people"></i> 2 members</span>
              <span><i class="bi bi-calendar"></i> Nov 15, 2026</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Pagination -->
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
      </ul>
    </nav>

  </div>
</div>
@endsection