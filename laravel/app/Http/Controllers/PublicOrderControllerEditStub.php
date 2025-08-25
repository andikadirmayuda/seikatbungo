<?php

namespace App\Http\Controllers;

use App\Models\PublicOrder;
use Illuminate\Http\Request;

class PublicOrderController extends Controller
{
    // ...existing code...

    /**
     * Tampilkan form edit pesanan publik
     */
    public function edit($public_code)
    {
        $order = PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        // Batasi edit hanya jika status masih pending
        if ($order->status !== 'pending') {
            abort(403, 'Pesanan tidak dapat diedit.');
        }
        return view('public.edit_order', compact('order'));
    }

    /**
     * Update pesanan publik
     */
    public function update(Request $request, $public_code)
    {
        $order = PublicOrder::where('public_code', $public_code)->with('items')->firstOrFail();
        if ($order->status !== 'pending') {
            abort(403, 'Pesanan tidak dapat diedit.');
        }
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'pickup_date' => 'required|date',
            'pickup_time' => 'nullable',
            'delivery_method' => 'required|string',
            'destination' => 'nullable|string',
            'wa_number' => 'nullable|string',
        ]);
        $order->update($validated);
        return redirect()->route('public.order.invoice', ['public_code' => $order->public_code])
            ->with('success', 'Pesanan berhasil diperbarui.');
    }
}
