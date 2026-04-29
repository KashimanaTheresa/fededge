@extends('layouts.app')
@section('page_title', 'User Profile')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-person-fill"></i> {{ $user->name }}</h1>
        <p class="page-subtitle">{{ $user->email }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit Role
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4 animate-up">

    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="card-header"><i class="bi bi-person-fill"></i> Profile</div>
            <div class="card-body">
                <div style="text-align:center;margin-bottom:1.25rem;">
                    <div style="width:72px;height:72px;border-radius:18px;background:var(--blue-bg);display:flex;align-items:center;justify-content:center;color:var(--blue);font-weight:800;font-size:1.75rem;margin:0 auto 0.75rem;">
                        {{ $user->initials() }}
                    </div>
                    <div style="font-weight:800;font-size:1.1rem;color:var(--text-head);">{{ $user->name }}</div>
                    <div style="font-size:0.85rem;color:var(--text-muted);">{{ $user->email }}</div>
                    @php
                        $roleMap = ['admin' => 'badge-danger', 'vehicle_owner' => 'badge-success', 'road_officer' => 'badge-info'];
                    @endphp
                    <div style="margin-top:0.5rem;">
                        <span class="badge {{ $roleMap[$user->role] ?? 'badge-secondary' }}">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                </div>

                <div style="border-top:1px solid var(--border);padding-top:1rem;">
                    @if ($user->phone)
                    <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.6rem;font-size:0.88rem;">
                        <i class="bi bi-phone" style="color:var(--text-muted);"></i>
                        <span>{{ $user->phone }}</span>
                    </div>
                    @endif
                    @if ($user->address)
                    <div style="display:flex;align-items:flex-start;gap:0.6rem;margin-bottom:0.6rem;font-size:0.88rem;">
                        <i class="bi bi-geo-alt" style="color:var(--text-muted);margin-top:2px;"></i>
                        <span>{{ $user->address }}</span>
                    </div>
                    @endif
                    <div style="display:flex;align-items:center;gap:0.6rem;font-size:0.88rem;">
                        <i class="bi bi-calendar" style="color:var(--text-muted);"></i>
                        <span>Joined {{ $user->created_at->format('F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Vehicles --}}
    <div class="col-lg-8">
        <div class="table-card">
            <div class="table-header">
                <span class="table-title"><i class="bi bi-car-front-fill"></i> Vehicles</span>
                <span style="font-size:0.8rem;color:var(--text-muted);">{{ $user->vehicles->count() }} total</span>
            </div>
            @if ($user->vehicles->isNotEmpty())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Plate</th>
                            <th>Model / Year</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->vehicles as $vehicle)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.6rem;">
                                    <div style="width:32px;height:32px;border-radius:7px;background:var(--red-glass);display:flex;align-items:center;justify-content:center;color:var(--red);font-size:0.95rem;flex-shrink:0;">
                                        <i class="bi bi-car-front-fill"></i>
                                    </div>
                                    <strong>{{ $vehicle->plate_number }}</strong>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:600;">{{ $vehicle->brand_model }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $vehicle->year_of_manufacture }}</div>
                            </td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">{{ ucfirst($vehicle->vehicle_type) }}</td>
                            <td>
                                @if ($vehicle->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif ($vehicle->status === 'suspended')
                                    <span class="badge badge-danger">Suspended</span>
                                @else
                                    <span class="badge badge-warning">{{ ucfirst($vehicle->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state" style="padding:2.5rem;">
                <div class="empty-icon" style="width:48px;height:48px;font-size:1.3rem;"><i class="bi bi-car-front"></i></div>
                <h5 style="font-size:0.95rem;">No vehicles registered</h5>
                <p>This user has no vehicles registered.</p>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection
