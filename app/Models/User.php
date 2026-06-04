<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Role constants
     */
    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_TELLER = 'teller';
    public const ROLE_CS = 'cs';
    public const ROLE_KIOSK = 'kiosk';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'branch_id',
        'role',
        'counter_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Branch relationship
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Served queues relationship
     */
    public function servedQueues(): HasMany
    {
        return $this->hasMany(Queue::class, 'served_by');
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperadmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is teller
     */
    public function isTeller(): bool
    {
        return $this->role === self::ROLE_TELLER;
    }

    /**
     * Check if user is CS
     */
    public function isCs(): bool
    {
        return $this->role === self::ROLE_CS;
    }

    /**
     * Check if user is kiosk
     */
    public function isKiosk(): bool
    {
        return $this->role === self::ROLE_KIOSK;
    }

    /**
     * Check if user can serve queues (teller, admin, or cs)
     */
    public function canServeQueue(): bool
    {
        return in_array($this->role, [self::ROLE_TELLER, self::ROLE_ADMIN, self::ROLE_CS]);
    }

    /**
     * Get service type that user can serve
     */
    public function getServiceType(): ?string
    {
        return match($this->role) {
            self::ROLE_TELLER => 'teller',
            self::ROLE_CS => 'cs',
            self::ROLE_ADMIN => 'admin',
            default => null,
        };
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            self::ROLE_SUPERADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_TELLER => 'Teller',
            self::ROLE_CS => 'Customer Service',
            self::ROLE_KIOSK => 'Kiosk',
            default => ucfirst($this->role),
        };
    }

    /**
     * Scope for specific role
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for specific branch
     */
    public function scopeForBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}

