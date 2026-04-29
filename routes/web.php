<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RoadOfficerController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::view('/', 'welcome')->name('home');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Vehicle Owner routes
    Route::middleware('role:vehicle_owner')->group(function () {
        Route::get('/dashboard', [VehicleController::class, 'dashboard'])->name('dashboard');
        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicle.index');
        Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicle.create');
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicle.store');
        Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicle.show');
        Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicle.edit');
        Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicle.update');
        Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');
        Route::get('/vehicles/{vehicle}/compliance', [VehicleController::class, 'complianceStatus'])->name('vehicle.compliance');

        Route::get('/documents', [DocumentController::class, 'index'])->name('document.index');
        Route::get('/documents/create/{vehicle}', [DocumentController::class, 'create'])->name('document.create');
        Route::post('/documents/{vehicle}', [DocumentController::class, 'store'])->name('document.store');
        Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('document.show');
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('document.download');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
        Route::get('/documents/{document}/reupload', [DocumentController::class, 'reupload'])->name('document.reupload');
        Route::post('/documents/{document}/reupload', [DocumentController::class, 'storeReupload'])->name('document.storeReupload');
    });

    // Road Officer routes
    Route::middleware('role:road_officer')->prefix('verification')->group(function () {
        Route::get('/dashboard', [RoadOfficerController::class, 'dashboard'])->name('officer.dashboard');
        Route::get('/search', [RoadOfficerController::class, 'search'])->name('officer.search');
        Route::post('/search', [RoadOfficerController::class, 'searchVehicle'])->name('officer.searchVehicle');
        Route::get('/verify/{vehicle}', [RoadOfficerController::class, 'verify'])->name('officer.verify');
        Route::get('/report/{vehicle}', [RoadOfficerController::class, 'report'])->name('officer.report');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/vehicles', [AdminDashboardController::class, 'vehicles'])->name('admin.vehicles.index');
        Route::get('/vehicles/{vehicle}', [AdminDashboardController::class, 'vehicleShow'])->name('admin.vehicles.show');

        Route::get('/users', [AdminDashboardController::class, 'users'])->name('admin.users.index');
        Route::get('/users/{user}', [AdminDashboardController::class, 'userShow'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [AdminDashboardController::class, 'userEdit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminDashboardController::class, 'userUpdate'])->name('admin.users.update');

        Route::get('/documents', [AdminDashboardController::class, 'documents'])->name('admin.documents.index');
        Route::get('/documents/{document}', [AdminDashboardController::class, 'documentShow'])->name('admin.documents.show');
        Route::get('/documents/{document}/download', [AdminDashboardController::class, 'documentDownload'])->name('admin.documents.download');
        Route::post('/documents/{document}/approve', [AdminDashboardController::class, 'documentApprove'])->name('admin.documents.approve');
        Route::post('/documents/{document}/reject', [AdminDashboardController::class, 'documentReject'])->name('admin.documents.reject');
    });
});
