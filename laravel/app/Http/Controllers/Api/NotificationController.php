<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get JSON response with proper headers
     */
    protected function jsonResponse($data, $status = 200)
    {
        return response()->json($data, $status)
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'no-cache, private');
    }

    /**
     * Check if user is authenticated
     */
    protected function checkAuth()
    {
        if (!Auth::check()) {
            throw new \Illuminate\Auth\AuthenticationException('Unauthenticated.');
        }
    }

    /**
     * Get pending notifications untuk admin
     */
    public function getPendingNotifications()
    {
        try {
            $this->checkAuth();

            $notifications = PushNotificationService::getPendingNotifications();

            return $this->jsonResponse([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            Log::error('Error in getPendingNotifications: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Failed to fetch notifications'
            ], 500);
        }
    }

    /**
     * Mark notification sebagai delivered
     */
    public function markAsDelivered($notificationId)
    {
        try {
            $this->checkAuth();

            $success = PushNotificationService::markAsDelivered($notificationId);

            if (!$success) {
                return $this->jsonResponse([
                    'success' => false,
                    'error' => 'Failed to mark notification as delivered'
                ], 400);
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Notification marked as delivered'
            ]);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            Log::error('Error marking notification as delivered: ' . $e->getMessage(), [
                'notification_id' => $notificationId,
                'exception' => $e
            ]);
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Test push notification
     */
    public function testNotification()
    {
        try {
            $this->checkAuth();

            $testData = [
                'type' => 'online_order',
                'customer_name' => 'Test Customer',
                'total' => 150000,
                'public_code' => 'TEST001'
            ];

            $success = PushNotificationService::createNotification($testData);

            if (!$success) {
                return $this->jsonResponse([
                    'success' => false,
                    'error' => 'Failed to create test notification'
                ], 400);
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Test notification created successfully'
            ]);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            Log::error('Error creating test notification: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return $this->jsonResponse([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }
}
