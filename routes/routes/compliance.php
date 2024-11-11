<?php

use App\Http\Controllers\Compliance\ComplianceRequirementController;
use App\Http\Controllers\Compliance\ComplianceAuditController;
use App\Http\Controllers\Compliance\ComplianceDocumentController;
use App\Http\Controllers\Compliance\ComplianceNotificationController;
use App\Http\Controllers\Compliance\ComplianceTrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('compliance')->name('compliance.')->group(function () {
  // Requirements
  Route::resource('requirements', ComplianceRequirementController::class);

  // Audits
  Route::resource('audits', ComplianceAuditController::class);

  // Documents
  Route::resource('documents', ComplianceDocumentController::class);
  Route::get('documents/{document}/download', [ComplianceDocumentController::class, 'download'])
    ->name('documents.download');

  // Notifications
  Route::get('notifications', [ComplianceNotificationController::class, 'index'])
    ->name('notifications.index');
  Route::post('notifications', [ComplianceNotificationController::class, 'store'])
    ->name('notifications.store');
  Route::patch('notifications/{notification}/mark-as-read', [ComplianceNotificationController::class, 'markAsRead'])
    ->name('notifications.mark-as-read');
  Route::delete('notifications/{notification}', [ComplianceNotificationController::class, 'destroy'])
    ->name('notifications.destroy');

  // Trainings
  Route::resource('trainings', ComplianceTrainingController::class);
  Route::post('trainings/{training}/complete', [ComplianceTrainingController::class, 'complete'])
    ->name('trainings.complete');
});
