<?php
// app/Notifications/TicketCommented.php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCommented extends Notification implements ShouldQueue
{
  use Queueable;

  protected $ticket;
  protected $comment;

  public function __construct(Ticket $ticket, TicketComment $comment)
  {
    $this->ticket = $ticket;
    $this->comment = $comment;
  }

  public function via($notifiable)
  {
    return ['mail', 'database'];
  }

  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('New Comment on Ticket: ' . $this->ticket->ticket_number)
      ->line('A new comment has been added to your ticket.')
      ->line('Ticket #: ' . $this->ticket->ticket_number)
      ->line('Comment by: ' . $this->comment->user->name)
      ->line('Comment: ' . substr($this->comment->comment, 0, 100) . '...')
      ->action('View Comment', route('admin.tickets.show', $this->ticket) . '#comment-' . $this->comment->id);
  }

  public function toDatabase($notifiable)
  {
    return [
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'comment_id' => $this->comment->id,
      'user_id' => $this->comment->user_id,
      'user_name' => $this->comment->user->name,
      'message' => $this->comment->user->name . ' commented on ticket'
    ];
  }
}