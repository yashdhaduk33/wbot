<?php
// app/Models/TicketAttachment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
  use HasFactory;

  protected $fillable = [
    'ticket_id',
    'comment_id',
    'user_id',
    'filename',
    'original_filename',
    'file_path',
    'file_size',
    'mime_type'
  ];

  public function ticket()
  {
    return $this->belongsTo(Ticket::class);
  }

  public function comment()
  {
    return $this->belongsTo(TicketComment::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}