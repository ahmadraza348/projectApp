@extends('layouts.layout')
@section('content')
<div class="main-content">
  <header class="topbar d-flex align-items-center justify-content-between px-3">
    <div class="d-flex align-items-center gap-2">
      <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
      <h5 class="mb-0 d-none d-lg-block">Users</h5>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-plus-lg"></i> New User</button>
  </header>

  <div class="page-content">

    <form class="filter-bar mb-4" method="GET" action="users.html">

      <div class="row g-2">
        <div class="col-md-5">
          <input type="text" class="form-control" name="search" placeholder="Search by name or email...">
        </div>
        <div class="col-md-4">
          <select class="form-select" name="role">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="project_manager">Project Manager</option>
            <option value="member">Member</option>
          </select>
        </div>
        <div class="col-md-3 d-grid">
          <button type="submit" class="btn btn-outline-primary"><i class="bi bi-funnel"></i> Filter</button>
        </div>
      </div>
    </form>

    <div class="card">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Projects</th>
              <th>Joined</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="avatar-circle">AK</div> Ali Khan</td>
              <td>admin@demo.com</td>
              <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle">Admin</span></td>
              <td>—</td>
              <td class="small">Jan 10, 2026</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" data-confirm-delete="Ali Khan"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="avatar-circle">SA</div> Sara Ahmed</td>
              <td>sara@demo.com</td>
              <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">Project Manager</span></td>
              <td>4</td>
              <td class="small">Feb 03, 2026</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" data-confirm-delete="Sara Ahmed"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="avatar-circle">BH</div> Bilal Hussain</td>
              <td>bilal@demo.com</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle">Member</span></td>
              <td>3</td>
              <td class="small">Mar 15, 2026</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" data-confirm-delete="Bilal Hussain"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="avatar-circle">AM</div> Ayesha Malik</td>
              <td>ayesha@demo.com</td>
              <td><span class="badge bg-success-subtle text-success border border-success-subtle">Member</span></td>
              <td>2</td>
              <td class="small">Apr 22, 2026</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" data-confirm-delete="Ayesha Malik"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
      </ul>
    </nav>

  </div>
</div>

<!-- Add/Edit User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('user.submit') }}" id="userForm" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input id="name" type="text" class="form-control"value="{{ old('name') }}" name="name" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" id="role" required>
              <option value="admin">Admin</option>
              <option value="project_manager">Project Manager</option>
              <option value="member" selected>Member</option>
            </select>
            @error('role')
                <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror 
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save User</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


