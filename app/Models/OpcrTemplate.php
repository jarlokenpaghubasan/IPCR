<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcrTemplate extends Model
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
        'is_active',
        'so_count_json',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'so_count_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
