<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bouquet;
use App\Models\BouquetComponent;

class CleanupBouquetComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bouquet:cleanup-components {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup bouquet components that reference deleted products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Scanning for invalid bouquet components...');
        
        // Find components with deleted products
        $invalidComponents = BouquetComponent::whereDoesntHave('product')->get();
        
        if ($invalidComponents->isEmpty()) {
            $this->info('✅ No invalid components found. All components are valid.');
            return;
        }
        
        $this->warn("Found {$invalidComponents->count()} invalid components:");
        
        $groupedByBouquet = $invalidComponents->groupBy('bouquet_id');
        
        foreach ($groupedByBouquet as $bouquetId => $components) {
            $bouquet = Bouquet::find($bouquetId);
            $bouquetName = $bouquet ? $bouquet->name : "Unknown Bouquet (ID: $bouquetId)";
            
            $this->line("📦 {$bouquetName}:");
            foreach ($components as $component) {
                $size = $component->size ? $component->size->name : 'Unknown Size';
                $this->line("   - Component ID {$component->id}: Product ID {$component->product_id} (Size: {$size}, Qty: {$component->quantity})");
            }
        }
        
        if ($dryRun) {
            $this->info('🔍 Dry run completed. Use --dry-run=false to actually delete these components.');
            return;
        }
        
        if ($this->confirm('Do you want to delete these invalid components?', true)) {
            $deleted = BouquetComponent::whereDoesntHave('product')->delete();
            $this->info("✅ Successfully deleted {$deleted} invalid components.");
        } else {
            $this->info('Operation cancelled.');
        }
    }
}
