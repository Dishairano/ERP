<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Root route - redirect based on auth status
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// Include route files - these are handled by RouteServiceProvider with proper middleware
require __DIR__.'/core.php';
require __DIR__.'/projects.php';
require __DIR__.'/finance.php';
require __DIR__.'/hrm.php';
require __DIR__.'/leave-requests.php';
require __DIR__.'/time-registrations.php';
require __DIR__.'/settings.php';
require __DIR__.'/scheduling.php';
