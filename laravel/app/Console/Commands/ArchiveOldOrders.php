<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ArchiveOldOrders extends Command
{
    protected $signature = 'orders:archive';
    protected $description = 'Archive old orders based on configured period';

    public function handle()
    {
        $period = Setting::getValue('archive_period', 'monthly');
        
        $cutoffDate = match($period) {
            'daily' => Carbon::now()->subDay(),
            'weekly' => Carbon::now()->subWeek(),
            'monthly' => Carbon::now()->subMonth(),
            default => Carbon::now()->subMonth(),
        };
        
        $oldOrders = Order::where('created_at', '<', $cutoffDate)
            ->with(['customer', 'items'])
            ->get();

        foreach ($oldOrders as $order) {
            $orderItems = $order->items->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? '[Deleted Product]',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                ];
            });

            // Create order history record
            OrderHistory::create([
                'order_number' => $order->order_number,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer ? $order->customer->name : '[Deleted Customer]',
                'customer_phone' => $order->customer ? $order->customer->phone : null,
                'customer_email' => $order->customer ? $order->customer->email : null,
                'status' => $order->status,
                'total' => $order->total,
                'down_payment' => $order->down_payment,
                'delivery_fee' => $order->delivery_fee,
                'delivery_method' => $order->delivery_method,
                'delivery_address' => $order->delivery_address,
                'pickup_date' => $order->pickup_date,
                'items_json' => json_encode($orderItems),
                'original_created_at' => $order->created_at,
                'original_updated_at' => $order->updated_at,
                'archived_at' => now(),
            ]);

            // Delete the original order (soft delete)
            $order->delete();
        }

        $this->info('Successfully archived ' . $oldOrders->count() . ' old orders.');
    }
}
