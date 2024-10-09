<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        $password = Str::random(10);

        $employee = Employee::create($validatedData);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($password),
        ]);

        // Optionally, associate the user with the employee (if needed)
        $employee->user()->associate($user);
        $employee->save();

        $user->assignRole('employee');

        Mail::to($validatedData['email'])->send(new EmployeeAccountCreated($employee, $password));

        return response()->json(['message' => 'Employee added successfully', 'employee' => $employee], 201);
    }

    public function aquaEmployeeListData()
    {
        $employeeList = Employee::with('department')->where('department_id', 1)->get();

        return response()->json($employeeList);
    }
}
