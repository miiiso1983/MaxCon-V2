<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenseController extends Controller
{
    /**
     * Display expired licenses
     */
    public function expired(): View
    {
        // Get tenants with expired licenses (assuming trial_ends_at field exists)
        $expiredTenants = Tenant::where('trial_ends_at', '<', now())
            ->orWhere('license_expires_at', '<', now())
            ->with(['users' => function($query) {
                $query->where('is_active', true);
            }])
            ->paginate(15);

        // Get statistics
        $stats = [
            'total_expired' => Tenant::where('trial_ends_at', '<', now())
                ->orWhere('license_expires_at', '<', now())
                ->count(),
            'trial_expired' => Tenant::where('trial_ends_at', '<', now())
                ->where(function($q) {
                    $q->whereNull('license_expires_at')
                      ->orWhere('license_expires_at', '>', now());
                })
                ->count(),
            'license_expired' => Tenant::where('license_expires_at', '<', now())
                ->count(),
            'expiring_soon' => Tenant::whereBetween('trial_ends_at', [now(), now()->addDays(7)])
                ->orWhereBetween('license_expires_at', [now(), now()->addDays(7)])
                ->count(),
        ];

        return view('admin.licenses.expired', compact('expiredTenants', 'stats'));
    }

    /**
     * Display expiring soon licenses
     */
    public function expiringSoon(): View
    {
        // Get tenants with licenses expiring in the next 7 days
        $expiringSoon = Tenant::whereBetween('trial_ends_at', [now(), now()->addDays(7)])
            ->orWhereBetween('license_expires_at', [now(), now()->addDays(7)])
            ->with(['users' => function($query) {
                $query->where('is_active', true);
            }])
            ->paginate(15);

        return view('admin.licenses.expiring-soon', compact('expiringSoon'));
    }

    /**
     * Extend license for a tenant
     */
    public function extend(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'extension_days' => 'required|integer|min:1|max:365',
            'license_type' => 'required|in:trial,license',
        ]);

        if ($validated['license_type'] === 'trial') {
            $tenant->trial_ends_at = now()->addDays($validated['extension_days']);
        } else {
            $tenant->license_expires_at = now()->addDays($validated['extension_days']);
        }

        $tenant->save();

        return redirect()->back()->with('success', 'تم تمديد الترخيص بنجاح لمدة ' . $validated['extension_days'] . ' يوم.');
    }
}
