<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpcomingDeadline extends Model
{
    protected $fillable = [
        'title',
        'description',
        'deadline_date',
        'audience',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deadline_date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: only active deadlines that haven't passed yet.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: only upcoming (today or future).
     */
    public function scopeUpcoming($query)
    {
        return $query->where('deadline_date', '>=', now()->toDateString());
    }

    /**
     * Scope: filter by audience role.
     */
    public function scopeForAudience($query, ?string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('audience', 'all');
            if ($role) {
                $q->orWhere('audience', $role);
            }
        });
    }

    /**
     * Get the number of days remaining until the deadline.
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->deadline_date, false));
    }
}
