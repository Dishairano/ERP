<?php

use App\Http\Controllers\Settings\GeneralSettingsController;
use App\Http\Controllers\Settings\CompanySettingsController;
use App\Http\Controllers\Settings\NotificationSettingsController;
use App\Http\Controllers\Settings\IntegrationSettingsController;
use App\Http\Controllers\Settings\RoleSettingsController;
use App\Http\Controllers\Settings\BackupSettingsController;
use App\Http\Controllers\Settings\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('settings')->name('settings.')->group(function () {
  // General settings
  Route::get('/general', [GeneralSettingsController::class, 'index'])->name('general');
  Route::post('/general', [GeneralSettingsController::class, 'update'])->name('general.update');

  // Company settings
  Route::get('/company', [CompanySettingsController::class, 'index'])->name('company');
  Route::post('/company', [CompanySettingsController::class, 'update'])->name('company.update');

  // System configuration routes
  Route::prefix('system')->name('system.')->group(function () {
    // Notification settings
    Route::get('/notifications', [NotificationSettingsController::class, 'index'])->name('notifications');
    Route::post('/notifications', [NotificationSettingsController::class, 'update'])->name('notifications.update');

    // Integration settings
    Route::get('/integrations', [IntegrationSettingsController::class, 'index'])->name('integrations');
    Route::post('/integrations', [IntegrationSettingsController::class, 'update'])->name('integrations.update');

    // Backup & Recovery settings
    Route::get('/backup', [BackupSettingsController::class, 'index'])->name('backup');
    Route::post('/backup/create', [BackupSettingsController::class, 'createBackup'])->name('backup.create');
    Route::post('/backup/restore/{backup}', [BackupSettingsController::class, 'restore'])->name('backup.restore');
    Route::get('/backup/download/{backup}', [BackupSettingsController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{backup}', [BackupSettingsController::class, 'destroy'])->name('backup.destroy');

    // Backup schedules
    Route::get('/backup/schedules', [BackupSettingsController::class, 'schedules'])->name('backup.schedules');
    Route::post('/backup/schedules', [BackupSettingsController::class, 'storeSchedule'])->name('backup.schedules.store');
    Route::put('/backup/schedules/{schedule}', [BackupSettingsController::class, 'updateSchedule'])->name('backup.schedules.update');
    Route::delete('/backup/schedules/{schedule}', [BackupSettingsController::class, 'destroySchedule'])->name('backup.schedules.destroy');
  });

  // Access control routes
  Route::prefix('access')->name('access.')->group(function () {
    // Roles & Permissions
    Route::get('/roles', [RoleSettingsController::class, 'index'])->name('roles');
    Route::get('/roles/create', [RoleSettingsController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleSettingsController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleSettingsController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleSettingsController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleSettingsController::class, 'destroy'])->name('roles.destroy');

    // User Management
    Route::get('/users', [RoleSettingsController::class, 'users'])->name('users');
    Route::get('/users/create', [RoleSettingsController::class, 'createUser'])->name('users.create');
    Route::post('/users', [RoleSettingsController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [RoleSettingsController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [RoleSettingsController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [RoleSettingsController::class, 'destroyUser'])->name('users.destroy');

    // Audit Log
    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log');
    Route::get('/audit-log/export', [AuditLogController::class, 'export'])->name('audit-log.export');
    Route::get('/audit-log/archives', [AuditLogController::class, 'archives'])->name('audit-log.archives');
    Route::post('/audit-log/archive', [AuditLogController::class, 'createArchive'])->name('audit-log.archive');
    Route::get('/audit-log/archives/{archive}/download', [AuditLogController::class, 'downloadArchive'])->name('audit-log.archives.download');
    Route::delete('/audit-log/archives/{archive}', [AuditLogController::class, 'destroyArchive'])->name('audit-log.archives.destroy');
  });
});
