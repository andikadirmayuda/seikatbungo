<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'minimum_spend',
        'maximum_discount',
        'usage_limit',
        'usage_count',
        'is_active',
        'start_date',
        'end_date',
        'event_name',
        'event_type',
        'first_purchase_only',
        'minimum_points',
        'member_level',
        'applied_to',
        'restrictions',
        'terms_and_conditions',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_spend' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'first_purchase_only' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'applied_to' => 'array',
        'restrictions' => 'array',
        'terms_and_conditions' => 'array',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($voucher) {
            $voucher->applied_to = [];
            $voucher->restrictions = [];
            $voucher->terms_and_conditions = [];
        });
    }

    // Voucher Types
    const TYPE_PERCENT = 'percent';
    const TYPE_NOMINAL = 'nominal';
    const TYPE_CASHBACK = 'cashback';
    const TYPE_SHIPPING = 'shipping';
    const TYPE_SEASONAL = 'seasonal';
    const TYPE_FIRST_PURCHASE = 'first_purchase';
    const TYPE_LOYALTY = 'loyalty';

    /**
     * Relationship with public orders
     */
    public function publicOrders()
    {
        return $this->hasMany(PublicOrder::class, 'voucher_code', 'code');
    }

    /**
     * Scope for active vouchers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Scope for available vouchers (not exceeded usage limit)
     */
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('usage_limit')
                ->orWhereRaw('usage_count < usage_limit');
        });
    }

    /**
     * Check if voucher is valid for use
     */
    public function isValid()
    {
        $valid = $this->is_active &&
            now()->between($this->start_date, $this->end_date) &&
            ($this->usage_limit === null || $this->usage_count < $this->usage_limit);

        Log::info('Voucher validity check:', [
            'code' => $this->code,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'usage_count' => $this->usage_count,
            'usage_limit' => $this->usage_limit,
            'is_valid' => $valid
        ]);

        return $valid;
    }

    /**
     * Get detailed status of the voucher
     */
    public function getStatus()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if (now()->lt($this->start_date)) {
            return 'pending';
        }

        if (now()->gt($this->end_date)) {
            return 'expired';
        }

        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            return 'exhausted';
        }

        return 'active';
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($subtotal, $shippingFee = 0)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($subtotal < $this->minimum_spend) {
            return 0;
        }

        switch ($this->type) {
            case self::TYPE_PERCENT:
                $discount = $subtotal * ($this->value / 100);
                return $this->maximum_discount
                    ? min($discount, $this->maximum_discount)
                    : $discount;

            case self::TYPE_NOMINAL:
                return min($this->value, $subtotal);

            case self::TYPE_CASHBACK:
                return min($this->value, $subtotal);

            case self::TYPE_SHIPPING:
                return min($this->value, $shippingFee);

            default:
                return min($this->value, $subtotal);
        }
    }

    /**
     * Check if voucher can be used by customer
     */
    public function canBeUsedBy($customer)
    {
        // First Purchase Check
        if ($this->first_purchase_only && $customer->orders()->count() > 0) {
            return false;
        }

        // Loyalty/Member Check
        if ($this->type === self::TYPE_LOYALTY) {
            if ($this->minimum_points && $customer->points < $this->minimum_points) {
                return false;
            }
            if ($this->member_level && $customer->level !== $this->member_level) {
                return false;
            }
        }

        return true;
    }

    /**
     * Record voucher usage
     */
    public function recordUsage($orderId)
    {
        $this->increment('usage_count');

        $applied = $this->getAppliedToAttribute($this->applied_to);
        $applied[] = [
            'order_id' => $orderId,
            'used_at' => now()->toDateTimeString()
        ];

        $this->forceFill(['applied_to' => $applied])->save();
    }

    /**
     * Format value for display
     */
    public function getFormattedValue()
    {
        switch ($this->type) {
            case self::TYPE_PERCENT:
                return $this->value . '%';
            case self::TYPE_NOMINAL:
            case self::TYPE_CASHBACK:
            case self::TYPE_SHIPPING:
                return 'Rp ' . number_format((float)$this->value, 0, ',', '.');
            default:
                return $this->value;
        }
    }

    /**
     * Get readable description of voucher type
     */
    /**
     * Get applied_to attribute
     */
    public function getAppliedToAttribute($value)
    {
        return is_array($value) ? $value : [];
    }

    /**
     * Get restrictions attribute
     */
    public function getRestrictionsAttribute($value)
    {
        return is_array($value) ? $value : [];
    }

    /**
     * Get terms_and_conditions attribute
     */
    public function getTermsAndConditionsAttribute($value)
    {
        return is_array($value) ? $value : [];
    }

    public function getTypeDescription()
    {
        switch ($this->type) {
            case self::TYPE_PERCENT:
                return 'Diskon Persentase';
            case self::TYPE_NOMINAL:
                return 'Diskon Nominal';
            case self::TYPE_CASHBACK:
                return 'Cashback';
            case self::TYPE_SHIPPING:
                return 'Potongan Ongkir';
            case self::TYPE_SEASONAL:
                return 'Voucher ' . ($this->event_name ?? 'Musiman');
            case self::TYPE_FIRST_PURCHASE:
                return 'Voucher Pembelian Pertama';
            case self::TYPE_LOYALTY:
                return 'Voucher Member';
            default:
                return 'Voucher';
        }
    }

    /**
     * Get minimum spend formatted
     */
    public function getFormattedMinimumSpend()
    {
        return 'Min. Belanja Rp ' . number_format(floatval($this->minimum_spend), 0, ',', '.');
    }

    public function checkMinimumSpend($total)
    {
        $minSpend = floatval($this->minimum_spend);
        $currentTotal = floatval($total);

        Log::info('Checking minimum spend:', [
            'voucher_code' => $this->code,
            'min_spend' => $minSpend,
            'total' => $currentTotal,
            'meets_requirement' => $currentTotal >= $minSpend
        ]);

        return $currentTotal >= $minSpend;
    }

    /**
     * Get usage info
     */
    public function getUsageInfo()
    {
        if ($this->usage_limit === null) {
            return 'Tidak ada batas penggunaan';
        }

        $remaining = $this->usage_limit - $this->usage_count;
        return "Tersisa {$remaining} dari {$this->usage_limit} kali penggunaan";
    }
}
