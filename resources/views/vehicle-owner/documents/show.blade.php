@extends('layouts.app')
@section('page_title', 'Document Details')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-file-earmark-text-fill"></i> {{ $document->getDocumentTypeLabel() }}</h1>
        <p class="page-subtitle">{{ $document->vehicle->plate_number }} &mdash; {{ $document->vehicle->brand_model }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('document.download', $document) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Download
        </a>
        <a href="{{ route('document.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-4 animate-up">

    {{-- Document Details --}}
    <div class="col-lg-7">
        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-file-earmark-text-fill"></i> Document Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Document Type</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $document->getDocumentTypeLabel() }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Status</div>
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
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Issue Date</div>
                        <div style="font-weight:600;margin-top:0.2rem;">{{ $document->issue_date->format('F j, Y') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Expiry Date</div>
                        <div style="font-weight:600;margin-top:0.2rem;{{ $document->isExpired() ? 'color:var(--rose)' : ($document->isExpiringSoon() ? 'color:var(--amber)' : '') }}">
                            {{ $document->expiry_date->format('F j, Y') }}
                            @if ($document->isExpired())
                                <span class="badge badge-danger" style="font-size:0.65rem;">Expired</span>
                            @elseif ($document->isExpiringSoon())
                                <span class="badge badge-warning" style="font-size:0.65rem;">{{ $document->daysUntilExpiry() }} days left</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">File</div>
                        <div style="margin-top:0.35rem;display:flex;align-items:center;gap:0.75rem;">
                            <i class="bi bi-file-earmark-pdf" style="color:var(--rose);font-size:1.15rem;"></i>
                            <span style="font-size:0.88rem;">{{ $document->original_filename }}</span>
                            <a href="{{ route('document.download', $document) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                    <div class="col-12">
                        <div style="font-size:0.72rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Submitted</div>
                        <div style="margin-top:0.2rem;color:var(--text-muted);font-size:0.88rem;">
                            {{ $document->created_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin Feedback --}}
        @if ($document->admin_feedback)
        <div class="chart-card">
            <div class="card-header"><i class="bi bi-chat-text-fill"></i> Admin Feedback</div>
            <div class="card-body">
                <div style="background:var(--body-bg);border-radius:var(--radius-md);padding:1rem 1.1rem;border-left:3px solid {{ $document->status === 'approved' ? 'var(--green)' : 'var(--rose)' }};font-size:0.9rem;line-height:1.6;margin-bottom:0.75rem;">
                    {{ $document->admin_feedback }}
                </div>
                @if ($document->approver)
                <div style="font-size:0.82rem;color:var(--text-muted);">
                    <i class="bi bi-person-fill"></i>
                    Reviewed by <strong style="color:var(--text-body);">{{ $document->approver->name }}</strong>
                    @if ($document->approved_at)
                        &mdash; {{ $document->approved_at->format('F j, Y') }}
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Actions sidebar --}}
    <div class="col-lg-5">

        {{-- Vehicle card --}}
        <div class="chart-card mb-4">
            <div class="card-header"><i class="bi bi-car-front-fill"></i> Vehicle</div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                    <div style="width:44px;height:44px;border-radius:10px;background:var(--red-glass);display:flex;align-items:center;justify-content:center;color:var(--red);font-size:1.3rem;flex-shrink:0;">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <div>
                        <div style="font-weight:800;color:var(--text-head);">{{ $document->vehicle->plate_number }}</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);">{{ $document->vehicle->brand_model }}</div>
                    </div>
                </div>
                <a href="{{ route('vehicle.show', $document->vehicle) }}" class="btn btn-sm btn-secondary w-100">
                    <i class="bi bi-arrow-right"></i> View Vehicle
                </a>
            </div>
        </div>

        {{-- Actions --}}
        <div class="chart-card">
            <div class="card-header"><i class="bi bi-gear-fill"></i> Actions</div>
            <div class="card-body d-flex flex-column gap-2">

                @if ($document->status === 'rejected')
                <a href="{{ route('document.reupload', $document) }}" class="btn btn-warning w-100">
                    <i class="bi bi-cloud-upload-fill"></i> Re-upload Document
                </a>
                @endif

                @if (in_array($document->status, ['pending', 'rejected']))
                <form action="{{ route('document.destroy', $document) }}" method="POST"
                      onsubmit="return confirm('Delete this document permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash3-fill"></i> Delete Document
                    </button>
                </form>
                @endif

                @if ($document->status === 'pending')
                <div class="notice-banner amber" style="border-radius:var(--radius-md);padding:0.75rem 1rem;margin:0;">
                    <i class="bi bi-hourglass-split"></i>
                    <span style="font-size:0.82rem;">Awaiting admin review.</span>
                </div>
                @endif

                @if ($document->status === 'approved')
                <div class="notice-banner" style="border-radius:var(--radius-md);padding:0.75rem 1rem;margin:0;background:var(--green-bg);border:1px solid rgba(16,185,129,0.25);">
                    <i class="bi bi-patch-check-fill" style="color:var(--green);"></i>
                    <span style="font-size:0.82rem;color:var(--green);">This document has been approved.</span>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

@endsection
