<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Authentication Routes
Route::middleware(['guest'])->group(function () {
  Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
  Route::post('login', [LoginController::class, 'login']);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
  Route::get('/', function () {
    // Return the analytics dashboard view
    return view('content.dashboard.dashboards-analytics');
  })->name('dashboard');

  // Logout Route
  Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
