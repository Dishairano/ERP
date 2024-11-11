<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditTrailController;

Route::middleware(['auth'])->group(function () {
  Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit-trail.index');
  Route::get('/audit-trail/{id}', [AuditTrailController::class, 'show'])->name('audit-trail.show');
  Route::get('/audit-trail/export', [AuditTrailController::class, 'export'])->name('audit-trail.export');
  Route::get('/audit-trail/settings', [AuditTrailController::class, 'settings'])->name('audit-trail.settings');
  Route::post('/audit-trail/settings', [AuditTrailController::class, 'updateSettings'])->name('audit-trail.settings.update');
  Route::get('/audit-trail/notifications', [AuditTrailController::class, 'notifications'])->name('audit-trail.notifications');
  Route::post('/audit-trail/notifications', [AuditTrailController::class, 'updateNotifications'])->name('audit-trail.notifications.update');
});
