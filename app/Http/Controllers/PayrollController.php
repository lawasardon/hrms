<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Deduction;
use Illuminate\Http\Request;

class PayrollController extends Controller
{

    public function showAllEmployeeRates()
    {
        return view('payroll.rates');
    }

    public function showAllEmployeeRatesData()
    {
        $employeeRates = Payroll::with('employee', 'deduction')->get()
            ->map(function ($payroll) {
                return [
                    'id' => $payroll->employee->id,
                    'id_number' => $payroll->employee->id_number,
                    'department_id' => $payroll->employee->department_id,
                    'name' => $payroll->employee->name,
                    'monthly_rate' => $payroll->monthly_rate,
                    'rate_per_day' => $payroll->rate_perday,
                    'sss' => $payroll->deduction[0]->sss ?? null,
                    'pag_ibig' => $payroll->deduction[0]->pag_ibig ?? null,
                    'phil_health' => $payroll->deduction[0]->phil_health ?? null,
                ];
            });
        return response()->json($employeeRates);
    }

    public function updateAquaPayroll(Request $request, $id)
    {
        $validatedData = $request->validate([
            'monthly_rate' => 'nullable|numeric',
            'sss' => 'nullable|numeric',
            'pag_ibig' => 'nullable|numeric',
            'phil_health' => 'nullable|numeric',
            'department_id' => 'nullable|integer',
        ]);

        $monthlyRate = Payroll::findOrFail($id);

        if (isset($validatedData['monthly_rate'])) {
            $monthlyRate->monthly_rate = $validatedData['monthly_rate'];

            $daysInMonth = 30;
            $weekdays = $daysInMonth - (4 * 2);

            $dailyRate = $validatedData['monthly_rate'] / $weekdays;

            $monthlyRate->rate_perday = round($dailyRate, 2);
        }

        $monthlyRate->save();

        $deductionData = [
            'sss' => $validatedData['sss'],
            'pag_ibig' => $validatedData['pag_ibig'],
            'phil_health' => $validatedData['phil_health'],
            'payroll_id' => $monthlyRate->id,
            'employee_id' => $monthlyRate->employee->id,
        ];

        Deduction::updateOrCreate(
            ['payroll_id' => $monthlyRate->id],
            $deductionData
        );

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
