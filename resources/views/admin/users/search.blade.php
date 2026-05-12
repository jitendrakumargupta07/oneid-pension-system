@extends('layouts.admin')
@section('title', 'Beneficiary Search')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-search-location me-2" style="color:var(--accent)"></i>Beneficiary Search</h2>
        <p class="page-subtitle">Find any citizen by OneID, Aadhaar number, phone, or name.</p>
    </div>
</div>

{{-- Search Box --}}
<div class="row justify-content-center mb-4">
    <div class="col-lg-8">
        <div class="card" style="border:none;box-shadow:0 4px 24px rgba(26,35,126,.12)">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.users.search') }}" id="searchForm">
                    <label class="form-label fw-bold mb-2" style="font-size:14px;color:var(--primary)">
                        <i class="fas fa-id-card me-1"></i>Search Citizen
                    </label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text" style="background:var(--primary);border:none;border-radius:12px 0 0 12px;padding:0 18px">
                            <i class="fas fa-search text-white"></i>
                        </span>
                        <input type="text" name="q" id="searchInput"
                            class="form-control"
                            style="border:2px solid #e2e8f0;border-left:none;font-size:15px;font-family:monospace;letter-spacing:.5px;border-radius:0"
                            placeholder="Enter OneID (OID-2026-XXXXXX), Aadhaar, phone, or name..."
                            value="{{ request('q') }}"
                            autocomplete="off">
                        <button type="submit" class="btn btn-lg px-4" style="background:var(--accent);color:#1a237e;font-weight:700;border-radius:0 12px 12px 0;border:2px solid var(--accent)">
                            Search
                        </button>
                    </div>
                    <div class="d-flex gap-2 mt-2 flex-wrap">
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Supported formats:</small>
                        <small class="badge bg-light text-dark border">OID-2026-483921</small>
                        <small class="badge bg-light text-dark border">123456789012 (Aadhaar)</small>
                        <small class="badge bg-light text-dark border">98XXXXXXXX (Phone)</small>
                        <small class="badge bg-light text-dark border">Ramesh Kumar (Name)</small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Results --}}
