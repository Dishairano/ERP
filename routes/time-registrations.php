<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeRegistrationController;

Route::middleware(['web', 'auth'])->group(function () {
    // Time Registration Dashboard
    Route::get('/time-registration/dashboard', [TimeRegistrationController::class, 'dashboard'])
        ->name('time-registration.dashboard');

    // Time Registration Calendar View
    Route::get('/time-registration/calendar', [TimeRegistrationController::class, 'calendar'])
        ->name('time-registration.calendar');

    // Time Registration Approvals
    Route::get('/time-registration/approvals', [TimeRegistrationController::class, 'approvals'])
        ->name('time-registration.approvals');

    // Time Registration CRUD Routes
    Route::resource('time-registration', TimeRegistrationController::class);

    // Additional Time Registration Actions
    Route::post('/time-registration/{registration}/submit', [TimeRegistrationController::class, 'submit'])
        ->name('time-registration.submit');
    Route::post('/time-registration/{registration}/approve', [TimeRegistrationController::class, 'approve'])
        ->name('time-registration.approve');
    Route::post('/time-registration/{registration}/reject', [TimeRegistrationController::class, 'reject'])
        ->name('time-registration.reject');

    // API Routes for Dynamic Data
    Route::get('/api/projects/{project}/tasks', [TimeRegistrationController::class, 'getProjectTasks'])
        ->name('api.project.tasks');
});
