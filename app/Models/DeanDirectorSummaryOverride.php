<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeanDirectorSummaryOverride extends Model
{
    protected $fillable = [
        'user_id',
        'strategic_score',
        'core_score',
        'support_score',
        'updated_by',
    ];

    protected $casts = [
        'strategic_score' => 'decimal:2',
        'core_score' => 'decimal:2',
        'support_score' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
