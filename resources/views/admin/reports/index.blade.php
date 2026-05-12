@extends('layouts.admin')
@section('title', 'Reports')
@section('content')
<div class="page-header mb-4">
    <h2 class="page-title"><i class="fas fa-file-pdf text-danger me-2"></i>Report Generation</h2>
    <p class="page-subtitle">Generate and download official pension reports in PDF format.</p>
</div>

<div class="row g-4">
    {{-- Monthly Payment Report --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div style="width:50px;height:50px;background:rgba(220,53,69,.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:14px">
                        <i class="fas fa-rupee-sign text-danger fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Monthly Payment Report</h5>
                        <small class="text-muted">Download all pension disbursements for a given month</small>
                    </div>
                </div>
                <form method="GET" action="{{ route('admin.reports.payments.pdf') }}">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:12px">Month</label>
                            <select name="month" class="form-select form-select-sm" required>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" style="font-size:12px">Year</label>
                            <select name="year" class="form-select form-select-sm" required>
                                @for($y = now()->year; $y >= 2024; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-download me-2"></i>Download Payment Report PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Applications Report --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div style="width:50px;height:50px;background:rgba(26,35,126,.1);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:14px">
                        <i class="fas fa-file-alt fa-lg" style="color:var(--primary)"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Applications Report</h5>
                        <small class="text-muted">Download all pension applications with status</small>
                    </div>
                </div>
                <form method="GET" action="{{ route('admin.reports.applications.pdf') }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px">Filter by Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Applications</option>
                            <option value="pending">Pending Only</option>
                            <option value="approved">Approved Only</option>
                            <option value="rejected">Rejected Only</option>
                        </select>
                    </div>
                    <button type="submit" class="btn w-100" style="background:var(--primary);color:#fff">
                        <i class="fas fa-download me-2"></i>Download Applications Report PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
