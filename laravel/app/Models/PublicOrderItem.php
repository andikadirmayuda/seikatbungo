<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $public_order_id
 * @property int|null $product_id
 * @property string $product_name
 * @property string|null $price_type
 * @property int|null $unit_equivalent
 * @property int $quantity
 * @property float $price
 * @property string|null $item_type
 * @property int|null $custom_bouquet_id
 * @property string|null $reference_image
 * @property string|null $custom_instructions
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\PublicOrder $order
 * @property \App\Models\Product|null $product
 */
class PublicOrderItem extends Model
{
    protected $casts = [
        'details' => 'json',
        'metadata' => 'json'
    ];

    protected $fillable = [
        'public_order_id',
        'product_id',
        'product_name',
        'price_type',
        'unit_equivalent',
        'quantity',
        'price',
        'item_type',
        'bouquet_id',
        'custom_bouquet_id',
        'reference_image',
        'custom_instructions',
        'greeting_card',
        'details',
        'metadata'
    ];

    /**
     * Mengurangi stok untuk item ini
     */
    public function reduceStock()
    {
        \Illuminate\Support\Facades\Log::info('Reducing stock for item:', [
            'item_id' => $this->id,
            'type' => $this->item_type,
            'product_name' => $this->product_name
        ]);

        if ($this->item_type === 'bouquet') {
            return $this->reduceBouquetStock();
        }

        // Dukung nilai lama 'custom' maupun nilai enum baru 'custom_bouquet'
        if ($this->item_type === 'custom' || $this->item_type === 'custom_bouquet') {
            return $this->reduceCustomBouquetStock();
        }

        return $this->reduceRegularProductStock();
    }

    /**
     * Mengurangi stok untuk bouquet regular
     */
    protected function reduceBouquetStock()
    {
        \Illuminate\Support\Facades\Log::info('Reducing bouquet stock:', [
            'item_id' => $this->id,
            'bouquet_id' => $this->bouquet_id,
            'price_type' => $this->price_type,
            'quantity' => $this->quantity
        ]);

        $components = $this->bouquetComponents();

        if ($components->isEmpty()) {
            \Illuminate\Support\Facades\Log::warning('No components found for bouquet', [
                'item_id' => $this->id,
                'bouquet_id' => $this->bouquet_id
            ]);
            throw new \Exception("Tidak ada komponen ditemukan untuk bouquet ini");
        }

        \Illuminate\Support\Facades\Log::info('Found bouquet components:', [
            'component_count' => $components->count(),
            'components' => $components->map(function ($c) {
                return [
                    'component_id' => $c->id,
                    'product_id' => $c->product_id,
                    'product_name' => $c->product->name ?? 'N/A',
                    'quantity' => $c->quantity,
                    'current_stock' => $c->product->current_stock ?? 0
                ];
            })
        ]);

        foreach ($components as $component) {
            if (!$component->product) {
                \Illuminate\Support\Facades\Log::warning('Component product not found', [
                    'component_id' => $component->id,
                    'product_id' => $component->product_id
                ]);
                continue;
            }

            $reduction = ($component->quantity ?? 1) * ($this->quantity ?? 1);

            \Illuminate\Support\Facades\Log::info('Calculating stock reduction:', [
                'component_id' => $component->id,
                'product_name' => $component->product->name,
                'component_quantity' => $component->quantity,
                'order_quantity' => $this->quantity,
                'total_reduction' => $reduction,
                'current_stock' => $component->product->current_stock
            ]);

            if ($component->product->current_stock < $reduction) {
                \Illuminate\Support\Facades\Log::error('Insufficient stock for component', [
                    'component_id' => $component->id,
                    'product_name' => $component->product->name,
                    'required' => $reduction,
                    'available' => $component->product->current_stock
                ]);
                throw new \Exception("Stok tidak mencukupi untuk komponen {$component->product->name}");
            }

            $component->product->decrement('current_stock', $reduction);

            // Catat pengurangan stok
            \App\Models\InventoryLog::create([
                'product_id' => $component->product->id,
                'qty' => -$reduction,
                'source' => 'public_order_bouquet',
                'reference_id' => $this->public_order_id,
                'notes' => "Pengurangan stok komponen bouquet: {$component->product->name}",
                'current_stock' => $component->product->current_stock
            ]);
        }

        return true;
    }

    /**
     * Mengurangi stok untuk custom bouquet
     */
    protected function reduceCustomBouquetStock()
    {
        // Ambil komponen dari metadata atau fallback ke details.components
        $customItems = json_decode($this->metadata ?? '[]', true);
        if (empty($customItems)) {
            $details = $this->details ?? [];
            if (is_array($details) && !empty($details['components'])) {
                $customItems = $details['components'];
            } elseif (is_object($details) && !empty($details->components)) {
                $customItems = $details->components;
            }
        }

        if (empty($customItems)) {
            throw new \Exception("Data komponen custom bouquet tidak ditemukan");
        }

        foreach ($customItems as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            if (!$product) {
                continue;
            }

            $price = $product->prices()->where('type', $item['price_type'])->first();
            $reduction = $item['quantity'] * ($price ? $price->unit_equivalent : 1);

            if ($product->current_stock < $reduction) {
                throw new \Exception("Stok tidak mencukupi untuk {$product->name}");
            }

            $product->decrement('current_stock', $reduction);

            // Catat pengurangan stok
            \App\Models\InventoryLog::create([
                'product_id' => $product->id,
                'qty' => -$reduction,
                'source' => 'public_order_custom',
                'reference_id' => $this->public_order_id,
                'notes' => "Pengurangan stok custom bouquet: {$product->name}",
                'current_stock' => $product->current_stock
            ]);
        }

        return true;
    }

