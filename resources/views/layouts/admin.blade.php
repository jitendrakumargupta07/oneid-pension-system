<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OneID Pension System') | Government of India</title>
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
                    <small>Government of India</small>
                </div>
            </div>
        </div>
        <ul class="nav flex-column mt-2">
            <li class="nav-section">Main</li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li class="nav-section">Citizens</li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> All Citizens
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.search') }}" class="{{ request()->routeIs('admin.users.search') ? 'active' : '' }}">
                    <i class="fas fa-search"></i> Search by OneID
                </a>
            </li>
            <li class="nav-section">Pension</li>
            <li class="nav-item">
                <a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Applications
                    @php $pendingCount = \App\Models\PensionApplication::where('status','pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge ms-auto" style="background:var(--accent);color:#1a237e;">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.schemes.index') }}" class="{{ request()->routeIs('admin.schemes.*') ? 'active' : '' }}">
                    <i class="fas fa-list-check"></i> Schemes
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> Payments
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="sidebar-avatar"><i class="fas fa-user-shield"></i></div>
                <div>
                    <div class="sidebar-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-role">Administrator</div>
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
        <nav aria-label="breadcrumb" class="d-none d-md-flex me-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <div class="topbar-avatar"><i class="fas fa-user-shield"></i></div>
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
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>
</html>
