@extends('layouts.layout')
@section('content')
<div class="main-content">
    <header class="topbar d-flex align-items-center px-3">
        <button id="sidebarToggle" class="btn btn-light border d-lg-none me-2"><i class="bi bi-list"></i></button>
        <h5 class="mb-0 d-none d-lg-block">Reports</h5>
    </header>

    <div class="page-content">

        <!-- Summary stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-primary">20</div>
                    <div class="small text-muted">Total Projects</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-success">248</div>
                    <div class="small text-muted">Total Tasks</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-warning">62%</div>
                    <div class="small text-muted">Avg Completion</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-3 h-100 text-center">
                    <div class="fs-3 fw-bold text-info">1,240</div>
                    <div class="small text-muted">Hours Logged</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Project progress report -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-white"><span class="section-title">Project Progress</span></div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Tasks</th>
                                    <th>Completed %</th>
                                    <th>Hours Logged</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Website Redesign</td>
                                    <td>12</td>
                                    <td style="width:160px;">
                                        <div class="progress">
                                            <div class="progress-bar" style="width:65%"></div>
                                        </div>
                                    </td>
                                    <td>142h</td>
                                </tr>
                                <tr>
                                    <td>Mobile App API</td>
                                    <td>18</td>
                                    <td style="width:160px;">
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width:85%"></div>
                                        </div>
                                    </td>
                                    <td>210h</td>
                                </tr>
                                <tr>
                                    <td>Internal CRM</td>
                                    <td>9</td>
                                    <td style="width:160px;">
                                        <div class="progress">
                                            <div class="progress-bar bg-secondary" style="width:15%"></div>
                                        </div>
                                    </td>
                                    <td>38h</td>
                                </tr>
                                <tr>
                                    <td>Payment Gateway</td>
                                    <td>15</td>
                                    <td style="width:160px;">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width:100%"></div>
                                        </div>
                                    </td>
                                    <td>256h</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Member workload -->
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header bg-white"><span class="section-title">Member Workload</span></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1"><span>Bilal Hussain</span><span>14 tasks · 92h</span></div>
                            <div class="progress">
                                <div class="progress-bar" style="width:78%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1"><span>Ayesha Malik</span><span>11 tasks · 74h</span></div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width:62%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1"><span>Hamza Tariq</span><span>9 tasks · 58h</span></div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width:48%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1"><span>Fatima Noor</span><span>7 tasks · 41h</span></div>
                            <div class="progress">
                                <div class="progress-bar bg-secondary" style="width:35%"></div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between small mb-1"><span>Sara Ahmed</span><span>6 tasks · 33h</span></div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width:28%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-download"></i> Export as CSV</button>
        </div>

    </div>
</div>
@endsection