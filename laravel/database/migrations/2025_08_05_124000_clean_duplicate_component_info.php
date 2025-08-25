<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PublicOrderItem;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean up duplicate component information in product_name
        $items = PublicOrderItem::where('item_type', 'custom_bouquet')
            ->where('product_name', 'LIKE', '%(Komponen:%')
            ->get();

        foreach ($items as $item) {
            $originalName = $item->product_name;

            // Remove the duplicate "(Komponen: ...)" part using regex
            $cleanedName = preg_replace('/\s*\(Komponen:[^)]*\)/', '', $originalName);

            if ($cleanedName !== $originalName) {
                $item->product_name = trim($cleanedName);
                $item->save();

                echo "Cleaned: {$originalName} -> {$item->product_name}\n";
            }
        }

        echo "Data cleaning completed!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
