<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function aquaLeaveList()
    {
        return view('leave.aqua.index');
    }

    public function aquaLeaveListData()
    {
        $aquaLeaveList = Leave::with('department')
            ->where('department_id', 1)
            ->get();

        return response()->json($aquaLeaveList);
    }

    public function aquaLeaveListUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string',
        ]);

        $leave = Leave::findOrFail($id);
        $leave->update($validatedData);

        return response()->json(['message' => 'Leave submitted successfully', 'employee' => $leave], 200);
    }


    public function leaveList()
    {
        return view('leave.employee.index');
    }

    // public function leaveListData()
    // {
    //     $employee = auth()->user()->employee;

    //     if ($employee) {
    //         $departmentId = $employee->department_id;

    //         $leave = Leave::with('department')
    //             ->where('department_id', $departmentId)
    //             ->where('user_id', auth()->id())
    //             ->get();

    //         return response()->json($leave);
    //     }

    //     return response()->json([]);
    // }

    public function leaveListData()
    {
        $leave = Leave::with('department')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($leave);
    }


    public function createLeave()
    {
        return view('leave.employee.create');
    }

    public function storeLeave(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => 'required|integer',
            'date_filed' => 'required|string',
            'name' => 'required|string',
            'date_start' => 'required|string',
            'date_end' => 'required|string',
            'type_of_day' => 'required|string',
            'type_of_leave' => 'required|string',
            'reason_to_leave' => 'required|string',
            'status' => 'required|string',
        ]);

        $validatedData['user_id'] = auth()->id();

        $leave = Leave::create($validatedData);

        return response()->json(['message' => 'Leave submitted successfully', 'employee' => $leave], 201);
    }
}
