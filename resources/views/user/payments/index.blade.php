@extends('layouts.user')

@section('title', 'My Payments')
@section('page-title', 'Payment History')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-money-bill-wave me-2" style="color:var(--accent);"></i>Payment History</h2>
</div>

@if($application)
{{-- Active scheme info banner --}}
<div class="card mb-4" style="background:linear-gradient(135deg,var(--primary) 0%,var(--primary-light) 100%);border:none;color:#fff;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div style="font-size:12px;opacity:0.7;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Active Scheme</div>
                <div style="font-size:17px;font-weight:700;">{{ $application->scheme?->name }}</div>
                <div style="font-size:12px;opacity:0.6;">{{ $application->application_number }}</div>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <div style="font-size:32px;font-weight:800;">₹{{ number_format($application->scheme?->monthly_amount, 2) }}</div>
                <div style="font-size:12px;opacity:0.7;">Monthly pension amount</div>
            </div>
        </div>
    </div>
</div>

@if($payments->count() > 0)
<div class="card">
    <div class="card-header"><i class="fas fa-receipt text-primary"></i> All Payments ({{ $payments->total() }})</div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Receipt No.</th>
                    <th>Month / Year</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Transaction Ref.</th>
                    <th>Status</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $pay)
                <tr>
                    <td>
                        <code style="background:#f0f2ff;color:var(--primary);padding:3px 8px;border-radius:5px;font-size:12px;">
                            {{ $pay->receipt_number }}
                        </code>
                    </td>
                    <td>
                        <span style="font-size:13.5px;font-weight:600;">{{ date('F', mktime(0,0,0,$pay->month,1)) }}</span>
                        <span style="font-size:12px;color:#9ca3af;"> {{ $pay->year }}</span>
                    </td>
                    <td class="fw-bold text-success" style="font-size:14px;">₹{{ number_format($pay->amount, 2) }}</td>
                    <td style="font-size:13px;color:#374151;">{{ $pay->payment_date?->format('d M Y') }}</td>
                    <td>
                        @if($pay->transaction_ref)
                            <code style="font-size:12px;color:#6b7280;">{{ $pay->transaction_ref }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td><span class="badge-status badge-{{ $pay->status }}">{{ ucfirst($pay->status) }}</span></td>
                    <td>
                        @if($pay->status === 'paid')
                        <a href="{{ route('user.payments.receipt', $pay) }}" class="btn btn-sm btn-outline-primary" title="Download Receipt">
                            <i class="fas fa-download me-1"></i>PDF
                        </a>
                        @else
                            <span class="text-muted" style="font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-body pt-3 border-top">
        {{ $payments->links('vendor.pagination.bootstrap-5') }}
    </div>
    @endif
</div>
@else
<div class="card">
    <div class="empty-state">
        <i class="fas fa-receipt"></i>
        <h5>No Payments Yet</h5>
        <p>Your pension payments will appear here once processed by the administration.</p>
    </div>
</div>
@endif

@elseif($profile && $profile->is_verified)
<div class="card">
    <div class="empty-state">
        <i class="fas fa-file-alt"></i>
        <h5>No Approved Pension</h5>
        <p>You don't have an approved pension application. Apply for a scheme first.</p>
        <a href="{{ route('user.applications.create') }}" class="btn btn-primary mt-2">
            <i class="fas fa-plus me-1"></i>Apply for Scheme
        </a>
    </div>
</div>
@elseif($profile)
<div class="card">
    <div class="empty-state">
        <i class="fas fa-hourglass-half"></i>
        <h5>Profile Verification Pending</h5>
        <p>Your profile needs to be verified before you can access payments.</p>
    </div>
</div>
@else
<div class="card">
    <div class="empty-state">
        <i class="fas fa-user-plus"></i>
        <h5>Profile Required</h5>
        <p>Please complete your profile to access pension services.</p>
        <a href="{{ route('user.profile.create') }}" class="btn btn-primary mt-2">Complete Profile</a>
    </div>
</div>
@endif
@endsection
