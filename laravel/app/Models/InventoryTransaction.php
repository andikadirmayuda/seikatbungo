<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'transaction_type',
        'quantity',
        'source',
        'reference_id',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'transaction_type' => 'string',
        'source' => 'string',
        'quantity' => 'integer',
    ];

    // Relasi
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Method untuk label transaksi
    public function getTransactionLabel(): string
    {
        $labels = [
            'stock_in' => 'Penambahan Stok',
            'stock_out' => 'Pengurangan Stok',
            'adjustment' => 'Penyesuaian Stok'
        ];

        return $labels[$this->transaction_type] ?? $this->transaction_type;
    }

    // Boot method untuk observer
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            $product = $transaction->product;
            
            // Update stok berdasarkan tipe transaksi
            if ($transaction->transaction_type === 'stock_in') {
                $product->increment('stock', $transaction->quantity);
            } elseif ($transaction->transaction_type === 'stock_out') {
                $product->decrement('stock', $transaction->quantity);
            }
            // Untuk adjustment, stok sudah diupdate melalui StockAdjustment
        });
    }
}
