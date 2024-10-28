<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StaticPagesController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/user/login', [AuthController::class, 'userLoggedIn'])->name('user.login');

Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [StaticPagesController::class, 'showIndex'])->name('home');

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/aqua/department', [DepartmentController::class, 'showAquaDepartment'])->name('show.aqua.department');
    });

    Route::group(['middleware' => ['role:admin|hr']], function () {
        Route::get('/aqua/employee/list', [DepartmentController::class, 'showAquaEmployeeList'])->name('show.aqua.employee.list');
        Route::get('/aqua/employee/list/data', [DepartmentController::class, 'aquaEmployeeListData'])->name('aqua.employee.list.data');
        Route::get('/aqua/add/employee', [DepartmentController::class, 'aquaAddEmployee'])->name('aqua.add.employee');
        Route::post('/aqua/store/employee', [DepartmentController::class, 'aquaStoreEmployee'])->name('aqua.store.employee');

        Route::get('/laminin/employee/list', [DepartmentController::class, 'showLamininEmployeeList'])->name('show.laminin.employee.list');
        Route::get('/laminin/employee/list/data', [DepartmentController::class, 'lamininEmployeeListData'])->name('laminin.employee.list.data');
        Route::get('/laminin/add/employee', [DepartmentController::class, 'lamininAddEmployee'])->name('laminin.add.employee');
        Route::post('/laminin/store/employee', [DepartmentController::class, 'lamininStoreEmployee'])->name('laminin.store.employee');

        Route::get('/aqua/leave/list', [LeaveController::class, 'aquaLeaveList'])->name('aqua.leave.list');
        Route::get('/aqua/leave/list/data', [LeaveController::class, 'aquaLeaveListData'])->name('aqua.leave.list.data');
        Route::post('/aqua/leave/list/update/{id}', [LeaveController::class, 'aquaLeaveListUpdate'])->name('aqua.leave.list.update');

        Route::get('/laminin/leave/list', [LeaveController::class, 'lamininLeaveList'])->name('laminin.leave.list');
        Route::get('/laminin/leave/list/data', [LeaveController::class, 'lamininLeaveListData'])->name('laminin.leave.list.data');
        Route::post('/laminin/leave/list/update/{id}', [LeaveController::class, 'lamininLeaveListUpdate'])->name('laminin.leave.list.update');

        // Route::get('/attendance/list', [AttendanceController::class, 'attendanceList'])->name('attendance.list');
        Route::get('/attendance/download/template', [AttendanceController::class, 'attendanceDownloadableTemplate'])->name('attendance.downloadable.template');
        Route::get('/attendance/show/upload/page', [AttendanceController::class, 'attendanceShowUploadPage'])->name('attendance.show.upload.page');
        Route::post('/attendance/upload', [AttendanceController::class, 'attendanceUpload'])->name('attendance.upload');

        Route::get('/attendance/list/all/employee', [AttendanceController::class, 'attendanceListAllEmployee'])->name('attendance.list.all.employee');
        Route::get('/attendance/list/all/employee/data', [AttendanceController::class, 'attendanceListAllEmployeeData'])->name('attendance.list.all.employee.data');
        Route::get('/attendance/list/aqua', [AttendanceController::class, 'attendanceListAqua'])->name('attendance.list.aqua');
        Route::get('/attendance/list/aqua/data', [AttendanceController::class, 'attendanceListAquaData'])->name('attendance.list.aqua.data');
        Route::get('/attendance/list/laminin', [AttendanceController::class, 'attendanceListLaminin'])->name('attendance.list.laminin');
        Route::get('/attendance/list/aqua/laminin', [AttendanceController::class, 'attendanceListLamininData'])->name('attendance.list.laminin.data');

        Route::get('/aqua/payroll', [PayrollController::class, 'showAquaPayroll'])->name('show.aqua.payroll');
        Route::get('/aqua/payroll/data', [PayrollController::class, 'showAquaPayrollData'])->name('show.aqua.payroll.data');
        Route::get('/aqua/show/payroll/{id}', [PayrollController::class,'aquaShowEditModal'])->name('aqua.payroll.show');
        Route::post('/aqua/payroll/update/{id}', [PayrollController::class, 'updateAquaPayroll'])->name('aqua.update.payroll');


    });

    Route::group(['middleware' => ['role:admin|employee']], function () {
        Route::get('/leave/list', [LeaveController::class, 'leaveList'])->name('employee.leave.list');
        Route::get('/leave/list/data', [LeaveController::class, 'leaveListData'])->name('employee.leave.list.data');
        Route::get('/get/department/id/data', [LeaveController::class, 'getDepartmentIdData'])->name('employee.get.department.id.data');
        Route::get('/leave/create', [LeaveController::class, 'createLeave'])->name('employee.leave.create');
        Route::post('/leave/store', [LeaveController::class, 'storeLeave'])->name('employee.leave.store');

    });
});
