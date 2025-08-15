<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryAudit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\Schema;

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

        // Build payload compatible with legacy/new schema
        $payload = [
            'warehouse_id' => (int) $request->input('warehouse_id'),
            'audit_number' => $auditNumber,
        ];
        $statusRequested = (string) $request->input('status', 'scheduled');
        // Map scheduled->planned for legacy enum
        $statusMapped = $statusRequested === 'scheduled' ? 'planned' : $statusRequested;

        if (Schema::hasColumn('inventory_audits', 'tenant_id')) { $payload['tenant_id'] = $tenantId; }
        if (Schema::hasColumn('inventory_audits', 'audit_type')) { $payload['audit_type'] = $request->input('audit_type'); }
        if (Schema::hasColumn('inventory_audits', 'status')) { $payload['status'] = $statusMapped; }
        if (Schema::hasColumn('inventory_audits', 'scheduled_date')) { $payload['scheduled_date'] = $request->input('scheduled_date'); }
        if (Schema::hasColumn('inventory_audits', 'audit_date')) { $payload['audit_date'] = substr((string)$request->input('scheduled_date'), 0, 10) ?: date('Y-m-d'); }
        if (Schema::hasColumn('inventory_audits', 'notes')) { $payload['notes'] = $request->input('notes'); }
        if (Schema::hasColumn('inventory_audits', 'description')) { $payload['description'] = $request->input('notes'); }
        if (Schema::hasColumn('inventory_audits', 'created_by')) { $payload['created_by'] = $user->id; }
        if (Schema::hasColumn('inventory_audits', 'auditor_id')) { $payload['auditor_id'] = $user->id; }

        try {
            $auditId = DB::table('inventory_audits')->insertGetId($payload);
            $audit = InventoryAudit::find($auditId);
        } catch (\Throwable $e) {
            Log::error('Audit store failed', ['error' => $e->getMessage(), 'payload' => $payload]);
            return back()->with('error', 'تعذر إنشاء الجرد: ' . $e->getMessage())->withInput();
        }

        // If audit_items[] provided from UI, insert initial items
        $items = $request->input('audit_items', []);
        if (is_array($items) && count($items)) {
            $now = now();
            $bulk = [];
            foreach ($items as $item) {
                $pid = (int) ($item['product_id'] ?? 0);
                if (!$pid) { continue; }
                $expected = (float) ($item['expected_quantity'] ?? 0);
                $bulk[] = [
                    'audit_id' => $audit->id,
                    'product_id' => $pid,
                    'system_quantity' => $expected,
                    'expected_quantity' => $expected,
                    'status' => 'pending',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if (!empty($bulk)) {
                if (Schema::hasTable('inventory_audit_items')) {
                    DB::table('inventory_audit_items')->insert($bulk);
                } else {
                    // Table missing on legacy DB: skip silently but notify user after redirect
                    session()->flash('warning', 'تم إنشاء الجرد، لكن لم تُحفظ عناصر الجرد لأن جدول العناصر غير متوفر على الخادم. يرجى تشغيل المهاجرات.');
                }
            }
        }

        return redirect()->route('tenant.inventory.audits.show', $audit)
            ->with('success', 'تم إنشاء الجرد وإضافة العناصر المحددة بنجاح');
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

    /**
     * Get products available in a warehouse for audit (JSON)
     */
    public function warehouseProducts(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id'
        ]);
        $warehouseId = (int) $request->query('warehouse_id');

        // Query inventory joined with products, limited to tenant and status active
        $rows = DB::table('inventory as i')
            ->join('products as p', 'p.id', '=', 'i.product_id')
            ->where('i.warehouse_id', $warehouseId)
            ->when(Schema::hasColumn('inventory', 'tenant_id'), function($q) use ($tenantId){
                $q->where('i.tenant_id', $tenantId);
            })
            ->leftJoin('warehouse_locations as wl', 'wl.id', '=', 'i.location_id')
            ->select([
                'p.id as product_id',
                'p.name as product_name',
                DB::raw("COALESCE(p.code, p.product_code) as product_code"),
                'i.available_quantity',
                'i.quantity',
                'i.batch_number',
                'p.category as category',
                'i.location_id',
                DB::raw("COALESCE(wl.name, wl.code) as location_name")
            ])
            ->orderBy('p.name')
            ->limit(1000)
            ->get();

        return response()->json(['data' => $rows]);
    }

}
