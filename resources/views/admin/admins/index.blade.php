@extends('layouts.admin')
@section('title', 'System Administrators')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-users-cog me-2" style="color:var(--accent)"></i>System Administrators</h2>
        <p class="page-subtitle">Manage high-privilege access to the OneID Pension System.</p>
    </div>
    <a href="{{ route('admin.admins.create') }}" class="btn fw-bold px-4" style="background:var(--primary);color:#fff">
        <i class="fas fa-user-plus me-2"></i>Register New Admin
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px"></th>
                        <th>Administrator Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr>
                        <td>
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#283593);color:white;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold" style="font-size:14px">{{ $admin->name }}</div>
                            @if($admin->id === auth()->id())
                                <span class="badge bg-success" style="font-size:10px;padding:2px 6px">You</span>
                            @endif
                        </td>
                        <td style="font-size:13px;color:#6b7280">{{ $admin->email }}</td>
                        <td><span class="status-badge status-verified"><i class="fas fa-shield-alt"></i> Super Admin</span></td>
                        <td style="font-size:12px;color:#6b7280">{{ $admin->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($admins->hasPages())
        <div class="p-3 border-top">{{ $admins->links() }}</div>
        @endif
    </div>
</div>
@endsection
