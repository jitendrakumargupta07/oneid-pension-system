@extends('layouts.user')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-bell me-2" style="color:var(--accent);"></i>Notifications</h2>
    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
    @if($unread > 0)
    <form method="POST" action="{{ route('user.notifications.readAll') }}">
        @csrf
        <button type="submit" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-check-double me-1"></i>Mark All as Read
        </button>
    </form>
    @endif
</div>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            @forelse($notifications as $notif)
            <div class="d-flex gap-3 px-4 py-4 {{ !$notif->is_read ? 'unread-notification' : '' }}"
                style="border-bottom:1px solid #f0f0f7;{{ !$notif->is_read ? 'background:#fdfeff;' : '' }}">

                {{-- Icon --}}
                <div style="flex-shrink:0;margin-top:2px;">
                    @php
                        $type = $notif->type ?? 'info';
                        $iconMap = ['success'=>'check-circle','danger'=>'times-circle','info'=>'info-circle','warning'=>'exclamation-circle'];
                        $colorMap = ['success'=>'#2e7d32','danger'=>'#c62828','info'=>'#0277bd','warning'=>'#f57c00'];
                        $bgMap = ['success'=>'#e8f5e9','danger'=>'#ffebee','info'=>'#e3f2fd','warning'=>'#fff8e1'];
                    @endphp
                    <div style="width:40px;height:40px;border-radius:50%;background:{{ $bgMap[$type] ?? '#e3f2fd' }};display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-{{ $iconMap[$type] ?? 'info-circle' }}" style="color:{{ $colorMap[$type] ?? '#0277bd' }};font-size:16px;"></i>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div style="font-size:14px;font-weight:700;color:#1a1f36;margin-bottom:4px;">
                                {{ $notif->title }}
                                @if(!$notif->is_read)
                                    <span style="width:8px;height:8px;background:var(--primary);border-radius:50%;display:inline-block;margin-left:6px;vertical-align:middle;"></span>
                                @endif
                            </div>
                            <div style="font-size:13px;color:#6b7280;line-height:1.6;margin-bottom:6px;">
                                {{ $notif->message }}
                            </div>
                            <div style="font-size:11.5px;color:#9ca3af;">
                                <i class="fas fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                            </div>
                        </div>
                        @if(!$notif->is_read)
                        <a href="{{ route('user.notifications.read', $notif) }}" class="btn btn-sm btn-outline-primary ms-3" style="flex-shrink:0;">
                            Mark Read
                        </a>
                        @endif
                    </div>
                    @if($notif->link)
                    <div class="mt-2">
                        <a href="{{ $notif->link }}" style="font-size:12.5px;color:var(--primary);font-weight:600;text-decoration:none;">
                            <i class="fas fa-arrow-right me-1"></i>View Details
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state py-5">
                <i class="fas fa-bell-slash"></i>
                <h5>No Notifications</h5>
                <p>You're all caught up! No notifications at this time.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
