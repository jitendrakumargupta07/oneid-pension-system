@extends('layouts.admin')

@section('title', 'Pension Applications')
@section('page-title', 'Pension Applications')

@section('breadcrumb')
    <li class="breadcrumb-item active">Applications</li>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-file-alt me-2" style="color:var(--accent);"></i>All Pension Applications</h2>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by app no., name, or OneID..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search','status']))
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-primary">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-list text-primary"></i> Applications ({{ $applications->total() }})</div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>App No.</th>
                    <th>Citizen</th>
                    <th>Scheme</th>
                    <th>Monthly Amt.</th>
                    <th>Status</th>
                    <th>Applied On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td><code style="font-size:12px;color:var(--primary);">{{ $app->application_number }}</code></td>
                    <td>
                        <div class="fw-semibold" style="font-size:13px;">{{ $app->elderlyProfile?->full_name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:#9ca3af;font-family:monospace;">{{ $app->elderlyProfile?->one_id }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $app->scheme?->name }}</td>
                    <td class="fw-semibold text-success" style="font-size:13.5px;">₹{{ number_format($app->scheme?->monthly_amount, 2) }}</td>
                    <td><span class="badge-status badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span></td>
                    <td style="font-size:12px;color:#6b7280;">{{ $app->applied_at?->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.applications.show', $app) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i>Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h5>No applications found</h5>
                        <p>No pension applications match your criteria.</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($applications->hasPages())
    <div class="card-body pt-3 border-top">
        {{ $applications->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
