<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\Regulatory\CompanyRegistrationController;
use App\Http\Controllers\Tenant\Regulatory\RegulatoryDashboardController;

/*
|--------------------------------------------------------------------------
| Regulatory Affairs Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the Regulatory Affairs module which handles
| pharmaceutical industry compliance, inspections, certifications,
| product recalls, and regulatory documentation.
|
*/

// Regulatory Dashboard
Route::get('dashboard', [RegulatoryDashboardController::class, 'index'])->name('dashboard');

// Company Registrations - Basic routes for testing
Route::get('companies', function() {
    return view('tenant.regulatory.companies.index');
})->name('companies.index');

Route::get('companies/create', function() {
    return view('tenant.regulatory.companies.create');
})->name('companies.create');

// Company Export/Import Routes
Route::get('companies/export', [App\Http\Controllers\Tenant\Regulatory\CompanyExportImportController::class, 'exportToExcel'])->name('companies.export');
Route::get('companies/import', [App\Http\Controllers\Tenant\Regulatory\CompanyExportImportController::class, 'showImportForm'])->name('companies.import.form');
Route::post('companies/import', [App\Http\Controllers\Tenant\Regulatory\CompanyExportImportController::class, 'importFromExcel'])->name('companies.import');
Route::get('companies/download-template', [App\Http\Controllers\Tenant\Regulatory\CompanyExportImportController::class, 'downloadTemplate'])->name('companies.download-template');

Route::get('products', function() {
    return view('tenant.regulatory.products.index');
})->name('products.index');

// Laboratory Tests Routes
Route::get('laboratory-tests', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'index'])->name('laboratory-tests.index');
Route::get('laboratory-tests/create', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'create'])->name('laboratory-tests.create');
Route::post('laboratory-tests', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'store'])->name('laboratory-tests.store');
Route::get('laboratory-tests/import', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'showImportForm'])->name('laboratory-tests.import.form');
Route::post('laboratory-tests/import', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'importFromExcel'])->name('laboratory-tests.import');
Route::get('laboratory-tests/export', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'exportToExcel'])->name('laboratory-tests.export');
Route::get('laboratory-tests/download-template', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'downloadTemplate'])->name('laboratory-tests.download-template');
Route::get('laboratory-tests/schedule', [App\Http\Controllers\Tenant\Regulatory\LaboratoryTestController::class, 'showSchedule'])->name('laboratory-tests.schedule');

// Inspections Routes
Route::get('inspections', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'index'])->name('inspections.index');
Route::get('inspections/create', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'create'])->name('inspections.create');
Route::post('inspections', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'store'])->name('inspections.store');
Route::get('inspections/import', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'showImportForm'])->name('inspections.import.form');
Route::post('inspections/import', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'importFromExcel'])->name('inspections.import');
Route::get('inspections/export', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'exportToExcel'])->name('inspections.export');
Route::get('inspections/download-template', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'downloadTemplate'])->name('inspections.download-template');
Route::get('inspections/schedule', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'showSchedule'])->name('inspections.schedule');
Route::get('inspections/calendar', [App\Http\Controllers\Tenant\Regulatory\InspectionController::class, 'showCalendar'])->name('inspections.calendar');

// Certificates Routes
Route::get('certificates', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'index'])->name('certificates.index');
Route::get('certificates/create', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'create'])->name('certificates.create');
Route::post('certificates', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'store'])->name('certificates.store');
Route::get('certificates/import', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'showImportForm'])->name('certificates.import.form');
Route::post('certificates/import', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'importFromExcel'])->name('certificates.import');
Route::get('certificates/export', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'exportToExcel'])->name('certificates.export');
Route::get('certificates/download-template', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'downloadTemplate'])->name('certificates.download-template');
Route::get('certificates/renewal', [App\Http\Controllers\Tenant\Regulatory\CertificateController::class, 'showRenewal'])->name('certificates.renewal');

// Product Recalls Routes
Route::get('recalls', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'index'])->name('recalls.index');
Route::get('recalls/create', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'create'])->name('recalls.create');
Route::post('recalls', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'store'])->name('recalls.store');
Route::get('recalls/import', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'showImportForm'])->name('recalls.import.form');
Route::post('recalls/import', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'importFromExcel'])->name('recalls.import');
Route::get('recalls/export', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'exportToExcel'])->name('recalls.export');
Route::get('recalls/download-template', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'downloadTemplate'])->name('recalls.download-template');

// Product Recalls Routes (Alternative naming for compatibility)
Route::get('product-recalls', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'index'])->name('product-recalls.index');
Route::get('product-recalls/create', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'create'])->name('product-recalls.create');
Route::post('product-recalls', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'store'])->name('product-recalls.store');
Route::get('product-recalls/import', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'showImportForm'])->name('product-recalls.import.form');
Route::post('product-recalls/import', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'importFromExcel'])->name('product-recalls.import');
Route::get('product-recalls/export', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'exportToExcel'])->name('product-recalls.export');
Route::get('product-recalls/download-template', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'downloadTemplate'])->name('product-recalls.download-template');
Route::get('product-recalls/statistics', [App\Http\Controllers\Tenant\Regulatory\ProductRecallController::class, 'showStatistics'])->name('product-recalls.statistics');

// Regulatory Reports Routes
Route::get('reports', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'index'])->name('reports.index');
Route::get('reports/create', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'create'])->name('reports.create');
Route::post('reports', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'store'])->name('reports.store');
Route::get('reports/import', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'showImportForm'])->name('reports.import.form');
Route::post('reports/import', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'importFromExcel'])->name('reports.import');
Route::get('reports/export', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'exportToExcel'])->name('reports.export');
Route::get('reports/download-template', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'downloadTemplate'])->name('reports.download-template');
Route::get('reports/templates', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'showTemplates'])->name('reports.templates');
Route::get('reports/templates/{templateId}/create', [App\Http\Controllers\Tenant\Regulatory\RegulatoryReportController::class, 'createFromTemplate'])->name('reports.create-from-template');

Route::get('documents', function() {
    return view('tenant.regulatory.documents.index');
})->name('documents.index');

// Test Buttons Page
Route::get('test-buttons', function() {
    return view('tenant.regulatory.test-buttons');
})->name('test-buttons');

// Regulatory Documents Routes
Route::get('documents', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'index'])->name('documents.index');
Route::get('documents/create', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'create'])->name('documents.create');
Route::post('documents', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'store'])->name('documents.store');
Route::get('documents/{id}/download', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'download'])->name('documents.download');
Route::get('documents/bulk-upload', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'showBulkUpload'])->name('documents.bulk-upload');
Route::post('documents/bulk-upload', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'bulkUpload'])->name('documents.bulk-upload.store');
Route::get('documents/export', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'exportToExcel'])->name('documents.export');
Route::get('documents/archive', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'showArchive'])->name('documents.archive');
Route::post('documents/{id}/archive', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'archive'])->name('documents.archive.store');
Route::post('documents/{id}/restore', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'restore'])->name('documents.restore');
Route::get('documents/download-all', [App\Http\Controllers\Tenant\Regulatory\RegulatoryDocumentController::class, 'downloadAll'])->name('documents.download-all');