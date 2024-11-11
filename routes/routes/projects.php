<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTemplateController;
use App\Http\Controllers\ProjectPerformanceController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\ProjectRiskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Basic project list and create routes
  Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
  Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
  Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

  // Project dashboard route
  Route::get('/projects/dashboard', [ProjectController::class, 'dashboard'])->name('projects.dashboard');

  // Project template routes
  Route::get('/projects/templates', [ProjectTemplateController::class, 'index'])->name('projects.templates.index');
  Route::get('/projects/templates/create', [ProjectTemplateController::class, 'create'])->name('projects.templates.create');
  Route::post('/projects/templates', [ProjectTemplateController::class, 'store'])->name('projects.templates.store');
  Route::get('/projects/templates/{template}', [ProjectTemplateController::class, 'show'])->name('projects.templates.show');
  Route::get('/projects/templates/{template}/edit', [ProjectTemplateController::class, 'edit'])->name('projects.templates.edit');
  Route::put('/projects/templates/{template}', [ProjectTemplateController::class, 'update'])->name('projects.templates.update');
  Route::delete('/projects/templates/{template}', [ProjectTemplateController::class, 'destroy'])->name('projects.templates.destroy');
  Route::post('/projects/templates/{template}/duplicate', [ProjectTemplateController::class, 'duplicate'])->name('projects.templates.duplicate');

  // Project task routes
  Route::get('/projects/tasks', [ProjectTaskController::class, 'index'])->name('projects.tasks');
  Route::post('/projects/{project}/tasks', [ProjectTaskController::class, 'store'])->name('projects.tasks.store');
  Route::put('/projects/{project}/tasks/{task}', [ProjectTaskController::class, 'update'])->name('projects.tasks.update');
  Route::delete('/projects/{project}/tasks/{task}', [ProjectTaskController::class, 'destroy'])->name('projects.tasks.destroy');

  // Project risk routes
  Route::get('/projects/risks', [ProjectRiskController::class, 'index'])->name('projects.risks');
  Route::get('/projects/risks/matrix', [ProjectRiskController::class, 'matrix'])->name('projects.risks.matrix');
  Route::get('/projects/risks/report', [ProjectRiskController::class, 'report'])->name('projects.risks.report');
  Route::post('/projects/{project}/risks', [ProjectRiskController::class, 'store'])->name('projects.risks.store');
  Route::put('/projects/{project}/risks/{risk}', [ProjectRiskController::class, 'update'])->name('projects.risks.update');
  Route::delete('/projects/{project}/risks/{risk}', [ProjectRiskController::class, 'destroy'])->name('projects.risks.destroy');

  // Project performance routes
  Route::get('/projects/{project}/performance', [ProjectPerformanceController::class, 'index'])->name('projects.performance.index');
  Route::post('/projects/{project}/performance', [ProjectPerformanceController::class, 'store'])->name('projects.performance.store');
  Route::get('/projects/{project}/performance/{metric}', [ProjectPerformanceController::class, 'show'])->name('projects.performance.show');
  Route::put('/projects/{project}/performance/{metric}', [ProjectPerformanceController::class, 'update'])->name('projects.performance.update');
  Route::delete('/projects/{project}/performance/{metric}', [ProjectPerformanceController::class, 'destroy'])->name('projects.performance.destroy');
  Route::get('/projects/{project}/performance/export', [ProjectPerformanceController::class, 'export'])->name('projects.performance.export');

  // Basic project routes with parameters (must come last)
  Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
  Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
  Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
  Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});
