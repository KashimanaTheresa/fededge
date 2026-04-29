@extends('layouts.app')
@section('page_title', 'All Vehicles')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-car-front-fill"></i> Vehicles</h1>
        <p class="page-subtitle">All registered vehicles in the system</p>
    </div>
</div>

@if ($vehicles->isNotEmpty())

<div class="table-card animate-up">
    <div class="table-header">
        <span class="table-title"><i class="bi bi-car-front-fill"></i> Vehicles</span>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $vehicles->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Plate</th>
                    <th>Model / Year</th>
                    <th>Type</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Compliance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
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
                        <div style="font-size:0.85rem;">{{ $vehicle->owner->name }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $vehicle->owner->email }}</div>
                    </td>
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
                        @php
                            $compMap = [
                                'compliant'         => ['class' => 'badge-success', 'icon' => 'bi-patch-check-fill', 'label' => 'Compliant'],
                                'expired'           => ['class' => 'badge-danger',  'icon' => 'bi-calendar-x',       'label' => 'Expired'],
                                'pending'           => ['class' => 'badge-warning', 'icon' => 'bi-hourglass-split',  'label' => 'Pending'],
                                'missing_documents' => ['class' => 'badge-info',    'icon' => 'bi-file-x',           'label' => 'Missing Docs'],
                            ];
                            $c = $compMap[$vehicle->compliance_status] ?? ['class' => 'badge-secondary', 'icon' => 'bi-circle', 'label' => ucfirst($vehicle->compliance_status)];
                        @endphp
                        <span class="badge {{ $c['class'] }}">
                            <i class="bi {{ $c['icon'] }}"></i> {{ $c['label'] }}
                        </span>
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
</div>

<div class="mt-4">{{ $vehicles->links() }}</div>

@else

<div class="card animate-up">
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-car-front"></i></div>
        <h5>No Vehicles Registered</h5>
        <p>No vehicles have been registered in the system yet.</p>
    </div>
</div>

@endif

@endsection
