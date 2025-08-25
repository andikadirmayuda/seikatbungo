<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BouquetOrder extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'wa_number',
        'notes',
        'total_price',
        'status',
        'delivery_method',
        'delivery_note',
        'delivery_at',
        'pickup_at',
    ];

    protected $dates = [
        'delivery_at',
        'pickup_at',
        'created_at',
        'updated_at',
    ];

    public function items()
    {
        return $this->hasMany(BouquetOrderItem::class);
    }
}
