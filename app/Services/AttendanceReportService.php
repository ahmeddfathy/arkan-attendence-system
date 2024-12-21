<?php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class AttendanceReportService
{
    public function generatePDF($employee_id)
    {
        $user = User::with('attendanceRecords')->where('employee_id', $employee_id)->firstOrFail();


        $data = [
            'user' => $user,
            'attendanceRecords' => $user->attendanceRecords,
        ];

        $pdf = Pdf::loadView('pdf.attendance_report', $data);
        return $pdf->download("Attendance_Report_User_{$user->employee_id}.pdf");
    }
}
