<?php

use App\Http\Controllers\AnalyticsDashboardController;
use App\Http\Controllers\BusinessIntelligenceController;
use App\Http\Controllers\AdvancedAnalyticsController;
use App\Http\Controllers\ComplianceController;
use Illuminate\Support\Facades\Route;

// Analytics Dashboards
Route::prefix('analytics')->name('analytics.')->group(function () {
  Route::get('/executive', [AnalyticsDashboardController::class, 'executive'])->name('executive');
  Route::get('/financial', [AnalyticsDashboardController::class, 'financial'])->name('financial');
  Route::get('/operational', [AnalyticsDashboardController::class, 'operational'])->name('operational');
  Route::get('/custom', [AnalyticsDashboardController::class, 'custom'])->name('custom');
});

// Reports
Route::prefix('reports')->name('reports.')->group(function () {
  Route::get('/standard', [AnalyticsDashboardController::class, 'standardReports'])->name('standard');
  Route::get('/custom', [AnalyticsDashboardController::class, 'customReports'])->name('custom');
  Route::get('/scheduler', [AnalyticsDashboardController::class, 'reportScheduler'])->name('scheduler');
  Route::get('/exports', [AnalyticsDashboardController::class, 'exportManager'])->name('exports');
});

// Business Intelligence
Route::prefix('bi')->name('bi.')->group(function () {
  Route::get('/analysis', [BusinessIntelligenceController::class, 'analysis'])->name('analysis');
  Route::get('/visualization', [BusinessIntelligenceController::class, 'visualization'])->name('visualization');
  Route::get('/predictive', [BusinessIntelligenceController::class, 'predictive'])->name('predictive');
  Route::get('/mining', [BusinessIntelligenceController::class, 'mining'])->name('mining');
});

// KPIs
Route::prefix('kpis')->name('kpis.')->group(function () {
  Route::get('/metrics', [AnalyticsDashboardController::class, 'metrics'])->name('metrics');
  Route::get('/scorecards', [AnalyticsDashboardController::class, 'scorecards'])->name('scorecards');
  Route::get('/benchmarks', [AnalyticsDashboardController::class, 'benchmarks'])->name('benchmarks');
  Route::get('/alerts', [AnalyticsDashboardController::class, 'alerts'])->name('alerts');
});

// Data Management
Route::prefix('data-management')->name('data-management.')->group(function () {
  Route::get('/integration', [BusinessIntelligenceController::class, 'integration'])->name('integration');
  Route::get('/quality', [BusinessIntelligenceController::class, 'quality'])->name('quality');
  Route::get('/governance', [BusinessIntelligenceController::class, 'governance'])->name('governance');
  Route::get('/security', [BusinessIntelligenceController::class, 'security'])->name('security');
});

// Advanced Analytics
Route::prefix('advanced-analytics')->name('advanced-analytics.')->group(function () {
  Route::get('/ml', [AdvancedAnalyticsController::class, 'machineLearning'])->name('ml');
  Route::get('/forecasting', [AdvancedAnalyticsController::class, 'forecasting'])->name('forecasting');
  Route::get('/optimization', [AdvancedAnalyticsController::class, 'optimization'])->name('optimization');
  Route::get('/scenarios', [AdvancedAnalyticsController::class, 'scenarios'])->name('scenarios');
});

// Compliance
Route::prefix('compliance')->name('compliance.')->group(function () {
  Route::get('/requirements', [ComplianceController::class, 'requirements'])->name('requirements');
  Route::get('/audits', [ComplianceController::class, 'audits'])->name('audits');
  Route::get('/documents', [ComplianceController::class, 'documents'])->name('documents');
  Route::get('/trainings', [ComplianceController::class, 'trainings'])->name('trainings');
  Route::get('/notifications', [ComplianceController::class, 'notifications'])->name('notifications');
});
