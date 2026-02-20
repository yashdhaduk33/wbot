<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'second_mysql'; // ✅ IMPORTANT
    protected $table = 'order';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_id');
    }
    public function orderedproducts()
    {
        return $this->hasMany(OrderedProduct::class, 'orderId', 'id');
    }
}
