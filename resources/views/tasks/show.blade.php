@extends('layouts.layout')
@section('content')

<div class="main-content">
    <header class="topbar d-flex align-items-center px-3">
        <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="projects.html">Projects</a></li>
                <li class="breadcrumb-item"><a href="project-show.html">Payment Gateway</a></li>
                <li class="breadcrumb-item active">Fix checkout bug</li>
            </ol>
        </nav>
    </header>

    <div class="page-content">
        <div class="row g-3">

            <!-- Main column -->
            <div class="col-lg-8">

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge badge-urgent mb-2">Urgent</span>
                                <h4 class="fw-bold mb-0">Fix checkout bug</h4>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <span class="badge badge-in_progress">In Progress</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">To Do</a></li>
                                    <li><a class="dropdown-item" href="#">In Progress</a></li>
                                    <li><a class="dropdown-item" href="#">Review</a></li>
                                    <li><a class="dropdown-item" href="#">Completed</a></li>
                                </ul>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Customers report the payment callback occasionally fails to update order status after a successful Stripe charge. Debug the webhook handler and add idempotency handling.</p>

                        <hr>

                        <div class="row g-3 small">
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Project</div>
                                <div class="fw-semibold"><a href="project-show.html">Payment Gateway</a></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Assignee</div>
                                <div class="fw-semibold d-flex align-items-center gap-2">
                                    <div class="avatar-circle" style="width:22px;height:22px;font-size:.6rem;">BH</div> Bilal Hussain
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Due Date</div>
                                <div class="fw-semibold">Aug 19, 2026</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-muted">Estimated</div>
                                <div class="fw-semibold">6 hrs</div>
                            </div>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <a href="task-create.html" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i> Edit</a>
                            <button class="btn btn-sm btn-outline-danger" data-confirm-delete="this task"><i class="bi bi-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="card mb-3">
                    <div class="card-header bg-white"><span class="section-title">Attachments</span></div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-file-earmark-image me-2"></i> error-screenshot.png</span>
                                <div class="d-flex gap-3 align-items-center">
                                    <span class="text-muted small">240 KB</span>
                                    <a href="#" class="small">Download</a>
                                    <button class="btn btn-sm btn-link text-danger p-0" data-confirm-delete="this file"><i class="bi bi-trash"></i></button>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-file-earmark-text me-2"></i> webhook-logs.txt</span>
                                <div class="d-flex gap-3 align-items-center">
                                    <span class="text-muted small">18 KB</span>
                                    <a href="#" class="small">Download</a>
                                    <button class="btn btn-sm btn-link text-danger p-0" data-confirm-delete="this file"><i class="bi bi-trash"></i></button>
                                </div>
                            </li>
                        </ul>

                        <form action="#" method="POST" enctype="multipart/form-data">
                            <div class="input-group">
                                <input type="file" class="form-control" id="attachmentInput" name="attachment" multiple>
                                <button class="btn btn-outline-primary" type="submit">Upload</button>
                            </div>
                            <ul id="attachmentPreviewList" class="list-group mt-2"></ul>
                            <div class="form-text">Max 10MB per file. Allowed: jpg, png, pdf, docx, txt, zip.</div>
                        </form>
                    </div>
                </div>

                <!-- Comments -->
                <div class="card">
                    <div class="card-header bg-white"><span class="section-title">Comments</span></div>
                    <div class="card-body">
                        <div id="commentList">
                            <div class="comment-item d-flex gap-3">
                                <div class="avatar-circle">SA</div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <strong>Sara Ahmed</strong>
                                        <span class="text-muted small">2 days ago</span>
                                    </div>
                                    <p class="mb-0 mt-1">Can we get a status update on this? It's blocking the release.</p>
                                </div>
                            </div>
                            <div class="comment-item d-flex gap-3">
                                <div class="avatar-circle">BH</div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <strong>Bilal Hussain</strong>
                                        <span class="text-muted small">1 day ago</span>
                                    </div>
                                    <p class="mb-0 mt-1">Found the root cause — the webhook wasn't verifying the signature correctly under load. Fix is in progress.</p>
                                </div>
                            </div>
                        </div>

                        <form id="commentForm" action="#" method="POST" class="mt-3">
                            <div class="mb-2">
                                <textarea class="form-control" name="comment" rows="3" placeholder="Write a comment..." required></textarea>
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

                        <form action="#" method="POST" class="mb-3 pb-3 border-bottom">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small">Hours</label>
                                    <input type="number" class="form-control form-control-sm" name="hours" min="0.25" step="0.25" placeholder="2.5" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small">Date</label>
                                    <input type="date" class="form-control form-control-sm" name="logged_at" required>
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
                            <span class="fw-bold text-dark">8.5 hrs</span>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Bilal Hussain</span>
                                    <span class="small text-muted">Aug 18</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Debugged payment callback issue</span>
                                    <span>3.0 hrs</span>
                                </div>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Bilal Hussain</span>
                                    <span class="small text-muted">Aug 17</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Reproduced issue locally</span>
                                    <span>2.0 hrs</span>
                                </div>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Sara Ahmed</span>
                                    <span class="small text-muted">Aug 16</span>
                                </div>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Investigated customer reports</span>
                                    <span>3.5 hrs</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection