<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;
use App\Models\AttendanceRecord;

class AttendanceRecordController extends Controller
{
    public function index()
    {
        $records = AttendanceRecord::all();
        return view('attendancesRecord.index', compact('records'));
    }

    public function import(Request $request)
    {
        Excel::import(new AttendanceImport, $request->file('file'));
        dd('imported successfully');
    }

    public function store(Request $request)
    {
        $fields = $request->input('fields');
        $filePath = session('attendance_file_path');
        $file = storage_path('app/' . $filePath);
        $data = Excel::toArray(new AttendanceImport, $file)[0];

        foreach ($data as $row) {
            $data_insert = [];
            foreach ($fields as $key => $value) {
                $data_insert[$value] = $row[$key];
            }
            AttendanceRecord::create($data_insert);
        }

        return redirect()->route('attendancesRecord.index')->with('success', 'تم حفظ بيانات الحضور بنجاح.');
    }
}
