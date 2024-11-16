<?php

use App\Http\Controllers\CoreSchedulingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Scheduling Routes
|--------------------------------------------------------------------------
|
| Here is where you can register scheduling related routes for your application.
|
*/

Route::middleware(['auth'])->prefix('scheduling')->name('scheduling.')->group(function () {
    // Shifts Management
    Route::get('/shifts', [CoreSchedulingController::class, 'shifts'])
        ->name('shifts');
    Route::get('/shifts/events', [CoreSchedulingController::class, 'events'])
        ->name('shifts.events');
    Route::get('/shifts/create', [CoreSchedulingController::class, 'create'])
        ->name('shifts.create');
    Route::post('/shifts', [CoreSchedulingController::class, 'store'])
        ->name('shifts.store');
    Route::get('/shifts/{shift}', [CoreSchedulingController::class, 'show'])
        ->name('shifts.show');
    Route::get('/shifts/{shift}/edit', [CoreSchedulingController::class, 'edit'])
        ->name('shifts.edit');
    Route::put('/shifts/{shift}', [CoreSchedulingController::class, 'update'])
        ->name('shifts.update');
    Route::delete('/shifts/{shift}', [CoreSchedulingController::class, 'destroy'])
        ->name('shifts.destroy');

    // Shift Status Updates
    Route::post('/shifts/{shift}/start', [CoreSchedulingController::class, 'startShift'])
        ->name('shifts.start');
    Route::post('/shifts/{shift}/complete', [CoreSchedulingController::class, 'completeShift'])
        ->name('shifts.complete');
    Route::post('/shifts/{shift}/cancel', [CoreSchedulingController::class, 'cancelShift'])
        ->name('shifts.cancel');
});
