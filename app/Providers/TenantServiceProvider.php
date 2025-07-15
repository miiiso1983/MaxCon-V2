<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant;
use App\Models\User;

/**
 * Tenant Service Provider
 *
 * Handles tenant-specific configurations and global query scopes
 */
class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register tenant instance
        $this->app->singleton('tenant', function () {
            return null;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add global scope for tenant isolation
        $this->addGlobalScopes();

        // Register tenant helper functions
        $this->registerHelpers();
    }

    /**
     * Add global scopes for tenant isolation
     */
    private function addGlobalScopes(): void
    {
        // Add global scope to User model to filter by tenant
        User::addGlobalScope('tenant', function (Builder $builder) {
            $tenant = app('tenant');

            if ($tenant instanceof Tenant) {
                $builder->where('tenant_id', $tenant->id);
            }
        });

        // You can add more models here that need tenant isolation
        // Example:
        // Invoice::addGlobalScope('tenant', function (Builder $builder) {
        //     $tenant = app('tenant');
        //     if ($tenant instanceof Tenant) {
        //         $builder->where('tenant_id', $tenant->id);
        //     }
        // });
    }

    /**
     * Register helper functions
     */
    private function registerHelpers(): void
    {
        // Helper functions will be loaded from a separate file
        require_once __DIR__ . '/../helpers.php';
    }
}
