@extends('layouts.app')
@section('page_title', 'Vehicle Details')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-car-front-fill"></i> {{ $vehicle->plate_number }}</h1>
        <p class="page-subtitle">{{ $vehicle->brand_model }} &mdash; {{ $vehicle->year_of_manufacture }}</p>
    </div>
    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Vehicles
    </a>
</div>

<div class="row g-4 animate-up">

    {{-- Vehicle Details + Documents --}}
    <div class="col-lg-8">

        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-car-front-fill"></i> Vehicle Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Plate Number</div>
                        <div style="font-weight:800;font-size:1.15rem;color:var(--text-head);margin-top:0.2rem;">{{ $vehicle->plate_number }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Brand / Model</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->brand_model }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Year</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->year_of_manufacture }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Type</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ ucfirst($vehicle->vehicle_type) }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Color</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->color }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Engine Capacity</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->engine_capacity ?? '—' }}</div>
                    </div>
                    @if ($vehicle->vin)
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">VIN</div>
                        <div style="font-weight:600;font-family:monospace;margin-top:0.2rem;">{{ $vehicle->vin }}</div>
                    </div>
                    @endif
                    @if ($vehicle->engine_number)
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Engine Number</div>
                        <div style="font-weight:600;font-family:monospace;margin-top:0.2rem;">{{ $vehicle->engine_number }}</div>
                    </div>
                    @endif
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Status</div>
                        <div style="margin-top:0.3rem;">
                            @if ($vehicle->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif ($vehicle->status === 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($vehicle->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Compliance</div>
                        <div style="margin-top:0.3rem;">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="table-card">
            <div class="table-header">
                <span class="table-title"><i class="bi bi-file-earmark-text"></i> Documents</span>
                <span style="font-size:0.8rem;color:var(--text-muted);">{{ $vehicle->documents->count() }} total</span>
            </div>
            @if ($vehicle->documents->isNotEmpty())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Issue Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicle->documents as $doc)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.5rem;">
                                    <i class="bi bi-file-earmark-pdf" style="color:var(--rose);"></i>
                                    <span style="font-size:0.88rem;">{{ $doc->getDocumentTypeLabel() }}</span>
                                </div>
                            </td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">{{ $doc->issue_date->format('M d, Y') }}</td>
                            <td>
                                @if ($doc->isExpired())
                                    <span style="color:var(--rose);font-size:0.85rem;font-weight:600;">{{ $doc->expiry_date->format('M d, Y') }}</span>
                                    <span class="badge badge-danger" style="font-size:0.65rem;">Expired</span>
                                @elseif ($doc->isExpiringSoon())
                                    <span style="color:var(--amber);font-size:0.85rem;font-weight:600;">{{ $doc->expiry_date->format('M d, Y') }}</span>
                                    <span class="badge badge-warning" style="font-size:0.65rem;">{{ $doc->daysUntilExpiry() }}d</span>
                                @else
                                    <span style="font-size:0.85rem;color:var(--text-muted);">{{ $doc->expiry_date->format('M d, Y') }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $smap = [
                                        'approved' => ['class' => 'badge-success', 'icon' => 'bi-patch-check-fill'],
                                        'pending'  => ['class' => 'badge-warning', 'icon' => 'bi-hourglass-split'],
                                        'rejected' => ['class' => 'badge-danger',  'icon' => 'bi-x-circle-fill'],
                                        'expired'  => ['class' => 'badge-info',    'icon' => 'bi-calendar-x'],
                                    ];
                                    $s = $smap[$doc->status] ?? ['class' => 'badge-secondary', 'icon' => 'bi-circle'];
                                @endphp
                                <span class="badge {{ $s['class'] }}">
                                    <i class="bi {{ $s['icon'] }}"></i> {{ ucfirst($doc->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.documents.show', $doc) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Review
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state" style="padding:2.5rem;">
                <div class="empty-icon" style="width:48px;height:48px;font-size:1.3rem;"><i class="bi bi-file-earmark-text"></i></div>
                <h5 style="font-size:0.95rem;">No documents uploaded</h5>
                <p>This vehicle has no documents yet.</p>
            </div>
            @endif
        </div>

    </div>

    {{-- Owner Info --}}
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="card-header"><i class="bi bi-person-fill"></i> Owner</div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
                    <div style="width:48px;height:48px;border-radius:12px;background:var(--blue-bg);display:flex;align-items:center;justify-content:center;color:var(--blue);font-weight:800;font-size:1.1rem;flex-shrink:0;">
                        {{ $vehicle->owner->initials() }}
                    </div>
                    <div>
                        <div style="font-weight:800;color:var(--text-head);">{{ $vehicle->owner->name }}</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);">{{ $vehicle->owner->email }}</div>
                    </div>
                </div>
                @if ($vehicle->owner->phone)
                <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.6rem;font-size:0.88rem;">
                    <i class="bi bi-phone" style="color:var(--text-muted);"></i>
                    <span>{{ $vehicle->owner->phone }}</span>
                </div>
                @endif
                @if ($vehicle->owner->address)
                <div style="display:flex;align-items:flex-start;gap:0.6rem;margin-bottom:0.6rem;font-size:0.88rem;">
                    <i class="bi bi-geo-alt" style="color:var(--text-muted);margin-top:2px;"></i>
                    <span>{{ $vehicle->owner->address }}</span>
                </div>
                @endif
                <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1.25rem;font-size:0.88rem;">
                    <i class="bi bi-person-badge" style="color:var(--text-muted);"></i>
                    <span class="badge badge-success">{{ ucwords(str_replace('_', ' ', $vehicle->owner->role)) }}</span>
                </div>
                <a href="{{ route('admin.users.show', $vehicle->owner) }}" class="btn btn-sm btn-secondary w-100">
                    <i class="bi bi-arrow-right"></i> View User Profile
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
