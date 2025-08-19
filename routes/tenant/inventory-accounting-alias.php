<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Accounting\ChartOfAccountController;
use App\Http\Controllers\Tenant\Accounting\CostCenterController;
use App\Http\Controllers\Tenant\Accounting\JournalEntryController;

// Provide compatibility aliases for old URLs under /tenant/inventory/accounting/*
Route::prefix('inventory/accounting')->name('inventory.accounting.')->group(function () {
    Route::resource('chart-of-accounts', ChartOfAccountController::class);
    Route::resource('cost-centers', CostCenterController::class);
    Route::resource('journal-entries', JournalEntryController::class);
});

