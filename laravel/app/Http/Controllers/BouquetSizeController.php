<?php

namespace App\Http\Controllers;

use App\Models\BouquetSize;
use Illuminate\Http\Request;

class BouquetSizeController extends Controller
{
    public function index()
    {
        $sizes = BouquetSize::all();
        return view('bouquet-sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('bouquet-sizes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        BouquetSize::create($validated);

        return redirect()->route('bouquet-sizes.index')
            ->with('success', 'Bouquet size created successfully.');
    }

    public function edit(BouquetSize $bouquetSize)
    {
        return view('bouquet-sizes.edit', compact('bouquetSize'));
    }

    public function update(Request $request, BouquetSize $bouquetSize)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $bouquetSize->update($validated);

        return redirect()->route('bouquet-sizes.index')
            ->with('success', 'Bouquet size updated successfully.');
    }

    public function destroy(BouquetSize $bouquetSize)
    {
        $bouquetSize->delete();

        return redirect()->route('bouquet-sizes.index')
            ->with('success', 'Bouquet size deleted successfully.');
    }
}
