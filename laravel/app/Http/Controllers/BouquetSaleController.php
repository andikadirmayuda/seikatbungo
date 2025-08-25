<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BouquetSaleController extends Controller
{
    public function index()
    {
        // Tampilkan daftar penjualan buket
        return view('bouquet.sales.index');
    }

    public function create()
    {
        // Tampilkan form tambah penjualan buket
        return view('bouquet.sales.create');
    }

    public function store(Request $request)
    {
        // Simpan penjualan buket baru
        // ...
        return redirect()->route('bouquet.sales.index');
    }

    public function show($id)
    {
        // Tampilkan detail penjualan buket
        return view('bouquet.sales.show', compact('id'));
    }

    public function edit($id)
    {
        // Tampilkan form edit penjualan buket
        return view('bouquet.sales.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Update penjualan buket
        // ...
        return redirect()->route('bouquet.sales.index');
    }

    public function destroy($id)
    {
        // Hapus penjualan buket
        // ...
        return redirect()->route('bouquet.sales.index');
    }
}
