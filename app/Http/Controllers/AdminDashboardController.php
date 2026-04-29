<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index(): View
    {
        $totalVehicles = Vehicle::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalDocuments = Document::count();
        $pendingDocuments = Document::where('status', 'pending')->count();
        $approvedDocuments = Document::where('status', 'approved')->count();
        $expiredDocuments = Document::where('status', 'expired')->count();
        $rejectedDocuments = Document::where('status', 'rejected')->count();

        // Get compliant vs non-compliant vehicles
        $vehicles = Vehicle::with('documents')->get();
        $compliantVehicles = $vehicles->filter(fn ($v) => $v->isCompliant())->count();
        $nonCompliantVehicles = $totalVehicles - $compliantVehicles;

        // Get recent pending documents
        $recentPendingDocuments = Document::with('vehicle', 'vehicle.owner')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get documents expiring soon (within 7 days)
        $expiringDocuments = Document::with('vehicle', 'vehicle.owner')
            ->where('status', 'approved')
            ->whereDate('expiry_date', '<=', now()->addDays(7))
            ->whereDate('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->take(5)
            ->get();

        // Document status breakdown
        $documentStatusData = [
            'Approved' => $approvedDocuments,
            'Pending' => $pendingDocuments,
            'Rejected' => $rejectedDocuments,
            'Expired' => $expiredDocuments,
        ];

        // Vehicle compliance breakdown
        $complianceData = [
            'Compliant' => $compliantVehicles,
            'Non-Compliant' => $nonCompliantVehicles,
        ];

        // Users by role
        $usersByRole = [
            'Vehicle Owners' => User::where('role', 'vehicle_owner')->count(),
            'Road Officers' => User::where('role', 'road_officer')->count(),
            'Admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalVehicles',
            'totalUsers',
            'totalDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'expiredDocuments',
            'rejectedDocuments',
            'compliantVehicles',
            'nonCompliantVehicles',
            'recentPendingDocuments',
            'expiringDocuments',
            'documentStatusData',
            'complianceData',
            'usersByRole'
        ));
    }

    /**
     * Display all vehicles
     */
    public function vehicles(): View
    {
        $vehicles = Vehicle::with('owner', 'documents')
            ->paginate(15);

        foreach ($vehicles as $vehicle) {
            $vehicle->compliance_status = $vehicle->getComplianceStatus();
        }

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Display vehicle details
     */
    public function vehicleShow(Vehicle $vehicle): View
    {
        $vehicle->load(['owner', 'documents']);
        $vehicle->compliance_status = $vehicle->getComplianceStatus();

        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Display all users
     */
    public function users(): View
    {
        $users = User::withCount('vehicles')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display user details
     */
    public function userShow(User $user): View
    {
        $user->load('vehicles');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Edit user role
     */
    public function userEdit(User $user): View
    {
        $roles = [
            'admin' => 'Admin',
            'vehicle_owner' => 'Vehicle Owner',
            'road_officer' => 'Road Officer',
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user role
     */
    public function userUpdate(User $user)
    {
        $validated = request()->validate([
            'role' => 'required|in:admin,vehicle_owner,road_officer',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User role updated successfully.');
    }

    /**
     * Display all documents
     */
    public function documents(): View
    {
        $status = request('status');
        $documents = Document::with('vehicle', 'vehicle.owner', 'approver')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Download document file
     */
    public function documentDownload(Document $document)
    {
        if (! Storage::exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($document->file_path, $document->original_filename);
    }

    /**
     * Display document details
     */
    public function documentShow(Document $document): View
    {
        $document->load(['vehicle', 'vehicle.owner', 'approver']);

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Approve document
     */
    public function documentApprove(Document $document)
    {
        $validated = request()->validate([
            'admin_feedback' => 'nullable|string|max:1000',
        ]);

        $document->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_feedback' => $validated['admin_feedback'] ?? null,
        ]);

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'Document approved successfully.');
    }

    /**
     * Reject document
     */
    public function documentReject(Document $document)
    {
        $validated = request()->validate([
            'admin_feedback' => 'required|string|max:1000',
        ]);

        $document->update([
            'status' => 'rejected',
            'admin_feedback' => $validated['admin_feedback'],
        ]);

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'Document rejected successfully.');
    }
}
