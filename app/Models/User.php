<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'department_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the department associated with the user.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        if (!$this->role) {
            return false;
        }

        return in_array($this->role->slug, ['admin', 'super-admin', 'administrator']);
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin()
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->slug === 'super-admin';
    }

    /**
     * Check if user has specific permission.
     */
    public function hasPermission($permission)
    {
        if (!$this->role) {
            return false;
        }

        $permissions = $this->role->permissions ?? [];

        if (is_string($permission)) {
            return in_array($permission, $permissions);
        }

        if (is_array($permission)) {
            return !empty(array_intersect($permission, $permissions));
        }

        return false;
    }

    /**
     * Get all permissions for the user.
     */
    public function getAllPermissions()
    {
        if (!$this->role) {
            return collect([]);
        }

        return collect($this->role->permissions ?? []);
    }

    /**
     * Check if user has role.
     */
    public function hasRole($role)
    {
        if (!$this->role) {
            return false;
        }

        if (is_string($role)) {
            return $this->role->slug === $role || strtolower($this->role->name) === strtolower($role);
        }

        return $this->role->id === $role->id;
    }

    /**
     * Get user's role name for display.
     */
    public function getRoleNameAttribute()
    {
        return $this->role ? $this->role->name : 'No Role';
    }

    /**
     * Check if user account is active.
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function ticketComments()
    {
        return $this->hasMany(TicketComment::class);
    }

    /**
     * Get the user's notifications
     */
    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications
     */
    public function unreadNotifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
            ->whereNull('read_at');
    }

    /**
     * Get count of unread notifications
     */
    public function unreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        return $this->unreadNotifications()->update(['read_at' => now()]);
    }
}