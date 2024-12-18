<?php

namespace App\Services;

use App\Models\OverTimeRequests;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OverTimeRequestService
{
    protected $notificationService;

    public function __construct(NotificationOvertimeService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getAllRequests(): LengthAwarePaginator
    {
        return OverTimeRequests::with('user')
            ->latest()
            ->paginate(10);
    }

    public function getUserRequests(): LengthAwarePaginator
    {
        $user = Auth::user();
        $query = OverTimeRequests::query();

        if ($user->role === 'manager') {
            $query->with('user');
        } else {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->paginate(10);
    }

    public function createRequest(array $data): OverTimeRequests
    {
        return DB::transaction(function () use ($data) {
            $userId = $data['user_id'] ?? Auth::id();

            $this->validateOverTimeRequest($userId, $data['overtime_date']);

            $request = OverTimeRequests::create([
                'user_id' => $userId,
                'overtime_date' => $data['overtime_date'],
                'reason' => $data['reason'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => 'pending',
            ]);

            $this->notificationService->createOvertimeRequestNotification($request);

            return $request;
        });
    }





    public function deleteRequest(OverTimeRequests $request)
    {

        return DB::transaction(function () use ($request) {
            return $request->delete();
        });
    }



    public function updateStatus(OverTimeRequests $request, array $data): bool
    {
        return DB::transaction(function () use ($request, $data) {
            $updated = $request->update([
                'status' => $data['status'],
                'rejection_reason' => $data['status'] === 'rejected' ? $data['rejection_reason'] : null
            ]);

            if ($updated) {
                $this->notificationService->createStatusUpdateNotification($request);
            }

            return $updated;
        });
    }

    public function resetStatus(OverTimeRequests $request): bool
    {
        return $request->update([
            'status' => 'pending',
            'rejection_reason' => null
        ]);
    }

    public function modifyResponse(OverTimeRequests $request, array $data): bool
    {
        return $request->update([
            'status' => $data['status'],
            'rejection_reason' => $data['status'] === 'rejected' ? $data['rejection_reason'] : null
        ]);
    }

    public function calculateOvertimeHours(int $userId): float
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $overtimeRequests = OverTimeRequests::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereBetween('overtime_date', [$startOfYear, $endOfYear])
            ->get();

        return $overtimeRequests->sum(function ($request) {
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);
            return $startTime->diffInMinutes($endTime) / 60;
        });
    }

    protected function validateOverTimeRequest(int $userId, string $overtimeDate, ?int $excludeId = null): void
    {
        $query = OverTimeRequests::where('user_id', $userId)
            ->where('overtime_date', $overtimeDate);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            throw new \Exception('An overtime request already exists for this date.');
        }
    }
}
