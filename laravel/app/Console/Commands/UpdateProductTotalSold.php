<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProductTotalSold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-total-sold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update total_sold count for all products based on completed sales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating product total sold counts...');

        // Reset all total_sold to 0
        Product::query()->update(['total_sold' => 0]);

        // Get total quantity sold for each product from completed sales
        $productSales = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('sale', function ($query) {
                $query->whereNull('deleted_at'); // Only count non-deleted sales
            })
            ->groupBy('product_id')
            ->get();

        $bar = $this->output->createProgressBar($productSales->count());
        $bar->start();

        foreach ($productSales as $sale) {
            if ($sale->product_id) {
                Product::where('id', $sale->product_id)
                    ->update(['total_sold' => $sale->total_quantity]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Product total sold counts have been updated successfully!');
    }
}
