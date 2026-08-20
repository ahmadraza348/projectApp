@extends('layouts.layout')
@section('content')
<div class="main-content">
    <header class="topbar d-flex align-items-center justify-content-between px-3">
        <div class="d-flex align-items-center gap-2">
            <button id="sidebarToggle" class="btn btn-light border d-lg-none"><i class="bi bi-list"></i></button>
            <h5 class="mb-0 d-none d-lg-block">Project Categories</h5>
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="bi bi-plus-lg"></i> New Category</button>
    </header>

    <div class="page-content">
        <div class="card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th># Projects</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($categories as $cat)
                        @php
                        $status = $cat->status == 1 ? 'Active' : 'Inactive';
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{$cat->name}}</td>
                            <td class="text-muted small">{{$cat->description}}</td>
                            <td>--</td>
                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $status }}</span></td>
                            <td class="text-end">


                                <button class="btn btn-sm btn-outline-secondary edit-user-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addCategoryModal"
                                    data-id="{{ $cat->id }}"
                                    data-name="{{ $cat->name }}"
                                    data-description="{{ $cat->description }}"
                                    data-status="{{ $cat->status }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('category.destroy', $cat) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                            <td colspan="6" class="text-center py-4">No Record found.</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <nav class="mt-4" aria-label="User navigation">
            {{ $categories->links() }}
        </nav>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" id="name" class="form-control" name="name" value="{{old('name')}}" required>
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{old('description')}}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

 @push('scripts')
<script>
    const addCategoryModal = document.getElementById('addCategoryModal');
    if (addCategoryModal) {
        addCategoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const modalTitle = addCategoryModal.querySelector('.modal-title');
            const form = addCategoryModal.querySelector('form');
            const nameInput = addCategoryModal.querySelector('#name');
            const descriptionInput = addCategoryModal.querySelector('#description');
            const statusSelect = addCategoryModal.querySelector('#status');

            if (button && button.classList.contains('edit-user-btn')) {
                const categoryId = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const description = button.getAttribute('data-description');
                const status = button.getAttribute('data-status');

                modalTitle.textContent = 'Edit Category';
                form.action = `{{ route('category.update', ':id') }}`.replace(':id', categoryId);
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
                descriptionInput.value = description || '';
                statusSelect.value = status || '1'; 
            } else {
                modalTitle.textContent = 'Add Category';
                form.action = "{{ route('category.store') }}"; 

                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();

                form.reset();
                statusSelect.value = '1'; 
            }
        });
    }
</script>
@endpush 