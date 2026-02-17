<?php
// app/Notifications/TicketStatusChanged.php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketStatusChanged extends Notification implements ShouldQueue
{
  use Queueable;

  protected $ticket;
  protected $oldStatus;

  public function __construct(Ticket $ticket, $oldStatus)
  {
    $this->ticket = $ticket;
    $this->oldStatus = $oldStatus;
  }

  public function via($notifiable)
  {
    return ['mail', 'database'];
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Ticket Status Updated: ' . $this->ticket->ticket_number)
      ->line('The status of your ticket has been updated.')
      ->line('Ticket #: ' . $this->ticket->ticket_number)
      ->line('Status changed from: ' . str_replace('_', ' ', ucfirst($this->oldStatus)))
      ->line('New status: ' . str_replace('_', ' ', ucfirst($this->ticket->status)))
      ->action('View Ticket', route('admin.tickets.show', $this->ticket));
  }

  public function toDatabase($notifiable)
  {
    return [
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'old_status' => $this->oldStatus,
      'new_status' => $this->ticket->status,
      'message' => 'Ticket status updated to ' . str_replace('_', ' ', $this->ticket->status)
    ];
  }
}