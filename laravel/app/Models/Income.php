<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'product_name',
        'quantity',
        'price',
        'income_date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'income_date' => 'date'
    ];
}
