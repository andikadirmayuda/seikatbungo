<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'order_time',
        'total',
        'subtotal',
        'payment_method',
        'public_code', // untuk public receipt
        'cash_given',
        'change',
        'wa_number',
        'deleted_by',
        'deletion_reason',
    ];

    protected $dates = ['deleted_at', 'order_time'];

    protected $casts = [
        'order_time' => 'datetime',
        'total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cash_given' => 'decimal:2',
        'change' => 'decimal:2',
    ];

    // Scope untuk data aktif (tidak terhapus)
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Scope untuk data terhapus
    public function scopeDeleted($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    // Relasi ke user yang menghapus
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
