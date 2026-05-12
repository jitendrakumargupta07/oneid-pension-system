@extends('layouts.user')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')

{{-- Profile Status Banner --}}
@if(!$profile)
<div class="status-banner unregistered mb-4">
    <div class="status-banner-icon">🆔</div>
    <div>
        <h6>Complete Your Profile</h6>
        <p>You need to submit your elderly citizen profile to access pension services.</p>
    </div>
    <a href="{{ route('user.profile.create') }}" class="btn btn-primary btn-sm ms-auto">Complete Profile</a>
</div>
@elseif(!$profile->is_verified)
<div class="status-banner pending mb-4">
    <div class="status-banner-icon">⏳</div>
    <div>
        <h6>Verification Pending</h6>
        <p>Your profile is under review. You'll be notified once verified by the administration.</p>
    </div>
</div>
@else
<div class="status-banner verified mb-4">
    <div class="status-banner-icon">✅</div>
    <div>
        <h6>Profile Verified!</h6>
        <p>Your identity has been verified. You can apply for pension schemes.</p>
    </div>
</div>
@endif

{{-- OneID & Quick Stats --}}
@if($profile)
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card" style="background:linear-gradient(135deg,var(--primary) 0%,var(--primary-light) 100%);border:none;color:#fff;">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-1" style="font-size:12px;opacity:0.7;text-transform:uppercase;letter-spacing:1px;">Your Unique OneID</p>
                        <div class="d-flex align-items-center gap-3 mt-1">
                            <span id="oneIdDisplay" style="font-family:'Courier New',monospace;font-size:26px;font-weight:800;letter-spacing:3px;">
                                {{ $profile->one_id }}
                            </span>
                            <button onclick="copyOneId()" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;border:1px solid rgba(255,255,255,0.3);">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <p class="mt-2 mb-0" style="font-size:12px;opacity:0.6;">This is your permanent Government Pension Identification Number</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div style="font-size:13px;opacity:0.8;">{{ $profile->full_name }}</div>
                        <div style="font-size:12px;opacity:0.6;">Age {{ $profile->age }} &bull; {{ ucfirst($profile->gender) }}</div>
                        @if($profile->is_verified)
                            <span class="badge mt-2" style="background:var(--accent);color:var(--primary-dark);font-size:11px;padding:5px 12px;border-radius:50px;">
                                <i class="fas fa-shield-alt me-1"></i>Verified Citizen
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card {{ $profile ? 'success' : 'primary' }}">
            <div class="stat-icon {{ $profile ? 'success' : 'primary' }}">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="stat-value" style="font-size:18px;">{{ $profile ? 'Done' : 'Pending' }}</div>
            <div class="stat-label">Profile Status</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card {{ $activeApplication ? 'success' : 'warning' }}">
            <div class="stat-icon {{ $activeApplication ? 'success' : 'warning' }}">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-value" style="font-size:18px;">{{ $activeApplication ? 'Active' : 'None' }}</div>
            <div class="stat-label">Pension Status</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card gold">
            <div class="stat-icon gold"><i class="fas fa-rupee-sign"></i></div>
            <div class="stat-value" style="font-size:22px;">₹{{ number_format($totalReceived/1000, 1) }}K</div>
            <div class="stat-label">Total Received</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card info">
            <div class="stat-icon info"><i class="fas fa-bell"></i></div>
            <div class="stat-value" style="font-size:22px;">{{ $notifications->count() }}</div>
            <div class="stat-label">Notifications</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Active Scheme --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-file-contract text-primary"></i> Active Pension Scheme</div>
            <div class="card-body">
                @if($activeApplication)
                <div class="text-center py-3">
                    <div style="font-size:36px;margin-bottom:8px;">🏛️</div>
                    <h5 class="fw-bold text-primary">{{ $activeApplication->scheme?->name }}</h5>
                    <div class="mt-3">
                        <div style="font-size:36px;font-weight:800;color:var(--success);">
                            ₹{{ number_format($activeApplication->scheme?->monthly_amount, 2) }}
                        </div>
                        <div style="font-size:13px;color:#9ca3af;">Monthly Pension Amount</div>
                    </div>
                    <div class="mt-3">
                        <code style="background:#f0f2ff;color:var(--primary);padding:5px 14px;border-radius:6px;font-size:13px;">
                            {{ $activeApplication->application_number }}
                        </code>
                    </div>
                    <a href="{{ route('user.payments.index') }}" class="btn btn-accent mt-3">
                        <i class="fas fa-receipt me-1"></i>View Payments
                    </a>
                </div>
                @elseif($profile && $profile->is_verified)
                <div class="empty-state py-4">
                    <i class="fas fa-file-plus" style="color:#dde0f0;"></i>
                    <h5>No Active Pension</h5>
                    <p>You can apply for available pension schemes.</p>
                    <a href="{{ route('user.applications.create') }}" class="btn btn-primary btn-sm mt-2">
                        <i class="fas fa-plus me-1"></i>Apply Now
                    </a>
                </div>
                @else
                <div class="empty-state py-4">
                    <i class="fas fa-lock" style="color:#dde0f0;"></i>
                    <h5>Profile Verification Required</h5>
                    <p>Your profile must be verified before applying.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Notifications --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-bell text-primary"></i> Recent Notifications</span>
                <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0 px-4">
                @forelse($notifications as $notif)
                <div class="notification-item">
                    <div class="notif-dot {{ $notif->type ?? 'info' }} {{ !$notif->is_read ? 'unread' : '' }}"></div>
                    <div class="flex-grow-1">
                        <div class="notif-title">{{ $notif->title }}</div>
                        <div class="notif-msg">{{ Str::limit($notif->message, 80) }}</div>
                        <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state py-4">
                    <i class="fas fa-bell-slash" style="color:#dde0f0;"></i>
                    <p>No notifications yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Payments --}}
    @if($recentPayments->count() > 0)
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-money-bill-wave text-primary"></i> Recent Payments</span>
                <a href="{{ route('user.payments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr><th>Receipt No.</th><th>Month/Year</th><th>Amount</th><th>Date</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayments as $pay)
                        <tr>
                            <td><code style="font-size:12px;">{{ $pay->receipt_number }}</code></td>
                            <td>{{ date('F', mktime(0,0,0,$pay->month,1)) }} {{ $pay->year }}</td>
                            <td class="fw-bold text-success">₹{{ number_format($pay->amount,2) }}</td>
                            <td style="font-size:12px;color:#6b7280;">{{ $pay->payment_date?->format('d M Y') }}</td>
                            <td><span class="badge-status badge-{{ $pay->status }}">{{ ucfirst($pay->status) }}</span></td>
                            <td>
                                <a href="{{ route('user.payments.receipt', $pay) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function copyOneId() {
    const text = document.getElementById('oneIdDisplay').textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
        const btn = event.target.closest('button');
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i>', 2000);
    });
}
</script>
@endpush
