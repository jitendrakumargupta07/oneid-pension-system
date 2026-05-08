<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OneID Pension System') | Citizen Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <nav id="sidebar">
        <div class="brand">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-icon"><i class="fas fa-landmark"></i></div>
                <div>
                    <h6 class="mb-0">OneID Pension</h6>
                    <small>Citizen Portal</small>
                </div>
            </div>
        </div>
        @php $profile = auth()->user()->elderlyProfile; @endphp
        @if($profile)
            <div class="sidebar-one-id">
                <div class="one-id-label-sm">Your OneID</div>
                <div class="one-id-val-sm">{{ $profile->one_id }}</div>
            </div>
        @endif
        <ul class="nav flex-column mt-2">
            <li class="nav-section">My Portal</li>
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.profile.index') }}" class="{{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
                    <i class="fas fa-id-card"></i> My Profile
                </a>
            </li>
            <li class="nav-section">Pension</li>
            <li class="nav-item">
                <a href="{{ route('user.applications.index') }}" class="{{ request()->routeIs('user.applications.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Applications
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.payments.index') }}" class="{{ request()->routeIs('user.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> Payments
                </a>
            </li>
            <li class="nav-section">Account</li>
            <li class="nav-item">
                <a href="{{ route('user.notifications.index') }}" class="{{ request()->routeIs('user.notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i> Notifications
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    @if($unread > 0)
                        <span class="badge ms-auto" style="background:var(--accent);color:#1a237e;">{{ $unread }}</span>
                    @endif
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="sidebar-avatar"><i class="fas fa-user"></i></div>
                <div>
                    <div class="sidebar-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-role">Citizen</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                    @csrf
                    <button type="submit" class="sidebar-logout" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <header id="topbar">
        <button class="btn d-md-none me-2" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="fas fa-bars text-primary"></i>
        </button>
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-3">
            @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
            <a href="{{ route('user.notifications.index') }}" class="notif-bell text-decoration-none text-dark">
                <i class="fas fa-bell" style="font-size:18px;color:#6b7280;"></i>
                @if($unreadCount > 0)
                    <span class="notif-count">{{ $unreadCount }}</span>
                @endif
            </a>
            <div class="topbar-avatar"><i class="fas fa-user"></i></div>
            <span class="topbar-name">{{ auth()->user()->name }}</span>
        </div>
    </header>
    <main id="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show mb-4">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-custom alert-dismissible fade show mb-4">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
