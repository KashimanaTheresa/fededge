@extends('layouts.app')
@section('page_title', $vehicle->plate_number)

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-car-front-fill"></i> {{ $vehicle->plate_number }}</h1>
        <p class="page-subtitle">{{ $vehicle->brand_model }} &mdash; {{ $vehicle->year_of_manufacture }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('document.create', $vehicle) }}" class="btn btn-primary">
            <i class="bi bi-upload"></i> Upload Document
        </a>
        <a href="{{ route('vehicle.edit', $vehicle) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4 animate-up">

    {{-- Vehicle Info + Documents --}}
    <div class="col-lg-8">

        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-car-front-fill"></i> Vehicle Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Plate Number</div>
                        <div style="font-weight:800;font-size:1.15rem;color:var(--text-head);margin-top:0.2rem;">{{ $vehicle->plate_number }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Brand / Model</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->brand_model }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Year</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->year_of_manufacture }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Type</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ ucfirst($vehicle->vehicle_type) }}</div>
                    </div>
                    @if ($vehicle->color)
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Color</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->color }}</div>
                    </div>
                    @endif
                    @if ($vehicle->engine_capacity)
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Engine Capacity</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $vehicle->engine_capacity }} cc</div>
                    </div>
                    @endif
                    @if ($vehicle->vin)
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">VIN</div>
                        <div style="font-weight:600;font-family:monospace;font-size:0.85rem;margin-top:0.2rem;">{{ $vehicle->vin }}</div>
                    </div>
                    @endif
                    @if ($vehicle->engine_number)
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Engine Number</div>
                        <div style="font-weight:600;font-family:monospace;font-size:0.85rem;margin-top:0.2rem;">{{ $vehicle->engine_number }}</div>
                    </div>
                    @endif
                    <div class="col-sm-6 col-md-4">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Status</div>
                        <div style="margin-top:0.3rem;">
                            @if ($vehicle->status === 'active')
                                <span class="badge badge-success"><i class="bi bi-circle-fill" style="font-size:0.5rem;"></i> Active</span>
                            @elseif ($vehicle->status === 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst($vehicle->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents --}}
        <div class="table-card">
            <div class="table-header">
                <span class="table-title"><i class="bi bi-file-earmark-text"></i> Documents</span>
                <a href="{{ route('document.create', $vehicle) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-upload"></i> Upload
                </a>
            </div>
            @if ($vehicle->documents->isNotEmpty())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
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
                                    <span style="font-size:0.88rem;font-weight:600;">{{ $doc->getDocumentTypeLabel() }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($doc->isExpired())
                                    <span style="color:var(--rose);font-size:0.85rem;font-weight:600;">{{ $doc->expiry_date->format('M d, Y') }}</span>
                                    <span class="badge badge-danger" style="font-size:0.65rem;">Expired</span>
                                @elseif ($doc->isExpiringSoon())
                                    <span style="color:var(--amber);font-size:0.85rem;font-weight:600;">{{ $doc->expiry_date->format('M d, Y') }}</span>
                                    <span class="badge badge-warning" style="font-size:0.65rem;">{{ $doc->daysUntilExpiry() }}d left</span>
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
                                <div class="d-flex gap-1">
                                    <a href="{{ route('document.show', $doc) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('document.download', $doc) }}" class="btn btn-sm btn-success" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    @if ($doc->status === 'rejected')
                                        <a href="{{ route('document.reupload', $doc) }}" class="btn btn-sm btn-warning" title="Re-upload">
                                            <i class="bi bi-cloud-upload"></i>
                                        </a>
                                    @endif
                                </div>
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
                <p>Upload compliance documents to keep this vehicle verified.</p>
                <a href="{{ route('document.create', $vehicle) }}" class="btn btn-primary btn-sm mt-1">
                    <i class="bi bi-upload"></i> Upload Document
                </a>
            </div>
            @endif
        </div>

    </div>

    {{-- Compliance Summary --}}
    <div class="col-lg-4">
        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-patch-check-fill"></i> Compliance</div>
            <div class="card-body">
                @php
                    $compMap = [
                        'compliant'         => ['class' => 'badge-success', 'icon' => 'bi-patch-check-fill',     'label' => 'Compliant',       'color' => 'var(--green)'],
                        'expired'           => ['class' => 'badge-danger',  'icon' => 'bi-calendar-x',           'label' => 'Expired Docs',    'color' => 'var(--rose)'],
                        'pending'           => ['class' => 'badge-warning', 'icon' => 'bi-hourglass-split',      'label' => 'Pending Review',  'color' => 'var(--amber)'],
                        'missing_documents' => ['class' => 'badge-info',    'icon' => 'bi-file-x',               'label' => 'Missing Docs',    'color' => 'var(--blue)'],
                    ];
                    $c = $compMap[$vehicle->compliance_status] ?? ['class' => 'badge-secondary', 'icon' => 'bi-circle', 'label' => ucfirst($vehicle->compliance_status), 'color' => 'var(--text-muted)'];
                @endphp
                <div style="text-align:center;padding:0.5rem 0 1rem;">
                    <div style="width:64px;height:64px;border-radius:16px;background:{{ $c['color'] }}22;display:flex;align-items:center;justify-content:center;font-size:1.75rem;color:{{ $c['color'] }};margin:0 auto 0.75rem;">
                        <i class="bi {{ $c['icon'] }}"></i>
                    </div>
                    <span class="badge {{ $c['class'] }}" style="font-size:0.82rem;padding:0.4rem 0.8rem;">{{ $c['label'] }}</span>
                </div>

                @if ($vehicle->days_until_expiry !== null)
                <div style="border-top:1px solid var(--border);padding-top:0.85rem;font-size:0.85rem;color:var(--text-muted);text-align:center;">
                    <i class="bi bi-clock"></i>
                    Next expiry in <strong style="color:var(--text-head);">{{ $vehicle->days_until_expiry }} day{{ $vehicle->days_until_expiry === 1 ? '' : 's' }}</strong>
                </div>
                @endif

                <a href="{{ route('vehicle.compliance', $vehicle) }}" class="btn btn-sm btn-secondary w-100 mt-3">
                    <i class="bi bi-check2-circle"></i> Full Compliance Report
                </a>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="chart-card">
            <div class="card-header" style="color:var(--rose);border-bottom-color:var(--rose-bg);"><i class="bi bi-exclamation-triangle-fill" style="color:var(--rose);"></i> Danger Zone</div>
            <div class="card-body">
                <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:0.75rem;">Permanently delete this vehicle and all its documents. This cannot be undone.</p>
                <form action="{{ route('vehicle.destroy', $vehicle) }}" method="POST"
                      onsubmit="return confirm('Delete {{ $vehicle->plate_number }} and ALL its documents? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="bi bi-trash3-fill"></i> Delete Vehicle
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
