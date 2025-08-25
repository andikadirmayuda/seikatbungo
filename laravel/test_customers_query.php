<?php
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\PublicOrder;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing online customers query...\n";
    
    // Test the exact query from the controller
    $query = PublicOrder::select(
        'public_orders.customer_name',
        'public_orders.wa_number',
        DB::raw('COUNT(public_orders.id) as total_orders'),
        DB::raw('SUM(public_orders.amount_paid) as total_spent'),
        DB::raw('MAX(public_orders.created_at) as last_order_date'),
        DB::raw('MIN(public_orders.created_at) as first_order_date'),
        'customers.is_reseller',
        'customers.promo_discount'
    )
    ->leftJoin('customers', 'public_orders.wa_number', '=', 'customers.phone')
    ->whereNotNull('public_orders.customer_name')
    ->whereNotNull('public_orders.wa_number')
    ->groupBy(
        'public_orders.customer_name', 
        'public_orders.wa_number',
        'customers.is_reseller',
        'customers.promo_discount'
    );

    $result = $query->get();
    
    echo "Query executed successfully!\n";
    echo "Number of customers found: " . $result->count() . "\n";
    
    if ($result->count() > 0) {
        echo "First customer example:\n";
        $first = $result->first();
        echo "- Name: " . $first->customer_name . "\n";
        echo "- Phone: " . $first->wa_number . "\n";
        echo "- Total orders: " . $first->total_orders . "\n";
        echo "- Is reseller: " . ($first->is_reseller ? 'Yes' : 'No') . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
