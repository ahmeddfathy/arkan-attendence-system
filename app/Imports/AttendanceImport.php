<?php
namespace App\Imports;

use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class AttendanceImport implements ToModel
{
    public function model(array $row)
    {
        if (empty($row[1]) || empty($row[2]) || empty($row[6])) {
            return null;
        }

        // Extract only the employee number from "name[number]" format
        $employeeNumber = $this->extractNumber($row[1]);

        // Format attendance date

        $attendanceDate = $this->formatDate($row[2]);

        // Entry and exit times
        $entryTime = $this->formatTime($row[6]);
        $exitTime = $this->formatTime($row[7]);

        return new AttendanceRecord([
            'user_id'  => null,
            'attendance_date'  => $attendanceDate,
            'day'              => $row[3] ?? null,
            'status'           => $row[4] ?? null,
            'shift'            => $row[5] ?? null,
            'shift_hours'      => isset($row[5]) ? (int)$row[6] : 0,
            'entry_time'       => $entryTime,
            'exit_time'        => $exitTime,
            'delay_minutes'    => isset($row[8]) ? (int)$row[7] : 0,
            'early_minutes'    => isset($row[9]) ? (int)$row[10] : 0,
            'working_hours'    => isset($row[10]) ? (int)$row[11] : 0,
            'overtime_hours'   => isset($row[11]) ? (int)$row[12] : 0,
            'penalty'          => $row[13] ?? null,
            'notes'            => $row[14] ?? null,
        ]);
    }

    // Helper to extract the number from "name[number]"
    private function extractNumber($string)
    {
        preg_match('/\[(\d+)\]/', $string, $matches);
        return $matches[1] ?? null; // Return the number if found, otherwise null
    }

    // Helper to format date from Excel serial number
    private function formatDate($excelDate)
    {
        if (!empty($excelDate) && is_numeric($excelDate)) {
            return Carbon::instance(
                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate)
            )->format('Y-m-d');
        }
        return null;
    }

    // Helper to format time from Excel
    private function formatTime($excelTime)
    {
        if (!empty($excelTime) && is_numeric($excelTime)) {
            return Carbon::instance(
                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelTime)
            )->format('H:i:s');
        }
        return null;
    }


    // Helper to format time from Excel

}
