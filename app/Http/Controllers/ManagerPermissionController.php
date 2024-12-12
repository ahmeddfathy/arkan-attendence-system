<?php

namespace App\Http\Controllers;

use App\Models\PermissionRequest;
use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagerPermissionController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $permissions = PermissionRequest::with(['user'])
            ->whereDate('departure_time', $today)
            ->latest()
            ->get();

        return view('manager.permissions.index', compact('permissions'));
    }

    public function updateResponse(Request $request, PermissionRequest $permission)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'manager_response' => 'required|string|max:255'
        ]);

        $permission->update($validated);

        // Check if a violation needs to be created
        if (!$permission->returned_on_time) {
            Violation::updateOrCreate(
                [
                    'permission_id' => $permission->id,
                    'user_id' => $permission->user_id
                ],
                [
                    'reason' => 'Did not return at the specified time',
                    'manager_mistake' => $validated['status'] === 'approved'
                ]
            );
        }

        return redirect()->route('manager.permissions.index')
            ->with('success', 'Permission response updated successfully');
    }

    public function deleteResponse(PermissionRequest $permission)
    {
        $permission->update([
            'status' => 'pending',
            'manager_response' => null
        ]);

        // Delete associated violation if it exists
        Violation::where('permission_id', $permission->id)->delete();

        return redirect()->route('manager.permissions.index')
            ->with('success', 'Permission response deleted successfully');
    }
}
