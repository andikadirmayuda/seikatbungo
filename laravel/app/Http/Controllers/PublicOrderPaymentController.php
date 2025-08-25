<?php

namespace App\Http\Controllers;


use App\Models\PublicOrder;
use App\Models\PublicOrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PublicOrderPaymentController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $order = PublicOrder::findOrFail($orderId);
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'public_order_id' => $order->id,
            'amount' => $request->amount,
            'note' => $request->note,
        ];
        if ($request->hasFile('proof')) {
            $data['proof'] = $request->file('proof')->store('public_order_payments', 'public');
        }
        PublicOrderPayment::create($data);

        // Update amount_paid di public_orders
        $order->amount_paid = $order->payments()->sum('amount');

        // Hitung total harga order dari item (sum quantity * price)
        $total = $order->items()->sum(DB::raw('quantity * price'));

        // Update status pembayaran dan status order
        if ($order->amount_paid >= $total && $total > 0) {
            $order->payment_status = 'paid';
            $order->status = 'processed'; // Ubah ke processed agar konsisten dengan flow
        } elseif ($order->amount_paid > 0) {
            $order->payment_status = 'waiting_verification'; // Menunggu verifikasi dari admin
        } else {
            $order->payment_status = 'waiting_confirmation';
        }

        $order->save();

        return back()->with('success', 'Pembayaran berhasil ditambahkan.');
    }
}
