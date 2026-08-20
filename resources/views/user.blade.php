@extends('layouts.layout')
@section('content')
<div class="main-content">
  <header class="topbar d-flex align-items-center justify-content-between px-3">
    <div class="d-flex align-items-center gap-2">
      <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
      <h5 class="mb-0 d-none d-lg-block">Users</h5>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal" id="addNewUserBtn"><i class="bi bi-plus-lg"></i> New User</button>
  </header>
  <div class="page-content">

    <form class="filter-bar mb-4" method="GET" action="{{ route('user.index') }}">
      <div class="row g-2">
        <div class="col-md-5">
          <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by name or email...">
        </div>
        <div class="col-md-4">
          <select class="form-select" name="role">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Project Manager</option>
            <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
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
            @forelse($users as $user)
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="avatar-circle">{{ substr($user->name, 0, 2) }}</div> {{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $user->role }}</span></td>
              <td>—</td>
              <td class="small">{{ $user->created_at->format('M d, Y') }}</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary edit-user-btn" 
                        data-bs-toggle="modal" 
                        data-bs-target="#addUserModal"
                        data-id="{{ $user->id }}" 
                        data-name="{{ $user->name }}" 
                        data-email="{{ $user->email }}" 
                        data-role="{{ $user->role }}">
                    <i class="bi bi-pencil"></i>
                </button>
               <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to delete this user?')">
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn btn-sm btn-outline-danger">
                      <i class="bi bi-trash"></i>
                  </button>
              </form>
              </td>
            </tr>          
            @empty
            <tr>
              <td colspan="6" class="text-center py-4">No users found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <nav class="mt-4" aria-label="User navigation">
      {{ $users->links() }}
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
            <input id="name" type="text" class="form-control" value="{{ old('name') }}" name="name" required>
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
              <option value="manager">Project Manager</option>
              <option value="member">Member</option>
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
          <button type="submit" class="btn btn-primary submit-btn">Save User</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    const addUserModal = document.getElementById('addUserModal');
    if (addUserModal) {
        addUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            const modalTitle = addUserModal.querySelector('.modal-title');
            const modalSubmit = addUserModal.querySelector('.submit-btn');
            const form = addUserModal.querySelector('#userForm');
            const nameInput = addUserModal.querySelector('#name');
            const emailInput = addUserModal.querySelector('#email');
            const roleSelect = addUserModal.querySelector('#role');
            const passwordInput = addUserModal.querySelector('#password');
            
            // Check if triggered by an edit button or the "New User" button
            if (button && button.classList.contains('edit-user-btn')) {
                const userId = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const email = button.getAttribute('data-email');
                const role = button.getAttribute('data-role');

                modalTitle.textContent = 'Edit User';
                modalSubmit.textContent = 'Update User';
                form.action = `{{ route('user.update', ':id') }}`.replace(':id', userId);
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                } else {
                    methodInput.value = 'PUT';
                }

                nameInput.value = name || '';
                emailInput.value = email || '';
                roleSelect.value = role || 'member'; // Properly assigns the specific user role
                passwordInput.required = false;
            } else {
                // Reset for "New User" mode
                modalTitle.textContent = 'Add User';
                modalSubmit.textContent = 'Save User';
                form.action = "{{ route('user.submit') }}";
                
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();

                form.reset();
                roleSelect.value = 'member'; // Default value reset
                passwordInput.required = true;
            }
        });
    }
</script>
@endpush