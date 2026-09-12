@extends('layouts.layout')

@section('content')
<div class="main-content">
    <header class="topbar d-flex align-items-center justify-content-between px-3">
        <h5 class="mb-0">Clients</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#clientModal"><i class="bi bi-plus-lg"></i> New Client</button>
    </header>
    <div class="page-content">
        <div class="card"><div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Name</th><th>Company</th><th>Contact</th><th>Industry</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td class="fw-semibold">{{ $client->name }}</td>
                        <td>{{ $client->company_name ?: '-' }}</td>
                        <td><div>{{ $client->email ?: '-' }}</div><small class="text-muted">{{ $client->phone }}</small></td>
                        <td>{{ $client->industry ?: '-' }}</td>
                        <td><span class="badge {{ $client->status === 'active' ? 'bg-success-subtle text-success' : ($client->status === 'lead' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary') }}">{{ ucfirst($client->status) }}</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-secondary edit-client-btn" data-bs-toggle="modal" data-bs-target="#clientModal"
                                data-id="{{ $client->id }}" data-name="{{ $client->name }}" data-company-name="{{ $client->company_name }}"
                                data-email="{{ $client->email }}" data-phone="{{ $client->phone }}" data-alternate-phone="{{ $client->alternate_phone }}"
                                data-website="{{ $client->website }}" data-address="{{ $client->address }}" data-city="{{ $client->city }}"
                                data-country="{{ $client->country }}" data-industry="{{ $client->industry }}" data-status="{{ $client->status }}" data-notes="{{ $client->notes }}"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('client.destroy', $client) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this client?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty <tr><td colspan="6" class="text-center py-4">No clients found.</td></tr> @endforelse
                </tbody>
            </table>
        </div></div>
        <nav class="mt-4">{{ $clients->links() }}</nav>
    </div>
</div>

<div class="modal fade" id="clientModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form action="{{ route('client.store') }}" method="POST">@csrf
        <div class="modal-header"><h5 class="modal-title">Add Client</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><div class="row g-3">
            @foreach([['name','Name',true],['company_name','Company name',false],['email','Email',false],['phone','Phone',false],['alternate_phone','Alternate phone',false],['website','Website',false],['city','City',false],['country','Country',false],['industry','Industry',false]] as $field)
            <div class="col-md-{{ $field[0] === 'name' || $field[0] === 'company_name' ? '6' : '4' }}"><label class="form-label">{{ $field[1] }}</label><input type="{{ $field[0] === 'email' ? 'email' : ($field[0] === 'website' ? 'url' : 'text') }}" name="{{ $field[0] }}" id="client-{{ $field[0] }}" class="form-control" {{ $field[2] ? 'required' : '' }}></div>
            @endforeach
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" id="client-address" class="form-control" rows="2"></textarea></div>
            <div class="col-md-4"><label class="form-label">Status</label><select name="status" id="client-status" class="form-select"><option value="active">Active</option><option value="lead">Lead</option><option value="inactive">Inactive</option></select></div>
            <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" id="client-notes" class="form-control" rows="2"></textarea></div>
        </div></div>
        <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Save Client</button></div>
    </form>
</div></div></div>
@endsection

@push('scripts')
<script>
const clientModal = document.getElementById('clientModal');
clientModal?.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget, editing = button?.classList.contains('edit-client-btn'), form = clientModal.querySelector('form');
    clientModal.querySelector('.modal-title').textContent = editing ? 'Edit Client' : 'Add Client';
    form.action = editing ? `{{ route('client.update', ':id') }}`.replace(':id', button.dataset.id) : '{{ route('client.store') }}';
    let method = form.querySelector('[name="_method"]');
    if (editing) { if (!method) { method = document.createElement('input'); method.type = 'hidden'; method.name = '_method'; form.appendChild(method); } method.value = 'PUT'; Object.keys(button.dataset).forEach(key => { const input = clientModal.querySelector('#client-' + key.replace(/[A-Z]/g, m => '-' + m.toLowerCase())); if (input) input.value = button.dataset[key] || ''; }); }
    else { method?.remove(); form.reset(); clientModal.querySelector('#client-status').value = 'active'; }
});
</script>
@endpush
