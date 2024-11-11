<?php

use App\Http\Controllers\ProductionController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\WorkCenterController;
use App\Http\Controllers\ManufacturingQualityController;
use App\Http\Controllers\MrpController;
use Illuminate\Support\Facades\Route;

// Production Routes
Route::prefix('production')->name('production.')->group(function () {
  Route::get('/dashboard', [ProductionController::class, 'dashboard'])->name('dashboard');
  Route::resource('orders', ProductionController::class);
  Route::get('/planning', [ProductionController::class, 'planning'])->name('planning');
  Route::get('/scheduling', [ProductionController::class, 'scheduling'])->name('scheduling');
});

// Bill of Materials Routes
Route::prefix('bom')->name('bom.')->group(function () {
  Route::get('/items', [BomController::class, 'items'])->name('items');
  Route::get('/versions', [BomController::class, 'versions'])->name('versions');
  Route::get('/costing', [BomController::class, 'costing'])->name('costing');
  Route::get('/engineering', [BomController::class, 'engineering'])->name('engineering');
});

// Work Centers Routes
Route::prefix('work-centers')->name('work-centers.')->group(function () {
  Route::get('/', [WorkCenterController::class, 'index'])->name('index');
  Route::get('/capacity', [WorkCenterController::class, 'capacity'])->name('capacity');
  Route::get('/maintenance', [WorkCenterController::class, 'maintenance'])->name('maintenance');
  Route::get('/efficiency', [WorkCenterController::class, 'efficiency'])->name('efficiency');
});

// Manufacturing Quality Routes
Route::prefix('manufacturing-quality')->name('manufacturing-quality.')->group(function () {
  Route::get('/inspections', [ManufacturingQualityController::class, 'inspections'])->name('inspections');
  Route::get('/standards', [ManufacturingQualityController::class, 'standards'])->name('standards');
  Route::get('/control-charts', [ManufacturingQualityController::class, 'controlCharts'])->name('control-charts');
  Route::get('/non-conformance', [ManufacturingQualityController::class, 'nonConformance'])->name('non-conformance');
});

// MRP Routes
Route::prefix('mrp')->name('mrp.')->group(function () {
  Route::get('/planning', [MrpController::class, 'planning'])->name('planning');
  Route::get('/requirements', [MrpController::class, 'requirements'])->name('requirements');
  Route::get('/forecasting', [MrpController::class, 'forecasting'])->name('forecasting');
  Route::get('/scenarios', [MrpController::class, 'scenarios'])->name('scenarios');
});
