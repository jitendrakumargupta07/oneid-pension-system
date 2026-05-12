@extends('layouts.admin')

@section('title', 'All Citizens')
@section('page-title', 'Citizen Management')

@section('breadcrumb')
    <li class="breadcrumb-item active">Citizens</li>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-users me-2" style="color:var(--accent);"></i>All Registered Citizens</h2>
    <a href="{{ route('admin.users.search') }}" class="btn btn-accent">
        <i class="fas fa-search me-2"></i>Search by OneID
    </a>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, email, OneID or Aadhaar..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="verified" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Pending Verification</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search','verified']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="fas fa-list text-primary"></i> Citizens ({{ $users->total() }})</span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Citizen</th>
                    <th>OneID</th>
                    <th>Age / Gender</th>
                    <th>Phone</th>
                    <th>Verification</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="profile-avatar-placeholder" style="width:36px;height:36px;font-size:14px;border:2px solid var(--accent);">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:13.5px;">{{ $user->name }}</div>
                                <div style="font-size:11.5px;color:#9ca3af;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->elderlyProfile)
                            <code style="background:#f0f2ff;color:var(--primary);padding:3px 8px;border-radius:5px;font-size:12px;">
                                {{ $user->elderlyProfile->one_id }}
                            </code>
                        @else
                            <span class="text-muted fst-italic" style="font-size:12px;">No profile</span>
                        @endif
                    </td>
                    <td style="font-size:13px;">
                        @if($user->elderlyProfile)
                            {{ $user->elderlyProfile->age }} yrs &bull;
                            {{ ucfirst($user->elderlyProfile->gender) }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td style="font-size:13px;">{{ $user->elderlyProfile?->phone ?? '—' }}</td>
                    <td>
                        @if(!$user->elderlyProfile)
                            <span class="badge-status badge-inactive">No Profile</span>
                        @elseif($user->elderlyProfile->is_verified)
                            <span class="badge-status badge-verified">Verified</span>
                        @else
                            <span class="badge-status badge-pending">Pending</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#6b7280;">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-users-slash"></i>
                            <h5>No citizens found</h5>
                            <p>Try adjusting your search or filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-body pt-3 border-top">
        {{ $users->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
