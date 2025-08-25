<?php

namespace App\Http\Controllers;

use App\Models\PublicInvoice;
use Illuminate\Http\Request;

class PublicInvoiceController extends Controller
{
    public function show($token)
    {
        $publicInvoice = PublicInvoice::where('token', $token)
            ->firstOrFail();

        if (!$publicInvoice->isValid()) {
            abort(403, 'Invoice link has expired');
        }

        $order = $publicInvoice->order()->with(['customer', 'items.product'])->firstOrFail();
        
        return view('orders.public-invoice', compact('order'));
    }
}
