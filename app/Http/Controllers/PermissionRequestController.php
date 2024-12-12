<?php

namespace App\Http\Controllers;

use App\Models\PermissionRequest;
use App\Services\PermissionRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionRequestController extends Controller
{
    protected $permissionRequestService;

    public function __construct(PermissionRequestService $permissionRequestService)
    {
        $this->permissionRequestService = $permissionRequestService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'manager') {
            $data = $this->permissionRequestService->getAllRequests();
            
        } elseif ($user->role === 'employee') {
            $data = $this->permissionRequestService->getUserRequestsAndLimits();
        } else {
            return redirect()->route('welcome');
        }

        return view('permission-requests.index', $data);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'employee') {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'departure_time' => 'required|date|after:now',
            'return_time' => 'required|date|after:departure_time',
            'reason' => 'required|string|max:255'
        ]);

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';
        $validated['returned_on_time'] = false;
        $validated['minutes_used'] = 0;

        $result = $this->permissionRequestService->createRequest($validated);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->route('permission-requests.index')
            ->with('success', 'Permission request submitted successfully.');
    }

    public function update(Request $request, PermissionRequest $permissionRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'manager' && $user->id !== $permissionRequest->user_id) {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'departure_time' => 'required|date|after:now',
            'return_time' => 'required|date|after:departure_time',

            'reason' => 'required|string|max:255',
            'returned_on_time' => 'nullable|boolean',
            'minutes_used' => 'nullable|integer'
        ]);

        $result = $this->permissionRequestService->updateRequest($permissionRequest, $validated);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->route('permission-requests.index')
            ->with('success', 'Permission request updated successfully.');
    }

    public function destroy(PermissionRequest $permissionRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'manager' && $user->id !== $permissionRequest->user_id) {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $this->permissionRequestService->deleteRequest($permissionRequest);

        return redirect()->route('permission-requests.index')
            ->with('success', 'Permission request deleted successfully.');
    }



    public function updateStatus(Request $request, PermissionRequest $permissionRequest)
    {
        $user = Auth::user();

        if ($user->role !== 'manager') {
            return redirect()->route('welcome')->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:255',
            'returned_on_time' => 'nullable|boolean',
            'minutes_used' => 'nullable|integer',
        ]);


        $this->permissionRequestService->updateStatus($permissionRequest, $validated);

        return redirect()->route('permission-requests.index')
            ->with('success', 'Request status updated successfully.');
    }
}
