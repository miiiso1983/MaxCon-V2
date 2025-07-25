<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Customer Management Controller for Super Admin
 * 
 * إدارة العملاء للسوبر أدمن
 */
class CustomerManagementController extends Controller
{
    /**
     * Display customers for a specific tenant
     */
    public function index(Request $request, Tenant $tenant): View
    {
        $query = Customer::where('tenant_id', $tenant->id)
            ->with(['tenant']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate statistics
        $totalCustomers = Customer::where('tenant_id', $tenant->id)->count();
        $maxCustomers = $tenant->max_customers ?? 100;
        $remainingSlots = max(0, $maxCustomers - $totalCustomers);
        $usagePercentage = $maxCustomers > 0 ? ($totalCustomers / $maxCustomers) * 100 : 0;

        $statistics = [
            'total_customers' => $totalCustomers,
            'active_customers' => Customer::where('tenant_id', $tenant->id)->where('is_active', true)->count(),
            'inactive_customers' => Customer::where('tenant_id', $tenant->id)->where('is_active', false)->count(),
            'max_customers' => $maxCustomers,
            'remaining_slots' => $remainingSlots,
            'usage_percentage' => $usagePercentage,
        ];

        return view('admin.customers.index', compact('tenant', 'customers', 'statistics'));
    }

    /**
     * Show customer details
     */
    public function show(Tenant $tenant, Customer $customer): View
    {
        // Ensure customer belongs to tenant
        if ($customer->getAttribute('tenant_id') !== $tenant->id) {
            abort(404);
        }

        $customer->load(['salesOrders', 'payments', 'invoices']);

        return view('admin.customers.show', compact('tenant', 'customer'));
    }

    /**
     * Update customer limits for tenant
     */
    public function updateLimits(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'max_customers' => 'required|integer|min:1',
        ]);

        $newLimit = $request->get('max_customers');
        $currentCount = $tenant->customers()->count();

        // Check if new limit is not less than current customers count
        if ($newLimit < $currentCount) {
            return back()->withErrors([
                'max_customers' => "لا يمكن تقليل الحد الأقصى إلى {$newLimit} لأن المستأجر لديه {$currentCount} عميل حالياً"
            ]);
        }

        $tenant->update([
            'max_customers' => $newLimit
        ]);

        // Update current count
        $tenant->updateCustomerCount();

        return back()->with('success', 'تم تحديث الحد الأقصى للعملاء بنجاح');
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus(Tenant $tenant, Customer $customer): RedirectResponse
    {
        // Ensure customer belongs to tenant
        if ($customer->getAttribute('tenant_id') !== $tenant->id) {
            abort(404);
        }

        $customer->update([
            'is_active' => !$customer->is_active
        ]);

        $status = $customer->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';

        return back()->with('success', "{$status} العميل {$customer->getAttribute('name')} بنجاح");
    }

    /**
     * Delete customer
     */
    public function destroy(Tenant $tenant, Customer $customer): RedirectResponse
    {
        // Ensure customer belongs to tenant
        if ($customer->getAttribute('tenant_id') !== $tenant->id) {
            abort(404);
        }

        $customerName = $customer->getAttribute('name');
        $customer->delete();

        return back()->with('success', "تم حذف العميل {$customerName} بنجاح");
    }

    /**
     * Get customer statistics for tenant
     */
    public function statistics(Tenant $tenant)
    {
        $totalCustomers = $tenant->customers()->count();
        $maxCustomers = $tenant->max_customers ?? 100;
        $remainingSlots = max(0, $maxCustomers - $totalCustomers);
        $usagePercentage = $maxCustomers > 0 ? ($totalCustomers / $maxCustomers) * 100 : 0;

        // Determine limit status and color
        $limitStatus = 'جيد';
        $limitColor = '#38a169';
        if ($usagePercentage >= 90) {
            $limitStatus = 'ممتلئ تقريباً';
            $limitColor = '#e53e3e';
        } elseif ($usagePercentage >= 75) {
            $limitStatus = 'مرتفع';
            $limitColor = '#dd6b20';
        }

        $statistics = [
            'total_customers' => $totalCustomers,
            'active_customers' => $tenant->customers()->where('is_active', true)->count(),
            'inactive_customers' => $tenant->customers()->where('is_active', false)->count(),
            'max_customers' => $maxCustomers,
            'remaining_slots' => $remainingSlots,
            'usage_percentage' => $usagePercentage,
            'limit_status' => $limitStatus,
            'limit_color' => $limitColor,
            'recent_customers' => $tenant->customers()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'email', 'created_at', 'is_active']),
        ];

        return response()->json($statistics);
    }
}
