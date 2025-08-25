<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bouquet_id',
        'size_id',
        'product_id',
        'quantity',
    ];

    public function bouquet()
    {
        return $this->belongsTo(Bouquet::class);
    }

    public function size()
    {
        return $this->belongsTo(BouquetSize::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
