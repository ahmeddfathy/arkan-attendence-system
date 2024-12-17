<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\SalarySheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\AttendanceReportService;

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

        // استرجاع ملفات PDF الخاصة بالـ user
        $salaryFiles = SalarySheet::where('user_id', $user->id)->get();

        // Check the user's role and return the appropriate view
        if ($user->role == 'manager') {
            return view('dashboard', compact('totalEmployees', 'presentToday', 'checkedOutToday', 'attendanceRate'));
        } elseif ($user->role == 'employee') {
            return view('profile.dashboard-user', compact('salaryFiles'));  // تمرير ملفات المستخدم إلى الـ view
        }

        // Default view if no role is set
        return view('welcome');
    }

    public function generateAttendancePDF($userId, AttendanceReportService $reportService)
    {
        return $reportService->generatePDF($userId);
    }
}
