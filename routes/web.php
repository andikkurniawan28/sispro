<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', DashboardController::class)->name('dashboard')->middleware(['auth']);
// Auth
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('loginProcess');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/change_datetime', [AuthController::class, 'changeDatetime'])->name('change_datetime');

Route::get('/setup', [SetupController::class, 'index'])->name('setup.index')->middleware(['auth', 'check.permission']);
Route::put('/setup/{id}', [SetupController::class, 'update'])->name('setup.update')->middleware(['auth', 'check.permission']);
Route::resource('/department', DepartmentController::class)->middleware(['auth', 'check.permission']);
