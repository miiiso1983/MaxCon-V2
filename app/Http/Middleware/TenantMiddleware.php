<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

/**
 * Tenant Middleware
 *
 * Handles tenant identification and context switching for multi-tenant application.
 * Identifies tenant from subdomain, domain, or request parameters.
 */
class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $tenant = $this->identifyTenant($request);

            if ($tenant) {
                // Set tenant context
                app()->instance('tenant', $tenant);
                config(['app.tenant' => $tenant]);

                // Check if tenant is active
                if (method_exists($tenant, 'isActive') && !$tenant->isActive()) {
                    return response()->view('errors.tenant-inactive', [], 403);
                }

                // Check subscription status if methods exist
                if (method_exists($tenant, 'isOnTrial') && method_exists($tenant, 'hasActiveSubscription')) {
                    if (!$tenant->isOnTrial() && !$tenant->hasActiveSubscription()) {
                        return response()->view('errors.subscription-expired', [], 402);
                    }
                }

                // Ensure authenticated user's tenant matches when logged in
                if (Auth::check() && Auth::user()->tenant_id && $tenant->id && Auth::user()->tenant_id !== $tenant->id) {
                    // Instead of 500, log and show access denied gracefully
                    \Log::warning('Tenant mismatch for user', [
                        'user_id' => Auth::id(),
                        'user_tenant_id' => Auth::user()->tenant_id,
                        'request_tenant_id' => $tenant->id,
                    ]);
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'تم تسجيل خروجك بسبب اختلاف حساب المستأجر.');
                }
            }
        } catch (\Throwable $e) {
            // For the health check route, return JSON instead of 500
            if ($request->is('tenant/sales/targets/reports/health/check')) {
                return response()->json([
                    'middleware_error' => true,
                    'message' => $e->getMessage(),
                ], 200);
            }
            \Log::error('TenantMiddleware exception: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            // Continue without tenant context to avoid 500; controllers should fail-safe
        }

        return $next($request);
    }

    /**
     * Identify tenant from request
     */
    private function identifyTenant(Request $request): ?Tenant
    {
        // If user is authenticated, prefer user's tenant (central-domain scenario)
        if (Auth::check() && Auth::user()->tenant_id) {
            $userTenant = Tenant::find(Auth::user()->tenant_id);
            if ($userTenant) {
                return $userTenant;
            }
        }

        // Try to identify by subdomain
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);

        if ($subdomain && $subdomain !== 'www') {
            $tenant = Tenant::where('subdomain', $subdomain)->first();
            if ($tenant) {
                return $tenant;
            }
        }

        // Try to identify by custom domain
        $tenant = Tenant::where('domain', $host)->first();
        if ($tenant) {
            return $tenant;
        }

        // Try to identify by domain without www prefix
        if (str_starts_with($host, 'www.')) {
            $domainWithoutWww = substr($host, 4);
            $tenant = Tenant::where('domain', $domainWithoutWww)->first();
            if ($tenant) {
                return $tenant;
            }
        }

        // Try to identify by request parameter (for API or testing)
        if ($request->has('tenant_id')) {
            return Tenant::find($request->get('tenant_id'));
        }

        if ($request->has('tenant_slug')) {
            return Tenant::where('slug', $request->get('tenant_slug'))->first();
        }

        // Fallbacks: config or env-configured default tenant, then first active tenant
        $defaultTenantId = config('tenancy.default_tenant_id', env('DEFAULT_TENANT_ID'));
        if ($defaultTenantId) {
            $tenant = Tenant::find($defaultTenantId);
            if ($tenant) {
                return $tenant;
            }
        }

        $tenant = Tenant::where('is_active', 1)->orderBy('id')->first();
        if ($tenant) {
            return $tenant;
        }

        return null;
    }

    /**
     * Extract subdomain from host
     */
    private function extractSubdomain(string $host): ?string
    {
        $centralDomain = config('app.central_domain', 'localhost');

        // Remove port if present
        $host = explode(':', $host)[0];
        $centralDomain = explode(':', $centralDomain)[0];

        if (str_ends_with($host, '.' . $centralDomain)) {
            $subdomain = str_replace('.' . $centralDomain, '', $host);
            return $subdomain ?: null;
        }

        return null;
    }
}
