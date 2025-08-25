<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetTemplateItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'bouquet_id', 'product_id', 'quantity'
    ];

    public function bouquet()
    {
        return $this->belongsTo(Bouquet::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
