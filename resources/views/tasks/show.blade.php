@extends('layouts.layout')
@section('content')

<div class="main-content">
    <header class="topbar d-flex align-items-center px-3">
        <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
                <li class="breadcrumb-item"><a href="{{ route('project.show', $task->project) }}">{{ $task->project->name }}</a></li>
                <li class="breadcrumb-item active">{{ $task->title }}</li>
            </ol>
        </nav>
    </header>

    <div class="page-content">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-3">

            <!-- Main column -->
            <div class="col-lg-8">

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge badge-{{ $task->priority }} mb-2">{{ ucfirst($task->priority) }}</span>
                                <h4 class="fw-bold mb-0">{{ $task->title }}</h4>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <span class="badge badge-{{ $task->status }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" data-url="{{ route('task.update-status', $task) }}">
                                    @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'completed' => 'Completed'] as $value => $label)
                                    <li><a class="dropdown-item status-option" href="#" data-status="{{ $value }}">{{ $label }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <p class="text-muted mb-0">{{ $task->description }}</p>

                        <hr>

                        <div class="row g-3 small">
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Project</div>
                                <div class="fw-semibold"><a href="{{ route('project.show', $task->project) }}">{{ $task->project->name }}</a></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Assignee</div>
                                <div class="fw-semibold d-flex align-items-center gap-2">
                                    @if ($task->assignee)
                                    <div class="avatar-circle" style="width:22px;height:22px;font-size:.6rem;">{{ strtoupper(substr($task->assignee->name, 0, 2)) }}</div>
                                    {{ $task->assignee->name }}
                                    @else
                                    <span class="text-muted">Unassigned</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Due Date</div>
                                <div class="fw-semibold">{{ $task->due_date?->format('M d, Y') ?? '—' }}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Estimated</div>
                                <div class="fw-semibold">{{ $task->estimated_hours ?? '—' }} hrs</div>
                            </div>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <a href="{{ route('task.edit', $task) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</a>
                            <form action="{{ route('task.destroy', $task) }}" method="POST" data-confirm-delete="this task">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="card mb-3">
                    <div class="card-header bg-white"><span class="section-title">Attachments</span></div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            @forelse ($task->attachments as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-file-earmark me-2"></i> {{ $file->original_name }}</span>
                                <div class="d-flex gap-3 align-items-center">
                                    <span class="text-muted small">{{ $file->size_for_humans }}</span>
                                    <a href="{{ $file->url }}" target="_blank" class="small">Download</a>
                                    <form action="{{ route('task.attachments.destroy', $file) }}" method="POST" data-confirm-delete="this file">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-link text-danger p-0" type="submit"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                            @empty
                            <li class="list-group-item text-muted small">No attachments yet.</li>
                            @endforelse
                        </ul>

                        <form action="{{ route('task.attachments.store', $task) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" name="attachment[]" multiple required>
                                <button class="btn btn-outline-primary" type="submit">Upload</button>
                            </div>
                            <div class="form-text">Max 10MB per file. Allowed: jpg, png, pdf, docx, txt, zip.</div>
                        </form>
                    </div>
                </div>

                <!-- Comments -->
                <div class="card">
                    <div class="card-header bg-white"><span class="section-title">Comments</span></div>
                    <div class="card-body">
                        <div id="commentList">
                            @forelse ($task->comments as $comment)
                            <div class="comment-item d-flex gap-3 mb-3">
                                <div class="avatar-circle">{{ strtoupper(substr($comment->user->name, 0, 2)) }}</div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $comment->user->name }}</strong>
                                        <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-0 mt-1">{{ $comment->body }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted small">No comments yet.</p>
                            @endforelse
                        </div>

                        <form action="{{ route('task.comments.store', $task) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="mb-2">
                                <textarea class="form-control" name="body" rows="3" placeholder="Write a comment..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Side column: Time logs -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-white"><span class="section-title">Time Logs</span></div>
                    <div class="card-body">

                        <form action="{{ route('task.time-logs.store', $task) }}" method="POST" class="mb-3 pb-3 border-bottom">
                            @csrf
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small">Hours</label>
                                    <input type="number" class="form-control form-control-sm" name="hours" min="0.25" step="0.25" placeholder="2.5" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Date</label>
                                    <input type="date" class="form-control form-control-sm" name="logged_at" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small">Description</label>
                                    <input type="text" class="form-control form-control-sm" name="description" placeholder="What did you work on?">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">Log Time</button>
                        </form>

                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span>Total logged</span>
                            <span class="fw-bold text-dark">{{ number_format($task->timeLogs->sum('hours'), 1) }} hrs</span>
                        </div>

                        <ul class="list-group list-group-flush">
                            @forelse ($task->timeLogs as $log)
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">{{ $log->user->name }}</span>
                                    <span class="small text-muted">{{ $log->logged_at->format('M d') }}</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">{{ $log->description ?: '—' }}</span>
                                    <span>{{ $log->hours }} hrs</span>
                                </div>
                            </li>
                            @empty
                            <li class="list-group-item px-0 text-muted small">No time logged yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.status-option').forEach(opt => {
        opt.addEventListener('click', async e => {
            e.preventDefault();
            const url = opt.closest('.dropdown-menu').dataset.url;

            const res = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    status: opt.dataset.status
                }),
            });

            if (res.ok) {
                location.reload();
            } else {
                alert('Could not update status, please try again.');
            }
        });
    });
</script>

@endsection