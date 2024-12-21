<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRecordController extends Controller
{
    public function index(Request $request)
    {
        // Get employees with both ID and name for the datalist
        $employees = User::select('id', 'name', 'employee_id')
            ->orderBy('name')
            ->get();

        // Query builder for attendance records
        $query = AttendanceRecord::query()
            ->join('users', 'attendance_records.employee_id', '=', 'users.employee_id')
            ->select('attendance_records.*', 'users.name as employee_name');

        // Apply employee filter if provided
        if ($request->has('employee_filter') && !empty($request->employee_filter)) {
            $query->where('attendance_records.employee_id', $request->employee_filter);
        }

        // Get paginated results with appended query parameters
        $records = $query->orderBy('attendance_date', 'desc')
                        ->paginate(10)
                        ->appends($request->except('page'));

        // Get the selected employee name for displaying in input
        $selectedEmployeeName = '';
        if ($request->has('employee_filter') && !empty($request->employee_filter)) {
            $selectedEmployee = $employees->firstWhere('employee_id', $request->employee_filter);
            $selectedEmployeeName = $selectedEmployee ? $selectedEmployee->name : '';
        }

        return view('attendancesRecord.index', compact('records', 'employees', 'selectedEmployeeName'));
    }



    public function import(Request $request)
    {
        Excel::import(new AttendanceImport, $request->file('file'));
        return redirect()->route('attendance.index')->with('success', 'Records imported successfully');
    }


}
