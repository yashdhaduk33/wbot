<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Correct namespace

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Fixed trait

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
        'department_id'
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

        // Check by slug or name
        if ($this->role->slug) {
            return in_array($this->role->slug, ['admin', 'super-admin', 'administrator']);
        }

        // Check by name if slug doesn't exist
        return in_array(strtolower($this->role->name), ['admin', 'super admin', 'administrator']);
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin()
    {
        if (!$this->role) {
            return false;
        }

        if ($this->role->slug) {
            return $this->role->slug === 'super-admin';
        }

        return strtolower($this->role->name) === 'super admin';
    }

    /**
     * Check if user has specific permission.
     */
    public function hasPermission($permission)
    {
        if ($this->role) {
            return $this->role->hasPermission($permission);
        }
        return false;
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

    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    public function unreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    
}