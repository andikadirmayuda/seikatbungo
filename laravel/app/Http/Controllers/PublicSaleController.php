<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class PublicSaleController extends Controller
{
    // Tampilkan struk penjualan publik berdasarkan kode unik
    public function show($public_code)
    {
        $sale = Sale::where('public_code', $public_code)->with('items.product')->firstOrFail();
        return view('sales.public-receipt', compact('sale'));
    }
}
