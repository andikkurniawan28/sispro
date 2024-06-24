<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\ProductStatusController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductWarehouseController;
use App\Http\Controllers\ProductionQualityController;
use App\Http\Controllers\RawMaterialCategoryController;
use App\Http\Controllers\RawMaterialWarehouseController;

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
Route::resource('/role', RoleController::class)->middleware(['auth', 'check.permission']);
Route::resource('/user', UserController::class)->middleware(['auth', 'check.permission']);
Route::get('/activity_log', ActivityLogController::class)->name('activity_log')->middleware(['auth', 'check.permission']);
Route::resource('/unit', UnitController::class)->middleware(['auth', 'check.permission']);
Route::resource('/raw_material_category', RawMaterialCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/raw_material', RawMaterialController::class)->middleware(['auth', 'check.permission']);
Route::resource('/raw_material_warehouse', RawMaterialWarehouseController::class)->middleware(['auth', 'check.permission']);
Route::resource('/product_category', ProductCategoryController::class)->middleware(['auth', 'check.permission']);
Route::resource('/product_status', ProductStatusController::class)->middleware(['auth', 'check.permission']);
Route::resource('/product', ProductController::class)->middleware(['auth', 'check.permission']);
Route::resource('/product_warehouse', ProductWarehouseController::class)->middleware(['auth', 'check.permission']);
Route::get('/formula', [FormulaController::class, 'index'])->name('formula.index')->middleware(['auth', 'check.permission']);
Route::get('/formula/{product_id}', [FormulaController::class, 'adjust'])->name('formula.adjust')->middleware(['auth', 'check.permission']);
Route::post('/formula/{product_id}', [FormulaController::class, 'update'])->name('formula.update')->middleware(['auth', 'check.permission']);
Route::resource('/quality', QualityController::class)->middleware(['auth', 'check.permission']);
Route::resource('/demand', DemandController::class)->middleware(['auth', 'check.permission']);
Route::resource('/production', ProductionController::class)->middleware(['auth', 'check.permission']);
Route::resource('/production_quality', ProductionQualityController::class)->middleware(['auth', 'check.permission']);
