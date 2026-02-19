<?php
// app/Notifications/TicketUpdated.php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TicketUpdated extends Notification 
{
  use Queueable;

  protected $ticket;
  protected $user;
  protected $changes;

  public function __construct(Ticket $ticket, User $user, array $changes = [])
  {
    $this->ticket = $ticket;
    $this->user = $user;
    $this->changes = $changes;
  }

  public function via($notifiable)
  {
    return ['database', 'broadcast', 'mail'];
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Ticket Updated: ' . $this->ticket->ticket_number)
      ->greeting('Hello ' . $notifiable->name . '!')
      ->line('Ticket #' . $this->ticket->ticket_number . ' has been updated by ' . $this->user->name)
      ->line('Title: ' . $this->ticket->title)
      ->line('Current Status: ' . ucfirst(str_replace('_', ' ', $this->ticket->status)))
      ->line('Priority: ' . ucfirst($this->ticket->priority))
      ->action('View Ticket', url('/admin/tickets/' . $this->ticket->id))
      ->line('Thank you for using our ticket system!');
  }

  public function toDatabase($notifiable)
  {
    return [
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'title' => $this->ticket->title,
      'user_id' => $this->user->id,
      'user_name' => $this->user->name,
      'message' => $this->user->name . ' updated ticket #' . $this->ticket->ticket_number,
      'type' => 'ticket_updated',
      'changes' => $this->changes,
      'action_url' => route('admin.tickets.show', $this->ticket),
      'icon' => 'pencil',
      'color' => 'warning'
    ];
  }

  public function toBroadcast($notifiable)
  {
    return new BroadcastMessage([
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'message' => $this->user->name . ' updated ticket #' . $this->ticket->ticket_number,
      'time' => now()->diffForHumans()
    ]);
  }
}