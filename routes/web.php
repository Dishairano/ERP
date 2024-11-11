<?php

use App\Http\Controllers\CoreDashboardController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
  // Make dashboard the default route
  Route::get('/', [CoreDashboardController::class, 'index'])->name('dashboard');
  Route::get('/dashboard', [CoreDashboardController::class, 'index'])->name('dashboard.index');
});

// Include other route files
require __DIR__ . '/core.php';
require __DIR__ . '/coreFunctions.php';
require __DIR__ . '/hrm.php';
require __DIR__ . '/finance.php';
require __DIR__ . '/projects.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/time.php';
require __DIR__ . '/time-registrations.php';
require __DIR__ . '/sales.php';
require __DIR__ . '/warehousing.php';
require __DIR__ . '/budgets.php';
require __DIR__ . '/manufacturing.php';
require __DIR__ . '/analytics.php';
