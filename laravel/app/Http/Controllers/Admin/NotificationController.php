<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index');
    }

    public function getAll()
    {
        $notifications = Notification::orderByDesc('created_at')
            ->take(50)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notifikasi Baru',
                    'message' => $notification->data['body'] ?? '',
                    'icon' => $notification->data['icon'] ?? '🔔',
                    'url' => $notification->data['url'] ?? null,
                    'created_at' => $notification->created_at,
                    'data' => $notification->data
                ];
            });

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Notification not found'], 404);
    }

    public function markAllAsRead()
    {
        Notification::unread()->update([
            'read' => true,
            'read_at' => now()
        ]);
        return response()->json(['success' => true]);
    }
}
