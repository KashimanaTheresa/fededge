<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'brand_model',
        'year_of_manufacture',
        'vin',
        'engine_number',
        'color',
        'engine_capacity',
        'owner_id',
        'status',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';

    const STATUS_INACTIVE = 'inactive';

    const STATUS_SUSPENDED = 'suspended';

    // Vehicle type constants
    const TYPE_CAR = 'car';

    const TYPE_TRUCK = 'truck';

    const TYPE_BUS = 'bus';

    const TYPE_MOTORCYCLE = 'motorcycle';

    const TYPE_TRAILER = 'trailer';

    const TYPE_TRICYCLE = 'tricycle';

    /**
     * Relationship: Vehicle belongs to User (owner)
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relationship: Vehicle has many documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get all approved documents
     */
    public function approvedDocuments(): HasMany
    {
        return $this->documents()->where('status', 'approved');
    }

    /**
     * Get all pending documents
     */
    public function pendingDocuments(): HasMany
    {
        return $this->documents()->where('status', 'pending');
    }

    /**
     * Get all expired documents
     */
    public function expiredDocuments(): HasMany
    {
        return $this->documents()->where('status', 'expired');
    }

    /**
     * Check if vehicle is compliant
     */
    public function isCompliant(): bool
    {
        $requiredDocuments = ['vehicle_license', 'insurance', 'roadworthiness_certificate'];

        foreach ($requiredDocuments as $docType) {
            $document = $this->documents()
                ->where('document_type', $docType)
                ->where('status', 'approved')
                ->where('expiry_date', '>', now())
                ->first();

            if (! $document) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get compliance status
     */
    public function getComplianceStatus(): string
    {
        if ($this->isCompliant()) {
            return 'compliant';
        }

        $hasExpired = $this->documents()
            ->where('status', 'approved')
            ->where('expiry_date', '<=', now())
            ->exists();

        if ($hasExpired) {
            return 'expired';
        }

        $hasPending = $this->documents()
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return 'pending';
        }

        return 'missing_documents';
    }

    /**
     * Get days until next expiry
     */
    public function daysUntilNextExpiry(): ?int
    {
        $nextExpiry = $this->documents()
            ->where('status', 'approved')
            ->orderBy('expiry_date', 'asc')
            ->first();

        if (! $nextExpiry) {
            return null;
        }

        return now()->diffInDays($nextExpiry->expiry_date);
    }
}
