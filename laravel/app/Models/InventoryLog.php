<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    // Source constants
    const SOURCE_SALE = 'sale';
    const SOURCE_PUBLIC_ORDER_PRODUCT = 'public_order_product';
    const SOURCE_PUBLIC_ORDER_BOUQUET = 'public_order_bouquet';
    const SOURCE_PUBLIC_ORDER_CUSTOM = 'public_order_custom';
    const SOURCE_PUBLIC_ORDER_HOLD = 'public_order_hold';
    const SOURCE_PUBLIC_ORDER_BOUQUET_HOLD = 'public_order_bouquet_hold';
    const SOURCE_PUBLIC_ORDER_CUSTOM_HOLD = 'public_order_custom_hold';
    const SOURCE_PUBLIC_ORDER_CANCEL = 'public_order_cancel';
    const SOURCE_PURCHASE = 'purchase';
    const SOURCE_RETURN = 'return';
    const SOURCE_ADJUSTMENT = 'adjustment';

    protected $fillable = [
        'product_id',
        'qty',
        'source',
        'reference_id',
        'notes'
    ];

    protected $casts = [
        'qty' => 'integer'
    ];

    /**
     * Get the product associated with this inventory log.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
