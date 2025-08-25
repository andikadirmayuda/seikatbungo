<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Total orders: " . \App\Models\PublicOrder::count() . "\n";

$order = \App\Models\PublicOrder::with('items')->latest()->first();
if ($order) {
    echo "Order public code: " . $order->public_code . "\n";
    echo "Customer: " . $order->customer_name . "\n";
    echo "Items count: " . $order->items->count() . "\n";

    foreach ($order->items as $item) {
        echo "- Item: " . $item->product_name . "\n";
        echo "  Type: " . $item->item_type . "\n";
        echo "  Custom Bouquet ID: " . $item->custom_bouquet_id . "\n";
        echo "  Price: " . $item->price . "\n";
        echo "  Quantity: " . $item->quantity . "\n";
        echo "\n";
    }
}
