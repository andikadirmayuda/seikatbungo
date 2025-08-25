<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{
    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        $this->updateProductSales($sale);
    }

    /**
     * Handle the Sale "deleted" event.
     */
    public function deleted(Sale $sale): void
    {
        $this->updateProductSales($sale, false);
    }

    /**
     * Update product sales count
     */
    private function updateProductSales(Sale $sale, bool $increment = true): void
    {
        foreach ($sale->items as $item) {
            if ($item->product) {
                $item->product->updateTotalSold($item->quantity, $increment);
            }
        }
    }
}
