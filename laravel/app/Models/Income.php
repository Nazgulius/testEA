<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'income_id',
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'date',
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
        'date_close' => 'date',
        'barcode' => 'integer',
        'income_id' => 'integer',
        'nm_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;
}
