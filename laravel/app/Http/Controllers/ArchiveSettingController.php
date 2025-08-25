<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class ArchiveSettingController extends Controller
{
    public function index()
    {
        $currentPeriod = Setting::getValue('archive_period', 'monthly');
        return view('settings.archive', compact('currentPeriod'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'period' => 'required|in:daily,weekly,monthly',
        ]);

        Setting::setValue('archive_period', $request->period);

        // Jalankan archiving dengan periode baru
        $cutoffDate = match($request->period) {
            'daily' => Carbon::now()->subDay(),
            'weekly' => Carbon::now()->subWeek(),
            'monthly' => Carbon::now()->subMonth(),
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
            \App\Models\OrderHistory::create([
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

        return redirect()->route('settings.archive')
            ->with('success', "Berhasil mengubah periode arsip menjadi {$request->period} dan mengarsipkan " . $oldOrders->count() . " pesanan.");
    }
}
