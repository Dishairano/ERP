<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PushNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', function (Request $request) {
    return $request->user();
  });

  // Push Notification Routes
  Route::prefix('notifications')->group(function () {
    Route::post('/register-device', [PushNotificationController::class, 'registerDevice']);
    Route::put('/update-preferences', [PushNotificationController::class, 'updatePreferences']);
    Route::post('/deactivate-device', [PushNotificationController::class, 'deactivateDevice']);
    Route::get('/settings', [PushNotificationController::class, 'getSettings']);
  });
});
