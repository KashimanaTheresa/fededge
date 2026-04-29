@extends('layouts.app')
@section('page_title', 'All Documents')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-file-earmark-text-fill"></i> Documents</h1>
        <p class="page-subtitle">Review and manage all vehicle compliance documents</p>
    </div>
</div>

{{-- Status filter --}}
<div class="d-flex gap-2 mb-4 flex-wrap animate-up">
    <a href="{{ route('admin.documents.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-secondary' }}">
        All
    </a>
    <a href="{{ route('admin.documents.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-secondary' }}">
        <i class="bi bi-hourglass-split"></i> Pending
    </a>
    <a href="{{ route('admin.documents.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-secondary' }}">
        <i class="bi bi-patch-check"></i> Approved
    </a>
    <a href="{{ route('admin.documents.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-secondary' }}">
        <i class="bi bi-x-circle"></i> Rejected
    </a>
    <a href="{{ route('admin.documents.index', ['status' => 'expired']) }}" class="btn btn-sm {{ request('status') === 'expired' ? 'btn-info' : 'btn-secondary' }}">
        <i class="bi bi-calendar-x"></i> Expired
    </a>
</div>

@if ($documents->isNotEmpty())

<div class="table-card animate-up">
    <div class="table-header">
        <span class="table-title"><i class="bi bi-file-earmark-text"></i> Documents</span>
        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $documents->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Document Type</th>
                    <th>Owner</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $doc)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <div style="width:32px;height:32px;border-radius:7px;background:var(--red-glass);display:flex;align-items:center;justify-content:center;color:var(--red);font-size:0.95rem;flex-shrink:0;">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <div>
                                <strong>{{ $doc->vehicle->plate_number }}</strong>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $doc->vehicle->brand_model }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <i class="bi bi-file-earmark-pdf" style="color:var(--rose);"></i>
                            <span style="font-size:0.88rem;">{{ $doc->getDocumentTypeLabel() }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:0.85rem;">{{ $doc->vehicle->owner->name }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $doc->vehicle->owner->email }}</div>
                    </td>
                    <td>
                        @if ($doc->isExpired())
                            <div>
                                <span style="color:var(--rose);font-size:0.85rem;font-weight:600;">{{ $doc->expiry_date->format('M d, Y') }}</span>
                                <span class="badge badge-danger" style="font-size:0.65rem;display:block;width:fit-content;">Expired</span>
                            </div>
                        @elseif ($doc->isExpiringSoon())
                            <div>
                                <span style="color:var(--amber);font-size:0.85rem;font-weight:600;">{{ $doc->expiry_date->format('M d, Y') }}</span>
                                <span class="badge badge-warning" style="font-size:0.65rem;display:block;width:fit-content;">{{ $doc->daysUntilExpiry() }}d left</span>
                            </div>
                        @else
                            <span style="font-size:0.85rem;color:var(--text-muted);">{{ $doc->expiry_date->format('M d, Y') }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusMap = [
                                'approved' => ['class' => 'badge-success', 'icon' => 'bi-patch-check-fill'],
                                'pending'  => ['class' => 'badge-warning', 'icon' => 'bi-hourglass-split'],
                                'rejected' => ['class' => 'badge-danger',  'icon' => 'bi-x-circle-fill'],
                                'expired'  => ['class' => 'badge-info',    'icon' => 'bi-calendar-x'],
                            ];
                            $s = $statusMap[$doc->status] ?? ['class' => 'badge-secondary', 'icon' => 'bi-circle'];
                        @endphp
                        <span class="badge {{ $s['class'] }}">
                            <i class="bi {{ $s['icon'] }}"></i> {{ ucfirst($doc->status) }}
                        </span>
                    </td>
                    <td style="font-size:0.82rem;color:var(--text-muted);">{{ $doc->created_at->format('M d, Y') }}</td>
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
</div>

<div class="mt-4">{{ $documents->links() }}</div>

@else

<div class="card animate-up">
    <div class="empty-state">
        <div class="empty-icon"><i class="bi bi-file-earmark-text"></i></div>
        <h5>No Documents Found</h5>
        <p>No documents match the current filter.</p>
        @if (request('status'))
            <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary mt-2">
                <i class="bi bi-x"></i> Clear Filter
            </a>
        @endif
    </div>
</div>

@endif

@endsection
