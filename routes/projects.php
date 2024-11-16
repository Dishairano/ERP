<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoreProjectController;
use App\Http\Controllers\CoreProjectCreatePageController;
use App\Http\Controllers\CoreProjectShowPageController;
use App\Http\Controllers\CoreProjectTaskController;
use App\Http\Controllers\CoreProjectRiskController;
use App\Http\Controllers\CoreProjectTimeController;

Route::middleware(['web', 'auth'])->group(function () {
    // Project Dashboard
    Route::get('/projects/dashboard', [CoreProjectController::class, 'dashboard'])
        ->name('projects.dashboard');

    // Project CRUD Routes
    Route::resource('projects', CoreProjectController::class);

    // Project Creation Page
    Route::get('/projects/create/page', [CoreProjectCreatePageController::class, 'create'])
        ->name('projects.create.page');

    // Project Show Page
    Route::get('/projects/{project}/page', [CoreProjectShowPageController::class, 'show'])
        ->name('projects.show.page');

    // Project Tasks
    Route::resource('projects.tasks', CoreProjectTaskController::class);
    Route::post('/projects/{project}/tasks/{task}/complete', [CoreProjectTaskController::class, 'complete'])
        ->name('projects.tasks.complete');

    // Project Risks
    Route::resource('projects.risks', CoreProjectRiskController::class);
    Route::get('/projects/{project}/risks/matrix', [CoreProjectRiskController::class, 'matrix'])
        ->name('projects.risks.matrix');
    Route::get('/projects/{project}/risks/report', [CoreProjectRiskController::class, 'report'])
        ->name('projects.risks.report');

    // Project Time Tracking
    Route::get('/projects/{project}/time', [CoreProjectTimeController::class, 'tracking'])
        ->name('project-time.tracking');

    // Project API Routes
    Route::prefix('api')->group(function () {
        Route::get('/projects/{project}/tasks', [CoreProjectTaskController::class, 'getTasks'])
            ->name('api.project.tasks');
        Route::get('/projects/{project}/time/stats', [CoreProjectTimeController::class, 'getStats'])
            ->name('api.project.time.stats');
    });
});
