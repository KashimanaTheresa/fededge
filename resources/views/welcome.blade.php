<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fededge - Vehicle Registration & Compliance Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset("assets/css/style.css") }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <i class="bi bi-car-front-fill"></i> Fededge
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#roles">Roles</a>
                    </li>
                    <li class="nav-item">
                        @auth
                            <a class="nav-link" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isRoadOfficer() ? route('officer.dashboard') : route('dashboard')) }}">
                                Dashboard
                            </a>
                        @else
                            <a class="nav-link btn btn-light btn-sm ms-2" href="{{ route('login') }}">Login</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content">
                <h1><i class="bi bi-car-front-fill"></i> Fededge</h1>
                <p>Professional Vehicle Registration & Compliance Management System</p>
                <p style="font-size: 1rem; opacity: 0.85; margin-top: 1rem;">Manage vehicles, track documents, and ensure compliance with ease</p>

                <div class="hero-buttons">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-hero btn-hero-primary">
                                <i class="bi bi-speedometer2"></i> Go to Admin Dashboard
                            </a>
                        @elseif (auth()->user()->isRoadOfficer())
                            <a href="{{ route('officer.dashboard') }}" class="btn-hero btn-hero-primary">
                                <i class="bi bi-search"></i> Go to Officer Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn-hero btn-hero-primary">
                                <i class="bi bi-speedometer2"></i> Go to My Dashboard
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-hero btn-hero-secondary">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-hero btn-hero-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn-hero btn-hero-secondary">
                            <i class="bi bi-person-plus"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Key Features</h2>
                <p>Everything you need for complete vehicle registration and compliance management</p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <h3>Vehicle Management</h3>
                        <p>Register and manage multiple vehicles with comprehensive tracking of vehicle details, maintenance records, and ownership information.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-pdf"></i>
                        </div>
                        <h3>Document Management</h3>
                        <p>Upload, store, and manage all vehicle documents with automatic expiry tracking and admin approval workflow.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h3>Compliance Tracking</h3>
                        <p>Automated compliance checking, expiry alerts, and status monitoring to ensure all vehicles meet regulatory requirements.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lock"></i>
                        </div>
                        <h3>Role-Based Security</h3>
                        <p>Secure access control with three distinct user roles: Admin, Vehicle Owner, and Road Officer.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h3>Analytics Dashboard</h3>
                        <p>Real-time dashboards with comprehensive statistics, charts, and compliance metrics at a glance.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h3>Responsive Design</h3>
                        <p>Works seamlessly on desktop, tablet, and mobile devices with light and dark mode support.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Roles Section -->
    <section class="roles-section" id="roles">
        <div class="container">
            <div class="section-title">
                <h2>User Roles</h2>
                <p>Fededge supports three distinct user roles with specialized features for each</p>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="role-box">
                        <span class="role-icon">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <h3>Administrator</h3>
                        <p>System administrators with full control</p>
                        <ul class="role-features">
                            <li>Manage all users</li>
                            <li>Approve/reject documents</li>
                            <li>View system analytics</li>
                            <li>Monitor compliance</li>
                            <li>Manage vehicle data</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="role-box">
                        <span class="role-icon">
                            <i class="bi bi-person"></i>
                        </span>
                        <h3>Vehicle Owner</h3>
                        <p>Vehicle owners managing their fleet</p>
                        <ul class="role-features">
                            <li>Register vehicles</li>
                            <li>Upload documents</li>
                            <li>Check compliance status</li>
                            <li>Receive alerts</li>
                            <li>Track documents</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="role-box">
                        <span class="role-icon">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        <h3>Road Officer</h3>
                        <p>Officers verifying vehicle compliance</p>
                        <ul class="role-features">
                            <li>Search vehicles</li>
                            <li>Verify compliance</li>
                            <li>View documents</li>
                            <li>Generate reports</li>
                            <li>Check status</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of users managing vehicle compliance efficiently with Fededge</p>

            <div class="hero-buttons" style="justify-content: center;">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-hero btn-hero-primary">
                            <i class="bi bi-arrow-right"></i> Admin Dashboard
                        </a>
                    @elseif (auth()->user()->isRoadOfficer())
                        <a href="{{ route('officer.dashboard') }}" class="btn-hero btn-hero-primary">
                            <i class="bi bi-arrow-right"></i> Officer Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-hero btn-hero-primary">
                            <i class="bi bi-arrow-right"></i> My Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                        <i class="bi bi-person-plus"></i> Create Account
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} <strong>Fededge</strong> - Vehicle Registration & Compliance Management System. All rights reserved.</p>
            <p style="margin-top: 0.5rem; font-size: 0.9rem;">Built with Laravel | Professional Vehicle Management</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Add scroll effect to navbar
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 20px rgba(211, 47, 47, 0.3)';
            } else {
                navbar.style.boxShadow = '0 4px 15px rgba(211, 47, 47, 0.2)';
            }
        });
    </script>
</body>

</html>
