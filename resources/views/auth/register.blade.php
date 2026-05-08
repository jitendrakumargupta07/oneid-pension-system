@extends('layouts.guest')
@section('title', 'Register')
@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="border-radius:8px;font-size:13px;">
            <i class="fas fa-exclamation-circle me-1"></i>
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
               placeholder="Enter your full name" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
               placeholder="Enter your email" required>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
        </div>
        <div class="col-6 mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
        </div>
    </div>

    <div class="mb-4 p-3" style="background:#e8f5e9;border-radius:10px;border:1px solid #c8e6c9;">
        <p class="mb-0" style="font-size:11px;color:#2e7d32;">
            <i class="fas fa-shield-alt me-1"></i>
            After registration, you will need to complete your elderly citizen profile to receive your <strong>OneID</strong> and apply for pension schemes.
        </p>
    </div>

    <button type="submit" class="btn-login">
        <i class="fas fa-user-plus me-2"></i>Create Account
    </button>

    <hr class="divider">
    <p class="text-center mb-0" style="font-size:13px;color:#6b7280;">
        Already registered?
        <a href="{{ route('login') }}" style="color:var(--primary);font-weight:700;">Sign in here</a>
    </p>
</form>
@endsection
