<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Tenant Model
 *
 * Represents a tenant in the multi-tenant SaaS application.
 * Each tenant has its own isolated data and settings.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $domain
 * @property string|null $subdomain
 * @property string|null $database_name
 * @property array|null $settings
 * @property string $status
 * @property \Carbon\Carbon|null $trial_ends_at
 * @property \Carbon\Carbon|null $subscription_ends_at
 * @property string $plan
 * @property array|null $features
 * @property int $max_users
 * @property int $storage_limit
 * @property array|null $contact_info
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Tenant extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'subdomain',
        'database_name',
        'settings',
        'status',
        'is_active',
        'trial_ends_at',
        'license_expires_at',
        'subscription_ends_at',
        'plan',
        'features',
        'max_users',
        'storage_limit',
        'contact_info',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
        'features' => 'array',
        'contact_info' => 'array',
        'trial_ends_at' => 'datetime',
        'license_expires_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'max_users' => 'integer',
        'storage_limit' => 'integer',
    ];

    /**
     * Activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'status', 'plan'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the users for the tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if tenant is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if tenant is on trial
     */
    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if tenant subscription is active
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    /**
     * Get tenant's full URL
     */
    public function getUrlAttribute(): string
    {
        if ($this->domain) {
            return "https://{$this->domain}";
        }

        if ($this->subdomain) {
            $centralDomain = config('app.central_domain', 'localhost');
            return "https://{$this->subdomain}.{$centralDomain}";
        }

        return config('app.url');
    }

    /**
     * Scope for active tenants
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for tenants on trial
     */
    public function scopeOnTrial($query)
    {
        return $query->where('trial_ends_at', '>', now());
    }
}
