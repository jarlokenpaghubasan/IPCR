<?php

namespace App\Models;

use App\Services\HtmlSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IpcrSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'school_year',
        'semester',
        'table_body_html',
        'noted_by',
        'approved_by',
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

    /**
     * Sanitize table_body_html before saving to prevent XSS.
     */
    public function setTableBodyHtmlAttribute($value)
    {
        $this->attributes['table_body_html'] = HtmlSanitizer::sanitize($value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deanCalibrations(): HasMany
    {
        return $this->hasMany(DeanCalibration::class);
    }
}
