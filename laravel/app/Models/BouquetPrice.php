<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BouquetPrice extends Pivot
{
    protected $table = 'bouquet_prices';
    
    protected $fillable = [
        'bouquet_id',
        'size_id',
        'price'
    ];

    public function bouquet()
    {
        return $this->belongsTo(Bouquet::class);
    }

    public function size()
    {
        return $this->belongsTo(BouquetSize::class);
    }
}
