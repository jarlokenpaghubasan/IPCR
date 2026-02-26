<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'acronym',
    ];

    /**
     * Get all user_roles entries with this role name.
     */
    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'role', 'name');
    }

    /**
     * Get all role names as a flat array.
     */
    public static function getNames(): array
    {
        return static::pluck('name')->toArray();
    }

    /**
     * Get the acronym for a given role name.
     */
    public static function getAcronym(string $roleName): ?string
    {
        return static::where('name', $roleName)->value('acronym');
    }

    /**
     * Permissions assigned to this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Check if this role has a specific permission.
     */
    public function hasPermission(string $key): bool
    {
        return $this->permissions()->where('key', $key)->exists();
    }

    /**
     * Sync permissions by their keys.
     */
    public function syncPermissions(array $permissionKeys): void
    {
        $permissionIds = Permission::whereIn('key', $permissionKeys)->pluck('id');
        $this->permissions()->sync($permissionIds);
    }
}
