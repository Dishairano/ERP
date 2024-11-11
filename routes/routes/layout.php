<?php

require_once __DIR__ . '/base.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\layouts\{WithoutMenu, WithoutNavbar, Fluid, Container, Blank};
use App\Http\Controllers\pages\{AccountSettingsAccount, AccountSettingsNotifications, AccountSettingsConnections, MiscError, MiscUnderMaintenance};
use App\Http\Controllers\authentications\{LoginBasic, RegisterBasic, ForgotPasswordBasic};
use App\Http\Controllers\cards\CardBasic;

// Layout Routes
Route::get('layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// Pages Routes
Route::get('pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// Authentication Routes
Route::get('auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-forgot-password-basic');

// Cards Route
Route::get('cards/basic', [CardBasic::class, 'index'])->name('cards-basic');
