@extends('layouts.admin')
@section('title', 'Record Payment')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-rupee-sign me-2" style="color:var(--accent)"></i>Record Pension Payment</h2>
        <p class="page-subtitle">Disburse monthly pension to an approved beneficiary.</p>
    </div>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back to Payments
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">

        @if($errors->any())
        <div class="alert alert-danger alert-custom mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Step 1: Select Application --}}
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800">1</div>
                    <h6 class="mb-0 fw-bold">Select Approved Application</h6>
                </div>
                <form method="GET" action="{{ route('admin.payments.create') }}" id="appSelectForm">
                    <div class="input-group">
                        <select name="application_id" class="form-select" id="appSelect" onchange="this.form.submit()">
                            <option value="">— Choose a beneficiary —</option>
                            @foreach($applications as $app)
                                <option value="{{ $app->id }}"
                                    {{ (isset($selectedApplication) && $selectedApplication->id === $app->id) ? 'selected' : '' }}>
                                    {{ $app->elderlyProfile?->full_name }} ({{ $app->elderlyProfile?->one_id }}) — {{ $app->scheme?->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary px-3">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @if($applications->isEmpty())
                    <div class="text-center py-3 text-muted" style="font-size:13px">
                        <i class="fas fa-info-circle me-1"></i>No approved applications found. <a href="{{ route('admin.applications.index') }}">Approve applications first.</a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Step 2: Payment details (only shown after selecting) --}}
        @if(isset($selectedApplication))
        {{-- Citizen Summary Card --}}
        <div class="card mb-4" style="border:2px solid #e8eaf6;background:linear-gradient(135deg,#f8f9ff,#fff)">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,var(--primary),#283593);display:flex;align-items:center;justify-content:center;color:white;font-size:22px">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:16px">{{ $selectedApplication->elderlyProfile?->full_name }}</div>
                        <div style="font-family:monospace;color:var(--primary);font-size:12px;font-weight:600">{{ $selectedApplication->elderlyProfile?->one_id }}</div>
                    </div>
                    <div class="ms-auto text-end">
                        <div style="font-size:22px;font-weight:800;color:#27ae60">₹{{ number_format($selectedApplication->scheme?->monthly_amount, 0) }}</div>
                        <div style="font-size:11px;color:#6b7280">Standard Monthly Amount</div>
                    </div>
                </div>
                <div class="row g-2" style="font-size:12px">
                    <div class="col-6"><span class="text-muted">Scheme:</span> <strong>{{ $selectedApplication->scheme?->name }}</strong></div>
                    <div class="col-6"><span class="text-muted">Bank:</span> <strong>{{ $selectedApplication->elderlyProfile?->bank_name }}</strong></div>
                    <div class="col-6"><span class="text-muted">IFSC:</span> <strong style="font-family:monospace">{{ $selectedApplication->elderlyProfile?->ifsc_code }}</strong></div>
                    <div class="col-6"><span class="text-muted">Account:</span> <strong style="font-family:monospace">{{ $selectedApplication->elderlyProfile?->bank_account_number }}</strong></div>
                </div>
            </div>
        </div>

        {{-- Step 2: Payment Form --}}
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800">2</div>
                    <h6 class="mb-0 fw-bold">Enter Payment Details</h6>
                </div>

                <form method="POST" action="{{ route('admin.payments.store') }}" autocomplete="off">
                    @csrf
                    <input type="hidden" name="pension_application_id" value="{{ $selectedApplication->id }}">

                    <div class="row g-3">
                        {{-- Amount --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Amount (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold" style="background:var(--primary);color:#fff;border:none">₹</span>
                                <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}"
                                    min="1" step="0.01" required
                                    placeholder="Enter amount"
                                    style="font-size:15px;font-weight:600">
                            </div>
                            @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        {{-- Payment Date --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:13px">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror"
                                value="{{ old('payment_date') }}" required>
                            @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Month --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:13px">Month <span class="text-danger">*</span></label>
                            <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                                <option value="" disabled {{ old('month') === null ? 'selected' : '' }}>Select Month...</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0,0,0,$m,1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Year --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:13px">Year <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                                value="{{ old('year') }}" min="2020" max="2100" placeholder="e.g. 2026" required>
                            @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:13px">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="paid" selected>✅ Paid</option>
                                <option value="pending">⏳ Pending</option>
                                <option value="failed">❌ Failed</option>
                            </select>
                        </div>

                        {{-- Transaction Ref --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:13px">Transaction Reference (Optional)</label>
                            <input type="text" name="transaction_ref" class="form-control"
                                value="{{ old('transaction_ref') }}"
                                placeholder="Enter Bank Transaction ID (if any)" style="font-family:monospace">
                        </div>

                        {{-- Remarks --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:13px">Remarks (Optional)</label>
                            <textarea name="remarks" class="form-control" rows="2"
                                placeholder="Optional notes about this payment...">{{ old('remarks') }}</textarea>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-lg fw-bold py-3" style="background:linear-gradient(135deg,var(--primary),#283593);color:#fff;border-radius:12px">
                            <i class="fas fa-paper-plane me-2"></i>Record Payment & Notify Citizen
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
