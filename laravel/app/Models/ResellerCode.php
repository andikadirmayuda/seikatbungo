<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ResellerCode extends Model
{
    protected $fillable = [
        'wa_number',
        'code',
        'is_used',
        'expires_at',
        'used_at',
        'used_for_order_id',
        'notes'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Generate kode reseller unik
     */
    public static function generateUniqueCode($length = 12)
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Buat kode reseller baru untuk customer
     */
    public static function createForCustomer($waNumber, $expiryHours = 24, $notes = null)
    {
        // Pastikan expiryHours adalah integer
        $expiryHours = (int) $expiryHours;
        
        return self::create([
            'wa_number' => $waNumber,
            'code' => self::generateUniqueCode(),
            'expires_at' => Carbon::now()->addHours($expiryHours),
            'notes' => $notes
        ]);
    }

    /**
     * Cek apakah kode valid untuk digunakan
     */
    public function isValid()
    {
        return !$this->is_used && $this->expires_at > Carbon::now();
    }

    /**
     * Tandai kode sebagai sudah digunakan
     */
    public function markAsUsed($orderId = null)
    {
        $this->update([
            'is_used' => true,
            'used_at' => Carbon::now(),
            'used_for_order_id' => $orderId
        ]);
    }

    /**
     * Scope untuk kode yang masih aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope untuk kode milik customer tertentu
     */
    public function scopeForCustomer($query, $waNumber)
    {
        return $query->where('wa_number', $waNumber);
    }

    /**
     * Relasi ke PublicOrder jika sudah digunakan
     */
    public function order()
    {
        return $this->belongsTo(PublicOrder::class, 'used_for_order_id');
    }

    /**
     * Validasi kode dan nomor WA
     */
    public static function validateCode($code, $waNumber)
    {
        $resellerCode = self::where('code', $code)
                           ->where('wa_number', $waNumber)
                           ->first();

        if (!$resellerCode) {
            return [
                'valid' => false,
                'message' => 'Kode reseller tidak valid atau nomor WhatsApp tidak cocok.'
            ];
        }

        if (!$resellerCode->isValid()) {
            if ($resellerCode->is_used) {
                return [
                    'valid' => false,
                    'message' => 'Kode reseller sudah pernah digunakan.'
                ];
            } else {
                return [
                    'valid' => false,
                    'message' => 'Kode reseller sudah kadaluarsa.'
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Kode reseller valid.',
            'code' => $resellerCode
        ];
    }
}
