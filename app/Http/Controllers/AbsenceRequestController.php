<?php

namespace App\Http\Controllers;

use App\Models\AbsenceRequest;
use App\Services\AbsenceRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AbsenceRequestController extends Controller
{
    protected $absenceRequestService;

    public function __construct(AbsenceRequestService $absenceRequestService)
    {
        $this->absenceRequestService = $absenceRequestService;
    }

    public function index()
{
    $user = Auth::user();

    if ($user->role === 'manager') {

        $requests = $this->absenceRequestService->getAllRequests();


        $users = User::select('id', 'name')->get();


        return view('absence-requests.index', compact('users', 'requests'));
    } elseif ($user->role === 'employee') {

        $requests = $this->absenceRequestService->getUserRequests();


        return view('absence-requests.index', compact('requests'));
    } else {

        return redirect()->route('welcome');
    }
}



public function store(Request $request)
{
    $user = Auth::user();


    if ($user->role !== 'employee' && $user->role !== 'manager') {
        return redirect()->route('welcome')->with('error', 'Unauthorized action.');
    }


    $validated = $request->validate([
        'absence_date' => 'required|date|after:today',
        'reason' => 'required|string|max:255',

        'user_id' => 'required_if:role,manager|exists:users,id|nullable'
    ]);


    if ($user->role === 'manager') {

        if ($request->input('user_id') && $request->input('user_id') !== $user->id) {

            $this->absenceRequestService->createRequestForUser($validated['user_id'], $validated);
        } else {

            $this->absenceRequestService->createRequest($validated);
        }
    } else {

        $this->absenceRequestService->createRequest($validated);
    }

    return redirect()->route('absence-requests.index')
        ->with('success', 'Absence request submitted successfully.');
}


    public function update(Request $request, AbsenceRequest $absenceRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'manager' && $user->id !== $absenceRequest->user_id) {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'absence_date' => 'required|date|after:today',
            'reason' => 'required|string|max:255'
        ]);

        $this->absenceRequestService->updateRequest($absenceRequest, $validated);

        return redirect()->route('absence-requests.index')
            ->with('success', 'Absence request updated successfully.');
    }

    public function destroy(AbsenceRequest $absenceRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'manager' && $user->id !== $absenceRequest->user_id) {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $this->absenceRequestService->deleteRequest($absenceRequest);

        return redirect()->route('absence-requests.index')
            ->with('success', 'Absence request deleted successfully.');
    }

    public function updateStatus(Request $request, AbsenceRequest $absenceRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'manager') {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255'
        ]);

        $this->absenceRequestService->updateStatus($absenceRequest, $validated);

        return redirect()->route('absence-requests.index')
            ->with('success', 'Request status updated successfully.');
    }

    public function modifyResponse(Request $request, $id)
    {
        $absenceRequest = AbsenceRequest::findOrFail($id);


        $absenceRequest->status = $request->status;
        $absenceRequest->rejection_reason = $request->rejection_reason;

        $absenceRequest->save();

        return redirect()->route('absence-requests.index')->with('success', 'Response updated successfully');
    }

    public function resetStatus(AbsenceRequest $absenceRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'manager') {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $this->absenceRequestService->resetStatus($absenceRequest);

        return redirect()->route('absence-requests.index')
            ->with('success', 'Request status reset to pending successfully.');
    }
}
