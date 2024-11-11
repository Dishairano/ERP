<?php

use App\Http\Controllers\CoreSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  // Main Settings Page
  Route::get('/settings', [CoreSettingController::class, 'index'])->name('settings');

  // General Settings
  Route::get('/settings/general', [CoreSettingController::class, 'general'])->name('settings.general');
  Route::post('/settings/general', [CoreSettingController::class, 'updateGeneral'])->name('settings.general.update');

  // Company Profile
  Route::get('/settings/company', [CoreSettingController::class, 'company'])->name('settings.company');
  Route::post('/settings/company', [CoreSettingController::class, 'updateCompany'])->name('settings.company.update');

  // Notifications
  Route::get('/settings/notifications', [CoreSettingController::class, 'notifications'])->name('settings.notifications');
  Route::post('/settings/notifications', [CoreSettingController::class, 'updateNotifications'])->name('settings.notifications.update');

  // Integrations
  Route::get('/settings/integrations', [CoreSettingController::class, 'integrations'])->name('settings.integrations');
  Route::post('/settings/integrations', [CoreSettingController::class, 'updateIntegrations'])->name('settings.integrations.update');

  // Backup & Recovery
  Route::get('/settings/backup', [CoreSettingController::class, 'backup'])->name('settings.backup');
  Route::post('/settings/backup/create', [CoreSettingController::class, 'createBackup'])->name('settings.backup.create');
  Route::post('/settings/backup/restore', [CoreSettingController::class, 'restoreBackup'])->name('settings.backup.restore');

  // Roles & Permissions
  Route::get('/settings/roles', [CoreSettingController::class, 'roles'])->name('settings.roles');
  Route::post('/settings/roles', [CoreSettingController::class, 'storeRole'])->name('settings.roles.store');
  Route::put('/settings/roles/{role}', [CoreSettingController::class, 'updateRole'])->name('settings.roles.update');
  Route::delete('/settings/roles/{role}', [CoreSettingController::class, 'deleteRole'])->name('settings.roles.delete');

  // User Management
  Route::get('/settings/users', [CoreSettingController::class, 'users'])->name('settings.users');
  Route::post('/settings/users', [CoreSettingController::class, 'storeUser'])->name('settings.users.store');
  Route::put('/settings/users/{user}', [CoreSettingController::class, 'updateUser'])->name('settings.users.update');
  Route::delete('/settings/users/{user}', [CoreSettingController::class, 'deleteUser'])->name('settings.users.delete');

  // Audit Log
  Route::get('/settings/audit-log', [CoreSettingController::class, 'auditLog'])->name('settings.audit-log');
  Route::get('/settings/audit-log/export', [CoreSettingController::class, 'exportAuditLog'])->name('settings.audit-log.export');

  // Security Settings
  Route::get('/settings/security', [CoreSettingController::class, 'security'])->name('settings.security');
  Route::post('/settings/security', [CoreSettingController::class, 'updateSecurity'])->name('settings.security.update');

  // Localization
  Route::get('/settings/localization', [CoreSettingController::class, 'localization'])->name('settings.localization');
  Route::post('/settings/localization', [CoreSettingController::class, 'updateLocalization'])->name('settings.localization.update');

  // Email Configuration
  Route::get('/settings/email', [CoreSettingController::class, 'email'])->name('settings.email');
  Route::post('/settings/email', [CoreSettingController::class, 'updateEmail'])->name('settings.email.update');
  Route::post('/settings/email/test', [CoreSettingController::class, 'testEmail'])->name('settings.email.test');

  // Workflow Settings
  Route::get('/settings/workflow', [CoreSettingController::class, 'workflow'])->name('settings.workflow');
  Route::post('/settings/workflow', [CoreSettingController::class, 'updateWorkflow'])->name('settings.workflow.update');

  // API Settings
  Route::get('/settings/api', [CoreSettingController::class, 'api'])->name('settings.api');
  Route::post('/settings/api', [CoreSettingController::class, 'updateApi'])->name('settings.api.update');
  Route::post('/settings/api/generate-key', [CoreSettingController::class, 'generateApiKey'])->name('settings.api.generate-key');
});
