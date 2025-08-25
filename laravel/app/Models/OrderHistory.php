<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'status',
        'total',
        'down_payment',
        'delivery_fee',
        'delivery_method',
        'delivery_address',
        'pickup_date',
        'items_json',
        'original_created_at',
        'original_updated_at',
        'archived_at'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'pickup_date' => 'datetime',
        'original_created_at' => 'datetime',
        'original_updated_at' => 'datetime',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getItemsAttribute()
    {
        return json_decode($this->items_json, true);
    }
}
