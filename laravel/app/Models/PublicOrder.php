<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $public_code
 * @property string $customer_name
 * @property string $pickup_date
 * @property string|null $pickup_time
 * @property string $delivery_method
 * @property string|null $destination
 * @property string|null $notes
 * @property string $status
 * @property string|null $payment_status
 * @property float|null $amount_paid
 * @property string|null $payment_proof
 * @property string|null $wa_number
 * @property string|null $packing_photo
 * @property array|null $packing_files
 * @property float $shipping_fee
 * @property bool $stock_holded
 * @property string|null $info
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property float $total
 * @property string $order_number
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PublicOrderItem[] $items
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PublicOrderPayment[] $payments
 */
class PublicOrder extends Model
{
    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_PACKING = 'packing';
    public const STATUS_READY = 'ready';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    // Payment status constants
    public const PAYMENT_WAITING_CONFIRMATION = 'waiting_confirmation';
    public const PAYMENT_READY_TO_PAY = 'ready_to_pay';
    public const PAYMENT_WAITING_PAYMENT = 'waiting_payment';
    public const PAYMENT_WAITING_VERIFICATION = 'waiting_verification';
    public const PAYMENT_DP_PAID = 'dp_paid';
    public const PAYMENT_PARTIAL_PAID = 'partial_paid';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_REJECTED = 'rejected';
    public const PAYMENT_CANCELLED = 'cancelled';

