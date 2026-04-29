@extends('layouts.auth')
@section('title', 'Fededge - Create New Password')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-card-header">
            <i class="bi bi-lock-fill"></i>
            <h2>Create New Password</h2>
            <p>Secure Your Account</p>
        </div>

        <div class="auth-card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="bi bi-exclamation-circle"></i> Error</strong><br>
                    Please correct the errors below and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="auth-form-group">
                    <label for="email" class="auth-form-label">Email Address</label>
                    <input
                        type="email"
                        class="auth-form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        placeholder="Enter your email"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="error-message">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-form-label">New Password</label>
                    <input
                        type="password"
                        class="auth-form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        placeholder="Create a strong password"
                        required
                    >
                    @error('password')
                        <div class="error-message">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="password-requirements">
                        <strong><i class="bi bi-shield-lock"></i> Password Requirements:</strong>
                        <ul>
                            <li>At least 8 characters</li>
                            <li>Uppercase letters (A-Z)</li>
                            <li>Lowercase letters (a-z)</li>
                            <li>Numbers (0-9)</li>
                            <li>Special symbols (!@#$%^&*)</li>
                        </ul>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="password_confirmation" class="auth-form-label">Confirm Password</label>
                    <input
                        type="password"
                        class="auth-form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        required
                    >
                    @error('password_confirmation')
                        <div class="error-message">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Reset Password
                </button>
            </form>

            <div class="back-link">
                <a href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right"></i> Back to Sign In
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
