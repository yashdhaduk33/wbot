<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission($permission)
    {
        $permissions = $this->permissions ?? [];

        if (is_string($permission)) {
            return in_array($permission, $permissions);
        }

        if (is_array($permission)) {
            return !empty(array_intersect($permission, $permissions));
        }

        return false;
    }

    /**
     * Get permissions as collection.
     */
    public function getPermissionsListAttribute()
    {
        return collect($this->permissions ?? []);
    }
}