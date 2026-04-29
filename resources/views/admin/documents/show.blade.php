@extends('layouts.app')
@section('page_title', 'Document Review')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-file-earmark-check"></i> Document Review</h1>
        <p class="page-subtitle">{{ $document->getDocumentTypeLabel() }} &mdash; {{ $document->vehicle->plate_number }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-secondary">
            <i class="bi bi-download"></i> Download File
        </a>
        <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4 animate-up">

    {{-- Left: Document details + feedback --}}
    <div class="col-lg-7">

        {{-- Document Info --}}
        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-file-earmark-text-fill"></i> Document Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Document Type</div>
                        <div style="font-weight:600;margin-top:0.25rem;">{{ $document->getDocumentTypeLabel() }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Status</div>
                        <div style="margin-top:0.3rem;">
                            @php
                                $statusMap = [
                                    'approved' => ['class' => 'badge-success', 'icon' => 'bi-patch-check-fill'],
                                    'pending'  => ['class' => 'badge-warning', 'icon' => 'bi-hourglass-split'],
                                    'rejected' => ['class' => 'badge-danger',  'icon' => 'bi-x-circle-fill'],
                                    'expired'  => ['class' => 'badge-info',    'icon' => 'bi-calendar-x'],
                                ];
                                $s = $statusMap[$document->status] ?? ['class' => 'badge-secondary', 'icon' => 'bi-circle'];
                            @endphp
                            <span class="badge {{ $s['class'] }}">
                                <i class="bi {{ $s['icon'] }}"></i> {{ ucfirst($document->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Issue Date</div>
                        <div style="font-weight:600;margin-top:0.25rem;">{{ $document->issue_date->format('F j, Y') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Expiry Date</div>
                        <div style="font-weight:600;margin-top:0.25rem;{{ $document->isExpired() ? 'color:var(--rose)' : ($document->isExpiringSoon() ? 'color:var(--amber)' : '') }}">
                            {{ $document->expiry_date->format('F j, Y') }}
                            @if ($document->isExpired())
                                <span class="badge badge-danger" style="font-size:0.65rem;">Expired</span>
                            @elseif ($document->isExpiringSoon())
                                <span class="badge badge-warning" style="font-size:0.65rem;">{{ $document->daysUntilExpiry() }} days left</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">File</div>
                        <div style="margin-top:0.35rem;display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                            <div style="display:flex;align-items:center;gap:0.4rem;">
                                <i class="bi bi-file-earmark-pdf" style="color:var(--rose);font-size:1.15rem;"></i>
                                <span style="font-size:0.88rem;">{{ $document->original_filename }}</span>
                            </div>
                            <a href="{{ route('admin.documents.download', $document) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="font-size:0.75rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Submitted</div>
                        <div style="margin-top:0.25rem;color:var(--text-muted);font-size:0.88rem;">
                            {{ $document->created_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Feedback / Review History --}}
        @if ($document->admin_feedback || $document->approver)
        <div class="chart-card">
            <div class="card-header"><i class="bi bi-chat-text-fill"></i> Admin Feedback</div>
            <div class="card-body">
                @if ($document->admin_feedback)
                <div style="background:var(--body-bg);border-radius:var(--radius-md);padding:1rem 1.1rem;border-left:3px solid {{ $document->status === 'approved' ? 'var(--green)' : 'var(--rose)' }};margin-bottom:0.85rem;font-size:0.9rem;line-height:1.6;">
                    {{ $document->admin_feedback }}
                </div>
                @endif
                @if ($document->approver)
                <div style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <i class="bi bi-person-fill"></i>
                    Reviewed by <strong style="color:var(--text-body);margin:0 0.2rem;">{{ $document->approver->name }}</strong>
                    @if ($document->approved_at)
                        &mdash; {{ $document->approved_at->format('F j, Y \a\t g:i A') }}
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- Right: Vehicle, Owner, Review Actions --}}
    <div class="col-lg-5">

        {{-- Vehicle --}}
        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-car-front-fill"></i> Vehicle</div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                    <div style="width:48px;height:48px;border-radius:12px;background:var(--red-glass);display:flex;align-items:center;justify-content:center;color:var(--red);font-size:1.5rem;flex-shrink:0;">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:800;font-size:1.1rem;color:var(--text-head);">{{ $document->vehicle->plate_number }}</div>
                        <div style="font-size:0.85rem;color:var(--text-muted);">{{ $document->vehicle->brand_model }}</div>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div style="font-size:0.72rem;color:var(--text-muted);">Type</div>
                        <div style="font-weight:600;font-size:0.9rem;">{{ ucfirst($document->vehicle->vehicle_type) }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:0.72rem;color:var(--text-muted);">Year</div>
                        <div style="font-weight:600;font-size:0.9rem;">{{ $document->vehicle->year_of_manufacture }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.vehicles.show', $document->vehicle) }}" class="btn btn-sm btn-secondary w-100">
                    <i class="bi bi-arrow-right"></i> View Vehicle
                </a>
            </div>
        </div>

        {{-- Owner --}}
        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-person-fill"></i> Owner</div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:0.9rem;margin-bottom:1rem;">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--blue-bg);display:flex;align-items:center;justify-content:center;color:var(--blue);font-weight:800;font-size:1rem;flex-shrink:0;">
                        {{ $document->vehicle->owner->initials() }}
                    </div>
                    <div>
                        <div style="font-weight:700;color:var(--text-head);">{{ $document->vehicle->owner->name }}</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);">{{ $document->vehicle->owner->email }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $document->vehicle->owner) }}" class="btn btn-sm btn-secondary w-100">
                    <i class="bi bi-arrow-right"></i> View User
                </a>
            </div>
        </div>

        {{-- Review Actions (pending only) --}}
        @if ($document->status === 'pending')
        <div class="chart-card">
            <div class="card-header gradient"><i class="bi bi-pencil-square"></i> Review Decision</div>
            <div class="card-body">

                {{-- Approve --}}
                <form action="{{ route('admin.documents.approve', $document) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label" style="font-size:0.82rem;font-weight:700;">Approval Note <span style="font-weight:400;color:var(--text-muted);">(optional)</span></label>
                        <textarea name="admin_feedback" class="form-control" rows="3" placeholder="Optional note for the vehicle owner...">{{ old('admin_feedback') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this document?')">
                        <i class="bi bi-patch-check-fill"></i> Approve Document
                    </button>
                </form>

                <div style="position:relative;text-align:center;margin:0.75rem 0;">
                    <hr style="margin:0;">
                    <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:var(--card-bg);padding:0 0.5rem;font-size:0.75rem;color:var(--text-muted);">OR</span>
                </div>

                {{-- Reject --}}
                <form action="{{ route('admin.documents.reject', $document) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label" style="font-size:0.82rem;font-weight:700;">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="admin_feedback" class="form-control" rows="3" placeholder="Explain why this document is being rejected..." required>{{ old('admin_feedback') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this document?')">
                        <i class="bi bi-x-circle-fill"></i> Reject Document
                    </button>
                </form>

            </div>
        </div>
        @endif

    </div>

</div>

@endsection
