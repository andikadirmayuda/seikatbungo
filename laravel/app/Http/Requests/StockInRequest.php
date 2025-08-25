<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // atau sesuaikan dengan logic autorisasi
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Pilih produk terlebih dahulu',
            'product_id.exists' => 'Produk tidak ditemukan',
            'quantity.required' => 'Jumlah stok harus diisi',
            'quantity.integer' => 'Jumlah stok harus berupa angka',
            'quantity.min' => 'Jumlah stok minimal 1',
            'notes.max' => 'Catatan maksimal 255 karakter'
        ];
    }
}
