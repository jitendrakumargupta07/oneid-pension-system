@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- Row 1: Core Stats --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-users"></i></div>
            <div class="stat-value">{{ number_format($stats['total_citizens']) }}</div>
            <div class="stat-label">Total Citizens</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fas fa-user-check"></i></div>
            <div class="stat-value">{{ number_format($stats['verified_citizens']) }}</div>
            <div class="stat-label">Verified Citizens</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fas fa-clock"></i></div>
            <div class="stat-value">{{ number_format($stats['pending_verifications']) }}</div>
            <div class="stat-label">Pending Verifications</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card info">
            <div class="stat-icon info"><i class="fas fa-file-alt"></i></div>
            <div class="stat-value">{{ number_format($stats['total_applications']) }}</div>
            <div class="stat-label">Total Applications</div>
        </div>
    </div>
</div>

{{-- Row 2: Application + Fraud Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fas fa-times-circle"></i></div>
            <div class="stat-value">{{ $stats['rejected'] }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-value">{{ $stats['pending_apps'] }}</div>
            <div class="stat-label">Pending Apps</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fas fa-shield-alt"></i></div>
            <div class="stat-value">{{ $stats['fraud_open'] }}</div>
            <div class="stat-label">Fraud Alerts</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card gold">
            <div class="stat-icon gold"><i class="fas fa-rupee-sign"></i></div>
            <div class="stat-value">₹{{ number_format($stats['this_month_paid']/1000, 1) }}K</div>
            <div class="stat-label">This Month Paid</div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-value">₹{{ number_format($stats['total_disbursed']/100000, 1) }}L</div>
            <div class="stat-label">Total Disbursed</div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-line text-primary"></i> Applications Over Last 6 Months</div>
            <div class="card-body">
                <div class="chart-container"><canvas id="applicationsChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-pie text-primary"></i> Payment Status</div>
            <div class="card-body">
                <div class="chart-container" style="height:200px"><canvas id="paymentChart"></canvas></div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="badge bg-success">Paid</span><span class="fw-bold">{{ $paymentChart['paid'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="badge bg-warning text-dark">Pending</span><span class="fw-bold">{{ $paymentChart['pending'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-danger">Failed</span><span class="fw-bold">{{ $paymentChart['failed'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Applications + Fraud Alerts --}}
<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-alt text-primary"></i> Recent Applications</span>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead><tr><th>Applicant</th><th>Scheme</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($recentApplications as $app)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:13px">{{ $app->elderlyProfile?->full_name ?? 'N/A' }}</div>
                                <div style="font-size:11px;color:#9ca3af;font-family:monospace">{{ $app->elderlyProfile?->one_id }}</div>
                            </td>
                            <td><span style="font-size:12.5px">{{ $app->scheme?->name }}</span></td>
                            <td><span class="status-badge status-{{ $app->status }}">{{ ucfirst($app->status) }}</span></td>
                            <td style="font-size:12px;color:#6b7280">{{ $app->applied_at?->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">No applications yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-shield-alt text-danger"></i> Open Fraud Alerts</span>
                <a href="{{ route('admin.fraud.index') }}" class="btn btn-sm btn-outline-danger">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($fraudAlerts as $alert)
                <div class="d-flex align-items-start gap-3 px-4 py-3" style="border-bottom:1px solid #f0f0f7">
                    <span class="badge bg-{{ $alert->severity_color }} mt-1">{{ strtoupper($alert->severity) }}</span>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:12px">{{ $alert->type_label }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $alert->elderlyProfile?->full_name }} · {{ $alert->elderlyProfile?->one_id }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-shield-check fa-2x text-success mb-2 d-block"></i>
                    <small class="text-muted">No open fraud alerts</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Applications Line Chart
const appCtx = document.getElementById('applicationsChart').getContext('2d');
new Chart(appCtx, {
    type: 'line',
    data: {
        labels: @json(collect($appChart)->pluck('label')),
        datasets: [{
            label: 'Applications',
            data: @json(collect($appChart)->pluck('count')),
            borderColor: '#1a237e',
            backgroundColor: 'rgba(26,35,126,0.08)',
            tension: 0.4, fill: true,
            pointBackgroundColor: '#1a237e', pointRadius: 5, pointHoverRadius: 7,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});

// Payment Doughnut
const payCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(payCtx, {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending', 'Failed'],
        datasets: [{
            data: [{{ $paymentChart['paid'] }}, {{ $paymentChart['pending'] }}, {{ $paymentChart['failed'] }}],
            backgroundColor: ['#2e7d32', '#f57c00', '#c62828'],
            borderWidth: 3, borderColor: '#fff'
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false, cutout: '70%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
