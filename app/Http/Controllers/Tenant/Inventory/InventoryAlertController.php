<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryAlert;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InventoryAlertController extends Controller
{
    /**
     * Display a listing of inventory alerts
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = InventoryAlert::with(['warehouse', 'product', 'resolvedBy'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('alert_type')) {
            $query->where('alert_type', $request->alert_type);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $alerts = $query->orderBy('priority', 'desc')
            ->orderBy('triggered_at', 'desc')
            ->paginate(20);

        // Get filter options
        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();
        $products = Product::where('tenant_id', $tenantId)->orderBy('name')->get();

        // Get statistics
        $stats = [
            'total_alerts' => InventoryAlert::where('tenant_id', $tenantId)->count(),
            'active_alerts' => InventoryAlert::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'critical_alerts' => InventoryAlert::where('tenant_id', $tenantId)
                ->where('status', 'active')->where('priority', 'critical')->count(),
            'resolved_today' => InventoryAlert::where('tenant_id', $tenantId)
                ->where('status', 'resolved')->whereDate('resolved_at', today())->count(),
        ];

        return view('tenant.inventory.alerts.index', compact('alerts', 'warehouses', 'products', 'stats'));
    }

    /**
     * Display the specified alert
     */
    public function show(InventoryAlert $alert): View
    {
        $user = auth()->user();

        if ($alert->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $alert->load(['warehouse', 'product', 'resolvedBy']);

        return view('tenant.inventory.alerts.show', compact('alert'));
    }

    /**
     * Mark alert as acknowledged
     */
    public function acknowledge(InventoryAlert $alert): RedirectResponse
    {
        $user = auth()->user();

        if ($alert->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $alert->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => $user->id,
        ]);

        return redirect()->back()->with('success', 'تم تأكيد التنبيه بنجاح');
    }

    /**
     * Mark alert as resolved
     */
    public function resolve(Request $request, InventoryAlert $alert): RedirectResponse
    {
        $user = auth()->user();

        if ($alert->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $alert->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $user->id,
            'resolution_notes' => $request->resolution_notes,
        ]);

        return redirect()->back()->with('success', 'تم حل التنبيه بنجاح');
    }

    /**
     * Mark alert as dismissed
     */
    public function dismiss(InventoryAlert $alert): RedirectResponse
    {
        $user = auth()->user();

        if ($alert->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $alert->update([
            'status' => 'dismissed',
            'resolved_at' => now(),
            'resolved_by' => $user->id,
        ]);

        return redirect()->back()->with('success', 'تم تجاهل التنبيه');
    }
}
