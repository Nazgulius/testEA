<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_name',
        'quantity',
        'price',
        'order_date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'order_date' => 'date'
    ];
}
