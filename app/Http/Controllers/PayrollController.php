<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function showAquaPayroll()
    {
        return view('payroll.aqua.index');
    }

    public function aquaShowEditModal($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return response()->json($payroll);
    }

    public function showAquaPayrollData()
    {
        $aquaPayroll = Payroll::where('department_id', 1)->with('employee')->get();

        return response()->json($aquaPayroll);
    }



}
