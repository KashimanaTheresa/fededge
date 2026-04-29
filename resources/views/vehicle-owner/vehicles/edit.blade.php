@extends('layouts.app')
@section('page_title', 'Edit Vehicle')

@section('content')

<div class="page-header animate-up">
    <div>
        <h1 class="page-title"><i class="bi bi-pencil-square"></i> Edit Vehicle</h1>
        <p class="page-subtitle">{{ $vehicle->plate_number }} &mdash; {{ $vehicle->brand_model }}</p>
    </div>
    <a href="{{ route('vehicle.show', $vehicle) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row justify-content-center animate-up">
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="card-header gradient"><i class="bi bi-car-front-fill"></i> Update Details</div>
            <div class="card-body">

                <form action="{{ route('vehicle.update', $vehicle) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold" for="plate_number">Plate Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('plate_number') is-invalid @enderror"
                                   id="plate_number" name="plate_number"
                                   value="{{ old('plate_number', $vehicle->plate_number) }}"
                                   style="text-transform:uppercase;" required>
                            @error('plate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-bold" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('vehicle_type') is-invalid @enderror"
                                    id="vehicle_type" name="vehicle_type" required>
                                @foreach ($vehicleTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('vehicle_type', $vehicle->vehicle_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
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
                                   value="{{ old('brand_model', $vehicle->brand_model) }}" required>
                            @error('brand_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label fw-bold" for="year_of_manufacture">Year <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('year_of_manufacture') is-invalid @enderror"
                                   id="year_of_manufacture" name="year_of_manufacture"
                                   value="{{ old('year_of_manufacture', $vehicle->year_of_manufacture) }}"
                                   min="1900" max="{{ date('Y') }}" required>
                            @error('year_of_manufacture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="border-top:1px solid var(--border);margin-bottom:1.25rem;padding-top:1.25rem;">
                        <p style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);margin:0 0 1rem;">Optional Details</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="color">Color</label>
                                <input type="text" class="form-control @error('color') is-invalid @enderror"
                                       id="color" name="color"
                                       value="{{ old('color', $vehicle->color) }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="engine_capacity">Engine Capacity (cc)</label>
                                <input type="number" step="0.1" class="form-control @error('engine_capacity') is-invalid @enderror"
                                       id="engine_capacity" name="engine_capacity"
                                       value="{{ old('engine_capacity', $vehicle->engine_capacity) }}">
                                @error('engine_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="vin">VIN Number</label>
                                <input type="text" class="form-control @error('vin') is-invalid @enderror"
                                       id="vin" name="vin"
                                       value="{{ old('vin', $vehicle->vin) }}"
                                       style="font-family:monospace;">
                                @error('vin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label fw-bold" for="engine_number">Engine Number</label>
                                <input type="text" class="form-control @error('engine_number') is-invalid @enderror"
                                       id="engine_number" name="engine_number"
                                       value="{{ old('engine_number', $vehicle->engine_number) }}"
                                       style="font-family:monospace;">
                                @error('engine_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                        <a href="{{ route('vehicle.show', $vehicle) }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
