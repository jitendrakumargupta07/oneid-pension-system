@extends('layouts.user')
@section('title', 'My Profile')

@section('content')
@if($profile)
<div class="row g-4">

    {{-- LEFT COLUMN: ID Card + Eligibility Status --}}
    <div class="col-md-4">

        {{-- ID Card --}}
        <div class="card mb-4 text-center" style="background:linear-gradient(135deg,var(--primary),#283593);color:#fff;border:none">
            <div class="card-body py-4">
                <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:32px;margin:0 auto 12px">
                    <i class="fas fa-user"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $profile->full_name }}</h5>
                <div style="font-size:12px;opacity:.75;margin-bottom:12px">{{ $user->email }}</div>
                <div style="background:rgba(255,255,255,.15);border-radius:20px;padding:6px 16px;font-family:monospace;font-size:13px;font-weight:700;letter-spacing:1.5px;border:1.5px solid rgba(255,255,255,.3);display:inline-block">
                    <i class="fas fa-id-card me-2" style="color:var(--accent)"></i>{{ $profile->one_id }}
                </div>
                <div class="mt-3">
                    @if($profile->is_verified)
                        <span class="badge px-3 py-2" style="background:#27ae60;font-size:12px"><i class="fas fa-shield-alt me-1"></i>Verified Citizen</span>
                        <div style="font-size:11px;opacity:.65;margin-top:6px">Verified on {{ $profile->verified_at?->format('d M Y') }}</div>
                    @else
                        <span class="badge px-3 py-2" style="background:#f57c00;font-size:12px"><i class="fas fa-hourglass-half me-1"></i>Pending Verification</span>
                        <div style="font-size:11px;opacity:.65;margin-top:6px">Under administrative review</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Eligibility Status Panel --}}
        <div class="card mb-4">
            <div class="card-header" style="font-size:13px"><i class="fas fa-clipboard-check text-success me-1"></i>My Eligibility Status</div>
            <div class="card-body p-3">
                @php
                $elig = [
                    ['label'=>'Age','value'=>$profile->age.' years','icon'=>'fa-user-clock','ok'=> $profile->age >= 60],
                    ['label'=>'Employment','value'=>$profile->employment_label,'icon'=>'fa-briefcase','ok'=>true,'neutral'=>true],
                    ['label'=>'Annual Income','value'=>$profile->income_level ? '₹'.number_format($profile->income_level) : 'Not declared','icon'=>'fa-rupee-sign','ok'=> ($profile->income_level??0) <= 200000],
                    ['label'=>'Disability','value'=>($profile->disability_percentage??0).'%','icon'=>'fa-wheelchair','ok'=> ($profile->disability_percentage??0) >= 40,'neutral'=> ($profile->disability_percentage??0)==0],
                    ['label'=>'Widow Status','value'=> $profile->is_widow?'Widowed':'N/A','icon'=>'fa-heart','ok'=> $profile->is_widow,'neutral'=> !$profile->is_widow],
                    ['label'=>'Caste','value'=>$profile->caste_label,'icon'=>'fa-users','ok'=>true,'neutral'=>true],
                ];
                @endphp
                @foreach($elig as $e)
                <div class="d-flex align-items-center gap-2 mb-2 p-2" style="border-radius:8px;background:{{ isset($e['neutral'])&&$e['neutral'] ? '#f8f9fa' : ($e['ok']?'#e8f5e9':'#fff8e1') }}">
                    <i class="fas {{ $e['icon'] }}" style="width:16px;color:{{ isset($e['neutral'])&&$e['neutral'] ? '#9ca3af' : ($e['ok']?'#27ae60':'#f57c00') }};font-size:13px"></i>
                    <div class="flex-grow-1">
                        <div style="font-size:11px;color:#9ca3af">{{ $e['label'] }}</div>
                        <div style="font-size:12px;font-weight:600">{{ $e['value'] }}</div>
                    </div>
                    @if(!isset($e['neutral']))
                        <i class="fas {{ $e['ok']?'fa-check-circle text-success':'fa-exclamation-circle' }}" style="{{ $e['ok']?'':'color:#f57c00' }}"></i>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bank Details --}}
        <div class="card">
            <div class="card-header" style="font-size:13px"><i class="fas fa-university text-primary me-1"></i>Bank Account</div>
            <div class="card-body p-3">
                <div class="info-row"><span class="info-label">Bank</span><span class="info-value">{{ $profile->bank_name }}</span></div>
                <div class="info-row"><span class="info-label">Account</span><span class="info-value" style="font-family:monospace">XXXX{{ substr($profile->bank_account_number,-4) }}</span></div>
                <div class="info-row"><span class="info-label">IFSC</span><span class="info-value" style="font-family:monospace">{{ $profile->ifsc_code }}</span></div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Profile Details + Edit --}}
    <div class="col-md-8">

        {{-- Personal Info --}}
        <div class="card mb-4">
            <div class="card-header" style="font-size:14px"><i class="fas fa-user-circle text-primary me-1"></i>Personal Information</div>
            <div class="card-body">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="info-row"><span class="info-label">Full Name</span><span class="info-value">{{ $profile->full_name }}</span></div>
                        <div class="info-row"><span class="info-label">Date of Birth</span><span class="info-value">{{ $profile->date_of_birth?->format('d M Y') ?? 'Not provided' }}</span></div>
                        <div class="info-row"><span class="info-label">Age</span><span class="info-value">{{ $profile->age }} years</span></div>
                        <div class="info-row"><span class="info-label">Gender</span><span class="info-value">{{ ucfirst($profile->gender) }}</span></div>
                        <div class="info-row"><span class="info-label">Phone</span><span class="info-value">{{ $profile->phone }}</span></div>
                    </div>
                    <div class="col-md-6" style="padding-left:16px;border-left:1px solid #f5f5f5">
                        <div class="info-row"><span class="info-label">Aadhaar</span><span class="info-value" style="font-family:monospace">XXXX XXXX {{ substr($profile->aadhaar_number,-4) }}</span></div>
                        <div class="info-row"><span class="info-label">Marital Status</span><span class="info-value">{{ ucfirst($profile->marital_status ?? 'N/A') }}</span></div>
                        <div class="info-row"><span class="info-label">Caste Category</span><span class="info-value">{{ $profile->caste_label }}</span></div>
                        <div class="info-row"><span class="info-label">Registered On</span><span class="info-value">{{ $profile->created_at->format('d M Y') }}</span></div>
                    </div>
                </div>
                <div class="info-row"><span class="info-label">Address</span><span class="info-value" style="max-width:65%;text-align:right">{{ $profile->address }}</span></div>
            </div>
        </div>

        {{-- Pension Eligibility Details --}}
        <div class="card mb-4" style="border:1.5px solid #e8eaf6">
            <div class="card-header" style="font-size:14px"><i class="fas fa-clipboard-list text-success me-1"></i>Pension Eligibility Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:12px">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;font-weight:700">Employment Status</div>
                            <div style="font-size:14px;font-weight:700;margin-top:4px;color:var(--primary)">{{ $profile->employment_label }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:#f8fafc;border-radius:10px;padding:12px">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;font-weight:700">Annual Family Income</div>
                            <div style="font-size:14px;font-weight:700;margin-top:4px;color:{{ ($profile->income_level??0)<=200000?'#27ae60':'#e74c3c' }}">
                                {{ $profile->income_level ? '₹'.number_format($profile->income_level) : 'Not declared' }}
                                @if(($profile->income_level??0) <= 200000)<span style="font-size:10px;color:#27ae60;font-weight:500"> ✅ Within limit</span>@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:{{ ($profile->disability_percentage??0)>=40?'#e8f5e9':'#f8fafc' }};border-radius:10px;padding:12px;border:{{ ($profile->disability_percentage??0)>=40?'1.5px solid #a5d6a7':'none' }}">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;font-weight:700">Disability Percentage</div>
                            <div style="font-size:14px;font-weight:700;margin-top:4px;color:{{ ($profile->disability_percentage??0)>=40?'#27ae60':('#374151') }}">
                                {{ $profile->disability_percentage ?? 0 }}%
                                @if(($profile->disability_percentage??0)>=40)
                                    <span style="font-size:10px;color:#27ae60;font-weight:500"> ✅ Qualifies for Disability Pension</span>
                                @elseif(($profile->disability_percentage??0)>0)
                                    <span style="font-size:10px;color:#f57c00"> ⚠ Need ≥40% for Disability Pension</span>
                                @else
                                    <span style="font-size:10px;color:#9ca3af"> (No disability declared)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="background:{{ $profile->is_widow?'#fce4ec':'#f8fafc' }};border-radius:10px;padding:12px;border:{{ $profile->is_widow?'1.5px solid #f48fb1':'none' }}">
                            <div style="font-size:10px;text-transform:uppercase;letter-spacing:.8px;color:#9ca3af;font-weight:700">Widow Status</div>
                            <div style="font-size:14px;font-weight:700;margin-top:4px;color:{{ $profile->is_widow?'#c0392b':'#374151' }}">
                                {{ $profile->is_widow ? '✅ Widowed — Eligible for Widow Pension' : 'Not applicable' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Form --}}
        <div class="card">
            <div class="card-header" style="font-size:14px"><i class="fas fa-edit text-primary me-1"></i>Update Editable Details</div>
            <div class="card-body">
                <div class="alert" style="background:#fff8e1;border-radius:8px;font-size:12.5px;padding:10px 14px;margin-bottom:20px;border:1px solid #ffe082">
                    <i class="fas fa-lock me-1 text-warning"></i>
                    <strong>Name, Aadhaar and age</strong> are immutable after submission. Contact the admin to update eligibility data (disability/widow/employment status).
                </div>
                <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $profile->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $profile->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $profile->bank_name) }}">
                            @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror" value="{{ old('bank_account_number', $profile->bank_account_number) }}" style="font-family:monospace">
                            @error('bank_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">IFSC Code</label>
                            <input type="text" name="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" value="{{ old('ifsc_code', $profile->ifsc_code) }}" style="font-family:monospace;text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
                            @error('ifsc_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Employment Status</label>
                            <select name="employment_status" class="form-select">
                                <option value="unemployed"    {{ old('employment_status',$profile->employment_status)==='unemployed'   ?'selected':'' }}>Unemployed</option>
                                <option value="retired"       {{ old('employment_status',$profile->employment_status)==='retired'      ?'selected':'' }}>Retired (Private)</option>
                                <option value="retired_govt"  {{ old('employment_status',$profile->employment_status)==='retired_govt' ?'selected':'' }}>Retired Govt Employee</option>
                                <option value="farmer"        {{ old('employment_status',$profile->employment_status)==='farmer'       ?'selected':'' }}>Farmer</option>
                                <option value="self_employed" {{ old('employment_status',$profile->employment_status)==='self_employed'?'selected':'' }}>Self-employed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Annual Income (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="income_level" class="form-control" value="{{ old('income_level', $profile->income_level ?? 0) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Disability %</label>
                            <div class="input-group">
                                <input type="number" name="disability_percentage" class="form-control" value="{{ old('disability_percentage', $profile->disability_percentage ?? 0) }}" min="0" max="100" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">0 = no disability. Min 40% needed for Disability Pension.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Update Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@else
<div class="card text-center py-5">
    <div class="card-body">
        <div style="font-size:64px;margin-bottom:16px;opacity:.2">👤</div>
        <h4 class="fw-bold">No Profile Yet</h4>
        <p class="text-muted">You haven't submitted your elderly citizen profile yet.</p>
        <a href="{{ route('user.profile.create') }}" class="btn btn-primary mt-2 px-4">
            <i class="fas fa-plus me-1"></i>Create Profile & Get OneID
        </a>
    </div>
</div>
@endif
@endsection
