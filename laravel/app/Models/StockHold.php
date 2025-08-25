<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class StockHold extends Model
{    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'status'
    ];

    protected $casts = [
        'quantity' => 'integer'
    ];

    // Konstanta untuk status
    const STATUS_HELD = 'held';
    const STATUS_RELEASED = 'released';
    const STATUS_COMPLETED = 'completed';

    // Validasi status
    public static function getAllowedStatuses(): array
    {
        return [
            self::STATUS_HELD,
            self::STATUS_RELEASED,
            self::STATUS_COMPLETED
        ];
    }

    // Relasi
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scope untuk hold yang masih aktif
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_HELD);
    }

    // Accessor untuk durasi hold dalam jam (tanpa kolom released_at)
    public function getDurationAttribute(): float
    {
        $start = Carbon::parse($this->created_at);
        // Jika status sudah released, gunakan updated_at sebagai akhir; jika masih held, gunakan sekarang
        $end = $this->status === self::STATUS_HELD ? Carbon::now() : Carbon::parse($this->updated_at);
        return round($start->diffInHours($end, true), 2);
    }
}
