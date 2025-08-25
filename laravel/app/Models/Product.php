<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\HasCustomPrices;


class Product extends Model
{
    use HasFactory, SoftDeletes, HasCustomPrices;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'image',
        'base_unit',
        'current_stock',
        'min_stock',
        'total_sold',
        'is_active'
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'total_sold' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Relasi ke riwayat stok (InventoryLog)
     */
    public function histories()
    {
        return $this->hasMany(\App\Models\InventoryLog::class, 'product_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    /**
     * Update total sold count for the product
     *
     * @param int $quantity
     * @param bool $increment
     * @return void
     */
    public function updateTotalSold(int $quantity, bool $increment = true): void
    {
        if ($increment) {
            $this->increment('total_sold', $quantity);
        } else {
            $this->decrement('total_sold', $quantity);
        }
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function decrementStock($amount)
    {
        return $this->decrement('current_stock', $amount);
    }

    public function incrementStock($amount)
    {
        return $this->increment('current_stock', $amount);
    }

    public function hasEnoughStock($amount)
    {
        return $this->current_stock >= $amount;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        });
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function scopeNeedsRestock($query)
    {
        return $query->whereRaw('current_stock < min_stock');
    }

    public function getFormattedStockAttribute()
    {
        return number_format($this->current_stock) . ' ' . $this->base_unit;
    }

    public function getNeedsRestockAttribute()
    {
        return $this->current_stock < $this->min_stock;
    }

    // Relasi Inventaris
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function stockHolds()
    {
        return $this->hasMany(StockHold::class);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    // Method untuk menampilkan stok dengan satuan
    public function currentStock(): string
    {
        $activeHolds = $this->stockHolds()
            ->active()
            ->sum('quantity');

        $availableStock = $this->current_stock - $activeHolds;

        return number_format($availableStock) . ' ' . $this->base_unit .
            ($activeHolds > 0 ? " (Hold: {$activeHolds})" : '');
    }

    // Method untuk riwayat stok 30 hari terakhir
    public function stockHistory()
    {
        return $this->inventoryTransactions()
            ->with(['creator:id,name'])
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->get()
            ->map(function ($transaction) {
                return [
                    'date' => $transaction->created_at->format('Y-m-d H:i'),
                    'type' => $transaction->getTransactionLabel(),
                    'quantity' => $transaction->quantity,
                    'source' => ucfirst($transaction->source),
                    'notes' => $transaction->notes,
                    'by' => $transaction->creator->name
                ];
            });
    }

    /**
     * Add stock to product inventory
     */
    public function addStock(int $quantity, string $source, string $referenceId, ?string $notes = null): InventoryLog
    {
        $this->increment('current_stock', $quantity);

        return $this->inventoryLogs()->create([
            'qty' => $quantity,
            'source' => $source,
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);
    }

    /**
     * Reduce stock from product inventory
     */
    public function reduceStock(int $quantity, string $source, string $referenceId, ?string $notes = null): InventoryLog
    {
        $this->decrement('current_stock', $quantity);

        return $this->inventoryLogs()->create([
            'qty' => -$quantity,
            'source' => $source,
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);
    }

    /**
     * Adjust stock to specific amount
     */
    public function adjustStock(int $newQuantity, string $referenceId, ?string $notes = null): InventoryLog
    {
        $difference = $newQuantity - $this->current_stock;
        $this->update(['current_stock' => $newQuantity]);

        return $this->inventoryLogs()->create([
            'qty' => $difference,
            'source' => 'adjustment',
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);
    }
}
