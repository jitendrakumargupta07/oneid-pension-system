@extends('layouts.admin')
@section('title', 'Review Application')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title">
            <i class="fas fa-file-alt me-2"></i>Application: {{ $application->application_number }}
        </h2>
        <div class="d-flex align-items-center gap-2 mt-1">
            <span class="status-badge status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
            @if($application->fraud_flagged)
                <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>Fraud Flagged</span>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-4">
    {{-- Left: Citizen Info --}}
    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-body">
                <div class="section-title mb-3">Citizen Details</div>
                <table style="width:100%;font-size:13px">
                    <tr><td class="fw-semibold py-1" style="width:45%;color:#6b7280">OneID</td><td><code>{{ $application->elderlyProfile->one_id }}</code></td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Full Name</td><td>{{ $application->elderlyProfile->full_name }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Age / Gender</td><td>{{ $application->elderlyProfile->age }} yrs · {{ ucfirst($application->elderlyProfile->gender) }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Aadhaar</td><td>{{ $application->elderlyProfile->aadhaar_number }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Phone</td><td>{{ $application->elderlyProfile->phone }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Employment</td><td>{{ $application->elderlyProfile->employment_label }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Income (Annual)</td><td>{{ $application->elderlyProfile->income_level ? '₹'.number_format($application->elderlyProfile->income_level) : 'Not declared' }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Disability</td><td>{{ $application->elderlyProfile->disability_percentage ?? 0 }}%</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Widow Status</td><td>{{ $application->elderlyProfile->is_widow ? '✅ Yes' : 'No' }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Caste</td><td>{{ $application->elderlyProfile->caste_label }}</td></tr>
                    <tr><td class="fw-semibold py-1" style="color:#6b7280">Bank</td><td>{{ $application->elderlyProfile->bank_name }}<br><small class="text-muted">{{ $application->elderlyProfile->bank_account_number }}</small></td></tr>
                </table>
            </div>
        </div>

        {{-- Fraud Alerts --}}
        @if($application->elderlyProfile->fraudAlerts->count() > 0)
        <div class="card mb-4" style="border:1.5px solid #dc3545">
            <div class="card-body">
                <div class="section-title text-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Fraud Alerts ({{ $application->elderlyProfile->fraudAlerts->count() }})</div>
                @foreach($application->elderlyProfile->fraudAlerts as $alert)
                <div class="mb-2 p-2" style="background:#fff5f5;border-radius:6px;font-size:12px">
                    <span class="badge bg-{{ $alert->severity_color }} me-1">{{ strtoupper($alert->severity) }}</span>
                    <strong>{{ $alert->type_label }}</strong><br>
                    <span class="text-muted">{{ $alert->description }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Scheme + Approval --}}
    <div class="col-lg-7">
        {{-- Scheme details --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="section-title mb-3">Scheme Details</div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:44px;height:44px;border-radius:10px;background:rgba(26,35,126,.1);display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--primary)">
                        <i class="fas {{ $application->scheme->type_icon }}"></i>
                    </div>
                    <div>
                        <div class="fw-bold">{{ $application->scheme->name }}</div>
                        <small class="text-muted">{{ $application->scheme->scheme_code }} · ₹{{ number_format($application->scheme->monthly_amount, 2) }}/month</small>
                    </div>
                </div>
                <p style="font-size:12px;color:#6b7280">{{ $application->scheme->eligibility_description }}</p>
                <div class="d-flex gap-3 flex-wrap" style="font-size:12px">
                    <span><strong>Applied:</strong> {{ $application->applied_at->format('d M Y') }}</span>
                    @if($application->reviewed_at)
                    <span><strong>Reviewed:</strong> {{ $application->reviewed_at->format('d M Y') }}</span>
                    <span><strong>By:</strong> {{ $application->reviewedBy?->name }}</span>
                    @endif
                </div>
                @if($application->remarks)
                <div class="mt-2 p-2" style="background:#f8f9fa;border-radius:6px;font-size:12px">
                    <strong>Remarks:</strong> {{ $application->remarks }}
                </div>
                @endif
            </div>
        </div>

        {{-- Document Review --}}
        @if($application->documents->count() > 0)
        <div class="card mb-4">
            <div class="card-body">
                <div class="section-title mb-3">
                    <i class="fas fa-paperclip me-1"></i>Uploaded Documents
                    <span class="badge bg-warning text-dark ms-2">{{ $application->pendingDocuments() }} Pending</span>
                </div>
                @foreach($application->documents as $doc)
                <div class="d-flex align-items-center gap-3 p-2 mb-2" style="background:#f8fafc;border-radius:8px;font-size:13px">
                    <div style="width:36px;height:36px;background:rgba(26,35,126,.1);border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-file-{{ str_contains($doc->file_path,'.pdf') ? 'pdf text-danger' : 'image text-primary' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:12px">{{ $doc->type_label }}</div>
                        <div style="font-size:10px;color:#6b7280">{{ $doc->original_name }}</div>
                    </div>
                    <span class="status-badge status-{{ $doc->status }}">{{ ucfirst($doc->status) }}</span>
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-size:11px">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($doc->isPending())
                    <button class="btn btn-sm btn-outline-secondary" style="font-size:11px"
                        data-bs-toggle="modal" data-bs-target="#docModal{{ $doc->id }}">Review</button>
                    @endif
                </div>

                @if($doc->isPending())
                <div class="modal fade" id="docModal{{ $doc->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h6 class="modal-title">Review: {{ $doc->type_label }}</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
                            <form method="POST" action="{{ route('admin.documents.review', $doc) }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="font-size:12px">Decision</label>
                                        <select name="action" class="form-select form-select-sm" required>
                                            <option value="approved">✅ Approve Document</option>
                                            <option value="rejected">❌ Reject Document</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold" style="font-size:12px">Remarks (optional)</label>
                                        <textarea name="admin_remarks" class="form-control form-control-sm" rows="2" placeholder="Add review remarks..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Save Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Approve / Reject --}}
        @if($application->isPending())
        <div class="card mb-4">
            <div class="card-body">
                <div class="section-title mb-3">Review Decision</div>
                <form method="POST" action="{{ route('admin.applications.review', $application) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Action</label>
                        <select name="action" class="form-select" required>
                            <option value="">Select decision...</option>
                            <option value="approve">✅ Approve Application</option>
                            <option value="reject">❌ Reject Application</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Add review remarks or reason for rejection..."></textarea>
                    </div>
                    <button type="submit" class="btn w-100 fw-bold" style="background:var(--primary);color:#fff">
                        <i class="fas fa-gavel me-2"></i>Submit Decision
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Payment History --}}
        @if($application->payments->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="section-title mb-3">Payment History ({{ $application->payments->count() }})</div>
                @foreach($application->payments as $pay)
                <div class="d-flex justify-content-between align-items-center p-2 mb-1" style="background:#f8fafc;border-radius:6px;font-size:13px">
                    <span>{{ date('F', mktime(0,0,0,$pay->month,1)) }} {{ $pay->year }}</span>
                    <span class="fw-bold" style="color:var(--primary)">₹{{ number_format($pay->amount, 2) }}</span>
                    <span class="status-badge status-{{ $pay->status === 'paid' ? 'approved' : 'pending' }}">{{ ucfirst($pay->status) }}</span>
                    <small class="text-muted">{{ $pay->receipt_number }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
