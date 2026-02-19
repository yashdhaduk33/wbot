<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count (AJAX).
     */
    public function getUnreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get recent notifications for dropdown (AJAX).
     */
    public function getRecent()
    {
        $notifications = auth()->user()
            ->notifications()
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id'         => $notification->id,
                    'data'       => $notification->data,
                    'read_at'    => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        $unreadCount = auth()->user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount
        ]);
    }

    /**
     * Mark a specific notification as read (AJAX).
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Mark all notifications as read (AJAX).
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Remove a specific notification.
     */
    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->delete();
        }

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification deleted');
    }

    /**
     * Clear all notifications.
     */
    public function clearAll()
    {
        auth()->user()->notifications()->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications cleared');
    }
}