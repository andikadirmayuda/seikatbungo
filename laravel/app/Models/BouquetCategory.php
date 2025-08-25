<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetCategory extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name'];

    public function bouquets()
    {
        return $this->hasMany(Bouquet::class, 'category_id');
    }
}
