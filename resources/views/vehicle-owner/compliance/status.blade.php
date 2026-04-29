@extends('layouts.app')
@section('page_title', 'Compliance Status')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-check2-circle"></i> Compliance Status</h1>
        <p class="page-subtitle">{{ $vehicle->plate_number }} &mdash; {{ $vehicle->brand_model }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('document.create', $vehicle) }}" class="btn btn-primary">
            <i class="bi bi-upload"></i> Upload Document
        </a>
        <a href="{{ route('vehicle.show', $vehicle) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Vehicle
        </a>
    </div>
</div>

{{-- Overall Status Banner --}}
@php
    $isCompliant = collect($documentStatus)->every(fn($d) => $d['status'] === 'approved' && !$d['is_expired']);
@endphp

@if ($isCompliant)
<div class="notice-banner mb-4 animate-up" style="background:var(--green-bg);border:1px solid rgba(16,185,129,0.3);border-radius:var(--radius-lg);">
    <i class="bi bi-patch-check-fill" style="color:var(--green);font-size:1.3rem;"></i>
    <div>
        <strong style="color:var(--green);">Fully Compliant</strong>
        <div style="font-size:0.88rem;color:var(--text-muted);margin-top:0.1rem;">All required documents are approved and valid.</div>
    </div>
</div>
@else
<div class="notice-banner amber mb-4 animate-up" style="border-radius:var(--radius-lg);">
    <i class="bi bi-exclamation-triangle-fill" style="font-size:1.3rem;"></i>
    <div>
        <strong>Action Required</strong>
        <div style="font-size:0.88rem;margin-top:0.1rem;">One or more required documents are missing, pending, expired, or rejected.</div>
    </div>
</div>
@endif

{{-- Required Documents --}}
<div class="row g-4 animate-up">
    @foreach ($documentStatus as $type => $info)
    <div class="col-md-4">
        <div class="chart-card h-100">
            <div class="card-header" style="
                @if ($info['status'] === 'approved' && !$info['is_expired'])
                    background:linear-gradient(135deg,#059669,var(--green));color:white;border:none;
                @elseif ($info['status'] === 'pending')
                    background:linear-gradient(135deg,#d97706,var(--amber));color:white;border:none;
                @elseif ($info['status'] === 'rejected' || $info['is_expired'])
                    background:linear-gradient(135deg,#dc2626,var(--rose));color:white;border:none;
                @else
                    background:var(--body-bg);
                @endif
            ">
                <i class="bi bi-file-earmark-text-fill"
                   @if(in_array($info['status'], ['approved', 'pending', 'rejected']) || $info['is_expired'])
                       style="color:rgba(255,255,255,0.8);"
                   @endif
                ></i>
                <span @if(in_array($info['status'], ['approved', 'pending', 'rejected']) || $info['is_expired']) style="color:white;" @endif>
                    {{ $info['label'] }}
                </span>
            </div>
            <div class="card-body" style="flex:1;">
                @if ($info['status'] === 'missing')
                    <div style="text-align:center;padding:1rem 0;">
                        <div style="width:52px;height:52px;border-radius:14px;background:var(--blue-bg);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--blue);margin:0 auto 0.75rem;">
                            <i class="bi bi-file-x"></i>
                        </div>
                        <div style="font-weight:700;color:var(--text-head);margin-bottom:0.25rem;">Not Uploaded</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:1rem;">This document has not been uploaded yet.</div>
                        <a href="{{ route('document.create', $vehicle) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-upload"></i> Upload Now
                        </a>
                    </div>

                @elseif ($info['is_expired'])
                    <div style="text-align:center;padding:0.75rem 0;">
                        <div style="width:52px;height:52px;border-radius:14px;background:var(--rose-bg);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--rose);margin:0 auto 0.75rem;">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <div style="font-weight:700;color:var(--rose);margin-bottom:0.25rem;">Expired</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:1rem;">
                            Expired on {{ $info['document']->expiry_date->format('M d, Y') }}
                        </div>
                        <a href="{{ route('document.create', $vehicle) }}" class="btn btn-sm btn-danger">
                            <i class="bi bi-arrow-repeat"></i> Renew
                        </a>
                    </div>

                @elseif ($info['status'] === 'approved')
                    @php $doc = $info['document']; @endphp
                    <div style="padding:0.25rem 0;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:40px;height:40px;border-radius:10px;background:var(--green-bg);display:flex;align-items:center;justify-content:center;font-size:1.15rem;color:var(--green);flex-shrink:0;">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;color:var(--green);">Approved</div>
                                <div style="font-size:0.78rem;color:var(--text-muted);">
                                    @if ($info['days_until_expiry'] !== null)
                                        Expires in {{ $info['days_until_expiry'] }} day{{ $info['days_until_expiry'] === 1 ? '' : 's' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:0.3rem;">
                            Issued: <strong>{{ $doc->issue_date->format('M d, Y') }}</strong>
                        </div>
                        <div style="font-size:0.82rem;color:var(--text-muted);">
                            Expires: <strong style="{{ $info['days_until_expiry'] !== null && $info['days_until_expiry'] <= 30 ? 'color:var(--amber)' : '' }}">
                                {{ $doc->expiry_date->format('M d, Y') }}
                            </strong>
                        </div>
                        <a href="{{ route('document.show', $doc) }}" class="btn btn-sm btn-secondary w-100 mt-3">
                            <i class="bi bi-eye"></i> View Document
                        </a>
                    </div>

                @elseif ($info['status'] === 'pending')
                    @php $doc = $info['document']; @endphp
                    <div style="text-align:center;padding:0.75rem 0;">
                        <div style="width:52px;height:52px;border-radius:14px;background:var(--amber-bg);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--amber);margin:0 auto 0.75rem;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div style="font-weight:700;color:var(--amber);margin-bottom:0.25rem;">Pending Review</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:1rem;">Awaiting admin approval.</div>
                        <a href="{{ route('document.show', $doc) }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>

                @elseif ($info['status'] === 'rejected')
                    @php $doc = $info['document']; @endphp
                    <div style="text-align:center;padding:0.75rem 0;">
                        <div style="width:52px;height:52px;border-radius:14px;background:var(--rose-bg);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--rose);margin:0 auto 0.75rem;">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div style="font-weight:700;color:var(--rose);margin-bottom:0.25rem;">Rejected</div>
                        @if ($doc->admin_feedback)
                        <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:1rem;padding:0 0.5rem;">
                            "{{ Str::limit($doc->admin_feedback, 80) }}"
                        </div>
                        @else
                        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:1rem;">Please re-upload a corrected document.</div>
                        @endif
                        <a href="{{ route('document.reupload', $doc) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-cloud-upload"></i> Re-upload
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
