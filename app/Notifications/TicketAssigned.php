<?php
// app/Notifications/TicketAssigned.php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAssigned extends Notification implements ShouldQueue
{
  use Queueable;

  protected $ticket;

  public function __construct(Ticket $ticket)
  {
    $this->ticket = $ticket;
  }

  public function via($notifiable)
  {
    return ['mail', 'database', 'broadcast'];
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('New Ticket Assigned: ' . $this->ticket->ticket_number)
      ->greeting('Hello ' . $notifiable->name . '!')
      ->line('A new ticket has been assigned to you.')
      ->line('Ticket #: ' . $this->ticket->ticket_number)
      ->line('Title: ' . $this->ticket->title)
      ->line('Priority: ' . ucfirst($this->ticket->priority))
      ->action('View Ticket', route('admin.tickets.show', $this->ticket))
      ->line('Thank you for using our ticket system!');
  }

  public function toDatabase($notifiable)
  {
    return [
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'title' => $this->ticket->title,
      'message' => 'A new ticket has been assigned to you',
      'type' => 'ticket_assigned'
    ];
  }

  public function toBroadcast($notifiable)
  {
    return [
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'message' => 'New ticket assigned'
    ];
  }
}