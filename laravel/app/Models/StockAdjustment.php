<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    protected $fillable = [
        'product_id',
        'adjustment_type',
        'quantity_before',
        'quantity_after',
        'reason',
        'adjusted_by',
        'adjustment_date'
    ];

    protected $casts = [
        'adjustment_type' => 'string',
        'adjustment_date' => 'datetime',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer'
    ];

    // Relasi
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function adjuster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    // Virtual column untuk selisih quantity
    public function getDifferenceAttribute(): int
    {
        return $this->quantity_after - $this->quantity_before;
    }

    // Boot method untuk mencatat perubahan stok
    protected static function boot()
    {
        parent::boot();

        static::created(function ($adjustment) {
            $adjustment->product->update(['stock' => $adjustment->quantity_after]);
            
            // Catat di inventory_transactions
            InventoryTransaction::create([
                'product_id' => $adjustment->product_id,
                'transaction_type' => 'adjustment',
                'quantity' => abs($adjustment->difference),
                'source' => 'manual',
                'reference_id' => $adjustment->id,
                'notes' => "Penyesuaian stok: {$adjustment->reason}",
                'created_by' => $adjustment->adjusted_by
            ]);
        });
    }
}
