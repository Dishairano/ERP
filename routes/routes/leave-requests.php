<?php

use App\Http\Controllers\LeaveRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
  Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
  Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
  Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
  Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
  Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
});
