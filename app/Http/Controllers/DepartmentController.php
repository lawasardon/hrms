<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\EmployeeAccountCreated;
use Illuminate\Support\Facades\Mail;

class DepartmentController extends Controller
{
    public function showAquaEmployeeList()
    {
        return view('department.aqua.index');
    }

    public function aquaAddEmployee()
    {
        return view('department.aqua.add');
    }

    public function aquaStoreEmployee(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:employee,email',
            'address' => 'required|string|max:255',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'birthday' => 'required|date',
            'religion' => 'required|string|max:255',
        ]);

        $year = date('y');
        $lastId = Employee::where('id_number', 'like', "$year%")->max('id_number');
        $increment = $lastId ? intval(substr($lastId, 2)) + 1 : 1;
        $idNumber = sprintf('%s%04d', $year, $increment);

        $password = Str::random(10);

        $employee = Employee::create(array_merge($validatedData, ['id_number' => $idNumber]));

        // $payroll = Payroll::create([
        //     'department_id' => $validatedData['department_id'],
        //     'employee_id' => $employee->id,
        //     'id_number' => $idNumber,
        // ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($password),
        ]);

        $employee->user()->associate($user);
        $employee->save();

        $user->assignRole('employee');

        Mail::to($validatedData['email'])->send(new EmployeeAccountCreated($employee, $password, $idNumber));

        return response()->json(['message' => 'Employee added successfully', 'employee' => $employee], 201);
    }

    public function aquaEmployeeListData()
    {
        $employeeList = Employee::with('department')->where('department_id', 1)->get();

        return response()->json($employeeList);
    }


    public function showLamininEmployeeList()
    {
        return view('department.laminin.index');
    }

    public function lamininAddEmployee()
    {
        return view('department.laminin.add');
    }

    public function lamininStoreEmployee(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:employee,email',
            'address' => 'required|string|max:255',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'birthday' => 'required|date',
            'religion' => 'required|string|max:255',
        ]);

        $password = Str::random(10);

        $employee = Employee::create($validatedData);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($password),
        ]);

        $employee->user()->associate($user);
        $employee->save();

        $user->assignRole('employee');

        Mail::to($validatedData['email'])->send(new EmployeeAccountCreated($employee, $password));

        return response()->json(['message' => 'Employee added successfully', 'employee' => $employee], 201);
    }

    public function lamininEmployeeListData()
    {
        $employeeList = Employee::with('department')->where('department_id', 2)->get();

        return response()->json($employeeList);
    }
}
