@extends('layouts.layout')

@section('content')
<div class="main-content">
    <header class="topbar d-flex align-items-center justify-content-between px-3"><h5 class="mb-0">Expenses</h5><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#expenceModal"><i class="bi bi-plus-lg"></i> New Expense</button></header>
    <div class="page-content"><div class="card"><div class="table-responsive">
        <table class="table align-middle mb-0"><thead><tr><th>Title</th><th>Category</th><th>Project</th><th>Amount</th><th>Date</th><th>Status</th><th class="text-end">Actions</th></tr></thead><tbody>
        @forelse($expences as $expence)
        <tr><td class="fw-semibold">{{ $expence->title }}</td><td>{{ ucfirst($expence->category) }}</td><td>{{ $expence->project?->name ?: '-' }}</td><td>{{ $expence->currency }} {{ number_format((float) $expence->amount, 2) }}</td><td>{{ $expence->expense_date?->format('Y-m-d') }}</td><td><span class="badge {{ $expence->status === 'approved' ? 'bg-success-subtle text-success' : ($expence->status === 'rejected' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">{{ ucfirst($expence->status) }}</span></td>
        <td class="text-end"><button class="btn btn-sm btn-outline-secondary edit-expence-btn" data-bs-toggle="modal" data-bs-target="#expenceModal" data-id="{{ $expence->id }}" data-project-id="{{ $expence->project_id }}" data-category="{{ $expence->category }}" data-title="{{ $expence->title }}" data-description="{{ $expence->description }}" data-amount="{{ $expence->amount }}" data-currency="{{ $expence->currency }}" data-expense-date="{{ $expence->expense_date?->format('Y-m-d') }}" data-vendor="{{ $expence->vendor }}" data-payment-method="{{ $expence->payment_method }}" data-receipt-path="{{ $expence->receipt_path }}" data-status="{{ $expence->status }}" data-notes="{{ $expence->notes }}"><i class="bi bi-pencil"></i></button>
        <form action="{{ route('expence.destroy', $expence) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td></tr>
        @empty <tr><td colspan="7" class="text-center py-4">No expenses found.</td></tr> @endforelse
        </tbody></table>
    </div></div><nav class="mt-4">{{ $expences->links() }}</nav></div>
</div>
<div class="modal fade" id="expenceModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><form action="{{ route('expence.store') }}" method="POST">@csrf
<div class="modal-header"><h5 class="modal-title">Add Expense</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Title</label><input name="title" id="expence-title" class="form-control" required></div><div class="col-md-6"><label class="form-label">Category</label><input name="category" id="expence-category" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Project</label><select name="project_id" id="expence-project-id" class="form-select"><option value="">No project</option>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->name }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Amount</label><input name="amount" id="expence-amount" type="number" step="0.01" min="0" class="form-control" required></div><div class="col-md-3"><label class="form-label">Currency</label><input name="currency" id="expence-currency" value="USD" maxlength="3" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Expense date</label><input name="expense_date" id="expence-expense-date" type="date" class="form-control" required></div><div class="col-md-4"><label class="form-label">Vendor</label><input name="vendor" id="expence-vendor" class="form-control"></div><div class="col-md-4"><label class="form-label">Payment method</label><input name="payment_method" id="expence-payment-method" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Receipt path</label><input name="receipt_path" id="expence-receipt-path" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" id="expence-status" class="form-select"><option value="pending">Pending</option><option value="approved">Approved</option><option value="rejected">Rejected</option></select></div>
<div class="col-12"><label class="form-label">Description</label><textarea name="description" id="expence-description" class="form-control"></textarea></div><div class="col-12"><label class="form-label">Notes</label><textarea name="notes" id="expence-notes" class="form-control"></textarea></div>
</div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Save Expense</button></div></form></div></div></div>
@endsection
@push('scripts')
<script>
const expenceModal = document.getElementById('expenceModal');
expenceModal?.addEventListener('show.bs.modal', event => { const button = event.relatedTarget, editing = button?.classList.contains('edit-expence-btn'), form = expenceModal.querySelector('form'); expenceModal.querySelector('.modal-title').textContent = editing ? 'Edit Expense' : 'Add Expense'; form.action = editing ? `{{ route('expence.update', ':id') }}`.replace(':id', button.dataset.id) : '{{ route('expence.store') }}'; let method = form.querySelector('[name="_method"]'); if (editing) { if (!method) { method = document.createElement('input'); method.type = 'hidden'; method.name = '_method'; form.appendChild(method); } method.value = 'PUT'; Object.keys(button.dataset).forEach(key => { const input = expenceModal.querySelector('#expence-' + key.replace(/[A-Z]/g, m => '-' + m.toLowerCase())); if (input) input.value = button.dataset[key] || ''; }); } else { method?.remove(); form.reset(); expenceModal.querySelector('#expence-currency').value = 'USD'; expenceModal.querySelector('#expence-status').value = 'pending'; } });
</script>
@endpush
