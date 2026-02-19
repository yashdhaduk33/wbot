<?php
// app/Notifications/TicketStatusChanged.php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TicketStatusChanged extends Notification 
{
  use Queueable;

  protected $ticket;
  protected $oldStatus;
  protected $changedBy;

  public function __construct(Ticket $ticket, $oldStatus, User $changedBy)
  {
    $this->ticket = $ticket;
    $this->oldStatus = $oldStatus;
    $this->changedBy = $changedBy;
  }

  private function formatStatus($status)
  {
    return ucfirst(str_replace('_', ' ', $status));
  }

  private function getStatusColor($status)
  {
    return match ($status) {
      'open' => 'blue',
      'in_progress' => 'yellow',
      'resolved' => 'green',
      'closed' => 'gray',
      'reopened' => 'purple',
      default => 'blue'
    };
  }

  public function via($notifiable)
  {
    return ['database', 'broadcast', 'mail'];
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Ticket Status Updated: ' . $this->ticket->ticket_number)
      ->greeting('Hello ' . $notifiable->name . '!')
      ->line($this->changedBy->name . ' changed the status of ticket #' . $this->ticket->ticket_number)
      ->line('From: ' . $this->formatStatus($this->oldStatus))
      ->line('To: ' . $this->formatStatus($this->ticket->status))
      ->line('Title: ' . $this->ticket->title)
      ->action('View Ticket', url('/admin/tickets/' . $this->ticket->id))
      ->line('Thank you for using our ticket system!');
  }

  public function toDatabase($notifiable)
  {
    return [
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'old_status' => $this->oldStatus,
      'new_status' => $this->ticket->status,
      'changed_by_id' => $this->changedBy->id,
      'changed_by_name' => $this->changedBy->name,
      'resolution_notes' => $this->ticket->resolution_notes,
      'message' => $this->changedBy->name . ' changed ticket status from ' . $this->formatStatus($this->oldStatus) . ' to ' . $this->formatStatus($this->ticket->status),
      'type' => 'ticket_status_changed',
      'action_url' => route('admin.tickets.show', $this->ticket),
      'icon' => 'arrow-path',
      'color' => $this->getStatusColor($this->ticket->status)
    ];
  }

  public function toBroadcast($notifiable)
  {
    return new BroadcastMessage([
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'old_status' => $this->oldStatus,
      'new_status' => $this->ticket->status,
      'message' => 'Ticket status updated to ' . $this->formatStatus($this->ticket->status),
      'time' => now()->diffForHumans()
    ]);
  }
}