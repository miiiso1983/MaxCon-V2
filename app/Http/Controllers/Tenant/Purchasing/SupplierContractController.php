<?php

namespace App\Http\Controllers\Tenant\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\SupplierContract;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // Get contracts with relationships
        $contracts = SupplierContract::with(['supplier', 'createdBy'])
            ->where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => SupplierContract::where('tenant_id', $tenantId)->count(),
            'active' => SupplierContract::where('tenant_id', $tenantId)->active()->count(),
            'expired' => SupplierContract::where('tenant_id', $tenantId)->expired()->count(),
            'expiring_soon' => SupplierContract::where('tenant_id', $tenantId)->expiringSoon()->count(),
        ];

        return view('tenant.purchasing.contracts.index', compact('contracts', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // Get suppliers for dropdown
        $suppliers = Supplier::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.purchasing.contracts.create_form', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'required|string|max:50|unique:supplier_contracts,contract_number',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:supply,service,maintenance,consulting,framework',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'signed_date' => 'nullable|date',
            'renewal_period_months' => 'nullable|integer|min:1|max:120',
            'auto_renewal' => 'boolean',
            'contract_value' => 'required|numeric|min:0',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'maximum_order_value' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_terms' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'quality_requirements' => 'nullable|string',
            'penalty_terms' => 'nullable|string',
            'termination_conditions' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['tenant_id'] = $tenantId;
        $validated['created_by'] = $user->id;
        $validated['status'] = 'draft';
        $validated['auto_renewal'] = $request->has('auto_renewal');

        $contract = SupplierContract::create($validated);

        return redirect()->route('tenant.purchasing.contracts.show', $contract)
            ->with('success', 'تم إنشاء العقد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierContract $contract): View
    {
        $user = auth()->user();

        if ($contract->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $contract->load(['supplier', 'createdBy', 'approvedBy']);

        return view('tenant.purchasing.contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierContract $contract): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if ($contract->tenant_id !== $tenantId) {
            abort(403, 'Unauthorized access');
        }

        // Get suppliers for dropdown
        $suppliers = Supplier::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.purchasing.contracts.edit', compact('contract', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierContract $contract): RedirectResponse
    {
        $user = auth()->user();

        if ($contract->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'required|string|max:50|unique:supplier_contracts,contract_number,' . $contract->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:supply,service,maintenance,consulting,framework',
            'status' => 'required|in:draft,pending,active,expired,terminated,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'signed_date' => 'nullable|date',
            'renewal_period_months' => 'nullable|integer|min:1|max:120',
            'auto_renewal' => 'boolean',
            'contract_value' => 'required|numeric|min:0',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'maximum_order_value' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_terms' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'quality_requirements' => 'nullable|string',
            'penalty_terms' => 'nullable|string',
            'termination_conditions' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['auto_renewal'] = $request->has('auto_renewal');

        $contract->update($validated);

        return redirect()->route('tenant.purchasing.contracts.show', $contract)
            ->with('success', 'تم تحديث العقد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierContract $contract): RedirectResponse
    {
        $user = auth()->user();

        if ($contract->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $contract->delete();

        return redirect()->route('tenant.purchasing.contracts.index')
            ->with('success', 'تم حذف العقد بنجاح');
    }
}
