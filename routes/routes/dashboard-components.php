<?php

use App\Http\Controllers\DashboardComponentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Dashboard Components
  Route::prefix('dashboards/{dashboard}/components')->group(function () {
    // List all components for a dashboard
    Route::get('/', [DashboardComponentController::class, 'index'])
      ->name('dashboard-components.index');

    // Show create component form
    Route::get('/create', [DashboardComponentController::class, 'create'])
      ->name('dashboard-components.create');

    // Store new component
    Route::post('/', [DashboardComponentController::class, 'store'])
      ->name('dashboard-components.store');

    // Show single component
    Route::get('/{component}', [DashboardComponentController::class, 'show'])
      ->name('dashboard-components.show');

    // Show edit component form
    Route::get('/{component}/edit', [DashboardComponentController::class, 'edit'])
      ->name('dashboard-components.edit');

    // Update component
    Route::put('/{component}', [DashboardComponentController::class, 'update'])
      ->name('dashboard-components.update');

    // Delete component
    Route::delete('/{component}', [DashboardComponentController::class, 'destroy'])
      ->name('dashboard-components.destroy');

    // Refresh component data
    Route::post('/{component}/refresh-data', [DashboardComponentController::class, 'refreshData'])
      ->name('dashboard-components.refresh-data');

    // Reorder components
    Route::post('/reorder', [DashboardComponentController::class, 'reorder'])
      ->name('dashboard-components.reorder');
  });
});
