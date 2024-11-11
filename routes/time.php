<?php

use App\Http\Controllers\CoreTimeRegistrationController;
use App\Http\Controllers\CoreProjectTimeController;
use App\Http\Controllers\CoreLeaveManagementController;
use App\Http\Controllers\CoreSchedulingController;
use App\Http\Controllers\CoreTimeReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Time Registration Routes
  Route::prefix('time-registration')->group(function () {
    Route::get('/dashboard', [CoreTimeRegistrationController::class, 'dashboard'])->name('time-registration.dashboard');
    Route::get('/', [CoreTimeRegistrationController::class, 'index'])->name('time-registration.index');
    Route::get('/create', [CoreTimeRegistrationController::class, 'create'])->name('time-registration.create');
    Route::post('/', [CoreTimeRegistrationController::class, 'store'])->name('time-registration.store');
    Route::get('/calendar', [CoreTimeRegistrationController::class, 'calendar'])->name('time-registration.calendar');
    Route::get('/approvals', [CoreTimeRegistrationController::class, 'approvals'])->name('time-registration.approvals');
    Route::post('/approve/{id}', [CoreTimeRegistrationController::class, 'approve'])->name('time-registration.approve');
    Route::post('/reject/{id}', [CoreTimeRegistrationController::class, 'reject'])->name('time-registration.reject');
    Route::get('/report', [CoreTimeRegistrationController::class, 'report'])->name('time-registration.report');
    Route::get('/export', [CoreTimeRegistrationController::class, 'export'])->name('time-registration.export');
  });

  // Project Time Routes
  Route::prefix('project-time')->group(function () {
    Route::get('/tracking', [CoreProjectTimeController::class, 'tracking'])->name('project-time.tracking');
    Route::post('/tracking', [CoreProjectTimeController::class, 'storeTracking'])->name('project-time.tracking.store');
    Route::get('/allocation', [CoreProjectTimeController::class, 'allocation'])->name('project-time.allocation');
    Route::post('/allocation', [CoreProjectTimeController::class, 'storeAllocation'])->name('project-time.allocation.store');
    Route::get('/budgets', [CoreProjectTimeController::class, 'budgets'])->name('project-time.budgets');
    Route::post('/budgets', [CoreProjectTimeController::class, 'storeBudget'])->name('project-time.budgets.store');
    Route::get('/analysis', [CoreProjectTimeController::class, 'analysis'])->name('project-time.analysis');
  });

  // Leave Management Routes
  Route::prefix('leave-management')->group(function () {
    // Leave Requests
    Route::get('/requests', [CoreLeaveManagementController::class, 'requests'])->name('leave-requests.index');
    Route::get('/requests/create', [CoreLeaveManagementController::class, 'createRequest'])->name('leave-requests.create');
    Route::post('/requests', [CoreLeaveManagementController::class, 'storeRequest'])->name('leave-requests.store');
    Route::post('/requests/{id}/approve', [CoreLeaveManagementController::class, 'approveRequest'])->name('leave-requests.approve');
    Route::post('/requests/{id}/reject', [CoreLeaveManagementController::class, 'rejectRequest'])->name('leave-requests.reject');

    // Leave Calendar
    Route::get('/calendar', [CoreLeaveManagementController::class, 'calendar'])->name('leave-management.calendar');

    // Leave Balances
    Route::get('/balances', [CoreLeaveManagementController::class, 'balances'])->name('leave-management.balances');
    Route::post('/balances/adjust', [CoreLeaveManagementController::class, 'adjustBalance'])->name('leave-management.balances.adjust');

    // Leave Policies
    Route::get('/policies', [CoreLeaveManagementController::class, 'policies'])->name('leave-management.policies');
    Route::post('/policies', [CoreLeaveManagementController::class, 'storePolicy'])->name('leave-management.policies.store');
    Route::put('/policies/{id}', [CoreLeaveManagementController::class, 'updatePolicy'])->name('leave-management.policies.update');
  });

  // Scheduling Routes
  Route::prefix('scheduling')->group(function () {
    // Shift Planning
    Route::get('/shifts', [CoreSchedulingController::class, 'shifts'])->name('scheduling.shifts');
    Route::post('/shifts', [CoreSchedulingController::class, 'storeShift'])->name('scheduling.shifts.store');
    Route::put('/shifts/{id}', [CoreSchedulingController::class, 'updateShift'])->name('scheduling.shifts.update');

    // Team Roster
    Route::get('/roster', [CoreSchedulingController::class, 'roster'])->name('scheduling.roster');
    Route::post('/roster/generate', [CoreSchedulingController::class, 'generateRoster'])->name('scheduling.roster.generate');
    Route::post('/roster/publish', [CoreSchedulingController::class, 'publishRoster'])->name('scheduling.roster.publish');

    // Availability
    Route::get('/availability', [CoreSchedulingController::class, 'availability'])->name('scheduling.availability');
    Route::post('/availability', [CoreSchedulingController::class, 'storeAvailability'])->name('scheduling.availability.store');

    // Overtime
    Route::get('/overtime', [CoreSchedulingController::class, 'overtime'])->name('scheduling.overtime');
    Route::post('/overtime/approve/{id}', [CoreSchedulingController::class, 'approveOvertime'])->name('scheduling.overtime.approve');
    Route::post('/overtime/reject/{id}', [CoreSchedulingController::class, 'rejectOvertime'])->name('scheduling.overtime.reject');
  });

  // Time Reports Routes
  Route::prefix('time-reports')->group(function () {
    Route::get('/attendance', [CoreTimeReportController::class, 'attendance'])->name('time-reports.attendance');
    Route::get('/overtime', [CoreTimeReportController::class, 'overtime'])->name('time-reports.overtime');
    Route::get('/productivity', [CoreTimeReportController::class, 'productivity'])->name('time-reports.productivity');
    Route::get('/cost', [CoreTimeReportController::class, 'cost'])->name('time-reports.cost');

    // Export Routes
    Route::get('/attendance/export', [CoreTimeReportController::class, 'exportAttendance'])->name('time-reports.attendance.export');
    Route::get('/overtime/export', [CoreTimeReportController::class, 'exportOvertime'])->name('time-reports.overtime.export');
    Route::get('/productivity/export', [CoreTimeReportController::class, 'exportProductivity'])->name('time-reports.productivity.export');
    Route::get('/cost/export', [CoreTimeReportController::class, 'exportCost'])->name('time-reports.cost.export');
  });
});
