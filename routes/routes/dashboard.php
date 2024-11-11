<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Settings\DashboardPreferencesController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Dashboard preferences
  Route::post('dashboard/preferences', [DashboardPreferencesController::class, 'updatePreferences'])
    ->name('dashboard.update-preferences');

  // Dashboard components
  Route::get('dashboard/components', [DashboardController::class, 'components'])
    ->name('dashboard.components');

  // Dashboard analytics
  Route::get('dashboard/analytics', [DashboardController::class, 'analytics'])
    ->name('dashboard.analytics');
});
