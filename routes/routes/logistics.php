<?php

use App\Http\Controllers\LogisticsManagementController;
use App\Http\Controllers\DistributionPlanningController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'logistics', 'as' => 'logistics.'], function () {
  // Logistics Management routes
  Route::group(['prefix' => 'logistics-management', 'as' => 'logistics-management.'], function () {
    Route::get('/', [LogisticsManagementController::class, 'index'])->name('index');
    Route::get('/create', [LogisticsManagementController::class, 'create'])->name('create');
    Route::post('/', [LogisticsManagementController::class, 'store'])->name('store');
    Route::get('/{id}', [LogisticsManagementController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [LogisticsManagementController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LogisticsManagementController::class, 'update'])->name('update');
    Route::delete('/{id}', [LogisticsManagementController::class, 'destroy'])->name('destroy');
  });

  // Distribution Planning routes
  Route::group(['prefix' => 'distribution-planning', 'as' => 'distribution-planning.'], function () {
    Route::get('/', [DistributionPlanningController::class, 'index'])->name('index');
    Route::get('/create', [DistributionPlanningController::class, 'create'])->name('create');
    Route::post('/', [DistributionPlanningController::class, 'store'])->name('store');
    Route::get('/{distributionPlanning}', [DistributionPlanningController::class, 'show'])->name('show');
    Route::get('/{distributionPlanning}/edit', [DistributionPlanningController::class, 'edit'])->name('edit');
    Route::put('/{distributionPlanning}', [DistributionPlanningController::class, 'update'])->name('update');
    Route::delete('/{distributionPlanning}', [DistributionPlanningController::class, 'destroy'])->name('destroy');
  });
});
