<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalePdfController extends Controller
{
    public function download($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);
        $pdf = Pdf::loadView('sales.receipt', compact('sale'));
        $filename = 'Struk-Penjualan-' . $sale->order_number . '.pdf';
        return $pdf->download($filename);
    }
}
