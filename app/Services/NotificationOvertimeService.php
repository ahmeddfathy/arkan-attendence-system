<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\OverTimeRequests;
use Carbon\Carbon;
class NotificationOvertimeService
{


public function createOvertimeRequestNotification(OverTimeRequests $request): void
{

    $overtimeDate = Carbon::parse($request->overtime_date);

    // Notify all managers
    User::where('role', 'manager')->each(function ($manager) use ($request, $overtimeDate) {
        Notification::create([
            'user_id' => $manager->id,
            'type' => 'new_overtime_request',
            'data' => [
                'message' => "{$request->user->name} has submitted an overtime request",
                'request_id' => $request->id,
                'date' => $overtimeDate->format('Y-m-d'),
            ],
            'related_id' => $request->id
        ]);
    });
}


    
    public function createStatusUpdateNotification(OverTimeRequests $request): void
    {
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'overtime_request_status_update',
            'data' => [
                'message' => "Your overtime request has been {$request->status}",
                'request_id' => $request->id,
                'status' => $request->status,
                'rejection_reason' => $request->rejection_reason,
            ],
            'related_id' => $request->id
        ]);
    }


    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->whereNull('read_at') // Only count unread notifications
            ->count();
    }


    public function getUserNotifications(User $user)
    {
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Show the most recent notifications first
            ->paginate(10); // Paginate to avoid loading too many notifications at once
    }


    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }
}
