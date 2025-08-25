<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicInvoice extends Model
{
    protected $fillable = [
        'order_id',
        'token',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function generateToken(): string
    {
        return md5(uniqid(rand(), true));
    }

    public function isValid(): bool
    {
        if ($this->expires_at === null) {
            return true;
        }
        return $this->expires_at->isFuture();
    }
}
