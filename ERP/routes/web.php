<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ... other routes ...

// Notification Settings Routes
Route::middleware(['auth'])->group(function () {
  Route::get('/notifications/settings', [PushNotificationController::class, 'showSettings'])
    ->name('notifications.settings');
  Route::put('/notifications/preferences', [PushNotificationController::class, 'updatePreferences'])
    ->name('notifications.update-preferences');
  Route::post('/notifications/deactivate-device', [PushNotificationController::class, 'deactivateDevice'])
    ->name('notifications.deactivate-device');
});