    /**
     * Mengurangi stok untuk produk regular
     */
    protected function reduceRegularProductStock()
    {
        if (!$this->product) {
            throw new \Exception("Produk tidak ditemukan");
        }

        $reduction = $this->quantity * ($this->unit_equivalent ?? 1);

        if ($this->product->current_stock < $reduction) {
            throw new \Exception("Stok tidak mencukupi untuk {$this->product->name}");
        }

        $this->product->decrement('current_stock', $reduction);

        // Catat pengurangan stok
        \App\Models\InventoryLog::create([
            'product_id' => $this->product->id,
            'qty' => -$reduction,
            'source' => 'public_order_product',
            'reference_id' => $this->public_order_id,
            'notes' => "Pengurangan stok produk: {$this->product->name}",
            'current_stock' => $this->product->current_stock
        ]);

        return true;
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
            'harga_grosir' => 'Harga Grosir',
            'custom_ikat' => 'Custom Ikat',
            'custom_tangkai' => 'Custom Tangkai',
            'custom_khusus' => 'Custom Khusus'
        ];
        return $priceTypeLabels[$this->price_type] ?? $this->price_type;
    }

    /**
     * Get the bouquet associated with this item.
     */
    public function bouquet()
    {
        return $this->belongsTo(Bouquet::class, 'bouquet_id');
    }

    /**
     * Get the bouquet components if this item is a bouquet
     */
    public function bouquetComponents()
    {
        if ($this->item_type !== 'bouquet' || !$this->bouquet_id) {
            return collect(); // Return empty collection instead of null
        }

        // Get components based on size if available
        $query = $this->bouquet->components()->with('product');

        // Prioritaskan size_id dari details metadata jika ada
        $details = $this->details ?? [];
        $sizeIdFromDetails = is_array($details) ? ($details['size_id'] ?? null) : (is_object($details) ? ($details->size_id ?? null) : null);

        if ($sizeIdFromDetails) {
            $query->where('size_id', (int)$sizeIdFromDetails);
        } elseif ($this->price_type) {
            // Fallback: map price_type string ke size id jika ada aturan penamaan
            $normalized = strtolower(trim($this->price_type));
            $aliases = [
                's' => 'small',
                'm' => 'medium',
                'l' => 'large',
            ];
            $name = $aliases[$normalized] ?? $normalized;
            $sizeMap = [
                'small' => 1,
                'medium' => 2,
                'large' => 3,
            ];
            $sizeId = $sizeMap[$name] ?? null;
            if ($sizeId) {
                $query->where('size_id', $sizeId);
            }
        }

        $components = $query->get();

        // Log component retrieval
        \Illuminate\Support\Facades\Log::info('Retrieved bouquet components', [
            'bouquet_id' => $this->bouquet_id,
            'price_type' => $this->price_type,
            'component_count' => $components->count(),
            'components' => $components->map(function ($c) {
                return [
                    'product_id' => $c->product_id,
                    'product_name' => $c->product->name ?? 'N/A',
                    'quantity' => $c->quantity
                ];
            })
        ]);

        return $components;
    }

    /**
     * Check if this item requires stock reduction for its components
     */
    public function validateBouquetStock(): bool
    {
        if (!$this->needsComponentStockReduction()) {
            return true;
        }

        $components = $this->bouquetComponents();
        foreach ($components as $component) {
            if (!$component->product) {
                continue;
            }

            $componentReduction = ($component->quantity ?? 1) * ($this->quantity ?? 1);
            if ($component->product->current_stock < $componentReduction) {
                throw new \Exception("Stok tidak mencukupi untuk komponen {$component->product->name} pada bouquet");
            }
        }

        return true;
    }

    public function needsComponentStockReduction(): bool
    {
        $needs = $this->item_type === 'bouquet' && $this->bouquet_id;

        // Log for debugging
        \Illuminate\Support\Facades\Log::info('Checking if item needs component stock reduction', [
            'item_id' => $this->id,
            'item_type' => $this->item_type,
            'bouquet_id' => $this->bouquet_id,
            'needs_reduction' => $needs,
            'price_type' => $this->price_type
        ]);

        return $needs;
    }

    public function order()
    {
        return $this->belongsTo(PublicOrder::class, 'public_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customBouquet()
    {
        return $this->belongsTo(CustomBouquet::class, 'custom_bouquet_id');
    }
}
