<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataAnalysisController;

Route::middleware(['auth'])->group(function () {
  Route::get('/data-analysis', [DataAnalysisController::class, 'index'])->name('data-analysis.index');
  Route::get('/data-analysis/create', [DataAnalysisController::class, 'create'])->name('data-analysis.create');
  Route::post('/data-analysis', [DataAnalysisController::class, 'store'])->name('data-analysis.store');
  Route::get('/data-analysis/{id}', [DataAnalysisController::class, 'show'])->name('data-analysis.show');
  Route::get('/data-analysis/{id}/edit', [DataAnalysisController::class, 'edit'])->name('data-analysis.edit');
  Route::put('/data-analysis/{id}', [DataAnalysisController::class, 'update'])->name('data-analysis.update');
  Route::delete('/data-analysis/{id}', [DataAnalysisController::class, 'destroy'])->name('data-analysis.destroy');
  Route::get('/data-analysis/dashboard', [DataAnalysisController::class, 'dashboard'])->name('data-analysis.dashboard');
});
