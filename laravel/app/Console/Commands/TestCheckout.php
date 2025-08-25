<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PublicOrder;
use Illuminate\Support\Facades\DB;

class TestCheckout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test checkout process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Test create order
        DB::beginTransaction();
        try {
            $publicCode = bin2hex(random_bytes(8));
            
            $order = PublicOrder::create([
                'public_code' => $publicCode,
                'customer_name' => 'Test Customer CLI',
                'pickup_date' => '2025-07-26',
                'pickup_time' => '10:00',
                'delivery_method' => 'Ambil Langsung',
                'destination' => 'Test Address CLI',
                'wa_number' => '08123456789',
                'status' => 'pending',
                'payment_status' => 'waiting_confirmation',
            ]);

            $order->items()->create([
                'product_id' => 1,
                'product_name' => 'Test Product CLI',
                'price_type' => 'default',
                'unit_equivalent' => 1,
                'quantity' => 1,
                'price' => 50000,
            ]);

            DB::commit();
            
            $this->info("Order created successfully with code: $publicCode");
            $this->info("Order ID: " . $order->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Failed to create order: " . $e->getMessage());
        }
    }
}
