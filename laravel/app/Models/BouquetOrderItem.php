<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouquetOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'bouquet_order_id',
        'bouquet_id',
        'size_id',
        'quantity',
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(BouquetOrder::class, 'bouquet_order_id');
    }

    public function bouquet()
    {
        return $this->belongsTo(Bouquet::class);
    }

    public function size()
    {
        return $this->belongsTo(BouquetSize::class);
    }
}
