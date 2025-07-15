<?php

use App\Http\Controllers\Tenant\SystemGuideController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| System Guide Routes
|--------------------------------------------------------------------------
|
| Routes for the "How to Use the System" section
|
*/

Route::prefix('system-guide')->name('system-guide.')->group(function () {
    
    // Main system guide page
    Route::get('/', [SystemGuideController::class, 'index'])->name('index');
    
    // Introduction page
    Route::get('/introduction', [SystemGuideController::class, 'introduction'])->name('introduction');
    
    // Module-specific guides
    Route::get('/module/{moduleSlug}', [SystemGuideController::class, 'module'])->name('module');
    
    // Video tutorials
    Route::get('/videos/{moduleSlug?}', [SystemGuideController::class, 'videos'])->name('videos');
    
    // FAQ page
    Route::get('/faq', [SystemGuideController::class, 'faq'])->name('faq');
    
    // Download user manual
    Route::get('/download-manual', [SystemGuideController::class, 'downloadManual'])->name('download-manual');
    
    // Interactive tour
    Route::post('/start-tour', [SystemGuideController::class, 'startTour'])->name('start-tour');
    
});
