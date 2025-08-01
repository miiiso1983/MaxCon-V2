<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\FinancialController;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| Here is where you can register customer routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "customer" middleware group.
|
*/

// Customer Authentication Routes
Route::prefix('customer')->name('customer.')->group(function () {
    
    // Guest routes (not authenticated)
    Route::middleware('guest:customer')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    });

    // Authenticated customer routes
    Route::middleware('auth:customer')->group(function () {
        
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
        
        // Orders - with permission middleware
        Route::middleware('permission:place_orders,customer')->group(function () {
            Route::resource('orders', OrderController::class)->except(['destroy']);
            Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        });
        
        // View own orders (separate permission)
        Route::middleware('permission:view_own_orders,customer')->group(function () {
            Route::get('/my-orders', [OrderController::class, 'index'])->name('my-orders');
            Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('my-orders.show');
        });
        
        // Financial Information
        Route::prefix('financial')->name('financial.')->group(function () {
            
            // Financial dashboard
            Route::middleware('permission:view_financial_info,customer')->group(function () {
                Route::get('/', [FinancialController::class, 'index'])->name('index');
            });
            
            // Payment history
            Route::middleware('permission:view_payment_history,customer')->group(function () {
                Route::get('/payments', [FinancialController::class, 'payments'])->name('payments');
            });
            
            // Debt details
            Route::middleware('permission:view_debt_details,customer')->group(function () {
                Route::get('/debt', [FinancialController::class, 'debt'])->name('debt');
            });
            
            // Credit limit
            Route::middleware('permission:view_credit_limit,customer')->group(function () {
                Route::get('/credit-limit', [FinancialController::class, 'creditLimit'])->name('credit-limit');
            });
            
            // Invoices
            Route::middleware('permission:view_own_invoices,customer')->group(function () {
                Route::get('/invoices', [FinancialController::class, 'invoices'])->name('invoices');
            });
            
            // Download invoices
            Route::middleware('permission:download_invoices,customer')->group(function () {
                Route::get('/invoices/{invoice}/download', [FinancialController::class, 'downloadInvoice'])->name('invoices.download');
            });
        });
        
        // API Routes for AJAX requests
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/products/search', function () {
                // Product search for order creation
            })->name('products.search');
            
            Route::get('/financial/summary', function () {
                // Financial summary for dashboard widgets
            })->name('financial.summary');
        });
    });
});

// Note: Admin customer management routes are defined in routes/web.php
// under the admin.customers.* namespace with tenant scoping

// Tenant-specific customer routes (if using multi-tenancy)
Route::domain('{tenant}.maxcon.app')->group(function () {
    // Customer routes specific to tenant subdomain
    // This would include the same routes as above but scoped to the tenant
});
