<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicOrderPayment extends Model
{
    protected $fillable = [
        'public_order_id', 'amount', 'note', 'proof'
    ];

    public function order()
    {
        return $this->belongsTo(PublicOrder::class, 'public_order_id');
    }
}
