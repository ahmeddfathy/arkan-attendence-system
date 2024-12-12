<?php

namespace App\Services;

use App\Models\PermissionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PermissionRequestService
{
    const MONTHLY_LIMIT_MINUTES = 180;

    public function getAllRequests()
    {
        $user = Auth::user();

        $requests = $user->role === 'manager'
            ? PermissionRequest::with('user')->latest()->paginate(10)
            : PermissionRequest::where('user_id', $user->id)->latest()->paginate(10);
    
        return [
            'requests' => $requests,
            'remainingMinutes' => $this->getRemainingMinutes($user->id)
        ];
    }

    public function getUserRequestsAndLimits()
    {
        return $this->getAllRequests();
    }

    public function createRequest(array $data)
    {
        $userId = Auth::id();


        $departureTime = Carbon::parse($data['departure_time']);
        $returnTime = Carbon::parse($data['return_time']);
        $durationMinutes = $departureTime->diffInMinutes($returnTime);


        $remainingMinutes = $this->getRemainingMinutes($userId);

        if ($durationMinutes > $remainingMinutes) {
            return [
                'success' => false,
                'message' => "لا يمكنك طلب إذن أكثر من {$remainingMinutes} دقيقة المتبقية."
            ];
        }

        PermissionRequest::create([
            'user_id' => $userId,
            'departure_time' => $data['departure_time'],
            'return_time' => $data['return_time'],
            'minutes_used' => $durationMinutes,
            'reason' => $data['reason'],
            'remaining_minutes' => $remainingMinutes - $durationMinutes, // الرصيد المتبقي بعد الطرح
            'status' => 'pending',
            'returned_on_time' => false,
        ]);

        return ['success' => true];
    }

    public function getRemainingMinutes(int $userId): int
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $usedMinutes = PermissionRequest::where('user_id', $userId)
            ->whereBetween('departure_time', [$startOfMonth, $endOfMonth])
            ->where('status', 'approved')
            ->sum('minutes_used');

        return max(0, self::MONTHLY_LIMIT_MINUTES - $usedMinutes);
    }

    public function updateRequest(PermissionRequest $request, array $data)
    {
        $userId = Auth::id();

        // Validate updated departure and return times
        $departureTime = Carbon::parse($data['departure_time']);
        $returnTime = Carbon::parse($data['return_time']);

        // Ensure returnTime is after departureTime
        if ($returnTime <= $departureTime) {
            return [
                'success' => false,
                'message' => 'Return time must be after departure time.'
            ];
        }

        // Calculate the new duration in minutes (always positive)
        $newDurationMinutes = $departureTime->diffInMinutes($returnTime);

        $currentUsedMinutes = $request->minutes_used;
        $additionalMinutes = max(0, $newDurationMinutes - $currentUsedMinutes);

        if ($additionalMinutes > 0 && !$this->canRequestPermission($userId, $additionalMinutes)) {
            return [
                'success' => false,
                'message' => 'The new duration would exceed your monthly permission limit.'
            ];
        }

        $request->update([
            'departure_time' => $data['departure_time'],
            'return_time' => $data['return_time'],
            'reason' => $data['reason'],
            'minutes_used' => $newDurationMinutes,
        ]);

        return ['success' => true];
    }

    public function deleteRequest(PermissionRequest $request)
    {
        $request->delete();

        return ['success' => true];
    }



    private function canRequestPermission(int $userId, int $requestedMinutes): bool
    {
        return $this->getRemainingMinutes($userId) >= $requestedMinutes;
    }

    public function updateStatus(PermissionRequest $request, array $data)
{

    $updateData = [
        'status' => $data['status'],
        'rejection_reason' => $data['status'] === 'rejected' ? $data['rejection_reason'] : null,
    ];


    if ($data['status'] === 'approved') {
        $departureTime = Carbon::parse($request->departure_time);
        $returnTime = Carbon::parse($request->return_time);
        $durationMinutes = $departureTime->diffInMinutes($returnTime);

        $updateData['minutes_used'] = $durationMinutes;
        $updateData['returned_on_time'] = $data['returned_on_time'] ?? null;
    }


    if (isset($data['minutes_used'])) {
        $updateData['minutes_used'] = $data['minutes_used'];
    }

    $request->update($updateData);

    return ['success' => true];
}
}
