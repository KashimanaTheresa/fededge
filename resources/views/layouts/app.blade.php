<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fededge') }} — @yield('page_title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>

<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">

            <!-- Brand -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-car-front-fill"></i> Fededge
            </a>

            <!-- Mobile toggler -->
            <button class="navbar-toggler border-0" type="button" id="sidebarToggle" style="background:none;color:var(--text-muted);">
                <i class="bi bi-list fs-4"></i>
            </button>

            <!-- Right-side controls -->
            <div class="d-flex align-items-center gap-2 ms-auto">

                @auth
                    {{-- Navbar links by role --}}
                    <div class="d-none d-lg-flex align-items-center gap-1 me-2">
                        @if (auth()->user()->isVehicleOwner())
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <a class="nav-link {{ request()->routeIs('vehicle.*') ? 'active' : '' }}" href="{{ route('vehicle.index') }}">
                                <i class="bi bi-car-front"></i> Vehicles
                            </a>
                            <a class="nav-link {{ request()->routeIs('document.*') ? 'active' : '' }}" href="{{ route('document.index') }}">
                                <i class="bi bi-file-earmark-text"></i> Documents
                            </a>
                        @elseif (auth()->user()->isRoadOfficer())
                            <a class="nav-link {{ request()->routeIs('officer.dashboard') ? 'active' : '' }}" href="{{ route('officer.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <a class="nav-link {{ request()->routeIs('officer.search*') ? 'active' : '' }}" href="{{ route('officer.search') }}">
                                <i class="bi bi-search"></i> Search
                            </a>
                        @elseif (auth()->user()->isAdmin())
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-grid"></i> Management
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.vehicles.index') }}"><i class="bi bi-car-front"></i> Vehicles</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Users</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.documents.index') }}"><i class="bi bi-file-earmark-text"></i> Documents</a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                @endauth

                {{-- Theme toggle --}}
                <button class="theme-toggle" id="themeToggle" title="Toggle theme">
                    <i class="bi bi-moon-fill"></i>
                </button>

                @auth
                    {{-- User dropdown --}}
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <div style="padding: 0.75rem 1rem 0.5rem; border-bottom: 1px solid var(--border); margin-bottom: 0.35rem;">
                                    <div style="font-weight: 700; font-size: 0.875rem; color: var(--text-head);">{{ auth()->user()->name }}</div>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">{{ auth()->user()->email }}</div>
                                </div>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit" style="color:var(--rose) !important;">
                                        <i class="bi bi-box-arrow-right"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>

        </div>
    </nav>

    <!-- ===== MAIN WRAPPER ===== -->
    <div class="main-wrapper">

        <!-- ===== SIDEBAR ===== -->
        @auth
        <nav class="sidebar d-none d-lg-block" id="sidebar">

            <div class="sidebar-header">
                @if (auth()->user()->isAdmin())
                    <span class="sidebar-role-badge"><i class="bi bi-shield-lock-fill"></i> Admin Panel</span>
                @elseif (auth()->user()->isRoadOfficer())
                    <span class="sidebar-role-badge"><i class="bi bi-person-badge-fill"></i> Road Officer</span>
                @else
                    <span class="sidebar-role-badge"><i class="bi bi-person-fill"></i> Vehicle Owner</span>
                @endif
            </div>

            <div class="sidebar-section-label">Navigation</div>

            <nav class="nav flex-column">
                @if (auth()->user()->isVehicleOwner())
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('vehicle.*') ? 'active' : '' }}" href="{{ route('vehicle.index') }}">
                        <i class="bi bi-car-front"></i> My Vehicles
                    </a>
                    <a class="nav-link {{ request()->routeIs('document.*') ? 'active' : '' }}" href="{{ route('document.index') }}">
                        <i class="bi bi-file-earmark-text"></i> Documents
                    </a>

                @elseif (auth()->user()->isRoadOfficer())
                    <a class="nav-link {{ request()->routeIs('officer.dashboard') ? 'active' : '' }}" href="{{ route('officer.dashboard') }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('officer.search*') ? 'active' : '' }}" href="{{ route('officer.search') }}">
                        <i class="bi bi-search"></i> Search Vehicles
                    </a>

                @elseif (auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>

                    <div class="sidebar-section-label mt-2">Management</div>
                    <a class="nav-link {{ request()->routeIs('admin.vehicles*') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">
                        <i class="bi bi-car-front"></i> Vehicles
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.documents*') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">
                        <i class="bi bi-file-earmark-text"></i> Documents
                    </a>
                @endif
            </nav>

            {{-- Sign out at bottom --}}
            <div style="position:absolute; bottom:1.5rem; left:0; right:0; padding:0 0.75rem;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link w-100 border-0 text-start" style="background:none; color:rgba(255,255,255,0.35) !important; font-size:0.875rem;">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>

        </nav>
        @endauth

        <!-- ===== MAIN CONTENT ===== -->
        <div class="main-content flex-grow-1">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show animate-up">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show animate-up">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate-up">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} <strong>Fededge</strong> — Vehicle Registration &amp; Compliance Management</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ── Theme Toggle ──────────────────────────────────────────
        const html = document.documentElement;
        const themeBtn = document.getElementById('themeToggle');
        const saved = localStorage.getItem('fedTheme') || 'light';
        html.setAttribute('data-bs-theme', saved);
        updateThemeIcon(saved);

        themeBtn?.addEventListener('click', () => {
            const next = html.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('fedTheme', next);
            updateThemeIcon(next);
        });

        function updateThemeIcon(t) {
            const i = themeBtn?.querySelector('i');
            if (i) i.className = t === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        }

        // ── Mobile sidebar ────────────────────────────────────────
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar')?.classList.toggle('show');
        });

        // ── Stat counter animation ────────────────────────────────
        document.querySelectorAll('.stat-value[data-count]').forEach(el => {
            const target = parseInt(el.dataset.count, 10);
            let current = 0;
            const step = Math.max(1, Math.floor(target / 40));
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = current.toLocaleString();
                if (current >= target) clearInterval(timer);
            }, 20);
        });
    </script>

    @stack('scripts')
</body>
</html>
