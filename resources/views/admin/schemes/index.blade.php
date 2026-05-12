@extends('layouts.admin')

@section('title', 'Pension Schemes')
@section('page-title', 'Pension Schemes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Schemes</li>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-list-check me-2" style="color:var(--accent);"></i>Pension Schemes</h2>
    <a href="{{ route('admin.schemes.create') }}" class="btn btn-accent">
        <i class="fas fa-plus me-2"></i>Add Scheme
    </a>
</div>

<div class="row g-4">
    @forelse($schemes as $scheme)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100" style="transition:all 0.25s ease;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div style="font-size:11px;font-family:monospace;color:#9ca3af;font-weight:600;letter-spacing:1px;" class="mb-1">
                            {{ $scheme->scheme_code }}
                        </div>
                        <h5 class="fw-bold" style="font-size:15px;color:var(--primary);margin:0;">{{ $scheme->name }}</h5>
                    </div>
                    <span class="badge-status badge-{{ $scheme->status }}">{{ ucfirst($scheme->status) }}</span>
                </div>

                <p style="font-size:13px;color:#6b7280;line-height:1.6;" class="mb-3">{{ Str::limit($scheme->description, 90) }}</p>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div style="background:#f0f2ff;border-radius:10px;padding:12px;text-align:center;">
                            <div style="font-size:20px;font-weight:800;color:var(--primary);">
                                ₹{{ number_format($scheme->monthly_amount) }}
                            </div>
                            <div style="font-size:11px;color:#9ca3af;font-weight:600;">Monthly</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#f0f9f1;border-radius:10px;padding:12px;text-align:center;">
                            <div style="font-size:20px;font-weight:800;color:var(--success);">
                                {{ $scheme->eligibility_age }}+
                            </div>
                            <div style="font-size:11px;color:#9ca3af;font-weight:600;">Min. Age</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between" style="border-top:1px solid #f0f0f7;padding-top:12px;">
                    <span style="font-size:12.5px;color:#6b7280;">
                        <i class="fas fa-file-alt me-1 text-primary"></i>
                        {{ $scheme->applications_count }} applications
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.schemes.edit', $scheme) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.schemes.destroy', $scheme) }}" onsubmit="return confirm('Delete this scheme?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="empty-state">
                <i class="fas fa-list-check"></i>
                <h5>No Schemes Found</h5>
                <p>Create your first pension scheme to get started.</p>
                <a href="{{ route('admin.schemes.create') }}" class="btn btn-accent mt-2">
                    <i class="fas fa-plus me-1"></i>Add Scheme
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($schemes->hasPages())
<div class="mt-4">{{ $schemes->links('vendor.pagination.bootstrap-5') }}</div>
@endif
@endsection
