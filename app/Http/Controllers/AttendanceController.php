<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceTemplateExport;

class AttendanceController extends Controller
{
    public function attendanceList()
    {
        return view('attendance.index');
    }

    public function attendanceDownloadableTemplate()
    {
        return Excel::download(new AttendanceTemplateExport, 'attendance_template.xlsx');
    }

    public function attendanceUpload(Request $request)
    {
        $request->validate([
            'attendance_file' => 'required|mimes:xlsx,csv',
        ]);

        \DB::transaction(function () use ($request) {
            Excel::import(new AttendanceImport, $request->file('attendance_file'));
        });

        return back()->with('success', 'Attendance uploaded successfully.');
    }

}
