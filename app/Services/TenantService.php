<?php

namespace App\Services;

use App\Repositories\TenantRepository;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

/**
 * Tenant Service
 * 
 * Handles business logic for tenant operations
 */
class TenantService
{
    protected TenantRepository $tenantRepository;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    /**
     * Get all tenants with pagination
     */
    public function getAllTenants(int $perPage = 15): LengthAwarePaginator
    {
        return Tenant::orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a new tenant
     */
    public function createTenant(array $data): Tenant
    {
        return DB::transaction(function () use ($data) {
            // Generate slug if not provided
            if (!isset($data['slug'])) {
                $data['slug'] = $this->generateUniqueSlug($data['name']);
            }

            // Set default values
            $data = array_merge([
                'status' => 'active',
                'is_active' => true,
                'plan' => 'basic',
                'max_users' => 10,
                'storage_limit' => 1073741824, // 1GB
                'trial_ends_at' => now()->addDays(14), // 14-day trial
            ], $data);

            // Create tenant
            $tenant = $this->tenantRepository->create($data);

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($tenant)
                ->log('Tenant created');

            return $tenant;
        });
    }

    /**
     * Update tenant
     */
    public function updateTenant(int $tenantId, array $data): Tenant
    {
        return DB::transaction(function () use ($tenantId, $data) {
            $tenant = $this->tenantRepository->findOrFail($tenantId);

            // Validate slug uniqueness if changed
            if (isset($data['slug']) && $data['slug'] !== $tenant->slug) {
                if (!$this->tenantRepository->isSlugAvailable($data['slug'], $tenantId)) {
                    throw new \Exception('Slug is already taken');
                }
            }

            // Update tenant
            $tenant = $this->tenantRepository->update($tenantId, $data);

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($tenant)
                ->log('Tenant updated');

            return $tenant;
        });
    }

    /**
     * Delete tenant
     */
    public function deleteTenant(int $tenantId): bool
    {
        return DB::transaction(function () use ($tenantId) {
            $tenant = $this->tenantRepository->findOrFail($tenantId);

            // Log activity before deletion
            activity()
                ->causedBy(auth()->user())
                ->performedOn($tenant)
                ->log('Tenant deleted');

            return $this->tenantRepository->delete($tenantId);
        });
    }

    /**
     * Search tenants
     */
    public function searchTenants(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->tenantRepository->searchTenants($search, $perPage);
    }

    /**
     * Get tenant by ID
     */
    public function getTenantById(int $tenantId): Tenant
    {
        return $this->tenantRepository->with(['users'])->findOrFail($tenantId);
    }

    /**
     * Suspend tenant
     */
    public function suspendTenant(int $tenantId, string $reason = null): bool
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        
        $result = $this->tenantRepository->suspendTenant($tenantId);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($tenant)
                ->withProperties(['reason' => $reason])
                ->log('Tenant suspended');
        }

        return $result;
    }

    /**
     * Activate tenant
     */
    public function activateTenant(int $tenantId): bool
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        
        $result = $this->tenantRepository->activateTenant($tenantId);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($tenant)
                ->log('Tenant activated');
        }

        return $result;
    }

    /**
     * Get tenant statistics
     */
    public function getTenantStatistics(): array
    {
        return $this->tenantRepository->getTenantStatistics();
    }

    /**
     * Get tenants by status
     */
    public function getTenantsByStatus(string $status): Collection
    {
        return $this->tenantRepository->getTenantsByStatus($status);
    }

    /**
     * Update tenant plan
     */
    public function updateTenantPlan(int $tenantId, string $plan, array $features = []): bool
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        
        $result = $this->tenantRepository->updatePlan($tenantId, $plan, $features);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($tenant)
                ->withProperties(['old_plan' => $tenant->plan, 'new_plan' => $plan])
                ->log('Tenant plan updated');
        }

        return $result;
    }

    /**
     * Extend tenant subscription
     */
    public function extendSubscription(int $tenantId, int $months): bool
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        
        $currentEnd = $tenant->subscription_ends_at ?: now();
        $newEnd = Carbon::parse($currentEnd)->addMonths($months);
        
        $result = $this->tenantRepository->extendSubscription($tenantId, $newEnd);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($tenant)
                ->withProperties(['months' => $months, 'new_end_date' => $newEnd])
                ->log('Tenant subscription extended');
        }

        return $result;
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (!$this->tenantRepository->isSlugAvailable($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if tenant can add more users
     */
    public function canAddMoreUsers(int $tenantId): bool
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        $currentUserCount = $tenant->users()->count();
        
        return $currentUserCount < $tenant->max_users;
    }

    /**
     * Get tenants with expired trials
     */
    public function getTenantsWithExpiredTrials(): Collection
    {
        return $this->tenantRepository->getTenantsWithExpiredSubscriptions();
    }

    /**
     * Get tenants on trial
     */
    public function getTenantsOnTrial(): Collection
    {
        return $this->tenantRepository->getTenantsOnTrial();
    }

    /**
     * Validate tenant domain
     */
    public function validateDomain(string $domain, ?int $excludeId = null): bool
    {
        // Basic domain validation
        if (!filter_var('http://' . $domain, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check if domain is available
        return $this->tenantRepository->isDomainAvailable($domain, $excludeId);
    }

    /**
     * Validate tenant slug
     */
    public function validateSlug(string $slug, ?int $excludeId = null): bool
    {
        // Basic slug validation
        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            return false;
        }

        // Check if slug is available
        return $this->tenantRepository->isSlugAvailable($slug, $excludeId);
    }
}
