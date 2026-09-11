@extends('layouts.layout')

@section('content')
<div class="main-content">
    <header class="topbar d-flex align-items-center justify-content-between px-3">
        <div class="d-flex align-items-center gap-2">
            <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
            <h5 class="mb-0 d-none d-lg-block">Departments</h5>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#departmentModal">
            <i class="bi bi-plus-lg"></i> New Department
        </button>
    </header>

    <div class="page-content">
        <div class="card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                        <tr>
                            <td class="fw-semibold">{{ $department->name }}</td>
                            <td>{{ $department->user->name }}</td>
                            <td class="text-muted small">{{ $department->description }}</td>
                            <td>
                                <span class="badge {{ $department->status ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }}">
                                    {{ $department->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary edit-department-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#departmentModal"
                                    data-id="{{ $department->id }}"
                                    data-user-id="{{ $department->user_id }}"
                                    data-name="{{ $department->name }}"
                                    data-description="{{ $department->description }}"
                                    data-status="{{ $department->status ? 1 : 0 }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('department.destroy', $department) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this department?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4">No departments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <nav class="mt-4" aria-label="Department navigation">{{ $departments->links() }}</nav>
    </div>
</div>

<div class="modal fade" id="departmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('department.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="department-user">User</label>
                        <select class="form-control" id="department-user" name="user_id" required>
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="department-name">Department Name</label>
                        <input type="text" id="department-name" class="form-control" name="name" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="department-description">Description</label>
                        <textarea class="form-control" id="department-description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="department-status">Status</label>
                        <select class="form-control" id="department-status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const departmentModal = document.getElementById('departmentModal');
    if (departmentModal) {
        departmentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const form = departmentModal.querySelector('form');
            const methodInput = form.querySelector('input[name="_method"]');
            const editing = button && button.classList.contains('edit-department-btn');

            departmentModal.querySelector('.modal-title').textContent = editing ? 'Edit Department' : 'Add Department';
            form.action = editing
                ? `{{ route('department.update', ':id') }}`.replace(':id', button.dataset.id)
                : '{{ route('department.store') }}';

            if (editing) {
                const input = methodInput || document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.value = 'PUT';
                if (!methodInput) form.appendChild(input);
                departmentModal.querySelector('#department-name').value = button.dataset.name || '';
                departmentModal.querySelector('#department-user').value = button.dataset.userId || '';
                departmentModal.querySelector('#department-description').value = button.dataset.description || '';
                departmentModal.querySelector('#department-status').value = button.dataset.status || '1';
            } else {
                if (methodInput) methodInput.remove();
                form.reset();
                departmentModal.querySelector('#department-user').value = '';
                departmentModal.querySelector('#department-status').value = '1';
            }
        });
    }
</script>
@endpush
