<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HrmController;
use App\Http\Controllers\Hrm\PayrollController;
use App\Http\Controllers\Hrm\RecruitmentController;
use App\Http\Controllers\Hrm\PerformanceController;
use App\Http\Controllers\Hrm\TrainingController;

Route::middleware(['auth'])->group(function () {
  // Main HRM routes
  Route::get('/hrm', [HrmController::class, 'index'])->name('hrm.index');
  Route::get('/hrm/employees', [HrmController::class, 'employees'])->name('hrm.employees');
  Route::get('/hrm/departments', [HrmController::class, 'departments'])->name('hrm.departments');
  Route::get('/hrm/positions', [HrmController::class, 'positions'])->name('hrm.positions');
  Route::get('/hrm/attendance', [HrmController::class, 'attendance'])->name('hrm.attendance');

  // Staff Management Routes
  Route::get('/hrm/staff-management', [HrmController::class, 'staffManagement'])->name('hrm.staff-management');
  Route::get('/hrm/staff-management/create', [HrmController::class, 'createStaff'])->name('hrm.staff-management.create');
  Route::post('/hrm/staff-management', [HrmController::class, 'storeStaff'])->name('hrm.staff-management.store');
  Route::get('/hrm/staff-management/{id}/edit', [HrmController::class, 'editStaff'])->name('hrm.staff-management.edit');
  Route::put('/hrm/staff-management/{id}', [HrmController::class, 'updateStaff'])->name('hrm.staff-management.update');
  Route::delete('/hrm/staff-management/{id}', [HrmController::class, 'destroyStaff'])->name('hrm.staff-management.destroy');

  // Payroll Routes
  Route::get('/hrm/payroll', [PayrollController::class, 'index'])->name('hrm.payroll');
  Route::get('/hrm/payroll/create', [PayrollController::class, 'create'])->name('hrm.payroll.create');
  Route::post('/hrm/payroll', [PayrollController::class, 'store'])->name('hrm.payroll.store');
  Route::get('/hrm/payroll/{id}', [PayrollController::class, 'show'])->name('hrm.payroll.show');
  Route::get('/hrm/payroll/{id}/edit', [PayrollController::class, 'edit'])->name('hrm.payroll.edit');
  Route::put('/hrm/payroll/{id}', [PayrollController::class, 'update'])->name('hrm.payroll.update');
  Route::delete('/hrm/payroll/{id}', [PayrollController::class, 'destroy'])->name('hrm.payroll.destroy');

  // Recruitment Routes
  Route::get('/hrm/recruitment', [RecruitmentController::class, 'index'])->name('hrm.recruitment');
  Route::get('/hrm/recruitment/create', [RecruitmentController::class, 'create'])->name('hrm.recruitment.create');
  Route::post('/hrm/recruitment', [RecruitmentController::class, 'store'])->name('hrm.recruitment.store');
  Route::get('/hrm/recruitment/{id}', [RecruitmentController::class, 'show'])->name('hrm.recruitment.show');
  Route::get('/hrm/recruitment/{id}/edit', [RecruitmentController::class, 'edit'])->name('hrm.recruitment.edit');
  Route::put('/hrm/recruitment/{id}', [RecruitmentController::class, 'update'])->name('hrm.recruitment.update');
  Route::delete('/hrm/recruitment/{id}', [RecruitmentController::class, 'destroy'])->name('hrm.recruitment.destroy');

  // Performance Evaluation Routes
  Route::get('/hrm/performance-evaluations', [PerformanceController::class, 'index'])->name('hrm.performance-evaluations');
  Route::get('/hrm/performance-evaluations/create', [PerformanceController::class, 'create'])->name('hrm.performance-evaluations.create');
  Route::post('/hrm/performance-evaluations', [PerformanceController::class, 'store'])->name('hrm.performance-evaluations.store');
  Route::get('/hrm/performance-evaluations/{id}', [PerformanceController::class, 'show'])->name('hrm.performance-evaluations.show');
  Route::get('/hrm/performance-evaluations/{id}/edit', [PerformanceController::class, 'edit'])->name('hrm.performance-evaluations.edit');
  Route::put('/hrm/performance-evaluations/{id}', [PerformanceController::class, 'update'])->name('hrm.performance-evaluations.update');
  Route::delete('/hrm/performance-evaluations/{id}', [PerformanceController::class, 'destroy'])->name('hrm.performance-evaluations.destroy');

  // Training & Development Routes
  Route::get('/hrm/training-development', [TrainingController::class, 'index'])->name('hrm.training-development');
  Route::get('/hrm/training-development/create', [TrainingController::class, 'create'])->name('hrm.training-development.create');
  Route::post('/hrm/training-development', [TrainingController::class, 'store'])->name('hrm.training-development.store');
  Route::get('/hrm/training-development/{id}', [TrainingController::class, 'show'])->name('hrm.training-development.show');
  Route::get('/hrm/training-development/{id}/edit', [TrainingController::class, 'edit'])->name('hrm.training-development.edit');
  Route::put('/hrm/training-development/{id}', [TrainingController::class, 'update'])->name('hrm.training-development.update');
  Route::delete('/hrm/training-development/{id}', [TrainingController::class, 'destroy'])->name('hrm.training-development.destroy');
});
