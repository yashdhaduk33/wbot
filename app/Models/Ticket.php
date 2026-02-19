<?php
// app/Models/Ticket.php

namespace App\Models;

use App\Notifications\TicketAssigned;
use App\Notifications\TicketCommented;
use App\Notifications\TicketStatusChanged;
use App\Notifications\TicketUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'ticket_number',
    'title',
    'description',
    'priority',
    'status',
    'category',
    'created_by',
    'assigned_to',
    'department_id',
    'due_date',
    'resolved_at',
    'closed_at',
    'resolution_notes'
  ];

  protected $casts = [
    'due_date' => 'datetime',
    'resolved_at' => 'datetime',
    'closed_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($ticket) {
      // Generate ticket number
      $ticket->ticket_number = 'TKT-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    });
  }

  // Relationships
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function assignedTo()
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  public function comments()
  {
    return $this->hasMany(TicketComment::class)->latest();
  }

  public function attachments()
  {
    return $this->hasMany(TicketAttachment::class);
  }

  public function activities()
  {
    return $this->hasMany(TicketActivity::class)->latest();
  }

  // Scopes
  public function scopeOpen($query)
  {
    return $query->whereIn('status', ['open', 'in_progress', 'reopened']);
  }

  public function scopeClosed($query)
  {
    return $query->whereIn('status', ['resolved', 'closed']);
  }

  public function scopeAssignedTo($query, $userId)
  {
    return $query->where('assigned_to', $userId);
  }

  public function scopeCreatedBy($query, $userId)
  {
    return $query->where('created_by', $userId);
  }

  public function scopeByPriority($query, $priority)
  {
    return $query->where('priority', $priority);
  }

  public function scopeByStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  // Accessors
  public function getPriorityBadgeAttribute()
  {
    $colors = [
      'low' => 'bg-blue-100 text-blue-800',
      'medium' => 'bg-yellow-100 text-yellow-800',
      'high' => 'bg-orange-100 text-orange-800',
      'urgent' => 'bg-red-100 text-red-800'
    ];

    return '<span class="px-2 py-1 rounded ' . ($colors[$this->priority] ?? 'bg-gray-100') . '">' . ucfirst($this->priority) . '</span>';
  }

  public function getStatusBadgeAttribute()
  {
    $colors = [
      'open' => 'bg-green-100 text-green-800',
      'in_progress' => 'bg-blue-100 text-blue-800',
      'resolved' => 'bg-purple-100 text-purple-800',
      'closed' => 'bg-gray-100 text-gray-800',
      'reopened' => 'bg-yellow-100 text-yellow-800'
    ];

    return '<span class="px-2 py-1 rounded ' . ($colors[$this->status] ?? 'bg-gray-100') . '">' . str_replace('_', ' ', ucfirst($this->status)) . '</span>';
  }

  // Methods
  public function assignTo(User $user)
  {
    $this->assigned_to = $user->id;
    $this->save();

    // Log activity
    $this->activities()->create([
      'user_id' => auth()->id(),
      'action' => 'assigned',
      'details' => "Ticket assigned to {$user->name}"
    ]);
  }

  public function resolve($notes = null)
  {
    $this->status = 'resolved';
    $this->resolved_at = now();
    if ($notes) {
      $this->resolution_notes = $notes;
    }
    $this->save();

    $this->activities()->create([
      'user_id' => auth()->id(),
      'action' => 'resolved',
      'details' => 'Ticket resolved'
    ]);
  }

  public function close($notes = null)
  {
    $this->status = 'closed';
    $this->closed_at = now();
    if ($notes) {
      $this->resolution_notes = $notes;
    }
    $this->save();

    $this->activities()->create([
      'user_id' => auth()->id(),
      'action' => 'closed',
      'details' => 'Ticket closed'
    ]);
  }

  public function reopen()
  {
    $this->status = 'reopened';
    $this->resolved_at = null;
    $this->closed_at = null;
    $this->save();

    $this->activities()->create([
      'user_id' => auth()->id(),
      'action' => 'reopened',
      'details' => 'Ticket reopened'
    ]);
  }

  /**
   * Send notification when ticket is assigned
   */
  public function sendAssignmentNotification(User $assignedBy)
  {
    if ($this->assigned_to) {
      $assignedUser = User::find($this->assigned_to);
      if ($assignedUser) {
        $assignedUser->notify(new TicketAssigned($this, $assignedBy));
      }
    }
  }

  /**
   * Send notification when status changes
   */
  public function sendStatusChangeNotification($oldStatus, User $changedBy)
  {
    // Notify the assigned user
    if ($this->assigned_to && $this->assigned_to != $changedBy->id) {
      $this->assignedTo->notify(new TicketStatusChanged($this, $oldStatus, $changedBy));
    }

    // Notify the creator if they're not the one who changed it
    if ($this->created_by && $this->created_by != $changedBy->id) {
      $this->creator->notify(new TicketStatusChanged($this, $oldStatus, $changedBy));
    }
  }

  /**
   * Send notification when comment is added
   */
  public function sendCommentNotification(TicketComment $comment, User $commenter)
  {
    // Notify assigned user
    if ($this->assigned_to && $this->assigned_to != $commenter->id) {
      $this->assignedTo->notify(new TicketCommented($this, $comment, $commenter));
    }

    // Notify creator if they're not the commenter
    if ($this->created_by && $this->created_by != $commenter->id) {
      $this->creator->notify(new TicketCommented($this, $comment, $commenter));
    }
  }

  /**
   * Send notification when ticket is updated
   */
  public function sendUpdateNotification(User $updatedBy, array $changes = [])
  {
    // Notify assigned user
    if ($this->assigned_to && $this->assigned_to != $updatedBy->id) {
      $this->assignedTo->notify(new TicketUpdated($this, $updatedBy, $changes));
    }

    // Notify creator if they're not the updater
    if ($this->created_by && $this->created_by != $updatedBy->id) {
      $this->creator->notify(new TicketUpdated($this, $updatedBy, $changes));
    }
  }
}