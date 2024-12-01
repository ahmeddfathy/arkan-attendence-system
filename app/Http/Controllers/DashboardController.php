<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();  // Get the authenticated user

        // Get total employees
        $totalEmployees = User::count();

        // Get present employees today (Check-in time within today)
        $presentToday = Attendance::whereDate('check_in_time', Carbon::today())->count();

        // Get employees who have checked out today (for leave)
        $checkedOutToday = Leave::whereDate('check_out_time', Carbon::today())->count();

        // Calculate attendance rate (present vs total employees)
        $attendanceRate = $totalEmployees > 0
                          ? ($presentToday / $totalEmployees) * 100
                          : 0;  // Prevent division by zero

        // Check the user's role and return the appropriate view
        if ($user->role == 'manager') {
            return view('dashboard', compact('totalEmployees', 'presentToday', 'checkedOutToday', 'attendanceRate'));
        } elseif ($user->role == 'employee') {
            return view('profile.dashboard-user');  // Employee dashboard view
        }

        // Default view if no role is set
        return view('welcome');
    }
}
