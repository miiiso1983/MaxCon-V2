<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasTenant
{
    /**
     * Boot the trait
     */
    protected static function bootHasTenant()
    {
        // Automatically set tenant_id when creating
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });

        // Global scope to filter by tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check() && Auth::user()->tenant_id) {
                $builder->where('tenant_id', Auth::user()->tenant_id);
            }
        });
    }

    /**
     * Scope to filter by specific tenant
     */
    public function scopeForTenant(Builder $query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Get the tenant relationship
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }

    /**
     * Check if model belongs to current tenant
     */
    public function belongsToCurrentTenant()
    {
        return Auth::check() && $this->tenant_id === Auth::user()->tenant_id;
    }

    /**
     * Check if model belongs to specific tenant
     */
    public function belongsToTenant($tenantId)
    {
        return $this->tenant_id === $tenantId;
    }
}
