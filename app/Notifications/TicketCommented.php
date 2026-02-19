<?php
// app/Notifications/TicketCommented.php

namespace App\Notifications;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TicketCommented extends Notification 
{
  use Queueable;

  protected $ticket;
  protected $comment;
  protected $user;

  public function __construct(Ticket $ticket, TicketComment $comment, User $user)
  {
    $this->ticket = $ticket;
    $this->comment = $comment;
    $this->user = $user;
  }

  public function via($notifiable)
  {
    return ['database', 'broadcast', 'mail'];
  }

  public function toMail($notifiable)
  {
    $mail = (new MailMessage)
      ->subject('New Comment on Ticket: ' . $this->ticket->ticket_number)
      ->greeting('Hello ' . $notifiable->name . '!')
      ->line($this->user->name . ' added a ' . ($this->comment->is_internal ? 'internal ' : '') . 'comment on ticket #' . $this->ticket->ticket_number)
      ->line('Comment:')
      ->line($this->comment->comment);

    if ($this->comment->is_internal) {
      $mail->line('⚠️ This is an internal note (visible only to staff)');
    }

    return $mail->action('View Comment', url('/admin/tickets/' . $this->ticket->id . '#comment-' . $this->comment->id))
      ->line('Thank you for using our ticket system!');
  }

  public function toDatabase($notifiable)
  {
    return [
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'comment_id' => $this->comment->id,
      'user_id' => $this->user->id,
      'user_name' => $this->user->name,
      'comment_preview' => substr($this->comment->comment, 0, 100) . (strlen($this->comment->comment) > 100 ? '...' : ''),
      'is_internal' => $this->comment->is_internal,
      'message' => $this->user->name . ' commented on ticket #' . $this->ticket->ticket_number,
      'type' => 'ticket_commented',
      'action_url' => route('admin.tickets.show', $this->ticket) . '#comment-' . $this->comment->id,
      'icon' => 'chat',
      'color' => $this->comment->is_internal ? 'warning' : 'info'
    ];
  }

  public function toBroadcast($notifiable)
  {
    return new BroadcastMessage([
      'ticket_id' => $this->ticket->id,
      'ticket_number' => $this->ticket->ticket_number,
      'user_name' => $this->user->name,
      'comment_preview' => substr($this->comment->comment, 0, 50) . '...',
      'is_internal' => $this->comment->is_internal,
      'time' => now()->diffForHumans()
    ]);
  }
}