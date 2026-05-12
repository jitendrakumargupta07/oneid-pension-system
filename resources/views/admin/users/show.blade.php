@extends('layouts.admin')

@section('title', 'Citizen Profile')
@section('page-title', 'Citizen Profile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Citizens</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                <div class="profile-avatar-placeholder mx-auto mb-3" style="width:90px;height:90px;font-size:36px;">
                    <i class="fas fa-user"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2" style="font-size:13px;">{{ $user->email }}</p>

                @if($user->elderlyProfile)
                    <div class="oneid-badge justify-content-center mb-3" style="font-size:13px;letter-spacing:1.5px;">
                        <i class="fas fa-id-card"></i> {{ $user->elderlyProfile->one_id }}
                    </div>
                    @if($user->elderlyProfile->is_verified)
                        <span class="badge-status badge-verified">Profile Verified</span>
                    @else
                        <span class="badge-status badge-pending">Pending Verification</span>
                    @endif
                @else
                    <span class="badge-status badge-inactive">No Profile Submitted</span>
                @endif
            </div>
        </div>

        @if($user->elderlyProfile && !$user->elderlyProfile->is_verified)
        <div class="card">
            <div class="card-header"><i class="fas fa-shield-alt text-primary"></i> Verify Profile</div>
            <div class="card-body">
                <p style="font-size:12.5px;color:#6b7280;" class="mb-3">
                    Review the citizen's documents before approving.
                </p>
                <form method="POST" action="{{ route('admin.profiles.verify', $user->elderlyProfile) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Remarks (optional)</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Add notes..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="approve" class="btn btn-success flex-grow-1">
                            <i class="fas fa-check me-1"></i>Approve
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger flex-grow-1"
                            onclick="return confirm('Reject this profile?')">
                            <i class="fas fa-times me-1"></i>Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @elseif($user->elderlyProfile && $user->elderlyProfile->is_verified)
        <div class="card">
            <div class="card-body text-center py-3">
                <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                <p class="mb-1 fw-semibold text-success">Verified on {{ $user->elderlyProfile->verified_at?->format('d M Y') }}</p>
                <form method="POST" action="{{ route('admin.profiles.verify', $user->elderlyProfile) }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="remarks" value="Verification revoked by admin.">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Revoke verification?')">
                        <i class="fas fa-ban me-1"></i>Revoke Verification
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        @if($user->elderlyProfile)
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user-circle text-primary"></i> Personal Information</div>
            <div class="card-body">
                <div class="row g-0">
                    <div class="col-6">
                        <div class="info-row"><span class="info-label">Full Name</span><span class="info-value">{{ $user->elderlyProfile->full_name }}</span></div>
                        <div class="info-row"><span class="info-label">Date of Birth</span><span class="info-value">{{ $user->elderlyProfile->date_of_birth?->format('d M Y') ?? 'N/A' }}</span></div>
                        <div class="info-row"><span class="info-label">Age</span><span class="info-value">{{ $user->elderlyProfile->age }} yrs</span></div>
                        <div class="info-row"><span class="info-label">Gender</span><span class="info-value">{{ ucfirst($user->elderlyProfile->gender) }}</span></div>
                        <div class="info-row"><span class="info-label">Phone</span><span class="info-value">{{ $user->elderlyProfile->phone }}</span></div>
                        <div class="info-row"><span class="info-label">Aadhaar</span><span class="info-value" style="font-family:monospace;">XXXX XXXX {{ substr($user->elderlyProfile->aadhaar_number,-4) }}</span></div>
                        <div class="info-row"><span class="info-label">Caste</span><span class="info-value">{{ $user->elderlyProfile->caste_label }}</span></div>
                    </div>
                    <div class="col-6" style="padding-left:16px; border-left:1px solid #f5f5f5;">
                        <div class="info-row"><span class="info-label">Employment</span><span class="info-value">{{ $user->elderlyProfile->employment_label }}</span></div>
                        <div class="info-row"><span class="info-label">Annual Income</span><span class="info-value">{{ $user->elderlyProfile->income_level ? '₹'.number_format($user->elderlyProfile->income_level) : 'Not declared' }}</span></div>
                        <div class="info-row"><span class="info-label">Disability</span><span class="info-value">{{ $user->elderlyProfile->disability_percentage ?? 0 }}%</span></div>
                        <div class="info-row"><span class="info-label">Marital Status</span><span class="info-value">{{ ucfirst($user->elderlyProfile->marital_status ?? 'N/A') }}</span></div>
                        <div class="info-row"><span class="info-label">Widow</span><span class="info-value">{{ $user->elderlyProfile->is_widow ? '✅ Yes' : 'No' }}</span></div>
                        <div class="info-row"><span class="info-label">Bank</span><span class="info-value">{{ $user->elderlyProfile->bank_name }}</span></div>
                        <div class="info-row"><span class="info-label">Account/IFSC</span><span class="info-value" style="font-family:monospace;">XXXX{{ substr($user->elderlyProfile->bank_account_number,-4) }} / {{ $user->elderlyProfile->ifsc_code }}</span></div>
                    </div>
                </div>
                <div class="info-row mt-1">
                    <span class="info-label">Address</span>
                    <span class="info-value" style="max-width:65%;text-align:right;">{{ $user->elderlyProfile->address }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-file-alt text-primary"></i> Pension Applications</div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr><th>App. No.</th><th>Scheme</th><th>Status</th><th>Applied</th><th>Payments</th></tr>
                    </thead>
                    <tbody>
                        @forelse($user->elderlyProfile->pensionApplications as $app)
                        <tr>
                            <td><code style="font-size:12px;">{{ $app->application_number }}</code></td>
                            <td style="font-size:13px;">{{ $app->scheme?->name }}</td>
                            <td><span class="badge-status badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span></td>
                            <td style="font-size:12px;color:#6b7280;">{{ $app->applied_at?->format('d M Y') }}</td>
                            <td><span class="badge bg-light text-dark" style="font-size:12px;">{{ $app->payments->count() }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No applications.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="card"><div class="empty-state"><i class="fas fa-user-slash"></i><h5>No Profile Submitted</h5><p>This user hasn't completed their profile.</p></div></div>
        @endif
    </div>
</div>
@endsection
