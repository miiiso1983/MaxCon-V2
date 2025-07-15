<?php

if (!function_exists('tenant')) {
    /**
     * Get current tenant instance
     */
    function tenant(): ?\App\Models\Tenant
    {
        return app('tenant');
    }
}

if (!function_exists('tenant_id')) {
    /**
     * Get current tenant ID
     */
    function tenant_id(): ?int
    {
        $tenant = tenant();
        return $tenant?->id;
    }
}

if (!function_exists('is_tenant_context')) {
    /**
     * Check if we're in a tenant context
     */
    function is_tenant_context(): bool
    {
        return tenant() !== null;
    }
}
