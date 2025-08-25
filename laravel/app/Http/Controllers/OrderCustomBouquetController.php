<?php

namespace App\Http\Controllers;

use App\Models\CustomBouquet;
use App\Models\PublicOrderItem;
use Illuminate\Http\Request;

class OrderCustomBouquetController extends Controller
{
    public function update(Request $request, PublicOrderItem $orderItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ribbon_color' => 'required|string|in:pink,red,purple,gold,silver,white',
            'special_instructions' => 'nullable|string',
            'quantity' => 'required|integer|min:1'
        ]);

        // Update custom bouquet
        $orderItem->customBouquet->update([
            'name' => $request->name,
            'ribbon_color' => $request->ribbon_color,
            'special_instructions' => $request->special_instructions
        ]);

        // Update order item
        $orderItem->update([
            'quantity' => $request->quantity,
            'subtotal' => $orderItem->unit_price * $request->quantity
        ]);

        // Recalculate order total
        $orderItem->order->recalculateTotal();

        return redirect()->back()->with('success', 'Custom bouquet berhasil diperbarui');
    }
}