    /**
     * Get all available order statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Proses',
            self::STATUS_PROCESSED => 'Diproses',
            self::STATUS_PACKING => 'Dikemas',
            self::STATUS_READY => 'Siap',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    /**
     * Get all available payment statuses
     */
    public static function getPaymentStatuses(): array
    {
        return [
            self::PAYMENT_WAITING_CONFIRMATION => 'Menunggu Konfirmasi',
            self::PAYMENT_READY_TO_PAY => 'Siap Bayar',
            self::PAYMENT_WAITING_PAYMENT => 'Menunggu Pembayaran',
            self::PAYMENT_WAITING_VERIFICATION => 'Menunggu Verifikasi',
            self::PAYMENT_DP_PAID => 'DP Dibayar',
            self::PAYMENT_PARTIAL_PAID => 'Sebagian Dibayar',
            self::PAYMENT_PAID => 'Lunas',
            self::PAYMENT_REJECTED => 'Ditolak',
            self::PAYMENT_CANCELLED => 'Dibatalkan'
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return self::getPaymentStatuses()[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Check if order can transition to given status
     */
    public function canTransitionTo(string $newStatus): bool
    {
        if (in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED])) {
            return false; // Final states
        }

        $allowedTransitions = [
            self::STATUS_PENDING => [self::STATUS_PROCESSED, self::STATUS_CANCELLED],
            self::STATUS_PROCESSED => [self::STATUS_PACKING, self::STATUS_CANCELLED],
            self::STATUS_PACKING => [self::STATUS_READY, self::STATUS_CANCELLED],
            self::STATUS_READY => [self::STATUS_SHIPPED, self::STATUS_COMPLETED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED => [self::STATUS_COMPLETED, self::STATUS_CANCELLED]
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    /**
     * Check if this is an online order
     */
    public function isOnlineOrder()
    {
        // Pesanan dianggap online jika memiliki public_code dan wa_number
        return !empty($this->public_code) && !empty($this->wa_number);
    }

    protected $fillable = [
        'public_code',
        'customer_name',
        'receiver_name',
        'pickup_date',
        'pickup_time',
        'delivery_method',
        'destination',
        'notes',
        'status',
        'payment_status',
        'amount_paid',
        'payment_proof',
        'wa_number',
        'receiver_wa',
        'packing_photo',
        'packing_files',
        'shipping_fee',
        'stock_holded',
        'info',
    ];

    protected $casts = [
        'packing_files' => 'array',
    ];

    protected $appends = ['total', 'order_number'];

    public function items()
    {
        return $this->hasMany(PublicOrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PublicOrderPayment::class, 'public_order_id');
    }

    public function customBouquet()
    {
        return $this->hasOne(CustomBouquet::class);
    }

    // Calculate total from items
    public function getTotalAttribute()
    {
        // Load items relation if not already loaded
        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }

        $itemsTotal = $this->items->sum(function ($item) {
            return ($item->quantity ?? 0) * ($item->price ?? 0);
        });

        return $itemsTotal + ($this->shipping_fee ?? 0);
    }

    // Get order number from public_code or generate one
    public function getOrderNumberAttribute()
    {
        return $this->public_code ?? 'PO-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    // Get customer phone from wa_number
    public function getCustomerPhoneAttribute()
    {
        return $this->wa_number;
    }

    // Default delivery fee (can be customized based on business logic)
    public function getDeliveryFeeAttribute()
    {
        return 0; // You can add logic here based on delivery_method or destination
    }

    /**
     * Generate WhatsApp notification URL for employee group
     */
    public function getEmployeeGroupWhatsAppUrlAttribute()
    {
        $message = \App\Services\WhatsAppNotificationService::generateNewOrderMessage($this);
        return $message ? \App\Services\WhatsAppNotificationService::generateEmployeeGroupWhatsAppUrl($message) : null;
    }

    /**
     * Generate WhatsApp notification message for employees
     */
    public function getEmployeeNotificationMessageAttribute()
    {
        return \App\Services\WhatsAppNotificationService::generateNewOrderMessage($this);
    }

    /**
     * Check if order can be shared to employee group
     */
    public function canShareToEmployeeGroupAttribute()
    {
        return !empty($this->public_code) && $this->items->count() > 0;
    }

    /**
     * Check if payment status can transition to given status
     */
    public function canTransitionPaymentTo(string $newStatus): bool
    {
        if (in_array($this->payment_status, [self::PAYMENT_PAID, self::PAYMENT_CANCELLED])) {
            return false; // Final payment states
        }

        $allowedTransitions = [
            self::PAYMENT_WAITING_CONFIRMATION => [
                self::PAYMENT_READY_TO_PAY,
                self::PAYMENT_CANCELLED
            ],
            self::PAYMENT_READY_TO_PAY => [
                self::PAYMENT_WAITING_PAYMENT,
                self::PAYMENT_CANCELLED
            ],
            self::PAYMENT_WAITING_PAYMENT => [
                self::PAYMENT_WAITING_VERIFICATION,
                self::PAYMENT_CANCELLED
            ],
            self::PAYMENT_WAITING_VERIFICATION => [
                self::PAYMENT_DP_PAID,
                self::PAYMENT_PARTIAL_PAID,
                self::PAYMENT_PAID,
                self::PAYMENT_REJECTED,
                self::PAYMENT_CANCELLED
            ],
            self::PAYMENT_DP_PAID => [
                self::PAYMENT_PARTIAL_PAID,
                self::PAYMENT_PAID,
                self::PAYMENT_CANCELLED
            ],
            self::PAYMENT_PARTIAL_PAID => [
                self::PAYMENT_PAID,
                self::PAYMENT_CANCELLED
            ],
            self::PAYMENT_REJECTED => [
                self::PAYMENT_WAITING_PAYMENT,
                self::PAYMENT_CANCELLED
            ]
        ];

        return in_array($newStatus, $allowedTransitions[$this->payment_status] ?? []);
    }

    /**
     * Get remaining payment amount
     */
    public function getRemainingPaymentAttribute(): float
    {
        return $this->total - ($this->amount_paid ?? 0);
    }

    /**
     * Check if order is fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID ||
            ($this->amount_paid && $this->amount_paid >= $this->total);
    }

    /**
     * Check if order can be processed based on payment status
     */
    public function canBeProcessed(): bool
    {
        return in_array($this->payment_status, [
            self::PAYMENT_PAID,
            self::PAYMENT_DP_PAID,
            self::PAYMENT_PARTIAL_PAID
        ]);
    }

    /**
     * Check if order requires payment proof
     */
    public function requiresPaymentProof(): bool
    {
        return in_array($this->payment_status, [
            self::PAYMENT_DP_PAID,
            self::PAYMENT_PARTIAL_PAID,
            self::PAYMENT_PAID
        ]) && empty($this->payment_proof);
    }
}
