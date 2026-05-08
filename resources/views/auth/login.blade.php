@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="border-radius:8px;font-size:13px;">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#f8f9fc;border:1.5px solid #e0e4ef;border-right:none;">
                <i class="fas fa-envelope text-muted" style="font-size:13px;"></i>
            </span>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Enter your email" required autofocus
                   style="border-radius:0 8px 8px 0;border-left:none;">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#f8f9fc;border:1.5px solid #e0e4ef;border-right:none;">
                <i class="fas fa-lock text-muted" style="font-size:13px;"></i>
            </span>
            <input type="password" name="password" class="form-control"
                   placeholder="Enter your password" required
                   style="border-radius:0 8px 8px 0;border-left:none;">
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember" style="font-size:12px;">Remember me</label>
        </div>
        @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" style="font-size:12px;color:var(--primary);">Forgot password?</a>
        @endif
    </div>

    <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In Securely
    </button>

    <hr class="divider">
    <p class="text-center mb-0" style="font-size:13px;color:#6b7280;">
        New citizen?
        <a href="{{ route('register') }}" style="color:var(--primary);font-weight:700;">Register here</a>
    </p>

    <div class="mt-4 p-3" style="background:#f8f9fc;border-radius:10px;border:1px solid #e8ebf5;">
        <p class="mb-1" style="font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.5px;">Demo Accounts</p>
        <p class="mb-0" style="font-size:11px;color:#6b7280;">
            <strong>Admin:</strong> admin@oneid.gov.in / Admin@1234<br>
            <strong>User:</strong> ramesh@example.com / User@1234
        </p>
    </div>
</form>
@endsection
