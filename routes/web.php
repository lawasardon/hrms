<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('auth.user-login');
// });

Route::get('/', [AuthController::class, 'userLoggedIn'])->name('user.login');

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/index', function () {
            return view('index');
        });
        Route::get('/aqua/department', [DepartmentController::class, 'showAquaDepartment'])->name('show.aqua.department');
    });

    Route::group(['middleware' => ['role:admin|hr']], function () {
        Route::get('/hrindex', function () {
            return view('index');
        });
        Route::get('/aqua/employee/list', [DepartmentController::class, 'showAquaEmployeeList'])->name('show.aqua.employee.list');
        Route::get('/aqua/employee/list/data', [DepartmentController::class, 'aquaEmployeeListData'])->name('aqua.employee.list.data');
        Route::get('/aqua/add/employee', [DepartmentController::class, 'aquaAddEmployee'])->name('aqua.add.employee');
        Route::post('/aqua/store/employee', [DepartmentController::class, 'aquaStoreEmployee'])->name('aqua.store.employee');

        Route::get('/laminin/employee/list', [DepartmentController::class, 'showLamininEmployeeList'])->name('show.laminin.employee.list');
        Route::get('/laminin/employee/list/data', [DepartmentController::class, 'lamininEmployeeListData'])->name('laminin.employee.list.data');
        Route::get('/laminin/add/employee', [DepartmentController::class, 'lamininAddEmployee'])->name('laminin.add.employee');
        Route::post('/laminin/store/employee', [DepartmentController::class, 'lamininStoreEmployee'])->name('laminin.store.employee');

    });

    Route::group(['middleware' => ['role:admin|employee']], function () {
        Route::get('/emindex', function () {
            return view('index');
        });
    });
});