@if($searched)
    @if($profile)
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Success banner --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i>Citizen Found</span>
                <span style="font-size:13px;color:#6b7280">Searched for: <strong>"{{ request('q') }}"</strong></span>
                <a href="{{ route('admin.users.show', $profile->user_id) }}" class="btn btn-sm ms-auto" style="background:var(--primary);color:#fff">
                    <i class="fas fa-external-link-alt me-1"></i>Open Full Profile
                </a>
            </div>

            <div class="row g-4">
                {{-- Profile card --}}
                <div class="col-md-5">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#283593);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:32px;color:white">
                                <i class="fas fa-user"></i>
                            </div>
                            <h4 class="fw-bold mb-1">{{ $profile->full_name }}</h4>
                            <div class="mb-2">
                                <span style="background:#e8eaf6;color:var(--primary);font-family:monospace;font-weight:700;padding:4px 14px;border-radius:20px;font-size:13px;letter-spacing:1px">
                                    {{ $profile->one_id }}
                                </span>
                            </div>
                            @if($profile->is_verified)
                                <span class="badge bg-success px-3 py-2"><i class="fas fa-shield-alt me-1"></i>Verified Citizen</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-clock me-1"></i>Pending Verification</span>
                            @endif
                            <hr class="my-3">
                            <div class="text-start" style="font-size:13px">
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Age</span><span class="fw-semibold">{{ $profile->age }} years</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Gender</span><span class="fw-semibold">{{ ucfirst($profile->gender) }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Phone</span><span class="fw-semibold">{{ $profile->phone }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Aadhaar</span><span class="fw-semibold" style="font-family:monospace">{{ substr($profile->aadhaar_number,0,4) }}XXXX{{ substr($profile->aadhaar_number,-4) }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Employment</span><span class="fw-semibold">{{ $profile->employment_label }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted">Registered</span><span class="fw-semibold">{{ $profile->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Applications + Fraud --}}
                <div class="col-md-7">
                    {{-- Bank details --}}
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <div class="fw-bold mb-2" style="font-size:13px;color:var(--primary)"><i class="fas fa-university me-1"></i>Bank Details</div>
                            <div class="row g-2" style="font-size:13px">
                                <div class="col-6"><span class="text-muted">Bank:</span> <strong>{{ $profile->bank_name }}</strong></div>
                                <div class="col-6"><span class="text-muted">IFSC:</span> <strong style="font-family:monospace">{{ $profile->ifsc_code }}</strong></div>
                                <div class="col-12"><span class="text-muted">Account:</span> <strong style="font-family:monospace">{{ $profile->bank_account_number }}</strong></div>
                            </div>
                        </div>
                    </div>

                    {{-- Applications --}}
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            <div class="fw-bold mb-3" style="font-size:13px;color:var(--primary)"><i class="fas fa-file-alt me-1"></i>Pension Applications</div>
                            @forelse($profile->pensionApplications as $app)
                            <div class="d-flex align-items-center justify-content-between p-2 mb-2" style="background:#f8f9ff;border-radius:8px;border:1px solid #e8eaf6">
                                <div>
                                    <div class="fw-semibold" style="font-size:13px">{{ $app->scheme?->name }}</div>
                                    <div style="font-size:11px;color:#9ca3af;font-family:monospace">{{ $app->application_number }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-badge status-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
                                    @if($app->status === 'approved')
                                        <strong class="text-success" style="font-size:12px">₹{{ number_format($app->scheme?->monthly_amount, 0) }}/mo</strong>
                                    @endif
                                    <a href="{{ route('admin.applications.show', $app) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:11px">View</a>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-2" style="font-size:13px">No applications yet.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Fraud alerts if any --}}
                    @if($profile->fraudAlerts->count() > 0)
                    <div class="card" style="border:1.5px solid #dc3545">
                        <div class="card-body p-3">
                            <div class="fw-bold mb-2 text-danger" style="font-size:13px"><i class="fas fa-exclamation-triangle me-1"></i>{{ $profile->fraudAlerts->count() }} Fraud Alert(s)</div>
                            @foreach($profile->fraudAlerts->take(3) as $alert)
                            <div class="d-flex align-items-center gap-2 mb-1" style="font-size:12px">
                                <span class="badge bg-{{ $alert->severity_color }}">{{ strtoupper($alert->severity) }}</span>
                                {{ $alert->type_label }}
                                <span class="ms-auto badge bg-{{ $alert->status==='open'?'danger':'success' }}">{{ ucfirst($alert->status) }}</span>
                            </div>
                            @endforeach
                            <a href="{{ route('admin.fraud.index') }}" class="btn btn-sm btn-outline-danger mt-2 w-100" style="font-size:11px">View All Fraud Alerts</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card text-center py-5">
                <div class="card-body">
                    <div style="font-size:64px;margin-bottom:16px;opacity:.3">🔍</div>
                    <h4 class="fw-bold">No Citizen Found</h4>
                    <p class="text-muted">No citizen matching <strong>"{{ request('q') }}"</strong> was found in the system.</p>
                    <p class="text-muted" style="font-size:13px">Try searching by OneID, Aadhaar number, phone number, or full name.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
@else
{{-- Initial state - no search yet --}}
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card text-center py-5" style="border:2px dashed #e2e8f0;background:transparent;box-shadow:none">
            <div class="card-body">
                <div style="font-size:56px;margin-bottom:16px;opacity:.25">🏛️</div>
                <h5 class="text-muted fw-semibold">Search for any registered citizen</h5>
                <p class="text-muted" style="font-size:13px">Enter their OneID, Aadhaar, phone, or name above to find their profile instantly.</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
