<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Analytics\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Analytics Routes
|--------------------------------------------------------------------------
|
| Here is where you can register analytics routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "tenant" middleware group.
|
*/

// Analytics Dashboard (redirect to predictions)
Route::get('/', [AnalyticsController::class, 'predictions'])->name('dashboard');

// Market Analysis
Route::get('/market-trends', [AnalyticsController::class, 'marketTrends'])->name('market-trends');

// Customer Behavior Analysis
Route::get('/customer-behavior', [AnalyticsController::class, 'customerBehavior'])->name('customer-behavior');

// AI Predictions
Route::get('/predictions', [AnalyticsController::class, 'predictions'])->name('predictions');

// Profitability Analysis
Route::get('/profitability', [AnalyticsController::class, 'profitability'])->name('profitability');

// Risk Management
Route::get('/risk-management', [AnalyticsController::class, 'riskManagement'])->name('risk-management');

// Executive Reports
Route::get('/executive-reports', [AnalyticsController::class, 'executiveReports'])->name('executive-reports');

// Real-time Data API
Route::get('/realtime-data', [AnalyticsController::class, 'getRealtimeData'])->name('realtime-data');
