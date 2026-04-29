<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Display user's vehicles (dashboard)
     */
    public function dashboard(): View
    {
        $vehicles = auth()->user()->vehicles()->with('documents')->get();

        foreach ($vehicles as $vehicle) {
            $vehicle->compliance_status = $vehicle->getComplianceStatus();
            $vehicle->days_until_expiry = $vehicle->daysUntilNextExpiry();
        }

        $pendingDocuments = Document::whereIn('vehicle_id', auth()->user()->vehicles()->pluck('id'))
            ->where('status', 'pending')
            ->count();

        $expiringDocuments = Document::whereIn('vehicle_id', auth()->user()->vehicles()->pluck('id'))
            ->where('status', 'approved')
            ->whereDate('expiry_date', '<=', now()->addDays(7))
            ->whereDate('expiry_date', '>', now())
            ->count();

        return view('vehicle-owner.dashboard', compact('vehicles', 'pendingDocuments', 'expiringDocuments'));
    }

    /**
     * Display all user vehicles
     */
    public function index(): View
    {
        $vehicles = auth()->user()->vehicles()->with('documents')->paginate(10);

        foreach ($vehicles as $vehicle) {
            $vehicle->compliance_status = $vehicle->getComplianceStatus();
        }

        return view('vehicle-owner.vehicles.index', compact('vehicles'));
    }

    /**
     * Show create vehicle form
     */
    public function create(): View
    {
        $vehicleTypes = [
            Vehicle::TYPE_CAR => 'Car',
            Vehicle::TYPE_TRUCK => 'Truck',
            Vehicle::TYPE_BUS => 'Bus',
            Vehicle::TYPE_MOTORCYCLE => 'Motorcycle',
            Vehicle::TYPE_TRAILER => 'Trailer',
            Vehicle::TYPE_TRICYCLE => 'Tricycle',
        ];

        return view('vehicle-owner.vehicles.create', compact('vehicleTypes'));
    }

    /**
     * Store new vehicle
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles|max:20',
            'vehicle_type' => 'required|string',
            'brand_model' => 'required|string|max:100',
            'year_of_manufacture' => 'required|integer|min:1900|max:'.date('Y'),
            'vin' => 'nullable|string|unique:vehicles|max:50',
            'engine_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'engine_capacity' => 'nullable|numeric|min:0',
        ]);

        $validated['owner_id'] = auth()->id();
        $validated['status'] = Vehicle::STATUS_ACTIVE;

        Vehicle::create($validated);

        return redirect()->route('vehicle.index')
            ->with('success', 'Vehicle added successfully. Now please upload required documents.');
    }

    /**
     * Show vehicle details
     */
    public function show(Vehicle $vehicle): View
    {
        $this->authorize('view', $vehicle);

        $vehicle->load('documents');
        $vehicle->compliance_status = $vehicle->getComplianceStatus();
        $vehicle->days_until_expiry = $vehicle->daysUntilNextExpiry();

        return view('vehicle-owner.vehicles.show', compact('vehicle'));
    }

    /**
     * Show edit vehicle form
     */
    public function edit(Vehicle $vehicle): View
    {
        $this->authorize('update', $vehicle);

        $vehicleTypes = [
            Vehicle::TYPE_CAR => 'Car',
            Vehicle::TYPE_TRUCK => 'Truck',
            Vehicle::TYPE_BUS => 'Bus',
            Vehicle::TYPE_MOTORCYCLE => 'Motorcycle',
            Vehicle::TYPE_TRAILER => 'Trailer',
            Vehicle::TYPE_TRICYCLE => 'Tricycle',
        ];

        return view('vehicle-owner.vehicles.edit', compact('vehicle', 'vehicleTypes'));
    }

    /**
     * Update vehicle
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number,'.$vehicle->id.'|max:20',
            'vehicle_type' => 'required|string',
            'brand_model' => 'required|string|max:100',
            'year_of_manufacture' => 'required|integer|min:1900|max:'.date('Y'),
            'vin' => 'nullable|string|unique:vehicles,vin,'.$vehicle->id.'|max:50',
            'engine_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'engine_capacity' => 'nullable|numeric|min:0',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicle.show', $vehicle)
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Delete vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return redirect()->route('vehicle.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Get vehicle compliance status
     */
    public function complianceStatus(Vehicle $vehicle): View
    {
        $this->authorize('view', $vehicle);

        $vehicle->load('documents');

        $requiredDocuments = [
            'vehicle_license' => 'Vehicle License',
            'insurance' => 'Insurance Certificate',
            'roadworthiness_certificate' => 'Roadworthiness Certificate',
        ];

        $documentStatus = [];

        foreach ($requiredDocuments as $type => $label) {
            $document = $vehicle->documents()
                ->where('document_type', $type)
                ->latest()
                ->first();

            $documentStatus[$type] = [
                'label' => $label,
                'document' => $document,
                'status' => $document?->status ?? 'missing',
                'is_expired' => $document && $document->isExpired(),
                'days_until_expiry' => $document ? $document->daysUntilExpiry() : null,
            ];
        }

        return view('vehicle-owner.compliance.status', compact('vehicle', 'documentStatus'));
    }
}
