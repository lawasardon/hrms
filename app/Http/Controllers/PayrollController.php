<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payroll;
use App\Mail\SalaryPaid;
use App\Models\Employee;
use App\Models\Deduction;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\EmployeeRates;
use Illuminate\Support\Facades\Mail;

class PayrollController extends Controller
{

    public function showAllEmployeeRates()
    {
        return view('payroll.rates');
    }

    public function showAllEmployeeRatesData()
    {
        $employeeRates = Employee::with('employeeRate', 'deduction')->get()
            ->map(function ($payroll) {
                return [
                    'id' => $payroll->id,
                    'id_number' => $payroll->id_number,
                    'department_id' => $payroll->department_id,
                    'name' => $payroll->name,
                    'monthly_rate' => $payroll->employeeRate->monthly_rate ?? null,
                    'rate_per_day' => $payroll->employeeRate->rate_perday ?? null,
                    'sss' => $payroll->deduction->sss ?? null,
                    'pag_ibig' => $payroll->deduction->pag_ibig ?? null,
                    'phil_health' => $payroll->deduction->phil_health ?? null,
                ];
            });
        return response()->json($employeeRates);
    }

    public function storeRateAndDeduction(Request $request)
    {
        $validatedData = $request->validate([
            'monthly_rate' => 'nullable|numeric',
            'sss' => 'nullable|numeric',
            'pag_ibig' => 'nullable|numeric',
            'phil_health' => 'nullable|numeric',
            'id_number' => 'required|integer',
            'employee_id' => 'required|integer',
        ]);

        $monthlyRate = EmployeeRates::updateOrCreate(
            [
                'id_number' => $validatedData['id_number'],
                'employee_id' => $validatedData['employee_id']
            ],
            [
                'monthly_rate' => $validatedData['monthly_rate'] ?? 0,
                'sss' => $validatedData['sss'] ?? 0,
                'pag_ibig' => $validatedData['pag_ibig'] ?? 0,
                'phil_health' => $validatedData['phil_health'] ?? 0,
            ]
        );

        if (isset($validatedData['monthly_rate'])) {
            $monthlyRate->monthly_rate = $validatedData['monthly_rate'];

            $daysInMonth = 30;
            $weekdays = $daysInMonth - (4 * 2);

            $dailyRate = $validatedData['monthly_rate'] / $weekdays;

            $monthlyRate->rate_perday = round($dailyRate, 2);
        }

        $monthlyRate->save();

        $deductionData = [
            'sss' => $validatedData['sss'] ?? 0,
            'pag_ibig' => $validatedData['pag_ibig'] ?? 0,
            'phil_health' => $validatedData['phil_health'] ?? 0,
            'employee_id' => $monthlyRate->employee->id,
        ];

        Deduction::updateOrCreate(
            ['id_number' => $monthlyRate->employee->id_number],
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

    public function aquaPayrollCalculation()
    {
        $aquaAttendance = Attendance::with([
            'employee' => function ($query) {
                $query->with(['employeeRate', 'deduction']);
            }
        ])
            ->where('department', 'aqua')
            ->get();

        $summarizedData = [];

        foreach ($aquaAttendance as $attendance) {
            $idNumber = $attendance->id_number;
            $date = Carbon::parse($attendance->date);

            $firstDayOfMonth = $date->copy()->startOfMonth();
            $lastDayOfMonth = $date->copy()->endOfMonth();
            $midMonth = $date->copy()->startOfMonth()->addDays(14);

            if ($date->between($firstDayOfMonth, $midMonth)) {
                $period = 'first_half';
                $periodStart = $firstDayOfMonth;
                $periodEnd = $midMonth;
            } elseif ($date->between($midMonth->addDay(), $lastDayOfMonth)) {
                $period = 'second_half';
                $periodStart = Carbon::parse($date)->startOfMonth()->addDays(15);
                $periodEnd = $lastDayOfMonth;
            } else {
                continue;
            }

            $durationString = sprintf(
                "%s %d - %s %d, %d",
                $periodStart->format('F'),
                $periodStart->day,
                $periodEnd->format('F'),
                $periodEnd->day,
                $periodEnd->year
            );

            $key = $idNumber . '_' . $period . '_' . $date->format('Y_m');

            if (!isset($summarizedData[$key])) {
                $employeeRate = EmployeeRates::where('id_number', $attendance->id_number)->first();
                $govDeduction = Deduction::where('id_number', $attendance->id_number)->first();
                $employeeId = Employee::where('id_number', $attendance->id_number)->first();

                $payrollStatus = Payroll::where('id_number', $attendance->id_number)
                    ->where('duration', $durationString)
                    ->first();

                $totalGovDeduction = 0;
                if ($govDeduction) {
                    $totalGovDeduction = ($govDeduction->sss + $govDeduction->pag_ibig + $govDeduction->phil_health) / 2;
                }

                $workingDays = Attendance::where('id_number', $attendance->id_number)
                    ->whereBetween('date', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')])
                    ->count();

                // $workingDays = 0;
                // $currentDate = $periodStart->copy();

                // while ($currentDate <= $periodEnd) {
                //     if ($currentDate->isWeekday()) {
                //         $workingDays++;
                //     }
                //     $currentDate->addDay();
                // }

                $summarizedData[$key] = [
                    'employee_id' => $employeeId ? $employeeId->id : null,
                    'id_number' => $idNumber,
                    'department_id' => $attendance->department === 'aqua' ? 1 : 2,
                    'name' => $attendance->name,
                    'monthly_rate' => $employeeRate ? $employeeRate->monthly_rate : 0,
                    'rate_perday' => $employeeRate ? $employeeRate->rate_perday : 0,
                    'total_working_days' => $workingDays,
                    'over_time' => 0,
                    'total_gov_deduction' => $totalGovDeduction,
                    'late' => 0,
                    'loan' => 0,
                    'salary' => 0,
                    'duration' => $durationString,
                    'status' => $payrollStatus ? $payrollStatus->status : 'pending',
                ];
            }

            if ($date->isWeekday()) {
                if ($attendance->time_in > '08:10:00') {
                    $lateMinutes = Carbon::parse($attendance->time_in)
                        ->diffInMinutes(Carbon::createFromTimeString('08:10:00'));
                    $summarizedData[$key]['late'] += $lateMinutes;
                }
            }
        }

        foreach ($summarizedData as &$data) {
            $baseSalary = $data['rate_perday'] * $data['total_working_days'];
            $lateDeduction = $data['late'] * 10;
            $govDeduction = $data['total_gov_deduction'];

            $overtimePay = 0;
            if ($data['over_time'] > 0) {
                $hourlyRate = $data['rate_perday'] / 8;
                $overtimeRate = $hourlyRate * 1.25;
                $overtimePay = $overtimeRate * $data['over_time'];
            }

            $data['salary'] = round($baseSalary + $overtimePay - $lateDeduction - $govDeduction, 2);
        }

        return response()->json(array_values($summarizedData));
    }

    public function aquaStorePayroll(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'id_number' => 'required|integer',
            'duration' => 'nullable|string',
            'salary' => 'required|numeric',
            'over_time' => 'nullable|numeric',
            'total_deduction' => 'nullable|numeric',
            'status' => 'required|string|in:pending,paid,hold',
        ]);

        try {
            $hourlyRate = $request->salary / 160;
            $overtimeEarnings = ($hourlyRate * 1.25) * ($request->over_time ?? 0);

            $payroll = Payroll::updateOrCreate(
                [
                    'duration' => $request->duration,
                    'id_number' => $request->id_number,
                ],
                [
                    'employee_id' => $request->employee_id,
                    'department_id' => $request->department_id,
                    'id_number' => $request->id_number,
                    'duration' => $request->duration,
                    'salary' => $request->salary,
                    'total_deduction' => $request->total_deduction ?? 0,
                    'status' => $request->status,
                    'over_time' => $overtimeEarnings,
                ]
            );

            $employee = Employee::findOrFail($request->employee_id);

            if ($request->status === 'paid') {
                Mail::to($employee->email)->send(new SalaryPaid($employee, $request->duration, $request->salary, $request->id_number));
            }

            return response()->json([
                'message' => 'Updated successfully',
                'payroll' => $payroll
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating payroll',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //LAMININ
    public function showLamininPayroll()
    {
        return view('payroll.laminin.index');
    }

    public function lamininShowEditModal($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return response()->json($payroll);
    }

    public function showLamininPayrollData()
    {
        $lamininPayroll = Payroll::where('department_id', 2)->with('employee')->get();

        return response()->json($lamininPayroll);
    }

    public function lamininPayrollCalculation()
    {
        $lamininAttendance = Attendance::with([
            'employee' => function ($query) {
                $query->with(['employeeRate', 'deduction']);
            }
        ])
            ->where('department', 'laminin')
            ->get();

        $summarizedData = [];

        foreach ($lamininAttendance as $attendance) {
            $idNumber = $attendance->id_number;
            $date = Carbon::parse($attendance->date);

            $firstDayOfMonth = $date->copy()->startOfMonth();
            $lastDayOfMonth = $date->copy()->endOfMonth();
            $midMonth = $date->copy()->startOfMonth()->addDays(14);

            if ($date->between($firstDayOfMonth, $midMonth)) {
                $period = 'first_half';
                $periodStart = $firstDayOfMonth;
                $periodEnd = $midMonth;
            } elseif ($date->between($midMonth->addDay(), $lastDayOfMonth)) {
                $period = 'second_half';
                $periodStart = Carbon::parse($date)->startOfMonth()->addDays(15);
                $periodEnd = $lastDayOfMonth;
            } else {
                continue;
            }

            $durationString = sprintf(
                "%s %d - %s %d, %d",
                $periodStart->format('F'),
                $periodStart->day,
                $periodEnd->format('F'),
                $periodEnd->day,
                $periodEnd->year
            );

            $key = $idNumber . '_' . $period . '_' . $date->format('Y_m');

            if (!isset($summarizedData[$key])) {
                $employeeRate = EmployeeRates::where('id_number', $attendance->id_number)->first();
                $govDeduction = Deduction::where('id_number', $attendance->id_number)->first();
                $employeeId = Employee::where('id_number', $attendance->id_number)->first();

                $payrollStatus = Payroll::where('id_number', $attendance->id_number)
                    ->where('duration', $durationString)
                    ->first();

                $totalGovDeduction = 0;
                if ($govDeduction) {
                    $totalGovDeduction = ($govDeduction->sss + $govDeduction->pag_ibig + $govDeduction->phil_health) / 2;
                }

                $workingDays = Attendance::where('id_number', $attendance->id_number)
                    ->whereBetween('date', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')])
                    ->count();

                // $workingDays = 0;
                // $currentDate = $periodStart->copy();

                // while ($currentDate <= $periodEnd) {
                //     if ($currentDate->isWeekday()) {
                //         $workingDays++;
                //     }
                //     $currentDate->addDay();
                // }

                $summarizedData[$key] = [
                    'employee_id' => $employeeId ? $employeeId->id : null,
                    'id_number' => $idNumber,
                    'department_id' => $attendance->department === 'laminin' ? 2 : 1,
                    'name' => $attendance->name,
                    'monthly_rate' => $employeeRate ? $employeeRate->monthly_rate : 0,
                    'rate_perday' => $employeeRate ? $employeeRate->rate_perday : 0,
                    'total_working_days' => $workingDays,
                    'over_time' => 0,
                    'total_gov_deduction' => $totalGovDeduction,
                    'late' => 0,
                    'loan' => 0,
                    'salary' => 0,
                    'duration' => $durationString,
                    'status' => $payrollStatus ? $payrollStatus->status : 'pending',
                ];
            }

            if ($date->isWeekday()) {
                if ($attendance->time_in > '08:10:00') {
                    $lateMinutes = Carbon::parse($attendance->time_in)
                        ->diffInMinutes(Carbon::createFromTimeString('08:10:00'));
                    $summarizedData[$key]['late'] += $lateMinutes;
                }
            }
        }

        foreach ($summarizedData as &$data) {
            $baseSalary = $data['rate_perday'] * $data['total_working_days'];
            $lateDeduction = $data['late'] * 10;
            $govDeduction = $data['total_gov_deduction'];

            $overtimePay = 0;
            if ($data['over_time'] > 0) {
                $hourlyRate = $data['rate_perday'] / 8;
                $overtimeRate = $hourlyRate * 1.25;
                $overtimePay = $overtimeRate * $data['over_time'];
            }

            $data['salary'] = round($baseSalary + $overtimePay - $lateDeduction - $govDeduction, 2);
        }

        return response()->json(array_values($summarizedData));
    }

    public function lamininStorePayroll(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'id_number' => 'required|integer',
            'duration' => 'nullable|string',
            'salary' => 'required|numeric',
            'over_time' => 'nullable|numeric',
            'total_deduction' => 'nullable|numeric',
            'status' => 'required|string|in:pending,paid,hold',
        ]);

        try {
            $hourlyRate = $request->salary / 160;
            $overtimeEarnings = ($hourlyRate * 1.25) * ($request->over_time ?? 0);

            $payroll = Payroll::updateOrCreate(
                [
                    'duration' => $request->duration,
                    'id_number' => $request->id_number,
                ],
                [
                    'employee_id' => $request->employee_id,
                    'department_id' => $request->department_id,
                    'id_number' => $request->id_number,
                    'duration' => $request->duration,
                    'salary' => $request->salary,
                    'total_deduction' => $request->total_deduction ?? 0,
                    'status' => $request->status,
                    'over_time' => $overtimeEarnings,
                ]
            );

            $employee = Employee::findOrFail($request->employee_id);

            if ($request->status === 'paid') {
                Mail::to($employee->email)->send(new SalaryPaid($employee, $request->duration, $request->salary, $request->id_number));
            }

            return response()->json([
                'message' => 'Updated successfully',
                'payroll' => $payroll
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating payroll',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
