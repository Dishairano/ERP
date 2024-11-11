<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeRegistrationController;

// Time Registration Routes
Route::prefix('time-registration')->group(function () {
  Route::get('/dashboard', [TimeRegistrationController::class, 'dashboard'])->name('time-registration.dashboard');
  Route::get('/', [TimeRegistrationController::class, 'index'])->name('time-registration.index');
  Route::get('/create', [TimeRegistrationController::class, 'create'])->name('time-registration.create');
  Route::post('/', [TimeRegistrationController::class, 'store'])->name('time-registration.store');
  Route::get('/calendar', [TimeRegistrationController::class, 'calendar'])->name('time-registration.calendar');
  Route::get('/approvals', [TimeRegistrationController::class, 'approvals'])->name('time-registration.approvals');
  Route::post('/{registration}/status', [TimeRegistrationController::class, 'updateStatus'])->name('time-registration.update-status');
  Route::get('/export', [TimeRegistrationController::class, 'export'])->name('time-registration.export');
});
