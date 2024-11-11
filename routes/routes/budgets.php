<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Budget\BudgetController;
use App\Http\Controllers\Budget\BudgetReportController;
use App\Http\Controllers\Budget\BudgetDepartmentController;
use App\Http\Controllers\Budget\BudgetProjectController;
use App\Http\Controllers\Budget\BudgetScenarioController;
use App\Http\Controllers\Budget\BudgetKpiController;
use App\Http\Controllers\Budget\BudgetTransferController;
use App\Http\Controllers\Budget\BudgetExpenseController;
use App\Http\Controllers\Budget\BudgetRevenueController;
use App\Http\Controllers\Budget\PresetDepartmentController;

Route::middleware(['auth'])->group(function () {
  // Preset Department Routes
  Route::resource('preset-departments', PresetDepartmentController::class);
  Route::get('api/preset-departments/active', [PresetDepartmentController::class, 'getActive'])
    ->name('api.preset-departments.active');

  // Department Budget Routes
  Route::get('/budgets/departments', [BudgetDepartmentController::class, 'index'])->name('budgets.departments');
  Route::get('/budgets/departments/{department}', [BudgetDepartmentController::class, 'show'])->name('budgets.departments.show');
  Route::post('/budgets/departments', [BudgetDepartmentController::class, 'store'])->name('budgets.departments.store');
  Route::put('/budgets/departments/{department}', [BudgetDepartmentController::class, 'update'])->name('budgets.departments.update');

  // Project Budget Routes
  Route::get('/budgets/projects', [BudgetProjectController::class, 'index'])->name('budgets.projects');
  Route::get('/budgets/projects/{project}', [BudgetProjectController::class, 'show'])->name('budgets.projects.show');
  Route::post('/budgets/projects', [BudgetProjectController::class, 'store'])->name('budgets.projects.store');
  Route::put('/budgets/projects/{project}', [BudgetProjectController::class, 'update'])->name('budgets.projects.update');

  // Scenario Routes
  Route::get('/budgets/scenarios', [BudgetScenarioController::class, 'index'])->name('budgets.scenarios');
  Route::post('/budgets/scenarios', [BudgetScenarioController::class, 'store'])->name('budgets.scenarios.store');
  Route::get('/budgets/scenarios/{scenario}', [BudgetScenarioController::class, 'show'])->name('budgets.scenarios.show');
  Route::post('/budgets/scenarios/{scenario}/apply', [BudgetScenarioController::class, 'apply'])->name('budgets.scenarios.apply');
  Route::post('/budgets/scenarios/{scenario}/reject', [BudgetScenarioController::class, 'reject'])->name('budgets.scenarios.reject');

  // Report Routes
  Route::get('/budgets/reports', [BudgetReportController::class, 'index'])->name('budgets.reports');
  Route::get('/budgets/reports/export', [BudgetReportController::class, 'export'])->name('budgets.reports.export');
  Route::post('/budgets/reports/generate', [BudgetReportController::class, 'generate'])->name('budgets.reports.generate');
  Route::get('/budgets/reports/automated', [BudgetReportController::class, 'automatedReports'])->name('budgets.reports.automated');

  // KPI Routes
  Route::get('/budgets/kpis/dashboard', [BudgetKpiController::class, 'dashboard'])->name('budgets.kpis.dashboard');
  Route::post('/budgets/{budget}/kpis', [BudgetKpiController::class, 'store'])->name('budgets.kpis.store');
  Route::get('/budgets/kpis/performance', [BudgetKpiController::class, 'performance'])->name('budgets.kpis.performance');

  // Transfer Routes
  Route::get('/budgets/transfers', [BudgetTransferController::class, 'index'])->name('budgets.transfers');
  Route::post('/budgets/transfer', [BudgetTransferController::class, 'store'])->name('budgets.transfer');
  Route::get('/budgets/transfers/history', [BudgetTransferController::class, 'history'])->name('budgets.transfers.history');

  // Expense Routes
  Route::get('/budgets/{budget}/expenses', [BudgetExpenseController::class, 'index'])->name('budgets.expenses');
  Route::get('/budgets/expenses/approvals', [BudgetExpenseController::class, 'approvals'])->name('budgets.expenses.approvals');
  Route::post('/budgets/expenses/{expense}/approve', [BudgetExpenseController::class, 'approve'])->name('budgets.expenses.approve');
  Route::post('/budgets/expenses/{expense}/reject', [BudgetExpenseController::class, 'reject'])->name('budgets.expenses.reject');

  // Revenue Routes
  Route::get('/budgets/revenue-streams', [BudgetRevenueController::class, 'index'])->name('budgets.revenue-streams');
  Route::get('/budgets/revenue-streams/{stream}', [BudgetRevenueController::class, 'show'])->name('budgets.revenue-streams.show');

  // Core Budget Routes
  Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
  Route::get('/budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
  Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
  Route::get('/budgets/{id}', [BudgetController::class, 'show'])->name('budgets.show');
  Route::get('/budgets/{id}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
  Route::put('/budgets/{id}', [BudgetController::class, 'update'])->name('budgets.update');
  Route::delete('/budgets/{id}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
});
