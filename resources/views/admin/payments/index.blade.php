@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-money-bill-wave me-2" style="color:var(--accent)"></i>Pension Payment Management</h2>
        <p class="page-subtitle">Track and manage all monthly pension disbursements.</p>
    </div>
    <a href="{{ route('admin.payments.create') }}" class="btn fw-bold px-4" style="background:var(--primary);color:#fff">
        <i class="fas fa-plus me-2"></i>Record Payment
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card gold">
            <div class="stat-icon gold"><i class="fas fa-rupee-sign"></i></div>
            <div class="stat-value">₹{{ number_format($totalPaid/1000, 1) }}K</div>
            <div class="stat-label">Total Disbursed</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value">₹{{ number_format($thisMonth/1000, 1) }}K</div>
            <div class="stat-label">This Month Paid</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-receipt"></i></div>
            <div class="stat-value">{{ $payments->total() }}</div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search citizen name or OneID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="month" class="form-select form-select-sm">
                    <option value="">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="year" class="form-control form-control-sm" placeholder="Year" value="{{ request('year', date('Y')) }}" min="2000">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('status')==='paid'?'selected':'' }}>✅ Paid</option>
                    <option value="pending" {{ request('status')==='pending'?'selected':'' }}>⏳ Pending</option>
                    <option value="failed" {{ request('status')==='failed'?'selected':'' }}>❌ Failed</option>
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-1"></i>Export PDF</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>RECEIPT</th>
                        <th>CITIZEN</th>
                        <th>SCHEME</th>
                        <th>PERIOD</th>
                        <th>AMOUNT</th>
                        <th>DATE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                    <tr>
                        <td>
                            <code style="font-size:11px;color:var(--primary);background:#e8eaf6;padding:2px 6px;border-radius:4px">
                                {{ $pay->receipt_number }}
                            </code>
                        </td>
                        <td>
                            <div class="fw-semibold" style="font-size:13px">{{ $pay->application?->elderlyProfile?->full_name }}</div>
                            <div style="font-size:11px;color:#9ca3af;font-family:monospace">{{ $pay->application?->elderlyProfile?->one_id }}</div>
                        </td>
                        <td style="font-size:12px;max-width:150px">{{ $pay->application?->scheme?->name }}</td>
                        <td style="font-size:13px;font-weight:600">
                            {{ date('M', mktime(0,0,0,$pay->month,1)) }} {{ $pay->year }}
                        </td>
                        <td>
                            <span class="fw-bold" style="font-size:14px;color:#27ae60">₹{{ number_format($pay->amount, 2) }}</span>
                        </td>
                        <td style="font-size:12px;color:#6b7280">{{ $pay->payment_date?->format('d M Y') }}</td>
                        <td>
                            <span class="status-badge status-{{ $pay->status === 'paid' ? 'approved' : ($pay->status === 'failed' ? 'rejected' : 'pending') }}">
                                {{ ucfirst($pay->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div style="font-size:48px;opacity:.2;margin-bottom:12px">💳</div>
                            <strong class="text-muted">No payment records found.</strong><br>
                            <a href="{{ route('admin.payments.create') }}" class="btn btn-sm btn-primary mt-2">Record First Payment</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
        <div class="p-3 border-top">{{ $payments->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
