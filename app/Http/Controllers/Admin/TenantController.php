<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Admin Tenant Controller
 *
 * Handles CRUD operations for tenants in the admin panel
 */
class TenantController extends Controller
{
    protected TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Display a listing of tenants
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');

        if ($search) {
            $tenants = $this->tenantService->searchTenants($search);
        } else {
            $tenants = $this->tenantService->getAllTenants();
        }

        $statistics = $this->tenantService->getTenantStatistics();

        return view('admin.tenants.index', compact('tenants', 'statistics', 'search'));
    }

    /**
     * Show the form for creating a new tenant
     */
    public function create(): View
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created tenant
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // معلومات المؤسسة
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tenants,slug',
            'domain' => 'nullable|string|max:255|unique:tenants,domain',
            'subdomain' => 'nullable|string|max:255|unique:tenants,subdomain',
            'plan' => 'required|string|in:basic,premium,enterprise',
            'max_users' => 'required|integer|min:1',
            'storage_limit' => 'required|integer|min:1',
            'is_active' => 'boolean',

            // معلومات مدير المؤسسة
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|max:255|unique:users,email',
            'admin_phone' => 'required|string|max:255',
            'admin_password' => 'required|string|min:8',
        ]);

        try {
            // إنشاء المستأجر
            $tenantData = [
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? null,
                'domain' => $validated['domain'] ?? null,
                'subdomain' => $validated['subdomain'] ?? null,
                'plan' => $validated['plan'],
                'max_users' => $validated['max_users'],
                'storage_limit' => $validated['storage_limit'] * 1073741824, // Convert GB to bytes
                'is_active' => $validated['is_active'] ?? true,
            ];

            $tenant = $this->tenantService->createTenant($tenantData);

            // إنشاء مدير المؤسسة
            $admin = \App\Models\User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'phone' => $validated['admin_phone'],
                'password' => bcrypt($validated['admin_password']),
                'email_verified_at' => now(),
                'is_active' => true,
                'tenant_id' => $tenant->id, // ربط المدير بالمؤسسة
            ]);

            // تعيين دور مدير المؤسسة
            $admin->assignRole('tenant-admin');

            return redirect()
                ->route('admin.tenants.maxcon')
                ->with('success', 'تم إنشاء المؤسسة ومدير المؤسسة بنجاح.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'فشل في إنشاء المؤسسة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified tenant
     */
    public function show(int $id): View
    {
        $tenant = $this->tenantService->getTenantById($id);

        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant
     */
    public function edit(int $id): View
    {
        $tenant = $this->tenantService->getTenantById($id);

        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified tenant
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tenants,slug,' . $id,
            'domain' => 'nullable|string|max:255|unique:tenants,domain,' . $id,
            'subdomain' => 'nullable|string|max:255|unique:tenants,subdomain,' . $id,
            'plan' => 'required|string|in:basic,premium,enterprise',
            'max_users' => 'required|integer|min:1',
            'storage_limit' => 'required|integer|min:1',
            'status' => 'required|string|in:active,inactive,suspended',
            'trial_ends_at' => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
            'contact_info.email' => 'nullable|email',
            'contact_info.phone' => 'nullable|string',
            'contact_info.address' => 'nullable|string',
        ]);

        try {
            $tenant = $this->tenantService->updateTenant($id, $validated);

            return redirect()
                ->route('admin.tenants.show', $tenant)
                ->with('success', 'Tenant updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update tenant: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified tenant
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->tenantService->deleteTenant($id);

            return redirect()
                ->route('admin.tenants.index')
                ->with('success', 'Tenant deleted successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete tenant: ' . $e->getMessage());
        }
    }

    /**
     * Suspend tenant
     */
    public function suspend(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->tenantService->suspendTenant($id, $request->get('reason'));

            return back()->with('success', 'Tenant suspended successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to suspend tenant: ' . $e->getMessage());
        }
    }

    /**
     * Activate tenant
     */
    public function activate(int $id): RedirectResponse
    {
        try {
            $this->tenantService->activateTenant($id);

            return back()->with('success', 'Tenant activated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to activate tenant: ' . $e->getMessage());
        }
    }

    /**
     * Export tenants data
     */
    public function export()
    {
        $tenants = $this->tenantService->getAllTenants();

        $csvData = [];
        $csvData[] = ['اسم المؤسسة', 'الرمز المختصر', 'النطاق الفرعي', 'الخطة', 'الحالة', 'تاريخ الإنشاء'];

        foreach ($tenants as $tenant) {
            $csvData[] = [
                $tenant->name,
                $tenant->slug ?? '',
                $tenant->subdomain ?? '',
                $tenant->plan ?? 'غير محدد',
                $tenant->is_active ? 'نشط' : 'معطل',
                $tenant->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'tenants_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
