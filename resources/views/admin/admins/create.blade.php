@extends('layouts.admin')
@section('title', 'Register Admin')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-user-shield me-2" style="color:var(--accent)"></i>Register Administrator</h2>
        <p class="page-subtitle">Provision a new high-privilege account.</p>
    </div>
    <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Back to Admins
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="alert alert-warning" style="background:#fff8e1;border:1px solid #ffe082;border-radius:12px;font-size:13px">
            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
            <strong>Security Warning:</strong> This account will have full access to verify citizens, process payments, and view fraud alerts.
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.admins.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        <div class="form-text">Must be a unique official email address.</div>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        <div class="form-text">Minimum 8 characters.</div>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg py-3 fw-bold" style="background:linear-gradient(135deg,var(--primary),#283593);color:#fff;border-radius:12px">
                            <i class="fas fa-user-check me-2"></i>Provision Admin Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
