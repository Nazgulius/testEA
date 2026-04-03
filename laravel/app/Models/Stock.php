<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_name',
        'quantity',
        'price',
        'stock_date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'stock_date' => 'date'
    ];
}
