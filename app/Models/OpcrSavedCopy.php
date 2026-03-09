<?php

namespace App\Models;

use App\Services\HtmlSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpcrSavedCopy extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'school_year',
        'semester',
        'table_body_html',
        'noted_by',
        'approved_by',
        'saved_at',
    ];

    protected $casts = [
        'saved_at' => 'datetime',
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
}
