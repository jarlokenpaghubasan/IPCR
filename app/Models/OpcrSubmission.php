<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpcrSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'school_year',
        'semester',
        'table_body_html',
        'so_count_json',
        'status',
        'is_active',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'so_count_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
