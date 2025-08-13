<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * Display a listing of warehouses
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user ? $user->tenant_id : null;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = Warehouse::forTenant($tenantId)->with(['manager']);

        // Apply filters
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $warehouses = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Warehouse::forTenant($tenantId)->count(),
            'active' => Warehouse::forTenant($tenantId)->active()->count(),
            'main' => Warehouse::forTenant($tenantId)->byType('main')->count(),
            'branches' => Warehouse::forTenant($tenantId)->byType('branch')->count(),
            'total_capacity' => Warehouse::forTenant($tenantId)->sum('total_capacity'),
            'used_capacity' => Warehouse::forTenant($tenantId)->sum('used_capacity'),
        ];

        $managers = User::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.warehouses.index', compact('warehouses', 'stats', 'managers'));
    }

    /**
     * Show the form for creating a new warehouse
     */
    public function create(): View
    {
        $user = Auth::user();
        $tenantId = $user ? $user->tenant_id : null;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $managers = User::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('tenant.inventory.warehouses.create', compact('managers'));
    }

    /**
     * Store a newly created warehouse
     */
    public function store(Request $request): RedirectResponse
    {
        // Log the incoming request
        \Log::info('Warehouse store request received', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'ip' => $request->ip()
        ]);

        $user = Auth::user();
        $tenantId = $user ? $user->tenant_id : null;

        if (!$tenantId) {
            \Log::error('No tenant access for warehouse creation', [
                'user_id' => Auth::id(),
                'user' => $user
            ]);
            abort(403, 'No tenant access');
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'manager_id' => 'nullable|exists:users,id',
                'type' => 'required|in:main,branch,storage,pharmacy',
                'total_capacity' => 'nullable|numeric|min:0',
                'settings' => 'nullable|array',
            ]);

            \Log::info('Warehouse validation passed', [
                'validated_data' => $validated,
                'tenant_id' => $tenantId
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Warehouse validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return back()->withErrors($e->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            $warehouse = new Warehouse();
            $warehouse->tenant_id = $tenantId;
            $warehouse->fill($validated);

            // Generate code if not provided
            if (empty($warehouse->code)) {
                $warehouse->code = $warehouse->generateCode();
            }

            // Log for debugging
            \Log::info('Creating warehouse', [
                'tenant_id' => $tenantId,
                'data' => $validated,
                'user_id' => Auth::id()
            ]);

            $warehouse->save();

            // Create default locations
            try {
                $this->createDefaultLocations($warehouse);
                \Log::info('Default locations created successfully', [
                    'warehouse_id' => $warehouse->id
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to create default locations', [
                    'warehouse_id' => $warehouse->id,
                    'error' => $e->getMessage()
                ]);
                // Continue without failing the warehouse creation
            }

            DB::commit();

            \Log::info('Warehouse created successfully', [
                'warehouse_id' => $warehouse->id,
                'warehouse_code' => $warehouse->code
            ]);

            return redirect()->route('tenant.inventory.warehouses.index')
                ->with('success', 'تم إنشاء المستودع بنجاح');

        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Warehouse creation failed', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $validated
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء المستودع: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified warehouse
     */
    public function show(Warehouse $warehouse): View
    {
        $user = Auth::user();

        if ($warehouse->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $warehouse->load(['manager', 'locations', 'inventory.product']);

        // Get warehouse statistics
        $stats = [
            'total_products' => $warehouse->getTotalProducts(),
            'total_quantity' => $warehouse->getTotalQuantity(),
            'available_quantity' => $warehouse->getAvailableQuantity(),
            'reserved_quantity' => $warehouse->getReservedQuantity(),
            'total_value' => $warehouse->getTotalValue(),
            'capacity_usage' => $warehouse->getCapacityUsagePercentage(),
            'locations_count' => $warehouse->locations()->count(),
            'active_locations' => $warehouse->locations()->where('is_active', true)->count(),
        ];

        return view('tenant.inventory.warehouses.show', compact('warehouse', 'stats'));
    }

    /**
     * Show the form for editing the specified warehouse
     */
    public function edit(Warehouse $warehouse): View
    {
        $user = Auth::user();

        if ($warehouse->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $managers = User::where('tenant_id', $user->tenant_id)->orderBy('name')->get();

        return view('tenant.inventory.warehouses.edit', compact('warehouse', 'managers'));
    }

    /**
     * Update the specified warehouse
     */
    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $user = Auth::user();

        if ($warehouse->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'type' => 'required|in:main,branch,storage,pharmacy',
            'is_active' => 'boolean',
            'total_capacity' => 'nullable|numeric|min:0',
            'settings' => 'nullable|array',
        ]);

        $warehouse->update($validated);

        return redirect()->route('tenant.inventory.warehouses.index')
            ->with('success', 'تم تحديث المستودع بنجاح');
    }

    /**
     * Remove the specified warehouse
     */
    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $user = Auth::user();

        if ($warehouse->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        // Check if warehouse has inventory
        if ($warehouse->inventory()->where('quantity', '>', 0)->exists()) {
            return redirect()->route('tenant.inventory.warehouses.index')
                ->with('error', 'لا يمكن حذف المستودع لأنه يحتوي على مخزون');
        }

        $warehouse->delete();

        return redirect()->route('tenant.inventory.warehouses.index')
            ->with('success', 'تم حذف المستودع بنجاح');
    }

    /**
     * Create default locations for a new warehouse
     */
    private function createDefaultLocations(Warehouse $warehouse)
    {
        $defaultLocations = [
            ['code' => 'A-01-01', 'name' => 'المنطقة A - الممر 1 - الرف 1', 'zone' => 'A', 'aisle' => '01', 'shelf' => '01', 'type' => 'shelf'],
            ['code' => 'A-01-02', 'name' => 'المنطقة A - الممر 1 - الرف 2', 'zone' => 'A', 'aisle' => '01', 'shelf' => '02', 'type' => 'shelf'],
            ['code' => 'A-02-01', 'name' => 'المنطقة A - الممر 2 - الرف 1', 'zone' => 'A', 'aisle' => '02', 'shelf' => '01', 'type' => 'shelf'],
            ['code' => 'B-01-01', 'name' => 'المنطقة B - الممر 1 - الرف 1', 'zone' => 'B', 'aisle' => '01', 'shelf' => '01', 'type' => 'shelf'],
        ];

        foreach ($defaultLocations as $locationData) {
            $warehouse->createLocation($locationData);
        }
    }
}
