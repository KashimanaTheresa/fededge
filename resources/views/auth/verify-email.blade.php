@extends('layouts.auth')
@section('title', 'Fededge - Verify Email')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-card-header">
            <i class="bi bi-envelope-check"></i>
            <h2>Verify Email</h2>
            <p>Confirm Your Email Address</p>
        </div>

        <div class="auth-card-body">
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <strong>Verification Link Sent!</strong><br>
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <div class="info-section">
                <i class="bi bi-info-circle"></i>
                <div>
                    <strong>Welcome to Fededge!</strong><br>
                    Thanks for signing up. Before you can access your account, please verify your email address by clicking on the link we just sent to your email. If you didn't receive the email, we'll gladly send you another.
                </div>
            </div>

            <div class="action-buttons">
                <form method="POST" action="{{ route('verification.send') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-envelope"></i> Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
