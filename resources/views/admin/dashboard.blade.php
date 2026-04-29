@extends('layouts.app')
@section('page_title', 'Admin Dashboard')

@section('content')

{{-- Page Header --}}
<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>
        <p class="page-subtitle">System overview &amp; compliance metrics — {{ now()->format('l, F j Y') }}</p>
    </div>
    <a href="{{ route('admin.documents.index') }}" class="btn btn-primary">
        <i class="bi bi-file-earmark-check"></i> Review Documents
    </a>
</div>

{{-- ── ROW 1: Stat Cards ──────────────────────────────── --}}
<div class="stat-cards-grid animate-up">
    <div class="stat-card red stagger-1">
        <div class="stat-icon"><i class="bi bi-car-front-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value" data-count="{{ $totalVehicles }}">{{ $totalVehicles }}</div>
            <div class="stat-label">Total Vehicles</div>
        </div>
    </div>
    <div class="stat-card blue stagger-2">
        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value" data-count="{{ $totalUsers }}">{{ $totalUsers }}</div>
            <div class="stat-label">Registered Users</div>
        </div>
    </div>
    <div class="stat-card amber stagger-3">
        <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-info">
            <div class="stat-value" data-count="{{ $pendingDocuments }}">{{ $pendingDocuments }}</div>
            <div class="stat-label">Pending Review</div>
        </div>
    </div>
    <div class="stat-card green stagger-4">
        <div class="stat-icon"><i class="bi bi-patch-check-fill"></i></div>
        <div class="stat-info">
            <div class="stat-value" data-count="{{ $compliantVehicles }}">{{ $compliantVehicles }}</div>
            <div class="stat-label">Compliant Vehicles</div>
        </div>
    </div>
</div>

{{-- ── ROW 2: Analytics ───────────────────────────────── --}}
<div class="row g-4 mb-4 animate-up">

    {{-- Document Status card — doughnut + 4 mini counters inside --}}
    <div class="col-lg-5">
        <div class="chart-card h-100">
            <div class="card-header"><i class="bi bi-pie-chart-fill"></i> Document Status Overview</div>
            <div class="card-body pb-2" style="flex:1;display:flex;align-items:center;justify-content:center;">
                <div style="max-width:240px;width:100%;">
                    <canvas id="documentStatusChart"></canvas>
                </div>
            </div>
            {{-- Inline mini-stats --}}
            <div class="doc-stat-row">
                <div class="doc-stat green">
                    <span class="doc-stat-value">{{ $approvedDocuments }}</span>
                    <span class="doc-stat-label">Approved</span>
                </div>
                <div class="doc-stat amber">
                    <span class="doc-stat-value">{{ $pendingDocuments }}</span>
                    <span class="doc-stat-label">Pending</span>
                </div>
                <div class="doc-stat rose">
                    <span class="doc-stat-value">{{ $rejectedDocuments }}</span>
                    <span class="doc-stat-label">Rejected</span>
                </div>
                <div class="doc-stat blue">
                    <span class="doc-stat-value">{{ $expiredDocuments }}</span>
                    <span class="doc-stat-label">Expired</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right column: Compliance bar + Users by Role stacked --}}
    <div class="col-lg-7 d-flex flex-column gap-4">

        <div class="chart-card">
            <div class="card-header"><i class="bi bi-bar-chart-fill"></i> Vehicle Compliance</div>
            <div class="card-body" style="padding-bottom:1rem;">
                <canvas id="complianceChart" style="max-height:160px;"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="card-header"><i class="bi bi-person-lines-fill"></i> Users by Role</div>
            <div class="card-body" style="padding-bottom:1rem;">
                <canvas id="usersByRoleChart" style="max-height:160px;"></canvas>
            </div>
        </div>

    </div>
</div>

