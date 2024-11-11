<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeRegistrationController;

Route::middleware(['auth'])->group(function () {
  Route::get('/time-registration', [TimeRegistrationController::class, 'index'])->name('time-registration.index');
  Route::get('/time-registration/create', [TimeRegistrationController::class, 'create'])->name('time-registration.create');
  Route::post('/time-registration', [TimeRegistrationController::class, 'store'])->name('time-registration.store');
  Route::get('/time-registration/{id}', [TimeRegistrationController::class, 'show'])->name('time-registration.show');
  Route::get('/time-registration/{id}/edit', [TimeRegistrationController::class, 'edit'])->name('time-registration.edit');
  Route::put('/time-registration/{id}', [TimeRegistrationController::class, 'update'])->name('time-registration.update');
  Route::delete('/time-registration/{id}', [TimeRegistrationController::class, 'destroy'])->name('time-registration.destroy');
});
