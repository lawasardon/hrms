<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{

    public function showAllEmployeeRates()
    {
        return view('payroll.rates');
    }

    public function showAllEmployeeRatesData()
    {
        $employeeRates = Payroll::with('employee')->get();
        return response()->json($employeeRates);
    }

    public function updateAquaPayroll(Request $request, $id)
    {
        $validatedData = $request->validate([
            'monthly_rate' => 'nullable|integer',
        ]);

        $monthlyRate = Payroll::findOrFail($id);
        $monthlyRate->update($validatedData);

        return response()->json(['message' => 'Updated successfully', 'rate' => $monthlyRate], 200);
    }

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
