<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataIntegrationController;

Route::middleware(['auth'])->group(function () {
  Route::get('/data-integration', [DataIntegrationController::class, 'index'])->name('data-integration.index');
  Route::get('/data-integration/create', [DataIntegrationController::class, 'create'])->name('data-integration.create');
  Route::post('/data-integration', [DataIntegrationController::class, 'store'])->name('data-integration.store');
  Route::get('/data-integration/{id}', [DataIntegrationController::class, 'show'])->name('data-integration.show');
  Route::get('/data-integration/{id}/edit', [DataIntegrationController::class, 'edit'])->name('data-integration.edit');
  Route::put('/data-integration/{id}', [DataIntegrationController::class, 'update'])->name('data-integration.update');
  Route::delete('/data-integration/{id}', [DataIntegrationController::class, 'destroy'])->name('data-integration.destroy');
  Route::get('/data-integration/logs', [DataIntegrationController::class, 'logs'])->name('data-integration.logs');
});
