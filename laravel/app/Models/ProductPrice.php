<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_id',
        'type',
        'price',
        'unit_equivalent',
        'is_default',
        'min_grosir_qty',
    ];


    protected $casts = [
        'price' => 'decimal:2',
        'unit_equivalent' => 'integer',
        'is_default' => 'boolean',
        'min_grosir_qty' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
