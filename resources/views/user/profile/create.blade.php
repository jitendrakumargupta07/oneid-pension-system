@extends('layouts.user')
@section('title', 'Complete Your Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">

        {{-- Info Banner --}}
        <div class="alert alert-info alert-custom mb-4" style="border-radius:12px;align-items:flex-start">
            <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
            <div>
                <strong style="font-size:14px">Complete your KYC profile to get your OneID</strong><br>
                <span style="font-size:12.5px">Your eligibility for pension schemes is automatically checked using the information you provide below. Be honest and accurate — all data will be verified by an administrator before you can apply.</span>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-custom mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>Please fix these errors:</strong>
                <ul class="mb-0 mt-1" style="font-size:13px">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('user.profile.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- ══════════════════════════════════════════════ --}}
            {{-- SECTION 1: Personal Information               --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="form-section-header mb-4">
                        <i class="fas fa-user me-2" style="color:var(--primary)"></i>Section 1 — Personal Information
                    </div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Full Name <small class="text-muted">(as on Aadhaar card)</small> <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                                value="{{ old('full_name') }}" placeholder="e.g. Ramesh Kumar Sharma">
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age" class="form-control @error('age') is-invalid @enderror"
                                value="{{ old('age') }}" min="18" max="120" placeholder="60">
                            <div class="form-text">Actual age in years</div>
                            @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select</option>
                                <option value="male"   {{ old('gender')==='male'   ?'selected':'' }}>Male</option>
                                <option value="female" {{ old('gender')==='female' ?'selected':'' }}>Female</option>
                                <option value="other"  {{ old('gender')==='other'  ?'selected':'' }}>Other</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}" placeholder="9876543210">
                            </div>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Aadhaar Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="aadhaar_number" class="form-control @error('aadhaar_number') is-invalid @enderror"
                                    value="{{ old('aadhaar_number') }}" maxlength="12" placeholder="123456789012"
                                    style="font-family:monospace;letter-spacing:2px"
                                    oninput="this.value=this.value.replace(/\D/g,'')">
                            </div>
                            <div class="form-text">12-digit number from your Aadhaar card</div>
                            @error('aadhaar_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}">
                            <div class="form-text">As per Aadhaar/Birth certificate</div>
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" id="maritalStatus" class="form-select @error('marital_status') is-invalid @enderror" onchange="toggleWidowField()">
                                <option value="">Select</option>
                                <option value="unmarried" {{ old('marital_status')==='unmarried'?'selected':'' }}>Unmarried</option>
                                <option value="married"   {{ old('marital_status')==='married'  ?'selected':'' }}>Married</option>
                                <option value="widowed"   {{ old('marital_status')==='widowed'  ?'selected':'' }}>Widowed</option>
                                <option value="divorced"  {{ old('marital_status')==='divorced' ?'selected':'' }}>Divorced</option>
                            </select>
                            @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Caste Category <span class="text-danger">*</span></label>
                            <select name="caste_category" class="form-select @error('caste_category') is-invalid @enderror">
                                <option value="">Select</option>
                                <option value="general" {{ old('caste_category')==='general'?'selected':'' }}>General</option>
                                <option value="obc"     {{ old('caste_category')==='obc'    ?'selected':'' }}>OBC</option>
                                <option value="sc"      {{ old('caste_category')==='sc'     ?'selected':'' }}>SC (Scheduled Caste)</option>
                                <option value="st"      {{ old('caste_category')==='st'     ?'selected':'' }}>ST (Scheduled Tribe)</option>
                            </select>
                            @error('caste_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Residential Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2"
                                placeholder="Full address with village/town, district, state and PIN code">{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- SECTION 2: Eligibility Factors                --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="form-section-header mb-2">
                        <i class="fas fa-clipboard-check me-2" style="color:#27ae60"></i>Section 2 — Pension Eligibility Factors
                    </div>
                    <div class="alert" style="background:#e8f5e9;border-radius:10px;border:1px solid #a5d6a7;font-size:12.5px;padding:10px 14px;margin-bottom:20px">
                        <i class="fas fa-info-circle me-1 text-success"></i>
                        These details determine which pension schemes you qualify for. All information is verified against official records.
                    </div>
                    <div class="row g-3">
                        {{-- Employment Status --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Employment Status <span class="text-danger">*</span></label>
                            <select name="employment_status" class="form-select @error('employment_status') is-invalid @enderror">
                                <option value="">— Select your status —</option>
                                <option value="unemployed"    {{ old('employment_status')==='unemployed'   ?'selected':'' }}>Unemployed / No formal job</option>
                                <option value="retired"       {{ old('employment_status')==='retired'      ?'selected':'' }}>Retired (Private sector)</option>
                                <option value="retired_govt"  {{ old('employment_status')==='retired_govt' ?'selected':'' }}>Retired Government Employee</option>
                                <option value="farmer"        {{ old('employment_status')==='farmer'       ?'selected':'' }}>Farmer / Agricultural Worker</option>
                                <option value="self_employed" {{ old('employment_status')==='self_employed'?'selected':'' }}>Self-employed / Small Business</option>
                            </select>
                            <div class="form-text">Select <strong>Retired Government Employee</strong> if you worked for Central/State Govt.</div>
                            @error('employment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Annual Income --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Annual Family Income (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold" style="background:var(--primary);color:#fff;border:none">₹</span>
                                <input type="number" name="income_level" class="form-control @error('income_level') is-invalid @enderror"
                                    value="{{ old('income_level') }}" min="0" max="10000000" placeholder="e.g. 60000">
                            </div>
                            <div class="form-text">Total household income per year. Enter 0 if no income. Most schemes require income ≤ ₹2,00,000/year.</div>
                            @error('income_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Widow status (shown only when marital=widowed) --}}
                        <div class="col-md-6" id="widowField" style="{{ old('marital_status')==='widowed' ? '' : 'display:none' }}">
                            <div style="background:#fff3cd;border:1px solid #ffe082;border-radius:10px;padding:14px">
                                <label class="form-label fw-bold mb-2">Widow Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_widow" value="1" id="isWidow"
                                        {{ old('is_widow') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="isWidow" style="font-size:13px">
                                        ✅ I confirm I am a widow (spouse deceased)
                                    </label>
                                </div>
                                <div class="form-text mt-1">Widow status enables eligibility for the <strong>Widow Pension Scheme</strong>. You will need to upload your spouse's death certificate with your application.</div>
                            </div>
                        </div>

                        {{-- Disability --}}
                        <div class="col-md-6">
                            <div style="background:#e3f2fd;border:1px solid #90caf9;border-radius:10px;padding:14px">
                                <label class="form-label fw-bold mb-2">Disability Status</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="hasDisability" name="has_disability" value="1"
                                        {{ old('disability_percentage') > 0 ? 'checked' : '' }}
                                        onchange="toggleDisabilityField()">
                                    <label class="form-check-label fw-semibold" for="hasDisability" style="font-size:13px">
                                        I have a government-recognized disability
                                    </label>
                                </div>
                                <div id="disabilityPctField" style="{{ old('disability_percentage') > 0 ? '' : 'display:none' }}">
                                    <label class="form-label" style="font-size:12px">Disability Percentage (as per Disability Certificate) <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="disability_percentage" class="form-control @error('disability_percentage') is-invalid @enderror"
                                            value="{{ old('disability_percentage') }}" min="1" max="100" placeholder="40">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="form-text">Enter percentage from your official <strong>UDID / Disability Certificate</strong>. Minimum 40% required for Disability Pension.</div>
                                    @error('disability_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div style="font-size:11.5px;color:#0277bd;margin-top:6px">
                                    <i class="fas fa-info-circle me-1"></i>Disability ≥ 40% qualifies for <strong>Disability Pension Scheme</strong>. Proof (UDID card / medical certificate) required at application.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- SECTION 3: Bank Details                       --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="form-section-header mb-4">
                        <i class="fas fa-university me-2" style="color:#8e44ad"></i>Section 3 — Bank Account Details
                    </div>
                    <div class="alert" style="background:#f3e5f5;border-radius:10px;border:1px solid #ce93d8;font-size:12.5px;padding:10px 14px;margin-bottom:20px">
                        <i class="fas fa-lock me-1" style="color:#8e44ad"></i>
                        Pension payments are credited directly to this account. Ensure all details are accurate.
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" list="bankList" class="form-control @error('bank_name') is-invalid @enderror"
                                value="{{ old('bank_name') }}" placeholder="e.g. State Bank of India">
                            <datalist id="bankList">
                                <option>State Bank of India</option>
                                <option>Punjab National Bank</option>
                                <option>Bank of Baroda</option>
                                <option>Canara Bank</option>
                                <option>Union Bank of India</option>
                                <option>HDFC Bank</option>
                                <option>ICICI Bank</option>
                                <option>Axis Bank</option>
                                <option>Indian Bank</option>
                                <option>Bank of India</option>
                                <option>Post Office Savings Bank</option>
                            </datalist>
                            @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Account Number <span class="text-danger">*</span></label>
                            <input type="text" name="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror"
                                value="{{ old('bank_account_number') }}" placeholder="Account number"
                                style="font-family:monospace" oninput="this.value=this.value.replace(/\D/g,'')">
                            @error('bank_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                            <input type="text" name="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror"
                                value="{{ old('ifsc_code') }}" placeholder="SBIN0001234" maxlength="11"
                                style="font-family:monospace;text-transform:uppercase"
                                oninput="this.value=this.value.toUpperCase()">
                            <div class="form-text">4 letters + 0 + 6 chars (e.g. SBIN0001234)</div>
                            @error('ifsc_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- SECTION 4: Documents                          --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="form-section-header mb-4">
                        <i class="fas fa-file-upload me-2" style="color:#e67e22"></i>Section 4 — Identity Documents
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Recent Passport-size Photo</label>
                            <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                            <div class="form-text">JPG/PNG, max 2MB. Clear face photo.</div>
                            @error('profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Aadhaar / Government ID Scan</label>
                            <input type="file" name="government_id_photo" class="form-control @error('government_id_photo') is-invalid @enderror" accept="image/*,application/pdf">
                            <div class="form-text">Scan/photo of Aadhaar card, Voter ID, or Passport.</div>
                            @error('government_id_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════ --}}
            {{-- Scheme Eligibility Preview                     --}}
            {{-- ══════════════════════════════════════════════ --}}
            <div class="card mb-4" style="border:2px solid #e8eaf6;background:linear-gradient(135deg,#f8f9ff,#fff)">
                <div class="card-body p-4">
                    <div class="fw-bold mb-3" style="color:var(--primary);font-size:14px">
                        <i class="fas fa-lightbulb me-2" style="color:var(--accent)"></i>Which Pension Scheme will I qualify for?
                    </div>
                    <div class="row g-3">
                        @php
                        $schemes = [
                            ['name'=>'Old Age Pension','icon'=>'fa-user-clock','color'=>'#1a237e','min_age'=>60,'requires'=>'Age ≥ 60 years, Income ≤ ₹2 lakh/year, Not employed'],
                            ['name'=>'Govt Employee Pension','icon'=>'fa-landmark','color'=>'#6a1b9a','min_age'=>58,'requires'=>'Retired Govt Employee, any income'],
                            ['name'=>'Widow Pension','icon'=>'fa-heart-broken','color'=>'#880e4f','min_age'=>18,'requires'=>'Widowed, Income ≤ ₹2 lakh/year'],
                            ['name'=>'Disability Pension','icon'=>'fa-wheelchair','color'=>'#01579b','min_age'=>18,'requires'=>'Disability ≥ 40% (UDID certificate), Income ≤ ₹2 lakh'],
                            ['name'=>'Farmer Pension','icon'=>'fa-seedling','color'=>'#1b5e20','min_age'=>60,'requires'=>'Farmer/Agricultural worker, Age ≥ 60, Income ≤ ₹2 lakh'],
                            ['name'=>'Family Pension','icon'=>'fa-home','color'=>'#bf360c','min_age'=>60,'requires'=>'Age ≥ 60, No other pension, Income ≤ ₹1 lakh'],
                        ];
                        @endphp
                        @foreach($schemes as $s)
                        <div class="col-md-4">
                            <div style="border:1.5px solid #e8eaf6;border-radius:10px;padding:12px;height:100%">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div style="width:32px;height:32px;border-radius:8px;background:{{ $s['color'] }}18;display:flex;align-items:center;justify-content:center">
                                        <i class="fas {{ $s['icon'] }}" style="color:{{ $s['color'] }};font-size:13px"></i>
                                    </div>
                                    <span class="fw-bold" style="font-size:12.5px">{{ $s['name'] }}</span>
                                </div>
                                <div style="font-size:11px;color:#6b7280;line-height:1.5">{{ $s['requires'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3 p-3" style="background:#fff8e1;border-radius:8px;font-size:12px;color:#7d5a00">
                        <i class="fas fa-check-circle me-1 text-success"></i>
                        <strong>Eligibility is checked automatically</strong> after you submit. You can apply for any scheme you qualify for once your profile is verified by an admin.
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-lg py-3 fw-bold" style="background:linear-gradient(135deg,var(--primary),#283593);color:#fff;border-radius:12px;font-size:15px">
                    <i class="fas fa-id-card me-2"></i>Submit Profile & Generate My OneID
                </button>
            </div>
            <p class="text-center text-muted mt-3" style="font-size:12px">
                <i class="fas fa-shield-alt me-1"></i>Your data is protected under the Government Data Privacy Act. All information is used only for pension eligibility verification.
            </p>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleWidowField() {
    const marital = document.getElementById('maritalStatus').value;
    const widowField = document.getElementById('widowField');
    widowField.style.display = (marital === 'widowed') ? '' : 'none';
    if (marital !== 'widowed') {
        document.getElementById('isWidow').checked = false;
    } else {
        document.getElementById('isWidow').checked = true;
    }
}

function toggleDisabilityField() {
    const checked = document.getElementById('hasDisability').checked;
    document.getElementById('disabilityPctField').style.display = checked ? '' : 'none';
    if (!checked) {
        document.querySelector('[name="disability_percentage"]').value = 0;
    }
}

// Run on page load for old() values
document.addEventListener('DOMContentLoaded', () => {
    toggleWidowField();
    toggleDisabilityField();
});
</script>
@endpush
@endsection
