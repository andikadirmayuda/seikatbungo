<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // ...existing code...        $schedule->command('orders:archive')->daily();
        
        // Jalankan pembersihan riwayat sesuai pengaturan
        $cleanupPeriod = \App\Models\Setting::getValue('history_cleanup_period', 'monthly');
        if ($cleanupPeriod === 'biweekly') {
            $schedule->command('orders:cleanup-histories')->everyTwoWeeks();
        } else {
            $schedule->command('orders:cleanup-histories')->monthly();
        }
    }

    // ...existing methods...
}