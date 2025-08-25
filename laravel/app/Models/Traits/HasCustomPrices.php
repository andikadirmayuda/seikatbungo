<?php

namespace App\Models\Traits;

use App\Enums\CustomPriceType;
use Illuminate\Support\Facades\Log;

trait HasCustomPrices
{
    /**
     * Get available custom price types for the product
     *
     * @return array
     */
    public function getAvailableCustomPriceTypes()
    {
        return $this->prices()
            ->whereIn('type', ['custom_ikat', 'custom_tangkai', 'custom_khusus'])
            ->pluck('type')
            ->toArray();
    }

    /**
     * Get default custom price for the product
     *
     * @return \App\Models\ProductPrice|null
     */
    public function getDefaultCustomPrice()
    {
        $priceTypes = ['custom_ikat', 'custom_tangkai', 'custom_khusus'];

        foreach ($priceTypes as $type) {
            $price = $this->prices()->where('type', $type)->first();
            if ($price) {
                return $price;
            }
        }

        return null;
    }

    /**
     * Check if product has valid custom prices
     *
     * @return bool
     */
    public function hasValidCustomPrices()
    {
        $hasCustomPrices = $this->prices()
            ->whereIn('type', [
                CustomPriceType::CUSTOM_IKAT,
                CustomPriceType::CUSTOM_TANGKAI,
                CustomPriceType::CUSTOM_KHUSUS
            ])
            ->exists();

        if (!$hasCustomPrices) {
            Log::info('Product does not have valid custom prices', [
                'product_id' => $this->id,
                'product_name' => $this->name
            ]);
            return false;
        }

        return true;
    }
}
