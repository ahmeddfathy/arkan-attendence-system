<?php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class AttendanceReportService
{
    public function generatePDF($userId)
    {
        $user = User::with('attendanceRecords')->findOrFail($userId);

        $data = [
            'user' => $user,
            'attendanceRecords' => $user->attendanceRecords,
        ];

        $pdf = Pdf::loadView('pdf.attendance_report', $data);
        return $pdf->download("Attendance_Report_User_{$user->id}.pdf");
    }
}
