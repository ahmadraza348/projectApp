@extends('layouts.layout')

@section('content')

<div class="main-content">

    {{-- Header --}}
    <header class="topbar d-flex align-items-center px-3">

        <button
            id="sidebarToggle"
            class="btn btn-light border d-lg-none me-2">
            <i class="bi bi-list"></i>
        </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Activity Logs
                </li>
            </ol>
        </nav>

    </header>


    {{-- Page content --}}
    <div class="page-content">

        {{-- Page heading --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="fw-bold mb-1">
                    Activity Logs
                </h4>

                <p class="text-muted mb-0">
                    Track important actions performed in the system.
                </p>
            </div>

        </div>


        {{-- Filters --}}
        <div class="card mb-4">

            <div class="card-body">

                <form
                    action="{{ route('user.activity-log.index') }}"
                    method="GET">

                    <div class="row g-3 align-items-end">

                        {{-- Search --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Search user, action or description...">

                        </div>


                        {{-- Action --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Action
                            </label>

                            <select
                                name="action"
                                class="form-select">

                                <option value="">
                                    All Actions
                                </option>

                                @foreach($actions as $action)

                                    <option
                                        value="{{ $action }}"
                                        {{ request('action') === $action ? 'selected' : '' }}>

                                        {{ Str::title(str_replace('_', ' ', $action)) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Buttons --}}
                        <div class="col-md-2 d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary w-100">

                                <i class="bi bi-search"></i>
                                Filter

                            </button>

                            <a
                                href="{{ route('user.activity-log.index') }}"
                                class="btn btn-outline-secondary"
                                title="Clear filters">

                                <i class="bi bi-arrow-counterclockwise"></i>

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Activity list --}}
        <div class="card">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <span class="section-title fw-bold">
                    Recent Activity
                </span>

                <span class="text-muted small">
                    {{ $activities->total() }} total records
                </span>

            </div>


            <div class="card-body p-0">

                @forelse($activities as $activity)

                    <div class="px-4 py-3 border-bottom">

                        <div class="d-flex gap-3">

                            {{-- Icon --}}
                            <div class="pt-1">

                                @php
                                    $icon = match($activity->action) {
                                        'created' => 'bi-plus-circle text-success',
                                        'updated' => 'bi-pencil-square text-primary',
                                        'deleted' => 'bi-trash text-danger',
                                        'status_changed' => 'bi-arrow-repeat text-warning',
                                        'completed' => 'bi-check-circle text-success',
                                        'assigned' => 'bi-person-plus text-info',
                                        default => 'bi-activity text-secondary',
                                    };
                                @endphp

                                <i class="bi {{ $icon }} fs-5"></i>

                            </div>


                            {{-- Activity content --}}
                            <div class="flex-grow-1">

                                <div class="d-flex justify-content-between gap-3">

                                    <div>

                                        {{-- User --}}
                                        <span class="fw-semibold">
                                            {{ $activity->user->name ?? 'System' }}
                                        </span>

                                        {{-- Action --}}
                                        <span class="text-muted">
                                            {{ Str::title(str_replace('_', ' ', $activity->action)) }}
                                        </span>

                                        {{-- Subject --}}
                                        @if($activity->subject)

                                            <span class="fw-semibold">
                                                {{ class_basename($activity->subject_type) }}
                                                #{{ $activity->subject_id }}
                                            </span>

                                        @endif

                                    </div>


                                    {{-- Time --}}
                                    <div class="text-muted small text-nowrap">

                                        {{ $activity->created_at->diffForHumans() }}

                                    </div>

                                </div>


                                {{-- Description --}}
                                @if($activity->description)

                                    <div class="text-muted mt-1">
                                        {{ $activity->description }}
                                    </div>

                                @endif


                                {{-- Properties --}}
                                @if(!empty($activity->properties))

                                    <div class="mt-2">

                                        @foreach($activity->properties as $key => $value)

                                            <span class="badge bg-light text-dark border me-1">

                                                {{ Str::title(str_replace('_', ' ', $key)) }}:

                                                @if(is_array($value))
                                                    {{ json_encode($value) }}
                                                @else
                                                    {{ $value }}
                                                @endif

                                            </span>

                                        @endforeach

                                    </div>

                                @endif


                                {{-- Exact date --}}
                                <div class="small text-muted mt-2">

                                    {{ $activity->created_at->format('M d, Y h:i A') }}

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i class="bi bi-clock-history fs-1 text-muted"></i>

                        <h6 class="mt-3">
                            No activity found
                        </h6>

                        <p class="text-muted mb-0">
                            There are no activity logs matching your filters.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- Pagination --}}
            @if($activities->hasPages())

                <div class="card-footer bg-white">

                    {{ $activities->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
