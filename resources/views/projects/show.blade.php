@extends('layouts.layout')
@section('content')
<div class="main-content">
  <header class="topbar d-flex align-items-center px-3">
    <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="projects.html">Projects</a></li>
        <li class="breadcrumb-item active">Website Redesign</li>
      </ol>
    </nav>
  </header>

  <div class="page-content">

    <!-- Project header -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <h4 class="fw-bold mb-0">Website Redesign</h4>
              <span class="badge badge-in_progress">In Progress</span>
            </div>
            <p class="text-muted mb-0">Full revamp of the marketing site with a new design system and CMS.</p>
          </div>
          <div class="d-flex gap-2">
            <a href="task-create.html" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Task</a>
            <a href="{{route('project.edit', $project)}}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil"></i> Edit</a>

             <form action="{{ route('project.destroy', $project) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>


          </div>
        </div>

        <hr>

        <div class="row g-3">
          <div class="col-6 col-md-3">
            <div class="text-muted small">Category</div>
            <div class="fw-semibold">Marketing</div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-muted small">Timeline</div>
            <div class="fw-semibold">Jul 01 – Sep 12, 2026</div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-muted small">Budget</div>
            <div class="fw-semibold">$18,500</div>
          </div>
          <div class="col-6 col-md-3">
            <div class="text-muted small">Project Manager</div>
            <div class="fw-semibold">Sara Ahmed</div>
          </div>
        </div>

        <div class="mt-3">
          <div class="d-flex justify-content-between small mb-1">
            <span class="text-muted">Overall progress</span>
            <span class="fw-semibold">65%</span>
          </div>
          <div class="progress"><div class="progress-bar" style="width:65%"></div></div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <!-- Tasks -->
      <div class="col-lg-8">
        <div class="card">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="section-title">Tasks (12)</span>
            <a href="tasks-kanban.html" class="small">Board view</a>
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
                <tr>
                  <td><a href="task-show.html" class="text-dark fw-semibold">Design landing page hero</a></td>
                  <td><div class="d-flex align-items-center gap-2"><div class="avatar-circle" style="width:28px;height:28px;font-size:.7rem;">HT</div> Hamza T.</div></td>
                  <td><span class="badge badge-medium">Medium</span></td>
                  <td><span class="badge badge-in_progress">In Progress</span></td>
                  <td class="small">Aug 22</td>
                </tr>
                <tr>
                  <td><a href="task-show.html" class="text-dark fw-semibold">Build responsive navbar</a></td>
                  <td><div class="d-flex align-items-center gap-2"><div class="avatar-circle" style="width:28px;height:28px;font-size:.7rem;">BH</div> Bilal H.</div></td>
                  <td><span class="badge badge-high">High</span></td>
                  <td><span class="badge badge-review">Review</span></td>
                  <td class="small">Aug 20</td>
                </tr>
                <tr>
                  <td><a href="task-show.html" class="text-dark fw-semibold">Set up CMS content model</a></td>
                  <td><div class="d-flex align-items-center gap-2"><div class="avatar-circle" style="width:28px;height:28px;font-size:.7rem;">AM</div> Ayesha M.</div></td>
                  <td><span class="badge badge-low">Low</span></td>
                  <td><span class="badge badge-todo">To Do</span></td>
                  <td class="small">Aug 28</td>
                </tr>
                <tr>
                  <td><a href="task-show.html" class="text-dark fw-semibold">QA cross-browser testing</a></td>
                  <td><div class="d-flex align-items-center gap-2"><div class="avatar-circle" style="width:28px;height:28px;font-size:.7rem;">FN</div> Fatima N.</div></td>
                  <td><span class="badge badge-urgent">Urgent</span></td>
                  <td><span class="badge badge-completed">Completed</span></td>
                  <td class="small">Aug 15</td>
                </tr>
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
            <li class="list-group-item d-flex align-items-center gap-2">
              <div class="avatar-circle">SA</div>
              <div class="flex-grow-1">
                <div class="fw-semibold">Sara Ahmed</div>
                <div class="text-muted small">Project Manager</div>
              </div>
            </li>
            <li class="list-group-item d-flex align-items-center gap-2">
              <div class="avatar-circle">BH</div>
              <div class="flex-grow-1">
                <div class="fw-semibold">Bilal Hussain</div>
                <div class="text-muted small">Developer</div>
              </div>
              <button class="btn btn-sm btn-link text-danger" title="Remove"><i class="bi bi-x-lg"></i></button>
            </li>
            <li class="list-group-item d-flex align-items-center gap-2">
              <div class="avatar-circle">AM</div>
              <div class="flex-grow-1">
                <div class="fw-semibold">Ayesha Malik</div>
                <div class="text-muted small">Developer</div>
              </div>
              <button class="btn btn-sm btn-link text-danger" title="Remove"><i class="bi bi-x-lg"></i></button>
            </li>
            <li class="list-group-item d-flex align-items-center gap-2">
              <div class="avatar-circle">HT</div>
              <div class="flex-grow-1">
                <div class="fw-semibold">Hamza Tariq</div>
                <div class="text-muted small">Designer</div>
              </div>
              <button class="btn btn-sm btn-link text-danger" title="Remove"><i class="bi bi-x-lg"></i></button>
            </li>
          </ul>
        </div>

        <div class="card">
          <div class="card-header bg-white"><span class="section-title">Hours Logged</span></div>
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2"><span class="small text-muted">Total this project</span><span class="fw-bold">142h</span></div>
            <div class="d-flex justify-content-between small mb-1"><span>Bilal Hussain</span><span>48h</span></div>
            <div class="progress mb-2"><div class="progress-bar" style="width:34%"></div></div>
            <div class="d-flex justify-content-between small mb-1"><span>Ayesha Malik</span><span>39h</span></div>
            <div class="progress mb-2"><div class="progress-bar" style="width:27%"></div></div>
            <div class="d-flex justify-content-between small mb-1"><span>Hamza Tariq</span><span>35h</span></div>
            <div class="progress mb-2"><div class="progress-bar" style="width:25%"></div></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="#" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Add Team Member</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label">Select user</label>
          <select class="form-select" name="user_id" required>
            <option value="">Choose a user...</option>
            <option value="7">Zainab Riaz — Developer</option>
            <option value="8">Usman Farooq — QA Engineer</option>
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