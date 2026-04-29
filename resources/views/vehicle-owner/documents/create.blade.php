@extends('layouts.app')
@section('page_title', 'Upload Document')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-upload"></i> Upload Document</h1>
        <p class="page-subtitle">{{ $vehicle->plate_number }} &mdash; {{ $vehicle->brand_model }}</p>
    </div>
    <a href="{{ route('vehicle.show', $vehicle) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Vehicle
    </a>
</div>

<div class="row justify-content-center animate-up">
    <div class="col-lg-7">
        <div class="chart-card">
            <div class="card-header gradient"><i class="bi bi-file-earmark-plus-fill"></i> Document Details</div>
            <div class="card-body">

                <form action="{{ route('document.store', $vehicle) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="document_type">Document Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('document_type') is-invalid @enderror"
                                id="document_type" name="document_type" required>
                            <option value="" disabled {{ old('document_type') ? '' : 'selected' }}>Select document type…</option>
                            @foreach ($documentTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('document_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="file">Document File <span class="text-danger">*</span></label>
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
                                   value="{{ old('issue_date') }}" required>
                            @error('issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-bold" for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
                                   id="expiry_date" name="expiry_date"
                                   value="{{ old('expiry_date') }}" required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Info note --}}
                    <div class="notice-banner amber mb-4" style="border-radius:var(--radius-md);padding:0.85rem 1rem;">
                        <i class="bi bi-info-circle-fill"></i>
                        <span style="font-size:0.85rem;">Uploaded documents are reviewed by an admin. You'll be notified once approved or rejected.</span>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-upload-fill"></i> Upload Document
                        </button>
                        <a href="{{ route('vehicle.show', $vehicle) }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
