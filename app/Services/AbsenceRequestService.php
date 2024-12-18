<?php

namespace App\Services;

use App\Models\AbsenceRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AbsenceRequestService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getAllRequests()
    {
        $user = Auth::user();

        if ($user->role === 'manager') {

            return AbsenceRequest::with('user')
                ->latest()
                ->paginate(10);
        }


        return AbsenceRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }
    public function getUserRequests()
    {
        $user = Auth::user();

        if ($user->role === 'manager') {
            return AbsenceRequest::with('user')->latest()->paginate(10);
        }

        return AbsenceRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
    }

    public function createRequest(array $data)
    {
        $userId = Auth::id();
        $existingRequest = AbsenceRequest::where('user_id', $userId)
            ->where('absence_date', $data['absence_date'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()->withErrors(['absence_date' => 'You have already requested this day off.']);
        }

        $request = AbsenceRequest::create([
            'user_id' => $userId,
            'absence_date' => $data['absence_date'],
            'reason' => $data['reason'],
            'status' => 'pending'
        ]);

        // Send notification to managers
        $this->notificationService->createLeaveRequestNotification($request);

        return $request;
    }



    public function updateRequest(AbsenceRequest $request, array $data)
{

    $existingRequest = AbsenceRequest::where('user_id', $request->user_id)
        ->where('absence_date', $data['absence_date'])
        ->where('id', '!=', $request->id)
        ->first();

    if ($existingRequest) {
        return redirect()->back()->withErrors(['absence_date' => 'You have already requested this day off.']);
    }

    return $request->update([
        'absence_date' => $data['absence_date'],
        'reason' => $data['reason']
    ]);
}


    public function deleteRequest(AbsenceRequest $request)
    {
        return $request->delete();
    }

    public function updateStatus(AbsenceRequest $request, array $data)
    {
        $request->update([
            'status' => $data['status'],
            'rejection_reason' => $data['status'] == 'rejected' ? $data['rejection_reason'] : null
        ]);

        // Send notification to employee
        $this->notificationService->createStatusUpdateNotification($request);

        return $request;
    }


    public function resetStatus(AbsenceRequest $request)
    {
        return $request->update([
            'status' => 'pending',
            'rejection_reason' => null
        ]);
    }


    public function modifyResponse(AbsenceRequest $request, array $data)
    {

        return $request->update([
            'status' => $data['status'],
            'rejection_reason' => $data['status'] === 'rejected' ? $data['rejection_reason'] : null
        ]);
    }

    public function createRequestForUser(int $userId, array $data)
{
    return AbsenceRequest::create([
        'user_id' => $userId,
        'absence_date' => $data['absence_date'],
        'reason' => $data['reason'],
        'status' => 'pending'
    ]);
}



public function calculateAbsenceDays($userId)
{
    $startOfYear = Carbon::now()->startOfYear();
    $endOfYear = Carbon::now()->endOfYear();

    $absenceDays = AbsenceRequest::where('user_id', $userId)
        ->where('status', 'approved')
        ->whereBetween('absence_date', [$startOfYear, $endOfYear])
        ->count();

    return $absenceDays;
}



}
