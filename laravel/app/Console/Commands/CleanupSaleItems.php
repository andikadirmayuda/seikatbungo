<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\SaleItem;

class CleanupSaleItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sale:cleanup-items {--dry-run : Show what would be updated without actually updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup sale items that reference deleted products by marking them as deleted items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Scanning for sale items with deleted products...');
        
        // Find sale items where product is null
        $invalidItems = SaleItem::with('sale')
            ->whereHas('sale', function($query) {
                // Only non-deleted sales
                $query->whereNull('deleted_at');
            })
            ->get()
            ->filter(function($item) {
                return $item->product === null;
            });
        
        if ($invalidItems->count() === 0) {
            $this->info('✅ No sale items with deleted products found. All items are valid.');
            return;
        }
        
        $this->info("Found {$invalidItems->count()} sale items with deleted products:");
        
        $table = [];
        foreach ($invalidItems as $item) {
            $table[] = [
                'Sale ID' => $item->sale_id,
                'Sale Number' => $item->sale->order_number ?? 'N/A',
                'Item ID' => $item->id,
                'Product ID' => $item->product_id,
                'Quantity' => $item->quantity,
                'Price' => 'Rp ' . number_format($item->price, 0, ',', '.'),
                'Subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.')
            ];
        }
        
        $this->table([
            'Sale ID', 'Sale Number', 'Item ID', 'Product ID', 'Quantity', 'Price', 'Subtotal'
        ], $table);
        
        if ($dryRun) {
            $this->warn('🔍 DRY RUN - No changes made. Run without --dry-run to update items.');
            return;
        }
        
        if (!$this->confirm('Do you want to mark these items as having deleted products?')) {
            $this->info('Operation cancelled.');
            return;
        }
        
        $updated = 0;
        foreach ($invalidItems as $item) {
            // Update the item to store original product name if available from item data
            $item->update([
                'product_name' => $item->product_name ?? "Produk Dihapus (ID: {$item->product_id})",
                'notes' => ($item->notes ? $item->notes . ' | ' : '') . 'Product was deleted after sale'
            ]);
            $updated++;
        }
        
        $this->info("✅ Updated {$updated} sale items to handle deleted products.");
        $this->info('💡 These items will now display as "Produk Dihapus" in reports and receipts.');
    }
}
