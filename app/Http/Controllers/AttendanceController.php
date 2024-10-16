<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Models\Employee;
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

    public function attendanceShowUploadPage()
    {
        return view('attendance.upload');
    }

    public function attendanceUpload(Request $request)
    {
        $request->validate([
            'attendance_file' => 'required|mimes:xlsx,csv',
        ]);

        \DB::transaction(function () use ($request) {
            Excel::import(new AttendanceImport, $request->file('attendance_file'));
        });

        return response()->json(['message' => 'Attendance uploaded successfully']);
    }

    public function attendanceListAllEmployee()
    {
        return view('attendance.index');
    }

    public function attendanceListAllEmployeeData()
    {
        $allEmployee = Attendance::all();
        return response()->json($allEmployee);
    }

    public function attendanceListAqua()
    {
        return view('attendance.aqua.index');
    }

    public function attendanceListAquaData()
    {
        $aquaAttendance = Attendance::where('department', 'aqua')->get();
        return response()->json($aquaAttendance);
    }

    public function attendanceListLaminin()
    {
        return view('attendance.laminin.index');
    }

    public function attendanceListLamininData()
    {
        $lamininAttendance = Attendance::where('department', 'laminin')->get();
        return response()->json($lamininAttendance);
    }
}
