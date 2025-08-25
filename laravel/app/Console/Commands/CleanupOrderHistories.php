<?php

namespace App\Console\Commands;

use App\Models\OrderHistory;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupOrderHistories extends Command
{
    protected $signature = 'orders:cleanup-histories {--days=365 : Number of days to keep histories}';
    protected $description = 'Clean up old order histories';    public function handle()
    {
        $daysToKeep = Setting::getValue('history_retention_days', $this->option('days'));
        $cutoffDate = Carbon::now()->subDays($daysToKeep);
        
        $count = OrderHistory::where('archived_at', '<', $cutoffDate)->delete();
        
        $this->info("Successfully deleted {$count} old order histories older than {$daysToKeep} days.");
    }
}
