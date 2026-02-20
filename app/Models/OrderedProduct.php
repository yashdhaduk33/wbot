<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderedProduct extends Model
{
  protected $table = 'orderedproducts'; // ✅ correct table name
  protected $connection = 'second_mysql';
  protected $fillable = [
    'orderId',
    'SKU',
    'name',
    'price',
    'size',
    'quantity',
    'image',
    'tax_per',
    'ourprice',
    'tax',
    'subprice',
    'hsn',
    'discount'
  ];
}