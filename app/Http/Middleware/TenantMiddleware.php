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
        $tenant = $this->identifyTenant($request);

        if ($tenant) {
            // Set tenant context
            app()->instance('tenant', $tenant);
            config(['app.tenant' => $tenant]);

            // Check if tenant is active
            if (!$tenant->isActive()) {
                return response()->view('errors.tenant-inactive', [], 403);
            }

            // Check subscription status
            if (!$tenant->isOnTrial() && !$tenant->hasActiveSubscription()) {
                return response()->view('errors.subscription-expired', [], 402);
            }

            // Add tenant ID to all database queries for this request
            if (Auth::check() && Auth::user()->tenant_id !== $tenant->id) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Access denied for this tenant.');
            }
        }

        return $next($request);
    }

    /**
     * Identify tenant from request
     */
    private function identifyTenant(Request $request): ?Tenant
    {
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

        // Try to identify by request parameter (for API or testing)
        if ($request->has('tenant_id')) {
            return Tenant::find($request->get('tenant_id'));
        }

        if ($request->has('tenant_slug')) {
            return Tenant::where('slug', $request->get('tenant_slug'))->first();
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
