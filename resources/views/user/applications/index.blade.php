@extends('layouts.user')

@section('title', 'My Applications')
@section('page-title', 'Pension Applications')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-file-alt me-2" style="color:var(--accent);"></i>My Pension Applications</h2>
    @if($profile && $profile->is_verified)
        <a href="{{ route('user.applications.create') }}" class="btn btn-accent">
            <i class="fas fa-plus me-2"></i>Apply for Scheme
        </a>
    @endif
</div>

@if(!$profile)
<div class="status-banner unregistered">
    <div class="status-banner-icon">📋</div>
    <div>
        <h6>Profile Required</h6>
        <p>You must complete your profile before applying for a pension scheme.</p>
    </div>
    <a href="{{ route('user.profile.create') }}" class="btn btn-primary btn-sm ms-auto">Create Profile</a>
</div>
@elseif(!$profile->is_verified)
<div class="status-banner pending mb-4">
    <div class="status-banner-icon">⏳</div>
    <div>
        <h6>Verification Pending</h6>
        <p>Your profile must be verified by the administration before you can apply for pension schemes.</p>
    </div>
</div>
@endif

@if($applications->count() > 0)
<div class="row g-4">
    @foreach($applications as $app)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div style="font-size:11px;color:#9ca3af;font-family:monospace;font-weight:600;margin-bottom:4px;">
                            {{ $app->application_number }}
                        </div>
                        <h5 class="fw-bold mb-0" style="font-size:15px;color:var(--primary);">
                            {{ $app->scheme?->name }}
                        </h5>
                    </div>
                    <span class="badge-status badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div style="background:#f8f9ff;border-radius:8px;padding:10px;text-align:center;">
                            <div style="font-size:18px;font-weight:800;color:var(--success);">
                                ₹{{ number_format($app->scheme?->monthly_amount) }}
                            </div>
                            <div style="font-size:11px;color:#9ca3af;">Monthly Amount</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#f8f9ff;border-radius:8px;padding:10px;text-align:center;">
                            <div style="font-size:14px;font-weight:700;color:var(--primary);">
                                {{ $app->applied_at?->format('d M Y') }}
                            </div>
                            <div style="font-size:11px;color:#9ca3af;">Applied On</div>
                        </div>
                    </div>
                </div>

                @if($app->remarks)
                <div class="p-2 mb-2" style="background:#f9f9f9;border-radius:7px;border-left:3px solid var(--primary);">
                    <div style="font-size:11px;color:#9ca3af;font-weight:600;margin-bottom:2px;">ADMIN REMARKS</div>
                    <div style="font-size:13px;">{{ $app->remarks }}</div>
                </div>
                @endif

                @if($app->status === 'pending')
                <div style="font-size:12.5px;color:#f57c00;background:#fff8e1;padding:8px 12px;border-radius:7px;">
                    <i class="fas fa-hourglass-half me-1"></i>
                    Your application is currently under review.
                </div>
                @elseif($app->status === 'approved')
                <a href="{{ route('user.payments.index') }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-receipt me-1"></i>View Payment History
                </a>
                @elseif($app->status === 'rejected')
                <div style="font-size:12.5px;color:#c62828;background:#ffebee;padding:8px 12px;border-radius:7px;">
                    <i class="fas fa-times-circle me-1"></i>
                    Application rejected. You may re-apply after addressing the remarks.
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@elseif($profile)
<div class="card">
    <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h5>No Applications Yet</h5>
        @if($profile->is_verified)
            <p>You haven't applied for any pension scheme yet.</p>
            <a href="{{ route('user.applications.create') }}" class="btn btn-accent mt-2">
                <i class="fas fa-plus me-1"></i>Apply Now
            </a>
        @else
            <p>Your profile must be verified before you can apply for a scheme.</p>
        @endif
    </div>
</div>
@endif
@endsection