{{-- ── ROW 3: Pending + Expiring Tables ──────────────── --}}
<div class="row g-4 mb-4 animate-up">

    <div class="col-lg-6">
        <div class="table-card h-100">
            <div class="table-header">
                <span class="table-title"><i class="bi bi-hourglass-split"></i> Pending Documents</span>
                <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-secondary">View All</a>
            </div>
            @if ($recentPendingDocuments->isNotEmpty())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Type</th>
                                <th>Owner</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentPendingDocuments as $doc)
                                <tr>
                                    <td><strong>{{ $doc->vehicle->plate_number }}</strong></td>
                                    <td style="font-size:0.82rem;color:var(--text-muted);">{{ $doc->getDocumentTypeLabel() }}</td>
                                    <td style="font-size:0.82rem;">{{ $doc->vehicle->owner->name }}</td>
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
                    <div class="empty-icon" style="width:56px;height:56px;font-size:1.5rem;"><i class="bi bi-check-circle"></i></div>
                    <h5 style="font-size:0.95rem;">All clear!</h5>
                    <p>No documents awaiting review.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="table-card h-100">
            <div class="table-header">
                <span class="table-title"><i class="bi bi-calendar-event"></i> Expiring Soon</span>
                <span class="badge badge-warning">Action needed</span>
            </div>
            @if ($expiringDocuments->isNotEmpty())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Expires In</th>
                                <th>Owner</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expiringDocuments as $doc)
                                <tr>
                                    <td><strong>{{ $doc->vehicle->plate_number }}</strong></td>
                                    <td>
                                        <span class="badge badge-warning">
                                            <i class="bi bi-clock"></i> {{ $doc->daysUntilExpiry() }}d
                                        </span>
                                    </td>
                                    <td style="font-size:0.82rem;">{{ $doc->vehicle->owner->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.documents.show', $doc) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state" style="padding:2.5rem;">
                    <div class="empty-icon" style="width:56px;height:56px;font-size:1.5rem;background:var(--green-bg);color:var(--green);"><i class="bi bi-calendar-check"></i></div>
                    <h5 style="font-size:0.95rem;">No expiring documents</h5>
                    <p>Everything is within validity window.</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- ── ROW 4: Quick Actions ───────────────────────────── --}}
<div class="row g-3 animate-up">
    <div class="col-md-4">
        <a href="{{ route('admin.vehicles.index') }}" class="action-card">
            <div class="action-card-icon"><i class="bi bi-car-front-fill"></i></div>
            <div class="action-card-body">
                <h6>All Vehicles</h6>
                <p>Browse and manage every registered vehicle.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.users.index') }}" class="action-card">
            <div class="action-card-icon blue"><i class="bi bi-people-fill"></i></div>
            <div class="action-card-body">
                <h6>Manage Users</h6>
                <p>View, edit, and assign roles to user accounts.</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('admin.documents.index') }}" class="action-card">
            <div class="action-card-icon amber"><i class="bi bi-file-earmark-check-fill"></i></div>
            <div class="action-card-body">
                <h6>Review Documents</h6>
                <p>Approve or reject vehicle compliance documents.</p>
            </div>
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const gridColor = dark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)';
    const labelColor = dark ? '#94a3b8' : '#64748b';

    // Document Status doughnut
    const docCtx = document.getElementById('documentStatusChart')?.getContext('2d');
    if (docCtx) {
        new Chart(docCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected', 'Expired'],
                datasets: [{
                    data: [{{ $approvedDocuments }}, {{ $pendingDocuments }}, {{ $rejectedDocuments }}, {{ $expiredDocuments }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6'],
                    borderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                cutout: '72%',
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: labelColor, padding: 16, boxWidth: 10, borderRadius: 4, useBorderRadius: true }
                    }
                }
            }
        });
    }

    // Compliance horizontal bar
    const compCtx = document.getElementById('complianceChart')?.getContext('2d');
    if (compCtx) {
        new Chart(compCtx, {
            type: 'bar',
            data: {
                labels: ['Compliant', 'Non-Compliant'],
                datasets: [{
                    label: 'Vehicles',
                    data: [{{ $compliantVehicles }}, {{ $nonCompliantVehicles }}],
                    backgroundColor: ['rgba(16,185,129,0.85)', 'rgba(239,68,68,0.80)'],
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 36,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: labelColor } },
                    y: { grid: { display: false }, ticks: { color: labelColor, font: { weight: '600' } } }
                }
            }
        });
    }

    // Users by Role horizontal bar
    const roleCtx = document.getElementById('usersByRoleChart')?.getContext('2d');
    if (roleCtx) {
        new Chart(roleCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($usersByRole)),
                datasets: [{
                    label: 'Users',
                    data: @json(array_values($usersByRole)),
                    backgroundColor: ['#d32f2f', '#3b82f6', '#f59e0b'],
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 36,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: labelColor } },
                    y: { grid: { display: false }, ticks: { color: labelColor, font: { weight: '600' } } }
                }
            }
        });
    }
})();
</script>
@endpush
