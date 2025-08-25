<?php

namespace App\Http\Controllers;

use App\Models\BouquetCategory;
use Illuminate\Http\Request;

class BouquetCategoryController extends Controller
{
    public function index()
    {
        $categories = BouquetCategory::orderBy('name')->paginate(15);
        return view('bouquet_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('bouquet_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:bouquet_categories,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        BouquetCategory::create($validated);
        return redirect()->route('bouquet-categories.index')->with('success', 'Kategori buket berhasil ditambahkan.');
    }

    public function edit(BouquetCategory $bouquet_category)
    {
        return view('bouquet_categories.edit', ['category' => $bouquet_category]);
    }

    public function update(Request $request, BouquetCategory $bouquet_category)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:bouquet_categories,code,' . $bouquet_category->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $bouquet_category->update($validated);
        return redirect()->route('bouquet-categories.index')->with('success', 'Kategori buket berhasil diupdate.');
    }

    public function destroy(BouquetCategory $bouquet_category)
    {
        $bouquet_category->delete();
        return redirect()->route('bouquet-categories.index')->with('success', 'Kategori buket berhasil dihapus.');
    }
}
