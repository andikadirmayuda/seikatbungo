<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'name',
        'prefix',
        'next_number'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function generateProductCode()
    {
        $code = $this->prefix . str_pad($this->next_number, 3, '0', STR_PAD_LEFT);
        $this->increment('next_number');
        return $code;
    }

    // Add method to check if a prefix exists
    public static function findByPrefix($prefix)
    {
        return static::where('prefix', $prefix)->first();
    }
}
