<?php

namespace App\Http\Controllers;

use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get pending notifications untuk admin
     */
    public function getPendingNotifications()
    {
        try {
            $notifications = PushNotificationService::getPendingNotifications();
            return response()->json($notifications);
        } catch (\Exception $e) {
            Log::error('Error getting pending notifications', [
                'error' => $e->getMessage()
            ]);
            return response()->json([], 500);
        }
    }

    /**
     * Mark notification sebagai delivered
     */
    public function markAsDelivered($id)
    {
        try {
            PushNotificationService::markAsDelivered($id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as delivered', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Send test notification
     */
    public function testNotification()
    {
        try {
            // Create dummy order untuk test
            $testOrder = (object) [
                'id' => 999,
                'customer_name' => 'Test Customer',
                'total' => 150000,
                'public_code' => 'TEST123'
            ];

            // Send test notification
            $success = PushNotificationService::sendNewOrderNotification($testOrder);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test notification sent successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test notification'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error sending test notification', [
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}