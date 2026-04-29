<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'document_type',
        'file_path',
        'original_filename',
        'issue_date',
        'expiry_date',
        'status',
        'admin_feedback',
        'approved_by',
        'approved_at',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    const STATUS_EXPIRED = 'expired';

    // Document type constants
    const TYPE_DRIVERS_LICENSE = 'drivers_license';

    const TYPE_VEHICLE_LICENSE = 'vehicle_license';

    const TYPE_INSURANCE = 'insurance';

    const TYPE_ROADWORTHINESS_CERTIFICATE = 'roadworthiness_certificate';

    const TYPE_REGISTRATION_CERTIFICATE = 'registration_certificate';

    const TYPE_INSPECTION_REPORT = 'inspection_report';

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Relationship: Document belongs to Vehicle
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Relationship: Document approved by User (admin)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if document is expired
     */
    public function isExpired(): bool
    {
        return now() > $this->expiry_date;
    }

    /**
     * Check if document is expiring soon
     */
    public function isExpiringSoon(int $days = 7): bool
    {
        return now()->addDays($days) >= $this->expiry_date && ! $this->isExpired();
    }

    /**
     * Get days until expiry
     */
    public function daysUntilExpiry(): int
    {
        return now()->diffInDays($this->expiry_date);
    }

    /**
     * Get human-readable document type
     */
    public function getDocumentTypeLabel(): string
    {
        return match ($this->document_type) {
            self::TYPE_DRIVERS_LICENSE => "Driver's License",
            self::TYPE_VEHICLE_LICENSE => 'Vehicle License',
            self::TYPE_INSURANCE => 'Insurance Certificate',
            self::TYPE_ROADWORTHINESS_CERTIFICATE => 'Roadworthiness Certificate',
            self::TYPE_REGISTRATION_CERTIFICATE => 'Registration Certificate',
            self::TYPE_INSPECTION_REPORT => 'Inspection Report',
            default => $this->document_type,
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_PENDING => 'warning',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_EXPIRED => 'info',
            default => 'secondary',
        };
    }
}
