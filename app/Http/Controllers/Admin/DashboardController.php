<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TenantService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin Dashboard Controller
 *
 * Handles the main admin dashboard with system overview and statistics
 */
class DashboardController extends Controller
{
    protected TenantService $tenantService;
    protected UserService $userService;

    public function __construct(TenantService $tenantService, UserService $userService)
    {
        $this->tenantService = $tenantService;
        $this->userService = $userService;
    }

    /**
     * Display the admin dashboard
     */
    public function index(): View
    {
        // Get system statistics
        $tenantStats = $this->tenantService->getTenantStatistics();
        $userStats = $this->userService->getUserStatistics();

        // Get recent tenants
        $recentTenants = $this->tenantService->getAllTenants(5);

        // Get tenants on trial
        $trialsExpiringSoon = $this->tenantService->getTenantsOnTrial()
            ->filter(function ($tenant) {
                return $tenant->trial_ends_at && $tenant->trial_ends_at->diffInDays(now()) <= 7;
            });

        // Get system health metrics
        $systemHealth = $this->getSystemHealth();

        return view('admin.modern-dashboard', compact(
            'tenantStats',
            'userStats',
            'recentTenants',
            'trialsExpiringSoon',
            'systemHealth'
        ));
    }

    /**
     * Get system health metrics
     */
    private function getSystemHealth(): array
    {
        return [
            'database' => $this->checkDatabaseHealth(),
            'storage' => $this->checkStorageHealth(),
            'cache' => $this->checkCacheHealth(),
            'queue' => $this->checkQueueHealth(),
        ];
    }

    /**
     * Check database health
     */
    private function checkDatabaseHealth(): array
    {
        try {
            \DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection is working'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }

    /**
     * Check storage health
     */
    private function checkStorageHealth(): array
    {
        try {
            $path = storage_path();
            $freeBytes = disk_free_space($path);
            $totalBytes = disk_total_space($path);
            $usedPercentage = (($totalBytes - $freeBytes) / $totalBytes) * 100;

            if ($usedPercentage > 90) {
                return ['status' => 'warning', 'message' => 'Storage usage is high: ' . round($usedPercentage, 1) . '%'];
            }

            return ['status' => 'healthy', 'message' => 'Storage usage: ' . round($usedPercentage, 1) . '%'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Unable to check storage'];
        }
    }

    /**
     * Check cache health
     */
    private function checkCacheHealth(): array
    {
        try {
            \Cache::put('health_check', 'test', 60);
            $value = \Cache::get('health_check');

            if ($value === 'test') {
                return ['status' => 'healthy', 'message' => 'Cache is working'];
            }

            return ['status' => 'error', 'message' => 'Cache test failed'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache connection failed'];
        }
    }

    /**
     * Check queue health
     */
    private function checkQueueHealth(): array
    {
        try {
            // Check if there are any failed jobs
            $failedJobs = \DB::table('failed_jobs')->count();

            if ($failedJobs > 0) {
                return ['status' => 'warning', 'message' => $failedJobs . ' failed jobs'];
            }

            return ['status' => 'healthy', 'message' => 'Queue is working'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Unable to check queue'];
        }
    }
}
