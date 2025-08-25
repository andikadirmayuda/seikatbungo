<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminNotificationController extends Controller
{
    /**
     * Display the notifications page
     */
    public function index()
    {
        return view('admin.notifications.index');
    }

    /**
     * Get all notifications for the admin
     */
    public function getAll()
    {
        $notifications = PushNotificationService::getPendingNotifications();
        return Response::json($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $success = PushNotificationService::markAsDelivered($id);
        return Response::json(['success' => $success]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $notifications = PushNotificationService::getPendingNotifications();
        $success = true;

        foreach ($notifications as $notification) {
            if (!PushNotificationService::markAsDelivered($notification['id'])) {
                $success = false;
            }
        }

        return Response::json(['success' => $success]);
    }
}
