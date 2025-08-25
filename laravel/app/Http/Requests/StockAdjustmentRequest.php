<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // atau sesuaikan dengan logic autorisasi
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:correction,damage,sample,other',
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Pilih produk terlebih dahulu',
            'product_id.exists' => 'Produk tidak ditemukan',
            'adjustment_type.required' => 'Tipe penyesuaian harus dipilih',
            'adjustment_type.in' => 'Tipe penyesuaian tidak valid',
            'new_quantity.required' => 'Jumlah stok baru harus diisi',
            'new_quantity.integer' => 'Jumlah stok harus berupa angka',
            'new_quantity.min' => 'Jumlah stok minimal 0',
            'reason.required' => 'Alasan penyesuaian harus diisi',
            'reason.max' => 'Alasan maksimal 255 karakter'
        ];
    }
}
