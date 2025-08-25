<?php

use App\Http\Controllers\Admin\AdminNotificationController;
use Illuminate\Support\Facades\Route;

// Notification Panel Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/api/admin/notifications', [AdminNotificationController::class, 'getAll'])->name('api.admin.notifications.all');
    Route::post('/api/admin/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('api.admin.notifications.read');
    Route::post('/api/admin/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('api.admin.notifications.mark-all-read');
});
