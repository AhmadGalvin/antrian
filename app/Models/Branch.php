<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'is_active',
        'has_admin',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_admin' => 'boolean',
    ];

    /**
     * Get users belonging to this branch
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get queues for this branch
     */
    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    /**
     * Get today's queues
     */
    public function todayQueues(): HasMany
    {
        return $this->hasMany(Queue::class)->whereDate('created_at', today());
    }

    /**
     * Scope for active branches only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get media files for this branch
     */
    public function media(): HasMany
    {
        return $this->hasMany(BranchMedia::class);
    }

    /**
     * Get active media files ordered by display_order
     */
    public function activeMedia(): HasMany
    {
        return $this->hasMany(BranchMedia::class)
            ->where('is_active', true)
            ->orderBy('display_order', 'asc');
    }
}
