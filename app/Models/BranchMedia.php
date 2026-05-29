<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchMedia extends Model
{
    use HasFactory;

    protected $table = 'branch_media';

    protected $fillable = [
        'branch_id',
        'type',
        'file_path',
        'title',
        'display_order',
        'duration_seconds',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'duration_seconds' => 'integer',
    ];

    /**
     * Get the branch that owns the media
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Scope for active media
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered media
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Get full URL for the media file
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
