<?php

namespace App\Models;

use App\Services\HtmlSanitizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpcrTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'period',
        'school_year',
        'semester',
        'content',
        'table_body_html',
        'noted_by',
        'approved_by',
        'is_active',
        'so_count_json',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_active' => 'boolean',
        'so_count_json' => 'array',
    ];

    /**
     * Sanitize table_body_html before saving to prevent XSS.
     */
    public function setTableBodyHtmlAttribute($value)
    {
        $this->attributes['table_body_html'] = HtmlSanitizer::sanitize($value);
    }

    /**
     * Get the user that owns the template.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
