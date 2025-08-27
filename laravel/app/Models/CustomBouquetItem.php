<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomBouquetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_bouquet_id',
        'product_id',
        'price_type',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationships
    public function customBouquet()
    {
        return $this->belongsTo(CustomBouquet::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function calculateSubtotal()
    {
        return $this->unit_price * $this->quantity;
    }

    public function getPriceTypeDisplayAttribute()
    {
        $priceTypeLabels = [
            'per_tangkai' => 'Per Tangkai',
            'ikat_3' => 'Ikat 3',
            'ikat_5' => 'Ikat 5',
            'ikat_10' => 'Ikat 10',
            'ikat_20' => 'Ikat 20',
            'reseller' => 'Reseller',
            'normal' => 'Normal',
            'promo' => 'Promo',
            'custom_ikat' => 'Custom Ikat',
            'custom_tangkai' => 'Custom Tangkai',
            'custom_khusus' => 'Custom Khusus'
        ];

        return $priceTypeLabels[$this->price_type] ?? $this->price_type;
    }

    public function getFormattedQuantityAttribute()
    {
        $unit = $this->price_type === 'per_tangkai' ? 'tangkai' : $this->price_type;
        return $this->quantity . ' ' . $unit;
    }

    // Boot method to auto-calculate subtotal
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->subtotal = $item->calculateSubtotal();
        });
    }
}
