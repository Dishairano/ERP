<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreDashboardController;

// Dashboard route - explicitly define middleware here
Route::middleware(['web', 'auth'])->group(function () {
    // Main dashboard route
    Route::get('/dashboard', [CoreDashboardController::class, 'index'])->name('dashboard');

    // Other dashboard routes
    Route::post('/dashboard/refresh', [CoreDashboardController::class, 'refreshMetrics'])->name('dashboard.refresh');
    Route::get('/dashboard/metrics/{type}', [CoreDashboardController::class, 'getMetricsByType'])->name('dashboard.metrics.type');
    Route::get('/dashboard/period/{period}', [CoreDashboardController::class, 'getMetricsByTimePeriod'])->name('dashboard.metrics.period');
});
