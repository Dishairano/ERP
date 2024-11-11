<?php

use App\Http\Controllers\TimeRegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  Route::get('/time-registrations', [TimeRegistrationController::class, 'index'])
    ->name('time-registrations.index');

  Route::get('/time-registrations/create', [TimeRegistrationController::class, 'create'])
    ->name('time-registrations.create');

  Route::post('/time-registrations', [TimeRegistrationController::class, 'store'])
    ->name('time-registrations.store');

  Route::get('/time-registrations/{timeRegistration}', [TimeRegistrationController::class, 'show'])
    ->name('time-registrations.show');

  Route::get('/time-registrations/{timeRegistration}/edit', [TimeRegistrationController::class, 'edit'])
    ->name('time-registrations.edit');

  Route::put('/time-registrations/{timeRegistration}', [TimeRegistrationController::class, 'update'])
    ->name('time-registrations.update');

  Route::delete('/time-registrations/{timeRegistration}', [TimeRegistrationController::class, 'destroy'])
    ->name('time-registrations.destroy');

  Route::get('/time-registrations/calendar', [TimeRegistrationController::class, 'calendar'])
    ->name('time-registrations.calendar');

  Route::get('/time-registrations/reports', [TimeRegistrationController::class, 'reports'])
    ->name('time-registrations.reports');

  Route::post('/time-registrations/{timeRegistration}/approve', [TimeRegistrationController::class, 'approve'])
    ->name('time-registrations.approve')
    ->middleware('can:approve-time-registrations');

  Route::post('/time-registrations/{timeRegistration}/reject', [TimeRegistrationController::class, 'reject'])
    ->name('time-registrations.reject')
    ->middleware('can:approve-time-registrations');

  Route::post('/time-registrations/{timeRegistration}/comments', [TimeRegistrationController::class, 'addComment'])
    ->name('time-registrations.comments.store');

  Route::delete('/time-registrations/{timeRegistration}/attachments/{attachment}', [TimeRegistrationController::class, 'deleteAttachment'])
    ->name('time-registrations.attachments.destroy');
});
