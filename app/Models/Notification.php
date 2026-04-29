<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'related_type',
        'related_id',
        'read_at',
    ];
    use HasFactory;

    // Type constants
    const TYPE_INFO = 'info';

    const TYPE_WARNING = 'warning';

    const TYPE_SUCCESS = 'success';

    const TYPE_ERROR = 'error';

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * Relationship: Notification belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if notification is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Get icon class based on type
     */
    public function getIconClass(): string
    {
        return match ($this->type) {
            self::TYPE_INFO => 'bi-info-circle',
            self::TYPE_WARNING => 'bi-exclamation-triangle',
            self::TYPE_SUCCESS => 'bi-check-circle',
            self::TYPE_ERROR => 'bi-x-circle',
            default => 'bi-bell',
        };
    }

    /**
     * Get bootstrap class based on type
     */
    public function getBootstrapClass(): string
    {
        return match ($this->type) {
            self::TYPE_INFO => 'info',
            self::TYPE_WARNING => 'warning',
            self::TYPE_SUCCESS => 'success',
            self::TYPE_ERROR => 'danger',
            default => 'secondary',
        };
    }
}
