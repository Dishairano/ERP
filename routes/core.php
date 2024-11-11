<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreDashboardController;

Route::middleware(['auth'])->group(function () {
  // Dashboard Analytics Routes
  Route::get('/dashboard', [CoreDashboardController::class, 'index'])->name('dashboard');
  Route::post('/dashboard/refresh', [CoreDashboardController::class, 'refreshMetrics'])->name('dashboard.refresh');
  Route::get('/dashboard/metrics/{type}', [CoreDashboardController::class, 'getMetricsByType'])->name('dashboard.metrics.type');
  Route::get('/dashboard/period/{period}', [CoreDashboardController::class, 'getMetricsByTimePeriod'])->name('dashboard.metrics.period');
});
