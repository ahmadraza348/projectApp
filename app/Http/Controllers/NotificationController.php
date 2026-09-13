<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Latest notifications for the header bell dropdown.
     */
=======
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
>>>>>>> agents/bugfix-crud-operations-and-notifications
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
<<<<<<< HEAD
            ->limit(15)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'data'       => $n->data,
                'read_at'    => $n->read_at,
                'created_at' => $n->created_at?->diffForHumans(),
            ]);

        return response()->json([
            'notifications'  => $notifications,
            'unread_count'   => $request->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json([
=======
            ->limit(10)
            ->get()
            ->map(fn ($notification) => [
                'id' => $notification->id,
                'message' => $notification->data['message'] ?? 'New activity',
                'url' => $notification->data['url'] ?? null,
                'read' => $notification->read_at !== null,
                'created_at' => $notification->created_at?->diffForHumans(),
            ]);

        return response()->json([
            'notifications' => $notifications,
>>>>>>> agents/bugfix-crud-operations-and-notifications
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

<<<<<<< HEAD
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['unread_count' => 0]);
    }
}
=======
    public function markAsRead(Request $request, string $notification): JsonResponse
    {
        $request->user()->notifications()->whereKey($notification)->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
>>>>>>> agents/bugfix-crud-operations-and-notifications
