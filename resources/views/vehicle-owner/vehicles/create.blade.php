@extends('layouts.app')
@section('page_title', 'Register Vehicle')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-plus-circle-fill"></i> Register Vehicle</h1>
        <p class="page-subtitle">Add a new vehicle to your account</p>
    </div>
    <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row justify-content-center animate-up">
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="card-header gradient"><i class="bi bi-car-front-fill"></i> Vehicle Details</div>
            <div class="card-body">

                <form action="{{ route('vehicle.store') }}" method="POST">
                    @csrf

                    {{-- Required fields --}}
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold" for="plate_number">Plate Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror"
                                   id="plate_number" name="plate_number"
                                   value="{{ old('plate_number') }}"
                                   placeholder="e.g. GR-1234-22"
                                   style="text-transform:uppercase;" required>
                            @error('plate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-bold" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('vehicle_type') is-invalid @enderror"
                                    id="vehicle_type" name="vehicle_type" required>
                                <option value="" disabled {{ old('vehicle_type') ? '' : 'selected' }}>Select type…</option>
                                @foreach ($vehicleTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('vehicle_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-8">
                            <label class="form-label fw-bold" for="brand_model">Brand / Model <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('brand_model') is-invalid @enderror"
                                   id="brand_model" name="brand_model"
                                   value="{{ old('brand_model') }}"
                                   placeholder="e.g. Toyota Corolla" required>
                            @error('brand_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label fw-bold" for="year_of_manufacture">Year <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('year_of_manufacture') is-invalid @enderror"
                                   id="year_of_manufacture" name="year_of_manufacture"
                                   value="{{ old('year_of_manufacture') }}"
                                   min="1900" max="{{ date('Y') }}"
                                   placeholder="{{ date('Y') }}" required>
                            @error('year_of_manufacture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div style="border-top:1px solid var(--border);margin-bottom:1.25rem;padding-top:1.25rem;">
                        <p style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin:0 0 1rem;">Optional Details</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="color">Color</label>
                                <input type="text" class="form-control @error('color') is-invalid @enderror"
                                       id="color" name="color"
                                       value="{{ old('color') }}"
                                       placeholder="e.g. Silver">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="engine_capacity">Engine Capacity (cc)</label>
                                <input type="number" step="0.1" class="form-control @error('engine_capacity') is-invalid @enderror"
                                       id="engine_capacity" name="engine_capacity"
                                       value="{{ old('engine_capacity') }}"
                                       placeholder="e.g. 1600">
                                @error('engine_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="vin">VIN Number</label>
                                <input type="text" class="form-control @error('vin') is-invalid @enderror"
                                       id="vin" name="vin"
                                       value="{{ old('vin') }}"
                                       placeholder="Vehicle Identification Number"
                                       style="font-family:monospace;">
                                @error('vin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="engine_number">Engine Number</label>
                                <input type="text" class="form-control @error('engine_number') is-invalid @enderror"
                                       id="engine_number" name="engine_number"
                                       value="{{ old('engine_number') }}"
                                       placeholder="Engine serial number"
                                       style="font-family:monospace;">
                                @error('engine_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-car-front-fill"></i> Register Vehicle
                        </button>
                        <a href="{{ route('vehicle.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
