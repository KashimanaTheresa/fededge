@extends('layouts.auth')
@section('title', 'Fededge - Create Account')
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
                <h2>Create your account</h2>
                <p>Join Fededge and manage your vehicles with ease</p>
            </div>

            @if ($errors->any())
                <div class="auth-alert auth-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Please fix the errors below and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="auth-field">
                    <label for="name">Full Name</label>
                    <div class="auth-input-wrap">
                        <i class="bi bi-person"></i>
                        <input type="text" id="name" name="name"
                            value="{{ old('name') }}"
                            placeholder="John Doe"
                            class="@error('name') is-invalid @enderror"
                            required autofocus>
                    </div>
                    @error('name')
                        <span class="auth-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="email">Email Address</label>
                    <div class="auth-input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            class="@error('email') is-invalid @enderror"
                            required>
                    </div>
                    @error('email')
                        <span class="auth-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-two-col">
                    <div class="auth-field">
                        <label for="phone">Phone <span class="auth-optional">(optional)</span></label>
                        <div class="auth-input-wrap">
                            <i class="bi bi-telephone"></i>
                            <input type="tel" id="phone" name="phone"
                                value="{{ old('phone') }}"
                                placeholder="+1 234 567 890"
                                class="@error('phone') is-invalid @enderror">
                        </div>
                        @error('phone')
                            <span class="auth-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="address">Address <span class="auth-optional">(optional)</span></label>
                        <div class="auth-input-wrap">
                            <i class="bi bi-geo-alt"></i>
                            <input type="text" id="address" name="address"
                                value="{{ old('address') }}"
                                placeholder="123 Main St"
                                class="@error('address') is-invalid @enderror">
                        </div>
                        @error('address')
                            <span class="auth-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="auth-two-col">
                    <div class="auth-field">
                        <label for="password">Password</label>
                        <div class="auth-input-wrap">
                            <i class="bi bi-lock"></i>
                            <input type="password" id="password" name="password"
                                placeholder="Min. 8 characters"
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

                    <div class="auth-field">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="auth-input-wrap">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Repeat password"
                                class="@error('password_confirmation') is-invalid @enderror"
                                required>
                            <button type="button" class="auth-toggle-pass" onclick="togglePassword('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="auth-field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="auth-submit-btn">
                    Create Account <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-divider"><span>or</span></div>

            <div class="auth-form-footer">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
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
