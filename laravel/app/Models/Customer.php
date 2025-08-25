<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Customer Model - Optimized for Online Customers
 * 
 * Model ini sekarang fokus pada customer yang berasal dari online orders
 * dan terintegrasi dengan WhatsApp untuk sistem reseller dan promo.
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'is_reseller',
        'promo_discount',
        'notes',
    ];

    protected $casts = [
        'is_reseller' => 'boolean',
        'promo_discount' => 'decimal:2',
    ];

    /**
     * Get the customer's full address
     */
    public function getFullAddressAttribute()
    {
        // Tidak ada field address lagi, return null
        return null;
    }

    /**
     * Get the formatted phone number for WhatsApp
     */
    public function getFormattedPhoneAttribute()
    {
        // Remove any non-numeric characters and format for WhatsApp
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        // Add country code if not present
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . ltrim($phone, '0');
        }
        
        return $phone;
    }

    /**
     * Get reseller status badge
     */
    public function getResellerBadgeAttribute()
    {
        if (!$this->is_reseller) {
            return null;
        }

        return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800'>
                    <i class='bi bi-star-fill mr-1'></i>
                    Reseller
                </span>";
    }

    /**
     * Get promo status badge
     */
    public function getPromoBadgeAttribute()
    {
        if (!$this->promo_discount) {
            return null;
        }

        $discount = (float) $this->promo_discount;
        return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800'>
                    <i class='bi bi-gift-fill mr-1'></i>
                    {$discount}% diskon
                </span>";
    }

    /**
     * Scope untuk customer reseller
     */
    public function scopeResellers($query)
    {
        return $query->where('is_reseller', true);
    }

    /**
     * Scope untuk customer dengan promo
     */
    public function scopeWithPromo($query)
    {
        return $query->whereNotNull('promo_discount')->where('promo_discount', '>', 0);
    }

    /**
     * Relasi ke public orders (online orders)
     */
    public function publicOrders()
    {
        return $this->hasMany(PublicOrder::class, 'wa_number', 'phone');
    }

    /**
     * Relasi ke reseller codes
     */
    public function resellerCodes()
    {
        return $this->hasMany(ResellerCode::class, 'wa_number', 'phone');
    }

    /**
     * Get active reseller codes
     */
    public function activeResellerCodes()
    {
        return $this->resellerCodes()->active();
    }

    /**
     * Check if customer has active reseller codes
     */
    public function hasActiveResellerCodes()
    {
        return $this->activeResellerCodes()->exists();
    }
}
