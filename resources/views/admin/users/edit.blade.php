@extends('layouts.app')
@section('page_title', 'Edit User Role')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-pencil-square"></i> Edit Role</h1>
        <p class="page-subtitle">Change role for {{ $user->name }}</p>
    </div>
    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row justify-content-center animate-up">
    <div class="col-md-6">
        <div class="chart-card">
            <div class="card-header gradient"><i class="bi bi-shield-lock-fill"></i> Role Assignment</div>
            <div class="card-body">

                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border);">
                    <div style="width:48px;height:48px;border-radius:12px;background:var(--blue-bg);display:flex;align-items:center;justify-content:center;color:var(--blue);font-weight:800;font-size:1.1rem;flex-shrink:0;">
                        {{ $user->initials() }}
                    </div>
                    <div>
                        <div style="font-weight:700;color:var(--text-head);">{{ $user->name }}</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);">{{ $user->email }}</div>
                    </div>
                </div>

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <label class="form-label" style="font-weight:700;margin-bottom:0.75rem;display:block;">Assign Role</label>

                    @foreach ($roles as $value => $label)
                    <label for="role_{{ $value }}" style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1rem;background:var(--body-bg);border-radius:var(--radius-md);border:2px solid {{ $user->role === $value ? 'var(--red)' : 'var(--border)' }};margin-bottom:0.6rem;cursor:pointer;transition:border-color 0.2s;">
                        <input class="form-check-input m-0" type="radio" name="role" id="role_{{ $value }}" value="{{ $value }}" {{ $user->role === $value ? 'checked' : '' }} style="flex-shrink:0;">
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            @if ($value === 'admin')
                                <i class="bi bi-shield-lock-fill" style="color:var(--rose);font-size:1.1rem;"></i>
                            @elseif ($value === 'vehicle_owner')
                                <i class="bi bi-car-front-fill" style="color:var(--green);font-size:1.1rem;"></i>
                            @else
                                <i class="bi bi-person-badge-fill" style="color:var(--blue);font-size:1.1rem;"></i>
                            @endif
                            <span style="font-weight:600;">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach

                    <button type="submit" class="btn btn-primary w-100 mt-3">
                        <i class="bi bi-check-lg"></i> Update Role
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
