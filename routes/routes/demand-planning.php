<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandPlanningController;

Route::middleware(['auth'])->group(function () {
  Route::get('/demand-planning', [DemandPlanningController::class, 'index'])->name('demand-planning.index');
  Route::get('/demand-planning/create', [DemandPlanningController::class, 'create'])->name('demand-planning.create');
  Route::post('/demand-planning', [DemandPlanningController::class, 'store'])->name('demand-planning.store');
  Route::get('/demand-planning/{id}', [DemandPlanningController::class, 'show'])->name('demand-planning.show');
  Route::get('/demand-planning/{id}/edit', [DemandPlanningController::class, 'edit'])->name('demand-planning.edit');
  Route::put('/demand-planning/{id}', [DemandPlanningController::class, 'update'])->name('demand-planning.update');
  Route::delete('/demand-planning/{id}', [DemandPlanningController::class, 'destroy'])->name('demand-planning.destroy');
  Route::get('/demand-planning/dashboard', [DemandPlanningController::class, 'dashboard'])->name('demand-planning.dashboard');
  Route::get('/demand-planning/scenarios', [DemandPlanningController::class, 'scenarios'])->name('demand-planning.scenarios');
  Route::get('/demand-planning/accuracy', [DemandPlanningController::class, 'accuracy'])->name('demand-planning.accuracy');
});
