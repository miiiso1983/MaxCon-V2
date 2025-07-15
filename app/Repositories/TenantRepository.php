<?php

namespace App\Repositories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Tenant Repository
 * 
 * Handles data access operations for Tenant model
 */
class TenantRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new Tenant();
    }

    /**
     * Get active tenants
     */
    public function getActiveTenants(): Collection
    {
        return $this->where('status', 'active')->all();
    }

    /**
     * Get tenants by status
     */
    public function getTenantsByStatus(string $status): Collection
    {
        return $this->where('status', $status)->all();
    }

    /**
     * Find tenant by slug
     */
    public function findBySlug(string $slug): ?Tenant
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Find tenant by domain
     */
    public function findByDomain(string $domain): ?Tenant
    {
        return $this->model->where('domain', $domain)->first();
    }

    /**
     * Find tenant by subdomain
     */
    public function findBySubdomain(string $subdomain): ?Tenant
    {
        return $this->model->where('subdomain', $subdomain)->first();
    }

    /**
     * Get tenants on trial
     */
    public function getTenantsOnTrial(): Collection
    {
        return $this->model->onTrial()->get();
    }

    /**
     * Get tenants with expired subscriptions
     */
    public function getTenantsWithExpiredSubscriptions(): Collection
    {
        return $this->model->where('subscription_ends_at', '<', now())
                          ->where('trial_ends_at', '<', now())
                          ->get();
    }

    /**
     * Get tenants by plan
     */
    public function getTenantsByPlan(string $plan): Collection
    {
        return $this->where('plan', $plan)->all();
    }

    /**
     * Search tenants
     */
    public function searchTenants(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%");
        })->paginate($perPage);
    }

    /**
     * Get tenants with users count
     */
    public function getTenantsWithUsersCount(): Collection
    {
        return $this->model->withCount('users')->get();
    }

    /**
     * Get tenant statistics
     */
    public function getTenantStatistics(): array
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'inactive' => $this->model->where('status', 'inactive')->count(),
            'suspended' => $this->model->where('status', 'suspended')->count(),
            'on_trial' => $this->model->onTrial()->count(),
        ];
    }

    /**
     * Suspend tenant
     */
    public function suspendTenant(int $tenantId): bool
    {
        return $this->update($tenantId, ['status' => 'suspended']);
    }

    /**
     * Activate tenant
     */
    public function activateTenant(int $tenantId): bool
    {
        return $this->update($tenantId, ['status' => 'active']);
    }

    /**
     * Update tenant plan
     */
    public function updatePlan(int $tenantId, string $plan, array $features = []): bool
    {
        return $this->update($tenantId, [
            'plan' => $plan,
            'features' => $features,
        ]);
    }

    /**
     * Extend subscription
     */
    public function extendSubscription(int $tenantId, \Carbon\Carbon $endDate): bool
    {
        return $this->update($tenantId, ['subscription_ends_at' => $endDate]);
    }

    /**
     * Check if slug is available
     */
    public function isSlugAvailable(string $slug, ?int $excludeId = null): bool
    {
        $query = $this->model->where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }

    /**
     * Check if domain is available
     */
    public function isDomainAvailable(string $domain, ?int $excludeId = null): bool
    {
        $query = $this->model->where('domain', $domain);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }
}
