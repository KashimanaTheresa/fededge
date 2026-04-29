<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'address'];

    protected $hidden = ['password', 'remember_token'];

    // Role constants
    const ROLE_ADMIN = 'admin';

    const ROLE_VEHICLE_OWNER = 'vehicle_owner';

    const ROLE_ROAD_OFFICER = 'road_officer';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is vehicle owner
     */
    public function isVehicleOwner(): bool
    {
        return $this->role === self::ROLE_VEHICLE_OWNER;
    }

    /**
     * Check if user is road officer
     */
    public function isRoadOfficer(): bool
    {
        return $this->role === self::ROLE_ROAD_OFFICER;
    }

    /**
     * Relationship: User has many vehicles
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    /**
     * Relationship: User has many notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
