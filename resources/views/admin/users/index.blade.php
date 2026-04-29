@extends('layouts.app')
@section('page_title', 'All Users')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-people-fill"></i> Users</h1>
        <p class="page-subtitle">All registered user accounts</p>
    </div>
</div>

@if ($users->isNotEmpty())

<div class="table-card animate-up">
    <div class="table-header">
        <span class="table-title"><i class="bi bi-people"></i> Users</span>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $users->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Vehicles</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.8rem;">
                            <div style="width:36px;height:36px;border-radius:9px;background:var(--blue-bg);display:flex;align-items:center;justify-content:center;color:var(--blue);font-weight:800;font-size:0.9rem;flex-shrink:0;">
                                {{ $user->initials() }}
                            </div>
                            <div>
                                <div style="font-weight:600;">{{ $user->name }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleMap = [
                                'admin'         => 'badge-danger',
                                'vehicle_owner' => 'badge-success',
                                'road_officer'  => 'badge-info',
                            ];
                        @endphp
                        <span class="badge {{ $roleMap[$user->role] ?? 'badge-secondary' }}">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td style="font-size:0.85rem;color:var(--text-muted);">{{ $user->phone ?? '—' }}</td>
                    <td>
                        <span style="font-weight:700;color:var(--text-head);">{{ $user->vehicles_count }}</span>
                        <span style="font-size:0.75rem;color:var(--text-muted);"> vehicles</span>
                    </td>
                    <td style="font-size:0.82rem;color:var(--text-muted);">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit Role">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $users->links() }}</div>

@else

<div class="card animate-up">
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-people"></i></div>
        <h5>No Users Found</h5>
        <p>No user accounts have been registered yet.</p>
    </div>
</div>

@endif

@endsection
