<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'path',
        'cloudinary_public_id',
        'original_name',
        'mime_type',
        'file_size',
        'is_profile_photo',
    ];

    protected $casts = [
        'is_profile_photo' => 'boolean',
        'file_size' => 'integer',
    ];

    /**
     * Get the user that owns this photo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL path to the photo.
     */
    public function getPhotoUrlAttribute()
    {
        // If using Cloudinary, path is already the full URL
        if ($this->cloudinary_public_id) {
            return $this->path;
        }
        
        // Fallback to local storage
        return asset("storage/user_photos/{$this->user_id}/{$this->filename}");
    }

    /**
     * Get the file size in MB.
     */
    public function getFileSizeInMBAttribute()
    {
        return round($this->file_size / 1024 / 1024, 2);
    }
}