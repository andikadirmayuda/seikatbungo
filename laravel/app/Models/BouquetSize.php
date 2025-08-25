<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetSize extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function prices()
    {
        return $this->hasMany(BouquetPrice::class, 'size_id');
    }

    public function components()
    {
        return $this->hasMany(BouquetComponent::class, 'size_id');
    }

    public function bouquets()
    {
        return $this->belongsToMany(Bouquet::class, 'bouquet_prices')
                    ->using(BouquetPrice::class)
                    ->withPivot('price')
                    ->withTimestamps();
    }
}
