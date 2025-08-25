<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PushNotificationService
{
    const CACHE_KEY_PREFIX = 'notifications:';
    const CACHE_TTL = 300; // 5 minutes
    const MAX_NOTIFICATION_AGE_DAYS = 30; // Hapus notifikasi lebih dari 30 hari

    /**
     * Clean up old notifications
     */
    public static function cleanupOldNotifications()
    {
        try {
            DB::table('notifications')
                ->where('created_at', '<', now()->subDays(self::MAX_NOTIFICATION_AGE_DAYS))
                ->delete();

            Cache::forget(self::CACHE_KEY_PREFIX . 'pending');
            return true;
        } catch (\Exception $e) {
            Log::error('Error cleaning up old notifications: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * Get pending notifications for admin
     */
    public static function getPendingNotifications()
    {
        try {
            $cacheKey = self::CACHE_KEY_PREFIX . 'pending';

            return Cache::remember($cacheKey, self::CACHE_TTL, function () {
                return DB::table('notifications')
                    ->where('delivered', false)
                    ->whereIn('type', ['online_order', 'status_update', 'payment_received'])
                    ->where('created_at', '>=', now()->subHours(24))
                    ->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get()
                    ->map(function ($notification) {
                        $data = json_decode($notification->data ?? '{}', true);
                        return [
                            'id' => $notification->id,
                            'message' => [
                                'title' => self::getNotificationTitle($notification->type),
                                'body' => $notification->message
                            ],
                            'created_at' => $notification->created_at,
                            'type' => $notification->type,
                            'data' => $data,
                            'url' => self::getNotificationUrl($notification->type, $data)
                        ];
                    })
                    ->toArray();
            });
        } catch (\Exception $e) {
            Log::error('Error getting pending notifications: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return [];
        }
    }

    private static function getNotificationTitle($type)
    {
        switch ($type) {
            case 'online_order':
                return '🌸 Pesanan Baru Masuk!';
            case 'status_update':
                return '🔔 Update Status Pesanan';
            case 'payment_received':
                return '💰 Pembayaran Diterima';
            default:
                return '🔔 Notifikasi Baru';
        }
    }

    private static function getNotificationUrl($type, $data)
    {
        switch ($type) {
            case 'online_order':
            case 'status_update':
                return isset($data['public_code']) ? '/admin/orders/' . $data['public_code'] : '/admin/orders';
            case 'payment_received':
                return isset($data['payment_id']) ? '/admin/payments/' . $data['payment_id'] : '/admin/payments';
            default:
                return '/admin';
        }
    }

    private static function validateNotificationData(array $data)
    {
        if (!isset($data['type'])) {
            Log::warning('Missing notification type', ['data' => $data]);
            return false;
        }

        switch ($data['type']) {
            case 'online_order':
                return self::validateOnlineOrderData($data);
            case 'status_update':
                return self::validateStatusUpdateData($data);
            case 'payment_received':
                return self::validatePaymentData($data);
            default:
                Log::warning('Invalid notification type', ['type' => $data['type']]);
                return false;
        }
    }

    private static function validateOnlineOrderData(array $data)
    {
        $required = ['customer_name', 'public_code', 'total'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                Log::warning('Missing or empty required field for online order', [
                    'field' => $field,
                    'data' => $data
                ]);
                return false;
            }
        }

        if (!is_numeric($data['total']) || $data['total'] < 0) {
            Log::warning('Invalid total amount', ['total' => $data['total']]);
            return false;
        }

        // Validate public_code format (contoh: SB001)
        if (!preg_match('/^[A-Z]{3}\d{3,}$/', $data['public_code'])) {
            Log::warning('Invalid public_code format', ['public_code' => $data['public_code']]);
            return false;
        }

        return true;
    }

    private static function validateStatusUpdateData(array $data)
    {
        $required = ['public_code', 'status', 'old_status'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                Log::warning('Missing or empty required field for status update', [
                    'field' => $field,
                    'data' => $data
                ]);
                return false;
            }
        }

        // Validate status values
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled', 'delivered'];
        if (!in_array($data['status'], $validStatuses) || !in_array($data['old_status'], $validStatuses)) {
            Log::warning('Invalid status value', [
                'status' => $data['status'],
                'old_status' => $data['old_status']
            ]);
            return false;
        }

        // Validate public_code format
        if (!preg_match('/^[A-Z]{3}\d{3,}$/', $data['public_code'])) {
            Log::warning('Invalid public_code format', ['public_code' => $data['public_code']]);
            return false;
        }

        // Prevent same status updates
        if ($data['status'] === $data['old_status']) {
            Log::info('Skipping notification for same status', ['status' => $data['status']]);
            return false;
        }

        return true;
    }

    private static function validatePaymentData(array $data)
    {
        $required = ['payment_id', 'amount', 'public_code'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                Log::warning('Missing or empty required field for payment', [
                    'field' => $field,
                    'data' => $data
                ]);
                return false;
            }
        }

        // Validate amount
        if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            Log::warning('Invalid payment amount', ['amount' => $data['amount']]);
            return false;
        }

        // Validate public_code format
        if (!preg_match('/^[A-Z]{3}\d{3,}$/', $data['public_code'])) {
            Log::warning('Invalid public_code format', ['public_code' => $data['public_code']]);
            return false;
        }

        // Validate payment_id format (numeric)
        if (!is_numeric($data['payment_id']) || $data['payment_id'] <= 0) {
            Log::warning('Invalid payment_id', ['payment_id' => $data['payment_id']]);
            return false;
        }

        return true;
    }

    /**
     * Mark notification as delivered
     */
    public static function markAsDelivered($notificationId)
    {
        try {
            DB::beginTransaction();

            $updated = DB::table('notifications')
                ->where('id', $notificationId)
                ->where('delivered', false)  // Only update if not already delivered
                ->update([
                    'delivered' => true,
                    'updated_at' => now()
                ]);

            if ($updated) {
                // Clear the pending notifications cache
                Cache::forget(self::CACHE_KEY_PREFIX . 'pending');
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error marking notification as delivered: ' . $e->getMessage(), [
                'notification_id' => $notificationId,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * Create a new notification
     */
    public static function createNotification(array $data)
    {
        try {
            DB::beginTransaction();

            // Validate based on notification type
            if (!self::validateNotificationData($data)) {
                DB::rollBack();
                return false;
            }

            // Check for duplicate within last 5 minutes
            $duplicate = DB::table('notifications')
                ->where('type', $data['type'])
                ->where('data', json_encode($data))
                ->where('created_at', '>=', now()->subMinutes(5))
                ->exists();

            if ($duplicate) {
                DB::rollBack();
                Log::info('Duplicate notification prevented', ['data' => $data]);
                return true;
            }

            $now = now();
            $id = DB::table('notifications')->insertGetId([
                'type' => $data['type'],
                'message' => self::generateMessage($data),
                'data' => json_encode($data),
                'delivered' => false,
                'created_at' => $now,
                'updated_at' => $now
            ]);

            if ($id) {
                // Clear the pending notifications cache
                Cache::forget(self::CACHE_KEY_PREFIX . 'pending');
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating notification: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * Send status update notification
     */
    public static function sendStatusUpdateNotification($orderData)
    {
        try {
            $notificationData = [
                'type' => 'status_update',
                'public_code' => $orderData['public_code'],
                'status' => $orderData['status'],
                'old_status' => $orderData['old_status']
            ];

            return self::createNotification($notificationData);
        } catch (\Exception $e) {
            Log::error('Error sending status update notification: ' . $e->getMessage(), [
                'order_data' => $orderData,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * Send new order notification
     */
    public static function sendNewOrderNotification($orderData)
    {
        try {
            $notificationData = [
                'type' => 'online_order',
                'customer_name' => $orderData['customer_name'] ?? 'Pelanggan',
                'public_code' => $orderData['public_code'],
                'total' => $orderData['total']
            ];

            return self::createNotification($notificationData);
        } catch (\Exception $e) {
            Log::error('Error sending new order notification: ' . $e->getMessage(), [
                'order_data' => $orderData,
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * Generate notification message based on data
     */
    private static function generateMessage(array $data): string
    {
        try {
            if (!self::validateNotificationData($data)) {
                return 'Invalid notification data';
            }

            switch ($data['type']) {
                case 'online_order':
                    return sprintf(
                        '%s telah membuat pesanan baru (Kode: %s) - Total: Rp%s',
                        htmlspecialchars($data['customer_name']),
                        htmlspecialchars($data['public_code']),
                        number_format((float)$data['total'], 0, ',', '.')
                    );

                case 'status_update':
                    return sprintf(
                        'Status pesanan %s diubah dari %s menjadi %s',
                        htmlspecialchars($data['public_code']),
                        self::formatStatus($data['old_status']),
                        self::formatStatus($data['status'])
                    );

                case 'payment_received':
                    return sprintf(
                        'Pembayaran diterima untuk pesanan %s - Rp%s',
                        htmlspecialchars($data['public_code']),
                        number_format((float)$data['amount'], 0, ',', '.')
                    );

                default:
                    return 'Notifikasi baru diterima';
            }
        } catch (\Exception $e) {
            Log::error('Error generating notification message: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            return 'Error generating notification message';
        }
    }

    private static function formatStatus(string $status): string
    {
        $statusMap = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'delivered' => 'Dikirim'
        ];

        return htmlspecialchars($statusMap[$status] ?? ucfirst($status));
    }
}
