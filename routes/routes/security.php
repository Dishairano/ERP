<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecurityController;

Route::middleware(['auth'])->group(function () {
  // Security Dashboard
  Route::get('/security', [SecurityController::class, 'dashboard'])->name('security.dashboard');

  // Security Settings
  Route::get('/security/settings', [SecurityController::class, 'settings'])->name('security.settings');
  Route::post('/security/settings', [SecurityController::class, 'updateSettings'])->name('security.settings.update');

  // Two-Factor Authentication
  Route::get('/security/2fa/enable', [SecurityController::class, 'showTwoFactorSetup'])->name('security.2fa.enable');
  Route::post('/security/2fa/enable', [SecurityController::class, 'enableTwoFactor'])->name('security.2fa.store');
  Route::post('/security/2fa/disable', [SecurityController::class, 'disableTwoFactor'])->name('security.2fa.disable');
  Route::post('/security/2fa/verify', [SecurityController::class, 'verifyTwoFactor'])->name('security.2fa.verify');
  Route::post('/security/2fa/resend', [SecurityController::class, 'resendVerificationCode'])->name('security.2fa.resend');

  // Session Management
  Route::delete('/security/sessions/{id}', [SecurityController::class, 'revokeSession'])->name('security.sessions.revoke');
});
