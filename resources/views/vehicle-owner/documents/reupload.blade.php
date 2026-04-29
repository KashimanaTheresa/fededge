@extends('layouts.app')
@section('page_title', 'Re-upload Document')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-cloud-upload-fill"></i> Re-upload Document</h1>
        <p class="page-subtitle">{{ $document->getDocumentTypeLabel() }} &mdash; {{ $document->vehicle->plate_number }}</p>
    </div>
    <a href="{{ route('document.show', $document) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row justify-content-center animate-up">
    <div class="col-lg-7">

        {{-- Rejection Reason --}}
        @if ($document->admin_feedback)
        <div class="notice-banner red mb-4" style="border-radius:var(--radius-md);">
            <i class="bi bi-x-circle-fill"></i>
            <div>
                <strong style="display:block;margin-bottom:0.2rem;">Rejection Reason</strong>
                <span style="font-size:0.88rem;">{{ $document->admin_feedback }}</span>
            </div>
        </div>
        @endif

        <div class="chart-card">
            <div class="card-header gradient"><i class="bi bi-cloud-upload-fill"></i> Upload New File</div>
            <div class="card-body">

                <form action="{{ route('document.storeReupload', $document) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="file">New Document File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                               id="file" name="file"
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text">Accepted formats: PDF, JPG, PNG. Maximum size: 5 MB.</div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold" for="issue_date">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('issue_date') is-invalid @enderror"
                                   id="issue_date" name="issue_date"
                                   value="{{ old('issue_date', $document->issue_date->format('Y-m-d')) }}" required>
                            @error('issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-bold" for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                   id="expiry_date" name="expiry_date"
                                   value="{{ old('expiry_date', $document->expiry_date->format('Y-m-d')) }}" required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload-fill"></i> Submit for Review
                        </button>
                        <a href="{{ route('document.show', $document) }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
