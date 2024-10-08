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
        Route::get('/aqua/department', [DepartmentController::class, 'showAquaDepartment'])->name('show.aqua.department');
        Route::get('/aqua/add/employee', [DepartmentController::class, 'aquaAddEmployee'])->name('aqua.add.employee');
        Route::post('/aqua/store/employee', [DepartmentController::class, 'aquaStoreEmployee'])->name('aqua.store.employee');
    });

    Route::group(['middleware' => ['role:admin|employee']], function () {
        Route::get('/emindex', function () {
            return view('index');
        });
    });
});
