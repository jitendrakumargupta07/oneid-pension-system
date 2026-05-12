@extends('layouts.user')
@section('title', 'Apply for Pension')
@section('content')
<div class="page-header mb-4">
    <h2 class="page-title"><i class="fas fa-file-signature me-2" style="color:var(--accent)"></i>Apply for Pension Scheme</h2>
    <p class="page-subtitle">Review eligibility and apply for a pension scheme that suits your profile.</p>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-custom mb-4"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="row g-4">
    @forelse($schemes as $scheme)
    @php $result = $eligibilityResults[$scheme->id] ?? ['eligible'=>false,'checks'=>[],'blockers'=>['Unknown error']]; @endphp

    <div class="col-md-6">
        <div class="card h-100" style="border: 2px solid {{ $result['eligible'] ? '#27ae60' : '#e0e4ef' }}; transition: border-color .2s;">
            <div class="card-body">
                {{-- Scheme header --}}
                <div class="d-flex align-items-center mb-3">
                    <div style="width:48px;height:48px;border-radius:12px;background:{{ $result['eligible'] ? 'rgba(39,174,96,.12)' : 'rgba(26,35,126,.08)' }};display:flex;align-items:center;justify-content:center;margin-right:12px;font-size:20px;color:{{ $result['eligible'] ? '#27ae60' : 'var(--primary)' }}">
                        <i class="fas {{ $scheme->type_icon }}"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold" style="font-size:15px">{{ $scheme->name }}</h5>
                        <small class="text-muted">{{ $scheme->scheme_code }} · ₹{{ number_format($scheme->monthly_amount, 0) }}/month</small>
                    </div>
                    <div class="ms-auto">
                        @if($result['eligible'])
                            <span class="badge bg-success">✅ Eligible</span>
                        @else
                            <span class="badge bg-secondary">❌ Not Eligible</span>
                        @endif
                    </div>
                </div>

                <p style="font-size:12px;color:#6b7280;margin-bottom:12px">{{ $scheme->eligibility_description }}</p>

                {{-- Eligibility Criteria Checklist --}}
                <div style="background:#f8fafc;border-radius:10px;padding:12px;margin-bottom:14px">
                    <div style="font-size:11px;font-weight:700;color:#374151;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px">Eligibility Check</div>
                    @foreach($result['checks'] as $check)
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="font-size:14px">{{ $check['passed'] ? '✅' : '❌' }}</span>
                        <span style="font-size:12px;color:{{ $check['passed'] ? '#27ae60' : '#e74c3c' }};font-weight:{{ $check['passed'] ? '500' : '600' }}">
                            {{ $check['label'] }}
                        </span>
                        <span style="font-size:11px;color:#9ca3af;margin-left:auto">{{ $check['value'] }}</span>
                    </div>
                    @endforeach
                </div>

                @if(!$result['eligible'])
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px;margin-bottom:12px">
                        <div style="font-size:11px;font-weight:700;color:#dc2626;margin-bottom:4px">Why not eligible:</div>
                        @foreach($result['blockers'] as $blocker)
                            <div style="font-size:11px;color:#dc2626"><i class="fas fa-times-circle me-1"></i>{{ $blocker }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Apply Form --}}
                @if($result['eligible'])
                <button class="btn btn-sm w-100" style="background:var(--primary);color:#fff;font-weight:600"
                    data-bs-toggle="modal" data-bs-target="#applyModal{{ $scheme->id }}">
                    <i class="fas fa-paper-plane me-2"></i>Apply for This Scheme
                </button>
                @else
                <button class="btn btn-sm btn-outline-secondary w-100" disabled>Not Eligible to Apply</button>
                @endif
            </div>
        </div>
    </div>

    {{-- Application Modal with Document Upload --}}
    @if($result['eligible'])
    <div class="modal fade" id="applyModal{{ $scheme->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--primary);color:#fff">
                    <h5 class="modal-title"><i class="fas fa-{{ $scheme->type_icon }} me-2"></i>Apply — {{ $scheme->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('user.applications.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="scheme_id" value="{{ $scheme->id }}">
                    <div class="modal-body">
                        {{-- Scheme summary --}}
                        <div style="background:#e8eaf6;border-radius:10px;padding:14px;margin-bottom:20px">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div style="font-size:11px;color:#666">Monthly Pension</div>
                                    <div style="font-size:20px;font-weight:800;color:var(--primary)">₹{{ number_format($scheme->monthly_amount, 0) }}</div>
                                </div>
                                <div class="col-6">
                                    <div style="font-size:11px;color:#666">Scheme Code</div>
                                    <div style="font-size:14px;font-weight:700">{{ $scheme->scheme_code }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Document Upload --}}
                        @if($scheme->required_documents && count($scheme->required_documents) > 0)
                        <div style="font-weight:700;font-size:13px;margin-bottom:12px;color:var(--primary)">
                            <i class="fas fa-paperclip me-1"></i>Required Documents
                            <small class="text-muted fw-normal ms-2">(Max 5MB per file, PDF/JPG/PNG)</small>
                        </div>
                        <div class="row g-3">
                            @foreach($scheme->required_documents as $docType)
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:12px">
                                    {{ \App\Models\ApplicationDocument::$typeLabels[$docType] ?? ucwords(str_replace('_',' ',$docType)) }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="documents[{{ $docType }}]"
                                    class="form-control form-control-sm"
                                    accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="mt-3 p-3" style="background:#fff8e1;border-radius:8px;font-size:12px;color:#856404">
                            <i class="fas fa-info-circle me-1"></i>
                            By submitting this application, you confirm that all details provided are accurate and truthful.
                            Providing false information is an offense under the Indian Pension Act.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm" style="background:var(--primary);color:#fff;font-weight:600">
                            <i class="fas fa-paper-plane me-2"></i>Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @empty
    <div class="col-12 text-center py-5">
        <i class="fas fa-list-alt fa-3x text-muted mb-3 d-block"></i>
        <strong>No active pension schemes available.</strong>
    </div>
    @endforelse
</div>
@endsection
