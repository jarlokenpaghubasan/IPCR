<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'group',
    ];

    /**
     * Roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get all permissions grouped by their group field.
     */
    public static function allGrouped(): array
    {
        return static::orderBy('group')->orderBy('name')
            ->get()
            ->groupBy('group')
            ->toArray();
    }
}
