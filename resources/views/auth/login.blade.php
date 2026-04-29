@extends('layouts.auth')
@section('title', 'Fededge - Sign In')
@section('body_class', 'auth-split-body')

@section('content')
<div class="auth-split-wrapper">

    <!-- Left Panel -->
    <div class="auth-split-left">
        <div class="auth-brand">
            <div class="auth-brand-logo">
                <i class="bi bi-car-front-fill"></i>
            </div>
            <h1>Fededge</h1>
            <p>Vehicle Registration &amp; Compliance Management System</p>
        </div>
        <ul class="auth-feature-list">
            <li><i class="bi bi-check-circle-fill"></i> Manage vehicles &amp; documents</li>
            <li><i class="bi bi-check-circle-fill"></i> Real-time compliance tracking</li>
            <li><i class="bi bi-check-circle-fill"></i> Role-based access control</li>
            <li><i class="bi bi-check-circle-fill"></i> Instant alerts &amp; notifications</li>
        </ul>
        <div class="auth-split-footer">
            &copy; {{ date('Y') }} Fededge. All rights reserved.
        </div>
    </div>

    <!-- Right Panel -->
    <div class="auth-split-right">
        <div class="auth-form-wrapper">

            <div class="auth-form-header">
                <h2>Welcome back</h2>
                <p>Sign in to your account to continue</p>
            </div>

            @if ($errors->any())
                <div class="auth-alert auth-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Invalid credentials. Please try again.
                </div>
            @endif

            @if (session('status'))
                <div class="auth-alert auth-alert-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="auth-field">
                    <label for="email">Email Address</label>
                    <div class="auth-input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            class="@error('email') is-invalid @enderror"
                            required autofocus>
                    </div>
                    @error('email')
                        <span class="auth-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-field">
                    <div class="auth-field-top">
                        <label for="password">Password</label>
                        <a href="{{ route('password.request') }}" class="auth-forgot">Forgot password?</a>
                    </div>
                    <div class="auth-input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" id="password" name="password"
                            placeholder="Enter your password"
                            class="@error('password') is-invalid @enderror"
                            required>
                        <button type="button" class="auth-toggle-pass" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="auth-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-remember">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Keep me signed in</label>
                </div>

                <button type="submit" class="auth-submit-btn">
                    Sign In <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-divider"><span>or</span></div>

            <div class="auth-form-footer">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>

            <div class="auth-demo-box">
                <div class="auth-demo-title"><i class="bi bi-info-circle"></i> Demo Credentials</div>
                <div class="auth-demo-grid">
                    <div><span>Admin</span> admin@fededge.com / password</div>
                    <div><span>Owner</span> owner@fededge.com / password</div>
                    <div><span>Officer</span> officer@fededge.com / password</div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
@endpush
