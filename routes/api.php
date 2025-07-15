<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes
Route::prefix('v1')->group(function () {
    
    // Authentication
    Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/auth/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/auth/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
    
    // Protected API routes
    Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
        
        // User profile
        Route::get('/user', function (Request $request) {
            return response()->json([
                'user' => $request->user()->load('roles', 'tenant')
            ]);
        });
        Route::post('/auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        // Route::put('/user/profile', [App\Http\Controllers\Api\UserController::class, 'updateProfile']);
        // Route::post('/user/avatar', [App\Http\Controllers\Api\UserController::class, 'uploadAvatar']);
        
        // 2FA - Commented out until controller is implemented
        // Route::post('/user/2fa/enable', [App\Http\Controllers\Api\TwoFactorController::class, 'enable']);
        // Route::post('/user/2fa/disable', [App\Http\Controllers\Api\TwoFactorController::class, 'disable']);
        // Route::post('/user/2fa/verify', [App\Http\Controllers\Api\TwoFactorController::class, 'verify']);
        
        // Users (with permissions) - Commented out until controller is implemented
        // Route::middleware('permission:users.view')->group(function () {
        //     Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index']);
        //     Route::get('/users/{user}', [App\Http\Controllers\Api\UserController::class, 'show']);
        // });

        // Route::middleware('permission:users.create')->group(function () {
        //     Route::post('/users', [App\Http\Controllers\Api\UserController::class, 'store']);
        // });

        // Route::middleware('permission:users.edit')->group(function () {
        //     Route::put('/users/{user}', [App\Http\Controllers\Api\UserController::class, 'update']);
        // });

        // Route::middleware('permission:users.delete')->group(function () {
        //     Route::delete('/users/{user}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
        // });
        
        // Roles and Permissions - Commented out until controllers are implemented
        // Route::middleware('permission:roles.view')->group(function () {
        //     Route::get('/roles', [App\Http\Controllers\Api\RoleController::class, 'index']);
        //     Route::get('/permissions', [App\Http\Controllers\Api\PermissionController::class, 'index']);
        // });

        // Activity Logs - Commented out until controller is implemented
        // Route::middleware('permission:activity-logs.view')->group(function () {
        //     Route::get('/activity-logs', [App\Http\Controllers\Api\ActivityLogController::class, 'index']);
        // });

        // Dashboard data - Commented out until controller is implemented
        // Route::middleware('permission:dashboard.view')->group(function () {
        //     Route::get('/dashboard/stats', [App\Http\Controllers\Api\DashboardController::class, 'stats']);
        // });
    });
    
    // Super Admin API routes - Commented out until controllers are implemented
    // Route::middleware(['auth:sanctum', 'role:super-admin'])->prefix('admin')->group(function () {
    //
    //     // Tenant management
    //     Route::get('/tenants', [App\Http\Controllers\Api\Admin\TenantController::class, 'index']);
    //     Route::post('/tenants', [App\Http\Controllers\Api\Admin\TenantController::class, 'store']);
    //     Route::get('/tenants/{tenant}', [App\Http\Controllers\Api\Admin\TenantController::class, 'show']);
    //     Route::put('/tenants/{tenant}', [App\Http\Controllers\Api\Admin\TenantController::class, 'update']);
    //     Route::delete('/tenants/{tenant}', [App\Http\Controllers\Api\Admin\TenantController::class, 'destroy']);
    //     Route::post('/tenants/{tenant}/suspend', [App\Http\Controllers\Api\Admin\TenantController::class, 'suspend']);
    //     Route::post('/tenants/{tenant}/activate', [App\Http\Controllers\Api\Admin\TenantController::class, 'activate']);
    //
    //     // System statistics
    //     Route::get('/system/stats', [App\Http\Controllers\Api\Admin\SystemController::class, 'stats']);
    //     Route::get('/system/health', [App\Http\Controllers\Api\Admin\SystemController::class, 'health']);
    //
    //     // Global user management
    //     Route::get('/users', [App\Http\Controllers\Api\Admin\UserController::class, 'index']);
    //     Route::get('/users/{user}', [App\Http\Controllers\Api\Admin\UserController::class, 'show']);
    //     Route::put('/users/{user}', [App\Http\Controllers\Api\Admin\UserController::class, 'update']);
    //     Route::delete('/users/{user}', [App\Http\Controllers\Api\Admin\UserController::class, 'destroy']);
    // });
});

// Rate limiting for API routes
Route::middleware(['throttle:api'])->group(function () {
    // All API routes are rate limited
});
