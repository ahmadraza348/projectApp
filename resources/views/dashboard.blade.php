@extends('layouts.layout')
@section('content')
<div class="main-content">
  <header class="topbar d-flex align-items-center justify-content-between px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
    <h5 class="mb-0 d-none d-lg-block">Dashboard</h5>
    <div class="d-flex align-items-center gap-3">
      <a href="#" class="position-relative text-dark"><i class="bi bi-bell fs-5"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">3</span>
      </a>
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
          <div class="avatar-circle">AK</div>
          <span class="d-none d-md-inline small">Ali Khan</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="profile.html">My Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="login.html">Logout</a></li>
        </ul>
      </div>
    </div>
  </header>

  <div class="page-content">

    <!-- Welcome -->
    <div class="mb-4">
      <h4 class="fw-bold mb-1">Welcome back, Ali 👋</h4>
      <p class="text-muted mb-0">Here's what's happening across your projects today.</p>
    </div>

    <!-- Stat cards -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="stat-card p-3 h-100">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small">Total Projects</div>
              <div class="fs-4 fw-bold">20</div>
            </div>
            <div class="stat-icon" style="background:#eef2ff;color:#4f46e5;"><i class="bi bi-folder2-open"></i></div>
          </div>
          <div class="small text-success mt-2"><i class="bi bi-arrow-up-short"></i> 4 active this week</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card p-3 h-100">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small">Total Tasks</div>
              <div class="fs-4 fw-bold">248</div>
            </div>
            <div class="stat-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-list-check"></i></div>
          </div>
          <div class="small text-muted mt-2">62 completed this month</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card p-3 h-100">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="text-muted small">Overdue Tasks</div>
              <div class="fs-4 fw-bold">9</div>
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
              <div class="text-muted small">Team Members</div>
              <div class="fs-4 fw-bold">25</div>
            </div>
            <div class="stat-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-people"></i></div>
          </div>
          <div class="small text-muted mt-2">Across all projects</div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <!-- Recent Projects -->
      <div class="col-lg-7">
        <div class="card">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="section-title">Recent Projects</span>
            <a href="projects.html" class="small">View all</a>
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
                <tr>
                  <td><a href="project-show.html" class="fw-semibold text-dark">Website Redesign</a><div class="text-muted small">Marketing</div></td>
                  <td><span class="badge badge-in_progress">In Progress</span></td>
                  <td style="width:140px;">
                    <div class="progress"><div class="progress-bar" style="width:65%"></div></div>
                    <span class="small text-muted">65%</span>
                  </td>
                  <td class="small">Sep 12, 2026</td>
                </tr>
                <tr>
                  <td><a href="project-show.html" class="fw-semibold text-dark">Mobile App API</a><div class="text-muted small">Engineering</div></td>
                  <td><span class="badge badge-review">Review</span></td>
                  <td style="width:140px;">
                    <div class="progress"><div class="progress-bar bg-warning" style="width:85%"></div></div>
                    <span class="small text-muted">85%</span>
                  </td>
                  <td class="small">Aug 30, 2026</td>
                </tr>
                <tr>
                  <td><a href="project-show.html" class="fw-semibold text-dark">Internal CRM</a><div class="text-muted small">Sales</div></td>
                  <td><span class="badge badge-todo">Planning</span></td>
                  <td style="width:140px;">
                    <div class="progress"><div class="progress-bar bg-secondary" style="width:15%"></div></div>
                    <span class="small text-muted">15%</span>
                  </td>
                  <td class="small">Oct 05, 2026</td>
                </tr>
                <tr>
                  <td><a href="project-show.html" class="fw-semibold text-dark">Payment Gateway</a><div class="text-muted small">Engineering</div></td>
                  <td><span class="badge badge-completed">Completed</span></td>
                  <td style="width:140px;">
                    <div class="progress"><div class="progress-bar bg-success" style="width:100%"></div></div>
                    <span class="small text-muted">100%</span>
                  </td>
                  <td class="small">Aug 01, 2026</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- My Tasks -->
      <div class="col-lg-5">
        <div class="card h-100">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="section-title">My Tasks Today</span>
            <a href="tasks-kanban.html" class="small">Board view</a>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold">Fix checkout bug</div>
                <div class="text-muted small">Payment Gateway</div>
              </div>
              <span class="badge badge-urgent">Urgent</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold">Design landing page hero</div>
                <div class="text-muted small">Website Redesign</div>
              </div>
              <span class="badge badge-medium">Medium</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold">Write API documentation</div>
                <div class="text-muted small">Mobile App API</div>
              </div>
              <span class="badge badge-low">Low</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-semibold">Review pull request #142</div>
                <div class="text-muted small">Internal CRM</div>
              </div>
              <span class="badge badge-high">High</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
