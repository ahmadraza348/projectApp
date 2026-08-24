@extends('layouts.layout')
@section('content')
<div class="page-content">
  <div class="row g-3 justify-content-center">

    <div class="col-lg-4">
      <div class="card text-center">
        <div class="card-body">
          <div class="avatar-circle mx-auto mb-3" style="width:80px;height:80px;font-size:1.75rem;">{{ substr($user->name, 0, 2) }}</div>
          <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
          <p class="text-muted small mb-2">{{ $user->email }}</p>
          @php
          $roleBadgeClass = match($user->role) {
          'admin' => 'bg-danger-subtle text-danger border border-danger-subtle',
          'manager' => 'bg-primary-subtle text-primary border border-primary-subtle',
          default => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
          };
          @endphp
          <span class="badge {{ $roleBadgeClass }}">{{ $user->role }}</span>
          <hr>
          <div class="d-flex justify-content-around text-center">
            <div>
              <div class="fw-bold">{{ $user->projects->count() }}</div>
              <div class="small text-muted">Projects</div>
            </div>
            <div>
              <div class="fw-bold">{{ $user->tasks->count() }}</div>
              <div class="small text-muted">Tasks</div>
            </div>
            <div>
              <div class="fw-bold">{{ $user->created_at->format('M Y') }}</div>
              <div class="small text-muted">Joined</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card mb-3">
        <div class="card-header bg-white"><span class="section-title">Account Details</span></div>
        <div class="card-body">
          <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="name" value="{{ $user->name }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" class="form-control" name="email" value="{{ $user->email }}">
            </div>
            <div class="mb-0 text-end">
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header bg-white"><span class="section-title">Change Password</span></div>
        <div class="card-body">
          <form action="{{ route('user.profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label class="form-label">Current Password</label>
              <input type="password" class="form-control" name="current_password">
            </div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" name="password">
              </div>
              <div class="col-md-6">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation">
              </div>
            </div>
            <div class="mb-0 text-end">
              <button type="submit" class="btn btn-outline-primary">Update Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection