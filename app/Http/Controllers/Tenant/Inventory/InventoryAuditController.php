<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryAudit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryAuditController extends Controller
{
    /**
     * Display a listing of inventory audits
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = InventoryAudit::with(['warehouse', 'createdBy'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        if ($request->filled('audit_type')) {
            $query->where('audit_type', $request->input('audit_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->input('date_to'));
        }

        $audits = $query->orderBy('scheduled_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get filter options
        $warehouses = Warehouse::where('tenant_id', $tenantId)->orderBy('name')->get();

        // Get statistics
        $stats = [
            'total_audits' => InventoryAudit::where('tenant_id', $tenantId)->count(),
            'scheduled' => InventoryAudit::where('tenant_id', $tenantId)->where('status', 'scheduled')->count(),
            'in_progress' => InventoryAudit::where('tenant_id', $tenantId)->where('status', 'in_progress')->count(),
            'completed' => InventoryAudit::where('tenant_id', $tenantId)->where('status', 'completed')->count(),
        ];

        return view('tenant.inventory.audits.index', compact('audits', 'warehouses', 'stats'));
    }

    /**
     * Show the form for creating a new audit
     */
    public function create(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $warehouses = Warehouse::where('tenant_id', $tenantId)->active()->orderBy('name')->get();

        return view('tenant.inventory.audits.create', compact('warehouses'));
    }

    /**
     * Store a newly created audit
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'audit_type' => 'required|in:full,partial,cycle,spot',
            'warehouse_id' => 'required|exists:warehouses,id',
            'scheduled_date' => 'required|date|after:now',
            'status' => 'required|in:scheduled,in_progress',
            'notes' => 'nullable|string',
        ]);

        // Generate audit number
        $auditNumber = 'AUD-' . date('Ymd') . '-' . str_pad(
            InventoryAudit::where('tenant_id', $tenantId)
                ->whereDate('created_at', today())
                ->count() + 1,
            4, '0', STR_PAD_LEFT
        );

        InventoryAudit::create([
            'tenant_id' => $tenantId,
            'audit_number' => $auditNumber,
            'warehouse_id' => $request->input('warehouse_id'),
            'audit_type' => $request->input('audit_type'),
            'status' => $request->input('status'),
            'scheduled_date' => $request->input('scheduled_date'),
            'notes' => $request->input('notes'),
            'created_by' => $user->id,
        ]);

        return redirect()->route('tenant.inventory.audits.index')
            ->with('success', 'تم إنشاء الجرد بنجاح');
    }

    /**
     * Display the specified audit
     */
    public function show(InventoryAudit $audit): View
    {
        $user = Auth::user();

        if ($audit->getAttribute('tenant_id') !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $audit->load(['warehouse', 'createdBy', 'auditItems.product', 'auditItems.location']);

        return view('tenant.inventory.audits.show', compact('audit'));
    }
}
