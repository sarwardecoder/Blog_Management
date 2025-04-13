<?php

namespace App\Services;

use App\Events\NewNotification;
use App\Models\Notification;

class NotificationService
{
    public function create($userId, $type, $data)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'data' => $data
        ]);

        // Broadcast the notification
        broadcast(new NewNotification($notification))->toOthers();

        return $notification;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->markAsRead();
        return $notification;
    }

    public function getUnreadNotifications($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllNotifications($userId)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}