<?php

use App\Http\Controllers\CoreLeaveManagementController;
use App\Http\Controllers\LeaveRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Leave Request Routes
|--------------------------------------------------------------------------
|
| Here is where you can register leave request related routes for your application.
|
*/

// Core Leave Management Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard and Overview
    Route::get('/leave-management/dashboard', [CoreLeaveManagementController::class, 'dashboard'])
        ->name('leave-management.dashboard');

    // Calendar Routes
    Route::get('/leave-management/calendar', [CoreLeaveManagementController::class, 'calendar'])
        ->name('leave-management.calendar');
    Route::get('/leave-management/calendar/events', [CoreLeaveManagementController::class, 'getCalendarEvents'])
        ->name('leave-management.calendar.events');

    // Leave Balances
    Route::get('/leave-management/balances', [CoreLeaveManagementController::class, 'balances'])
        ->name('leave-management.balances');
});

// Leave Request CRUD Routes
Route::middleware(['auth'])->group(function () {
    // List and Create
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])
        ->name('leave-requests.index');
    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])
        ->name('leave-requests.create');
    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])
        ->name('leave-requests.store');

    // View and Edit
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])
        ->name('leave-requests.show');
    Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])
        ->name('leave-requests.edit');
    Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])
        ->name('leave-requests.update');

    // Workflow Actions
    Route::post('/leave-requests/{leaveRequest}/submit', [LeaveRequestController::class, 'submit'])
        ->name('leave-requests.submit');
    Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])
        ->name('leave-requests.approve');
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])
        ->name('leave-requests.reject');
});
