<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'role',
        'department_id',
        'designation_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the department that the user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the designation of the user.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Get all photos for this user.
     */
    public function photos()
    {
        return $this->hasMany(UserPhoto::class);
    }

    /**
     * Get the current profile photo.
     */
    public function profilePhoto()
    {
        return $this->hasOne(UserPhoto::class)->where('is_profile_photo', true);
    }

    /**
     * Get profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        try {
        $photo = $this->profilePhoto()->first();
        if ($photo) {
            return $photo->photo_url;
        }
    } catch (\Exception $e) {
        // If there's an error, just return the default
    }
    return '/images/default_avatar.jpg';
    }

    /**
     * Check if user has a profile photo.
     */
    public function hasProfilePhoto()
    {
        return $this->profilePhoto()->exists();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->is_active === true;
    }
}