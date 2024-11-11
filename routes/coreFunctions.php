<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreProjectDashboardController;
use App\Http\Controllers\CoreProjectTaskController;
use App\Http\Controllers\CoreProjectRiskController;
use App\Http\Controllers\CoreProjectTemplateController;
use App\Http\Controllers\CoreSettingsController;

Route::middleware(['auth'])->group(function () {
  // Project Dashboard Routes
  Route::prefix('projects')->group(function () {
    Route::get('/dashboard', [CoreProjectDashboardController::class, 'index'])->name('projects.dashboard');
    Route::get('/dashboard/{project}', [CoreProjectDashboardController::class, 'show'])->name('projects.dashboard.show');
    Route::post('/dashboard/{project}/refresh', [CoreProjectDashboardController::class, 'refreshMetrics'])->name('projects.dashboard.refresh');
    Route::get('/dashboard/{project}/timeline', [CoreProjectDashboardController::class, 'getProjectTimeline'])->name('projects.dashboard.timeline');
    Route::get('/dashboard/{project}/team-performance', [CoreProjectDashboardController::class, 'getTeamPerformance'])->name('projects.dashboard.team-performance');

    // Project Tasks Routes (Nested under specific project)
    Route::prefix('{project}/tasks')->group(function () {
      Route::get('/', [CoreProjectTaskController::class, 'index'])->name('projects.tasks.index');
      Route::get('/create', [CoreProjectTaskController::class, 'create'])->name('projects.tasks.create');
      Route::post('/', [CoreProjectTaskController::class, 'store'])->name('projects.tasks.store');
      Route::get('/{task}', [CoreProjectTaskController::class, 'show'])->name('projects.tasks.show');
      Route::get('/{task}/edit', [CoreProjectTaskController::class, 'edit'])->name('projects.tasks.edit');
      Route::put('/{task}', [CoreProjectTaskController::class, 'update'])->name('projects.tasks.update');
      Route::delete('/{task}', [CoreProjectTaskController::class, 'destroy'])->name('projects.tasks.destroy');

      // Additional Task Routes
      Route::post('/{task}/comments', [CoreProjectTaskController::class, 'addComment'])->name('projects.tasks.add-comment');
      Route::post('/{task}/attachments', [CoreProjectTaskController::class, 'addAttachment'])->name('projects.tasks.add-attachment');
      Route::patch('/{task}/progress', [CoreProjectTaskController::class, 'updateProgress'])->name('projects.tasks.update-progress');
      Route::get('/{task}/timeline', [CoreProjectTaskController::class, 'getTimeline'])->name('projects.tasks.timeline');
    });

    // Project Risks Routes (Nested under specific project)
    Route::prefix('{project}/risks')->group(function () {
      Route::get('/', [CoreProjectRiskController::class, 'index'])->name('projects.risks.index');
      Route::get('/matrix', [CoreProjectRiskController::class, 'matrix'])->name('projects.risks.matrix');
      Route::get('/report', [CoreProjectRiskController::class, 'report'])->name('projects.risks.report');
      Route::get('/create', [CoreProjectRiskController::class, 'create'])->name('projects.risks.create');
      Route::post('/', [CoreProjectRiskController::class, 'store'])->name('projects.risks.store');
      Route::get('/{risk}', [CoreProjectRiskController::class, 'show'])->name('projects.risks.show');
      Route::get('/{risk}/edit', [CoreProjectRiskController::class, 'edit'])->name('projects.risks.edit');
      Route::put('/{risk}', [CoreProjectRiskController::class, 'update'])->name('projects.risks.update');
      Route::delete('/{risk}', [CoreProjectRiskController::class, 'destroy'])->name('projects.risks.destroy');
    });

    // Project Templates Routes
    Route::prefix('templates')->group(function () {
      Route::get('/', [CoreProjectTemplateController::class, 'index'])->name('projects.templates.index');
      Route::get('/create', [CoreProjectTemplateController::class, 'create'])->name('projects.templates.create');
      Route::post('/', [CoreProjectTemplateController::class, 'store'])->name('projects.templates.store');
      Route::get('/{template}', [CoreProjectTemplateController::class, 'show'])->name('projects.templates.show');
      Route::get('/{template}/edit', [CoreProjectTemplateController::class, 'edit'])->name('projects.templates.edit');
      Route::put('/{template}', [CoreProjectTemplateController::class, 'update'])->name('projects.templates.update');
      Route::delete('/{template}', [CoreProjectTemplateController::class, 'destroy'])->name('projects.templates.destroy');
      Route::post('/{template}/duplicate', [CoreProjectTemplateController::class, 'duplicate'])->name('projects.templates.duplicate');
      Route::post('/{template}/archive', [CoreProjectTemplateController::class, 'archive'])->name('projects.templates.archive');
      Route::post('/{template}/restore', [CoreProjectTemplateController::class, 'restore'])->name('projects.templates.restore');
    });
  });

  // Settings Routes
  Route::prefix('settings')->group(function () {
    // General Settings
    Route::get('/general', [CoreSettingsController::class, 'general'])->name('settings.general');
    Route::post('/general', [CoreSettingsController::class, 'updateGeneral'])->name('settings.general.update');

    // Company Profile
    Route::get('/company', [CoreSettingsController::class, 'company'])->name('settings.company');
    Route::post('/company', [CoreSettingsController::class, 'updateCompany'])->name('settings.company.update');

    // System Configuration
    Route::get('/notifications', [CoreSettingsController::class, 'notifications'])->name('settings.notifications');
    Route::post('/notifications', [CoreSettingsController::class, 'updateNotifications'])->name('settings.notifications.update');

    Route::get('/integrations', [CoreSettingsController::class, 'integrations'])->name('settings.integrations');
    Route::post('/integrations', [CoreSettingsController::class, 'updateIntegrations'])->name('settings.integrations.update');

    Route::get('/backup', [CoreSettingsController::class, 'backup'])->name('settings.backup');
    Route::post('/backup', [CoreSettingsController::class, 'createBackup'])->name('settings.backup.create');
    Route::get('/backup/{filename}', [CoreSettingsController::class, 'downloadBackup'])->name('settings.backup.download');
    Route::delete('/backup/{filename}', [CoreSettingsController::class, 'deleteBackup'])->name('settings.backup.delete');
  });
});
