@extends('layouts.admin')
@section('title', 'Activity Log')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-history me-2" style="color:var(--accent)"></i>Activity Log & Audit Trail</h2>
        <p class="page-subtitle">A complete timestamped record of all admin actions on the system.</p>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-filter text-muted"></i></span>
                    <input type="text" name="action" class="form-control border-start-0" placeholder="Filter by action (e.g. application.approved)" value="{{ request('action') }}">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
</div>

{{-- Log Timeline --}}
<div class="card">
    <div class="card-body p-0">
        @forelse($logs as $log)
        @php
            $actionColor = match(true) {
                str_contains($log->action, 'approved') => '#27ae60',
                str_contains($log->action, 'rejected') => '#e74c3c',
                str_contains($log->action, 'verified') => '#2980b9',
                str_contains($log->action, 'fraud')    => '#e67e22',
                str_contains($log->action, 'payment')  => '#8e44ad',
                str_contains($log->action, 'document') => '#16a085',
                default                                 => '#6b7280',
            };
            $actionIcon = match(true) {
                str_contains($log->action, 'approved') => 'fa-check-circle',
                str_contains($log->action, 'rejected') => 'fa-times-circle',
                str_contains($log->action, 'verified') => 'fa-user-check',
                str_contains($log->action, 'fraud')    => 'fa-shield-alt',
                str_contains($log->action, 'payment')  => 'fa-money-bill-wave',
                str_contains($log->action, 'document') => 'fa-file-check',
                default                                 => 'fa-circle',
            };
        @endphp
        <div class="d-flex gap-0" style="border-bottom:1px solid #f3f4f8;transition:background .15s" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">
            {{-- Time column --}}
            <div style="width:110px;min-width:110px;padding:14px 12px;text-align:center;border-right:1px solid #f3f4f8">
                <div style="font-size:11px;font-weight:700;color:#374151">{{ $log->created_at->format('d M Y') }}</div>
                <div style="font-size:11px;color:#9ca3af">{{ $log->created_at->format('h:i A') }}</div>
                <div style="font-size:10px;color:#c4c9d4;margin-top:2px">{{ $log->created_at->diffForHumans() }}</div>
            </div>

            {{-- Icon --}}
            <div style="width:48px;min-width:48px;display:flex;align-items:center;justify-content:center;padding:0 6px">
                <div style="width:32px;height:32px;border-radius:50%;background:{{ $actionColor }}18;display:flex;align-items:center;justify-content:center">
                    <i class="fas {{ $actionIcon }}" style="color:{{ $actionColor }};font-size:13px"></i>
                </div>
            </div>

            {{-- Content --}}
            <div style="flex:1;padding:14px 16px">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span style="background:{{ $actionColor }}18;color:{{ $actionColor }};font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;font-family:monospace;letter-spacing:.5px">{{ $log->action }}</span>
                    @if($log->model_type)
                    <span style="font-size:10px;color:#9ca3af;background:#f3f4f8;padding:2px 8px;border-radius:20px">
                        {{ $log->model_type }} #{{ $log->model_id }}
                    </span>
                    @endif
                </div>
                <div style="font-size:13px;color:#374151">{{ $log->description }}</div>
                @if($log->ip_address)
                <div style="font-size:11px;color:#9ca3af;margin-top:2px">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ $log->ip_address }}
                </div>
                @endif
            </div>

            {{-- Performed by --}}
            <div style="width:140px;min-width:140px;padding:14px 12px;text-align:right;border-left:1px solid #f3f4f8">
                <div style="font-size:12px;font-weight:600;color:#374151">{{ $log->user?->name ?? 'System' }}</div>
                <div style="font-size:10px;color:#9ca3af">{{ ucfirst($log->user?->role ?? 'auto') }}</div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <div style="font-size:56px;opacity:.2;margin-bottom:12px">📋</div>
            <h5 class="text-muted">No activity logs found</h5>
            <p class="text-muted" style="font-size:13px">Activity logs will appear here as admins take actions like approving applications, reviewing documents, and recording payments.</p>
        </div>
        @endforelse
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white border-top py-3">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
