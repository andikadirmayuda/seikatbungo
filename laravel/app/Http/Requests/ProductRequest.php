<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_unit' => ['required', 'in:tangkai,item'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],

            // Validate prices            'prices' => ['array'],
            'prices.*.type' => ['nullable', 'string', Rule::in($this->getPriceTypes())],
            'prices.*.price' => ['nullable', 'numeric', 'min:0'],
            'prices.*.unit_equivalent' => [
                'required_if:prices.*.type,custom_ikat',
                'nullable',
                'integer',
                Rule::when(fn($input) => $input->input('prices.*.type') === 'custom_ikat', ['in:5,10,20']),
                'min:1'
            ],
            'prices.*.is_default' => ['boolean'],

            // At least one price must be set if prices array exists
            'prices' => [
                'array',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !collect($value)->contains('price', '!=', null)) {
                        $fail('Setidaknya satu harga harus diisi.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'prices.*.price.required' => 'Harga harus diisi untuk setiap jenis',
            'prices.*.price.numeric' => 'Harga harus berupa angka',
            'prices.*.price.min' => 'Harga tidak boleh negatif',
            'prices.*.unit_equivalent.required' => 'Unit equivalent harus diisi',
            'prices.*.unit_equivalent.integer' => 'Unit equivalent harus berupa angka bulat',
            'prices.*.unit_equivalent.min' => 'Unit equivalent minimal 1',
            'prices.*.unit_equivalent.required_if' => 'Unit equivalent harus diisi untuk tipe Custom Ikat',
            'prices.*.unit_equivalent.in' => 'Unit equivalent untuk Custom Ikat hanya boleh 5, 10, atau 20',
        ];
    }

    public static function getPriceTypes(): array
    {
        return [
            'per_tangkai',
            'ikat_5',
            'ikat_10',
            'ikat_20',
            'reseller',
            'normal',
            'promo',
            'custom_ikat',
            'custom_tangkai',
            'custom_khusus'
        ];
    }

    public static function getDefaultUnitEquivalent(string $type): int
    {
        return match ($type) {
            'per_tangkai' => 1,
            'ikat_5' => 5,
            'ikat_10' => 10,
            'ikat_20' => 20,
            'custom_tangkai' => 1,  // Default untuk Custom Tangkai
            'custom_khusus' => 1,   // Default untuk Custom Khusus
            // custom_ikat tidak punya default karena bisa bervariasi (5/10/20)
            default => 1,
        };
    }
    protected function prepareForValidation()
    {
        // Get the selected default price type from the request
        $defaultPriceType = $this->input('default_price_type');

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'prices' => collect($this->prices ?? [])->map(function ($price) use ($defaultPriceType) {
                return array_merge($price, [
                    'is_default' => $defaultPriceType === $price['type'],
                    'unit_equivalent' => $price['unit_equivalent'] ?? self::getDefaultUnitEquivalent($price['type'])
                ]);
            })->toArray()
        ]);
    }
}
