<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order4p extends Model
{
  protected $connection = 'second_mysql';
  protected $primaryKey = 'id';
  protected $table = 'Order4p'; // or whatever the actual table name is

  protected $fillable = [];

  public function tickets()
  {
    return $this->hasMany(Ticket::class, 'order_id');
  }
}