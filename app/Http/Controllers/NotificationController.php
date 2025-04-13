<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Notification;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
$this->middleware(['auth']);
    }

    public function index(): JsonResponse
    {
        try {
            $notifications = $this->notificationService->getAllNotifications(auth()->id());
            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch notifications'], 500);
        }
    }

    public function unread(): JsonResponse
    {
        try {
            $notifications = $this->notificationService->getUnreadNotifications(auth()->id());
            return response()->json($notifications);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch unread notifications'], 500);
        }
    }

    public function markAsRead($id): JsonResponse
    {
        try {
            $notification = Notification::findOrFail($id);
            
            if ($notification->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $notification = $this->notificationService->markAsRead($id);
            return response()->json([
                'message' => 'Notification marked as read',
                'notification' => $notification
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Notification not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to mark notification as read'], 500);
        }
    }

    public function markAllAsRead(): JsonResponse
    {
        try {
            $affected = Notification::where('user_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'message' => 'All notifications marked as read',
                'affected_count' => $affected
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to mark all notifications as read'], 500);
        }
    }
}