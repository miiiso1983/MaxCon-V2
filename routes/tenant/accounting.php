<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Accounting\ChartOfAccountController;
use App\Http\Controllers\Tenant\Accounting\CostCenterController;
use App\Http\Controllers\Tenant\Accounting\JournalEntryController;
use App\Http\Controllers\Tenant\Accounting\FinancialReportController;

/*
|--------------------------------------------------------------------------
| Tenant Accounting Routes
|--------------------------------------------------------------------------
|
| Here is where you can register accounting routes for tenants. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "tenant" middleware group.
|
*/

Route::prefix('accounting')->name('accounting.')->group(function () {

    // Chart of Accounts Routes
    Route::prefix('chart-of-accounts')->name('chart-of-accounts.')->group(function () {
        Route::get('/', [ChartOfAccountController::class, 'index'])->name('index');
        Route::get('/create', [ChartOfAccountController::class, 'create'])->name('create');
        Route::post('/', [ChartOfAccountController::class, 'store'])->name('store');
        Route::get('/{chartOfAccount}', [ChartOfAccountController::class, 'show'])->name('show');
        Route::get('/{chartOfAccount}/edit', [ChartOfAccountController::class, 'edit'])->name('edit');
        Route::put('/{chartOfAccount}', [ChartOfAccountController::class, 'update'])->name('update');
        Route::delete('/{chartOfAccount}', [ChartOfAccountController::class, 'destroy'])->name('destroy');

        // Additional Chart of Accounts Routes
        Route::post('/{chartOfAccount}/activate', [ChartOfAccountController::class, 'activate'])->name('activate');
        Route::post('/{chartOfAccount}/deactivate', [ChartOfAccountController::class, 'deactivate'])->name('deactivate');
        Route::get('/export/excel', [ChartOfAccountController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ChartOfAccountController::class, 'exportPdf'])->name('export.pdf');
    });

    // Cost Centers Routes
    Route::prefix('cost-centers')->name('cost-centers.')->group(function () {
        Route::get('/', [CostCenterController::class, 'index'])->name('index');
        Route::get('/create', [CostCenterController::class, 'create'])->name('create');
        Route::post('/', [CostCenterController::class, 'store'])->name('store');
        Route::get('/{costCenter}', [CostCenterController::class, 'show'])->name('show');
        Route::get('/{costCenter}/edit', [CostCenterController::class, 'edit'])->name('edit');
        Route::put('/{costCenter}', [CostCenterController::class, 'update'])->name('update');
        Route::delete('/{costCenter}', [CostCenterController::class, 'destroy'])->name('destroy');

        // Additional Cost Center Routes
        Route::post('/{costCenter}/activate', [CostCenterController::class, 'activate'])->name('activate');
        Route::post('/{costCenter}/deactivate', [CostCenterController::class, 'deactivate'])->name('deactivate');
        Route::get('/{costCenter}/budget-report', [CostCenterController::class, 'budgetReport'])->name('budget-report');
        Route::post('/{costCenter}/update-budget', [CostCenterController::class, 'updateBudget'])->name('update-budget');
    });

    // Journal Entries Routes
    Route::prefix('journal-entries')->name('journal-entries.')->group(function () {
        Route::get('/', [JournalEntryController::class, 'index'])->name('index');
        Route::get('/create', [JournalEntryController::class, 'create'])->name('create');
        Route::post('/', [JournalEntryController::class, 'store'])->name('store');
        Route::get('/{journalEntry}', [JournalEntryController::class, 'show'])->name('show');
        Route::get('/{journalEntry}/edit', [JournalEntryController::class, 'edit'])->name('edit');
        Route::put('/{journalEntry}', [JournalEntryController::class, 'update'])->name('update');
        Route::delete('/{journalEntry}', [JournalEntryController::class, 'destroy'])->name('destroy');

        // Journal Entry Workflow Routes
        Route::post('/{journalEntry}/submit', [JournalEntryController::class, 'submit'])->name('submit');
        Route::post('/{journalEntry}/approve', [JournalEntryController::class, 'approve'])->name('approve');
        Route::post('/{journalEntry}/reject', [JournalEntryController::class, 'reject'])->name('reject');
        Route::post('/{journalEntry}/post', [JournalEntryController::class, 'post'])->name('post');
        Route::post('/{journalEntry}/reverse', [JournalEntryController::class, 'reverse'])->name('reverse');

        // Additional Journal Entry Routes
        Route::get('/{journalEntry}/duplicate', [JournalEntryController::class, 'duplicate'])->name('duplicate');
        Route::get('/{journalEntry}/print', [JournalEntryController::class, 'print'])->name('print');
        Route::get('/export/excel', [JournalEntryController::class, 'exportExcel'])->name('export.excel');
    });

    // Financial Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [FinancialReportController::class, 'index'])->name('index');

        // Trial Balance
        Route::get('/trial-balance', [FinancialReportController::class, 'trialBalance'])->name('trial-balance');
        Route::get('/trial-balance/export/excel', [FinancialReportController::class, 'trialBalanceExcel'])->name('trial-balance.excel');
        Route::get('/trial-balance/export/pdf', [FinancialReportController::class, 'trialBalancePdf'])->name('trial-balance.pdf');

        // Income Statement
        Route::get('/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('income-statement');
        Route::get('/income-statement/export/excel', [FinancialReportController::class, 'incomeStatementExcel'])->name('income-statement.excel');
        Route::get('/income-statement/export/pdf', [FinancialReportController::class, 'incomeStatementPdf'])->name('income-statement.pdf');

        // Balance Sheet
        Route::get('/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('balance-sheet');
        Route::get('/balance-sheet/export/excel', [FinancialReportController::class, 'balanceSheetExcel'])->name('balance-sheet.excel');
        Route::get('/balance-sheet/export/pdf', [FinancialReportController::class, 'balanceSheetPdf'])->name('balance-sheet.pdf');

        // Cash Flow Statement
        Route::get('/cash-flow', [FinancialReportController::class, 'cashFlow'])->name('cash-flow');
        Route::get('/cash-flow/export/excel', [FinancialReportController::class, 'cashFlowExcel'])->name('cash-flow.excel');
        Route::get('/cash-flow/export/pdf', [FinancialReportController::class, 'cashFlowPdf'])->name('cash-flow.pdf');

        // Account Ledger
        Route::get('/account-ledger', [FinancialReportController::class, 'accountLedger'])->name('account-ledger');
        Route::get('/account-ledger/export/excel', [FinancialReportController::class, 'accountLedgerExcel'])->name('account-ledger.excel');
        Route::get('/account-ledger/export/pdf', [FinancialReportController::class, 'accountLedgerPdf'])->name('account-ledger.pdf');

        // General Ledger
        Route::get('/general-ledger', [FinancialReportController::class, 'generalLedger'])->name('general-ledger');
        Route::get('/general-ledger/export/excel', [FinancialReportController::class, 'generalLedgerExcel'])->name('general-ledger.excel');

        // Aged Receivables
        Route::get('/aged-receivables', [FinancialReportController::class, 'agedReceivables'])->name('aged-receivables');
        Route::get('/aged-receivables/export/excel', [FinancialReportController::class, 'agedReceivablesExcel'])->name('aged-receivables.excel');

        // Aged Payables
        Route::get('/aged-payables', [FinancialReportController::class, 'agedPayables'])->name('aged-payables');
        Route::get('/aged-payables/export/excel', [FinancialReportController::class, 'agedPayablesExcel'])->name('aged-payables.excel');

        // Cost Center Reports
        Route::get('/cost-center-analysis', [FinancialReportController::class, 'costCenterAnalysis'])->name('cost-center-analysis');
        Route::get('/budget-variance', [FinancialReportController::class, 'budgetVariance'])->name('budget-variance');

        // Custom Reports
        Route::get('/custom', [FinancialReportController::class, 'customReports'])->name('custom');
        Route::post('/custom/generate', [FinancialReportController::class, 'generateCustomReport'])->name('custom.generate');
    });

    // API Routes for AJAX calls
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/accounts/search', [ChartOfAccountController::class, 'searchAccounts'])->name('accounts.search');
        Route::get('/accounts/{account}/balance', [ChartOfAccountController::class, 'getAccountBalance'])->name('accounts.balance');
        Route::get('/cost-centers/search', [CostCenterController::class, 'searchCostCenters'])->name('cost-centers.search');
        Route::get('/journal-entries/validate', [JournalEntryController::class, 'validateEntry'])->name('journal-entries.validate');
        Route::get('/exchange-rates/latest', [FinancialReportController::class, 'getLatestExchangeRates'])->name('exchange-rates.latest');
    });

    // Dashboard and Analytics
    Route::get('/dashboard', [FinancialReportController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [FinancialReportController::class, 'analytics'])->name('analytics');

    // Settings and Configuration
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [FinancialReportController::class, 'settings'])->name('index');
        Route::post('/fiscal-year', [FinancialReportController::class, 'updateFiscalYear'])->name('fiscal-year');
        Route::post('/currencies', [FinancialReportController::class, 'updateCurrencies'])->name('currencies');
        Route::post('/account-numbering', [FinancialReportController::class, 'updateAccountNumbering'])->name('account-numbering');
    // Receivables (Collections)
    Route::prefix('receivables')->name('receivables.')->group(function(){
        Route::get('/', [\App\Http\Controllers\Tenant\Accounting\ReceivablesController::class, 'index'])->name('index');
        Route::get('/invoice/{invoice}', [\App\Http\Controllers\Tenant\Accounting\ReceivablesController::class, 'showInvoice'])->name('invoice');
        Route::post('/invoice/{invoice}/payments', [\App\Http\Controllers\Tenant\Accounting\ReceivablesController::class, 'storePayment'])->name('invoice.payments.store');
        Route::post('/payments/{payment}/send-whatsapp', [\App\Http\Controllers\Tenant\Accounting\ReceivablesController::class, 'sendReceiptWhatsApp'])->name('payments.send-whatsapp');
    });
    });
});
