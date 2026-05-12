@extends('layouts.admin')
@section('title', 'Fraud Alerts')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-title"><i class="fas fa-shield-alt text-danger me-2"></i>Fraud Detection Dashboard</h2>
        <p class="page-subtitle">Monitor and manage suspicious activity and fraud alerts.</p>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color:#dc3545">
            <div class="stat-icon" style="background:rgba(220,53,69,.1);color:#dc3545"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-value">{{ $stats['open'] }}</div>
            <div class="stat-label">OPEN ALERTS</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color:#c0392b">
            <div class="stat-icon" style="background:rgba(192,57,43,.1);color:#c0392b"><i class="fas fa-fire"></i></div>
            <div class="stat-value">{{ $stats['high'] }}</div>
            <div class="stat-label">HIGH SEVERITY</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color:#f39c12">
            <div class="stat-icon" style="background:rgba(243,156,18,.1);color:#f39c12"><i class="fas fa-exclamation-circle"></i></div>
            <div class="stat-value">{{ $stats['open'] - $stats['high'] }}</div>
            <div class="stat-label">MEDIUM / LOW</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="border-top-color:#27ae60">
            <div class="stat-icon" style="background:rgba(39,174,96,.1);color:#27ae60"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value">{{ $stats['resolved'] }}</div>
            <div class="stat-label">RESOLVED</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
                    <option value="resolved" {{ request('status')=='resolved'?'selected':'' }}>Resolved</option>
                    <option value="dismissed" {{ request('status')=='dismissed'?'selected':'' }}>Dismissed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="severity" class="form-select form-select-sm">
                    <option value="">All Severity</option>
                    <option value="high" {{ request('severity')=='high'?'selected':'' }}>High</option>
                    <option value="medium" {{ request('severity')=='medium'?'selected':'' }}>Medium</option>
                    <option value="low" {{ request('severity')=='low'?'selected':'' }}>Low</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(\App\Models\FraudAlert::$typeLabels as $key => $label)
                        <option value="{{ $key }}" {{ request('type')==$key?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="{{ route('admin.fraud.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
</div>

{{-- Alerts Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CITIZEN</th>
                        <th>ALERT TYPE</th>
                        <th>DESCRIPTION</th>
                        <th>SEVERITY</th>
                        <th>STATUS</th>
                        <th>DATE</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alerts as $alert)
                    <tr>
                        <td>
                            <div class="fw-semibold" style="font-size:13px">{{ $alert->elderlyProfile?->full_name ?? 'N/A' }}</div>
                            <div style="font-size:11px;color:#6b7280">{{ $alert->elderlyProfile?->one_id }}</div>
                        </td>
                        <td>
                            <span style="font-size:12px;font-weight:600">
                                <i class="fas {{ \App\Models\FraudAlert::$typeIcons[$alert->alert_type] ?? 'fa-flag' }} me-1 text-danger"></i>
                                {{ $alert->type_label }}
                            </span>
                        </td>
                        <td style="font-size:12px;max-width:250px">{{ Str::limit($alert->description, 80) }}</td>
                        <td>
                            <span class="badge bg-{{ $alert->severity_color }}">{{ strtoupper($alert->severity) }}</span>
                        </td>
                        <td>
                            @if($alert->status === 'open')
                                <span class="status-badge status-pending">Open</span>
                            @elseif($alert->status === 'resolved')
                                <span class="status-badge status-approved">Resolved</span>
                            @else
                                <span class="status-badge status-rejected">Dismissed</span>
                            @endif
                        </td>
                        <td style="font-size:12px">{{ $alert->created_at->format('d M Y') }}</td>
                        <td>
                            @if($alert->isOpen())
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#resolveModal{{ $alert->id }}">
                                <i class="fas fa-check me-1"></i>Resolve
                            </button>
                            @else
                            <span style="font-size:11px;color:#6b7280">{{ ucfirst($alert->status) }} by {{ $alert->resolvedBy?->name }}</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Resolve Modal --}}
                    @if($alert->isOpen())
                    <div class="modal fade" id="resolveModal{{ $alert->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fas fa-shield-alt me-2"></i>Resolve Fraud Alert</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.fraud.resolve', $alert) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-muted" style="font-size:13px">{{ $alert->description }}</p>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Action</label>
                                            <select name="action" class="form-select" required>
                                                <option value="resolved">Mark as Resolved (Legitimate)</option>
                                                <option value="dismissed">Dismiss (False Positive)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Resolution Notes</label>
                                            <textarea name="resolution_notes" class="form-control" rows="3" placeholder="Add your findings or notes..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i>Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-shield-check fa-3x text-success mb-3 d-block"></i>
                            <strong>No fraud alerts found.</strong>
                            <p class="text-muted mt-1">The system is clean — no suspicious activity detected.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $alerts->links() }}</div>
    </div>
</div>
@endsection
