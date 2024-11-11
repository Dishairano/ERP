<?php

use App\Http\Controllers\CoreProjectController;
use App\Http\Controllers\CoreProjectCreatePageController;
use App\Http\Controllers\CoreProjectEditPageController;
use App\Http\Controllers\CoreProjectIndexPageController;
use App\Http\Controllers\CoreProjectShowPageController;
use App\Http\Controllers\CoreProjectTaskController;
use App\Http\Controllers\CoreProjectRiskController;
use App\Http\Controllers\CoreProjectTemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Project CRUD
  Route::get('/projects', [CoreProjectIndexPageController::class, 'index'])->name('projects.index');
  Route::get('/projects/create', [CoreProjectCreatePageController::class, 'index'])->name('projects.create');
  Route::post('/projects', [CoreProjectCreatePageController::class, 'store'])->name('projects.store');
  Route::get('/projects/{project}', [CoreProjectShowPageController::class, 'show'])->name('projects.show');
  Route::get('/projects/{project}/edit', [CoreProjectEditPageController::class, 'edit'])->name('projects.edit');
  Route::put('/projects/{project}', [CoreProjectEditPageController::class, 'update'])->name('projects.update');
  Route::delete('/projects/{project}', [CoreProjectEditPageController::class, 'destroy'])->name('projects.destroy');

  // Project Dashboard
  Route::get('/projects/dashboard', [CoreProjectController::class, 'dashboard'])->name('projects.dashboard');

  // Project Tasks (Nested under projects)
  Route::get('/projects/{project}/tasks', [CoreProjectTaskController::class, 'index'])->name('projects.tasks.index');
  Route::get('/projects/{project}/tasks/create', [CoreProjectTaskController::class, 'create'])->name('projects.tasks.create');
  Route::post('/projects/{project}/tasks', [CoreProjectTaskController::class, 'store'])->name('projects.tasks.store');
  Route::get('/projects/{project}/tasks/{task}', [CoreProjectTaskController::class, 'show'])->name('projects.tasks.show');
  Route::get('/projects/{project}/tasks/{task}/edit', [CoreProjectTaskController::class, 'edit'])->name('projects.tasks.edit');
  Route::put('/projects/{project}/tasks/{task}', [CoreProjectTaskController::class, 'update'])->name('projects.tasks.update');
  Route::delete('/projects/{project}/tasks/{task}', [CoreProjectTaskController::class, 'destroy'])->name('projects.tasks.destroy');

  // Additional Task Routes
  Route::post('/projects/{project}/tasks/{task}/comments', [CoreProjectTaskController::class, 'addComment'])->name('projects.tasks.add-comment');
  Route::post('/projects/{project}/tasks/{task}/attachments', [CoreProjectTaskController::class, 'addAttachment'])->name('projects.tasks.add-attachment');
  Route::patch('/projects/{project}/tasks/{task}/progress', [CoreProjectTaskController::class, 'updateProgress'])->name('projects.tasks.update-progress');

  // Project Performance
  Route::get('/projects/performance', [CoreProjectController::class, 'performance'])->name('projects.performance');

  // Overall Risk Report
  Route::get('/projects/risks/report', [CoreProjectRiskController::class, 'overallReport'])->name('projects.risks.overall-report');

  // Project Risks (Nested under projects)
  Route::get('/projects/{project}/risks', [CoreProjectRiskController::class, 'index'])->name('projects.risks.index');
  Route::get('/projects/{project}/risks/matrix', [CoreProjectRiskController::class, 'matrix'])->name('projects.risks.matrix');
  Route::get('/projects/{project}/risks/report', [CoreProjectRiskController::class, 'report'])->name('projects.risks.report');
  Route::get('/projects/{project}/risks/create', [CoreProjectRiskController::class, 'create'])->name('projects.risks.create');
  Route::post('/projects/{project}/risks', [CoreProjectRiskController::class, 'store'])->name('projects.risks.store');
  Route::get('/projects/{project}/risks/{risk}', [CoreProjectRiskController::class, 'show'])->name('projects.risks.show');
  Route::get('/projects/{project}/risks/{risk}/edit', [CoreProjectRiskController::class, 'edit'])->name('projects.risks.edit');
  Route::put('/projects/{project}/risks/{risk}', [CoreProjectRiskController::class, 'update'])->name('projects.risks.update');
  Route::delete('/projects/{project}/risks/{risk}', [CoreProjectRiskController::class, 'destroy'])->name('projects.risks.destroy');

  // Project Templates
  Route::get('/projects/templates', [CoreProjectTemplateController::class, 'index'])->name('projects.templates.index');
  Route::get('/projects/templates/create', [CoreProjectTemplateController::class, 'create'])->name('projects.templates.create');
  Route::post('/projects/templates', [CoreProjectTemplateController::class, 'store'])->name('projects.templates.store');
  Route::get('/projects/templates/{template}', [CoreProjectTemplateController::class, 'show'])->name('projects.templates.show');
  Route::get('/projects/templates/{template}/edit', [CoreProjectTemplateController::class, 'edit'])->name('projects.templates.edit');
  Route::put('/projects/templates/{template}', [CoreProjectTemplateController::class, 'update'])->name('projects.templates.update');
  Route::delete('/projects/templates/{template}', [CoreProjectTemplateController::class, 'destroy'])->name('projects.templates.destroy');
  Route::post('/projects/templates/{template}/duplicate', [CoreProjectTemplateController::class, 'duplicate'])->name('projects.templates.duplicate');
});
