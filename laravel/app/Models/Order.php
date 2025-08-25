<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name', // field baru
        'status',
        'total',
        'pickup_date',
        'delivery_method',
        'down_payment',
        'delivery_address',
        'delivery_fee'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'pickup_date' => 'datetime',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Get the remaining payment amount
     */
    public function getRemainingPaymentAttribute(): float
    {
        return $this->total + $this->delivery_fee - $this->down_payment;
    }

    /**
     * Get the formatted delivery method
     */
    public function getDeliveryMethodLabelAttribute(): string
    {
        return match($this->delivery_method) {
            'pickup' => 'Ambil Langsung',
            'gosend' => 'GoSend',
            'gocar' => 'GoCar',
            default => 'Ambil Langsung'
        };
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockHolds(): HasMany
    {
        return $this->hasMany(StockHold::class);
    }

    public static function boot()
    {
        parent::boot();

        // Hapus stock holds saat order dihapus
        static::deleting(function ($order) {
            $order->stockHolds()->delete();
        });
    }
}
