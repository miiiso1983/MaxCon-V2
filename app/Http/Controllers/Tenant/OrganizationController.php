<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Organization Controller
 * 
 * تحكم في تفاصيل المؤسسة للمستأجر
 */
class OrganizationController extends Controller
{
    /**
     * Display organization details
     */
    public function index(): View
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        // Get organization statistics
        $statistics = $this->getOrganizationStatistics($tenant);
        
        return view('tenant.organization.index', compact('tenant', 'statistics'));
    }

    /**
     * Show edit organization form
     */
    public function edit(): View
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        
        return view('tenant.organization.edit', compact('tenant'));
    }

    /**
     * Update organization details
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'commercial_register' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
            'industry' => 'nullable|string|max:100',
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'employee_count' => 'nullable|integer|min:1',
            'annual_revenue' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
        ]);

        $data = $request->except(['logo']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($tenant->logo && Storage::disk('public')->exists($tenant->logo)) {
                Storage::disk('public')->delete($tenant->logo);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('tenant-logos', 'public');
            $data['logo'] = $logoPath;
        }

        $tenant->update($data);

        return redirect()->route('tenant.organization.index')
            ->with('success', 'تم تحديث تفاصيل المؤسسة بنجاح');
    }

    /**
     * Get organization statistics
     */
    private function getOrganizationStatistics($tenant): array
    {
        return [
            // Users statistics
            'total_users' => $tenant->users()->count(),
            'active_users' => $tenant->users()->where('is_active', true)->count(),
            'admin_users' => $tenant->users()->whereHas('roles', function($q) {
                $q->where('name', 'tenant_admin');
            })->count(),

            // Sales statistics
            'total_customers' => $tenant->customers()->count(),
            'active_customers' => $tenant->customers()->where('is_active', true)->count(),
            'total_orders' => $tenant->salesOrders()->count(),
            'this_month_orders' => $tenant->salesOrders()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            // Inventory statistics
            'total_products' => $tenant->products()->count(),
            'active_products' => $tenant->products()->where('is_active', true)->count(),
            'low_stock_products' => $tenant->products()
                ->whereRaw('stock_quantity <= minimum_stock_level')
                ->count(),

            // Financial statistics
            'total_invoices' => $tenant->invoices()->count(),
            'this_month_revenue' => $tenant->invoices()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'pending_payments' => $tenant->payments()
                ->where('status', 'pending')
                ->sum('amount'),

            // System usage
            'storage_used' => $this->calculateStorageUsed($tenant),
            'last_login' => $tenant->users()
                ->whereNotNull('last_login_at')
                ->orderBy('last_login_at', 'desc')
                ->first()?->last_login_at,
            'system_health' => $this->getSystemHealth($tenant),
        ];
    }

    /**
     * Calculate storage used by tenant
     */
    private function calculateStorageUsed($tenant): array
    {
        $totalSize = 0;
        $fileCount = 0;

        // Calculate tenant-specific storage
        $tenantPath = "tenant-{$tenant->id}";
        
        if (Storage::disk('public')->exists($tenantPath)) {
            $files = Storage::disk('public')->allFiles($tenantPath);
            $fileCount = count($files);
            
            foreach ($files as $file) {
                $totalSize += Storage::disk('public')->size($file);
            }
        }

        return [
            'total_size' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'file_count' => $fileCount,
            'usage_percentage' => min(100, ($totalSize / (100 * 1024 * 1024)) * 100), // Assuming 100MB limit
        ];
    }

    /**
     * Get system health status
     */
    private function getSystemHealth($tenant): array
    {
        $health = [
            'overall' => 'good',
            'database' => 'good',
            'storage' => 'good',
            'performance' => 'good',
            'issues' => [],
        ];

        // Check database health
        try {
            $tenant->users()->count();
        } catch (\Exception $e) {
            $health['database'] = 'error';
            $health['issues'][] = 'مشكلة في الاتصال بقاعدة البيانات';
        }

        // Check storage health
        $storage = $this->calculateStorageUsed($tenant);
        if ($storage['usage_percentage'] > 90) {
            $health['storage'] = 'warning';
            $health['issues'][] = 'مساحة التخزين تقارب الامتلاء';
        } elseif ($storage['usage_percentage'] > 95) {
            $health['storage'] = 'error';
            $health['issues'][] = 'مساحة التخزين ممتلئة تقريباً';
        }

        // Check performance (simplified)
        $recentOrders = $tenant->salesOrders()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        if ($recentOrders > 1000) {
            $health['performance'] = 'warning';
            $health['issues'][] = 'حمولة عالية على النظام';
        }

        // Determine overall health
        if (in_array('error', [$health['database'], $health['storage'], $health['performance']])) {
            $health['overall'] = 'error';
        } elseif (in_array('warning', [$health['database'], $health['storage'], $health['performance']])) {
            $health['overall'] = 'warning';
        }

        return $health;
    }

    /**
     * Download organization report
     */
    public function downloadReport()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        $statistics = $this->getOrganizationStatistics($tenant);

        // Generate PDF report (simplified)
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('tenant.organization.report', compact('tenant', 'statistics'));
        
        return $pdf->download("organization-report-{$tenant->id}.pdf");
    }

    /**
     * Export organization data
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        $format = $request->get('format', 'excel');

        $data = [
            'organization' => $tenant->toArray(),
            'statistics' => $this->getOrganizationStatistics($tenant),
            'users' => $tenant->users()->get()->toArray(),
            'customers' => $tenant->customers()->get()->toArray(),
        ];

        if ($format === 'json') {
            return response()->json($data);
        }

        // Excel export would be implemented here
        return response()->json(['message' => 'Excel export coming soon']);
    }
}
