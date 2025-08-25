<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomBouquet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'customer_name',
        'description',
        'total_price',
        'reference_image',
        'ribbon_color',
        'status',
        'special_instructions'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    protected $attributes = [
        'ribbon_color' => 'pink', // Default value untuk ribbon_color
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(CustomBouquetItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\PublicOrderItem::class);
    }

    // Helper methods
    public function calculateTotalPrice()
    {
        return $this->items->sum('subtotal');
    }

    public function getComponentsSummary()
    {
        return $this->items()
            ->with('product')
            ->get()
            ->map(function ($item) {
                return $item->product->name . ' ' . $item->quantity . ' ' .
                    ($item->price_type === 'per_tangkai' ? 'tangkai' : $item->price_type);
            })
            ->join(', ');
    }

    public function getComponentsArray()
    {
        return $this->items()
            ->with('product')
            ->get()
            ->map(function ($item) {
                return $item->product->name . ' ' . $item->quantity . ' ' .
                    ($item->price_type === 'per_tangkai' ? 'tangkai' : $item->price_type);
            })
            ->toArray();
    }

    public function canDelete()
    {
        return $this->status === 'draft' && !$this->orderItems()->exists();
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeInCart($query)
    {
        return $query->where('status', 'in_cart');
    }

    public function scopeOrdered($query)
    {
        return $query->where('status', 'ordered');
    }
}
