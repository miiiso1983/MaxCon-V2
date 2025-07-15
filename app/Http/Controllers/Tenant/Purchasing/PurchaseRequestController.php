<?php

namespace App\Http\Controllers\Tenant\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of purchase requests
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = PurchaseRequest::where('tenant_id', $tenantId)
            ->with(['requestedBy', 'approvedBy', 'items']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('requested_by')) {
            $query->where('requested_by', $request->requested_by);
        }

        $purchaseRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => PurchaseRequest::where('tenant_id', $tenantId)->count(),
            'pending' => PurchaseRequest::where('tenant_id', $tenantId)->where('status', 'pending')->count(),
            'approved' => PurchaseRequest::where('tenant_id', $tenantId)->where('status', 'approved')->count(),
            'urgent' => PurchaseRequest::where('tenant_id', $tenantId)->where('is_urgent', true)->count(),
        ];

        return view('tenant.purchasing.purchase-requests.index', compact('purchaseRequests', 'stats'));
    }

    /**
     * Show the form for creating a new purchase request
     */
    public function create(): View
    {
        $products = Product::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.purchasing.purchase-requests.create', compact('products'));
    }

    /**
     * Store a newly created purchase request
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'required_date' => 'required|date|after:today',
            'justification' => 'nullable|string',
            'estimated_total' => 'nullable|numeric|min:0',
            'budget_code' => 'nullable|string|max:50',
            'cost_center' => 'nullable|string|max:50',
            'special_instructions' => 'nullable|string',
            'is_urgent' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.estimated_price' => 'nullable|numeric|min:0',
            'items.*.specifications' => 'nullable|string',
        ]);

        // Generate request number
        $requestNumber = 'PR-' . date('Y') . '-' . str_pad(
            PurchaseRequest::where('tenant_id', $tenantId)->count() + 1,
            4,
            '0',
            STR_PAD_LEFT
        );

        $data = $request->all();
        $data['tenant_id'] = $tenantId;
        $data['requested_by'] = $user->id;
        $data['request_number'] = $requestNumber;
        $data['is_urgent'] = $request->has('is_urgent');

        $purchaseRequest = PurchaseRequest::create($data);

        // Create items
        foreach ($request->items as $itemData) {
            $itemData['total_estimated'] = $itemData['quantity'] * ($itemData['estimated_price'] ?? 0);
            $purchaseRequest->items()->create($itemData);
        }

        // Update estimated total
        $purchaseRequest->update([
            'estimated_total' => $purchaseRequest->items()->sum('total_estimated')
        ]);

        return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
            ->with('success', 'تم إنشاء طلب الشراء بنجاح');
    }

    /**
     * Display the specified purchase request
     */
    public function show(PurchaseRequest $purchaseRequest): View
    {
        $user = auth()->user();

        if ($purchaseRequest->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $purchaseRequest->load(['requestedBy', 'approvedBy', 'rejectedBy', 'items.product']);

        return view('tenant.purchasing.purchase-requests.show', compact('purchaseRequest'));
    }

    /**
     * Show the form for editing the specified purchase request
     */
    public function edit(PurchaseRequest $purchaseRequest): View
    {
        $user = auth()->user();

        if ($purchaseRequest->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($purchaseRequest->status, ['draft', 'pending'])) {
            return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
                ->with('error', 'لا يمكن تعديل طلب الشراء في هذه الحالة');
        }

        $products = Product::where('tenant_id', $user->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $purchaseRequest->load('items');

        return view('tenant.purchasing.purchase-requests.edit', compact('purchaseRequest', 'products'));
    }

    /**
     * Update the specified purchase request
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $user = auth()->user();

        if ($purchaseRequest->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($purchaseRequest->status, ['draft', 'pending'])) {
            return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
                ->with('error', 'لا يمكن تعديل طلب الشراء في هذه الحالة');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'required_date' => 'required|date|after:today',
            'justification' => 'nullable|string',
            'estimated_total' => 'nullable|numeric|min:0',
            'budget_code' => 'nullable|string|max:50',
            'cost_center' => 'nullable|string|max:50',
            'special_instructions' => 'nullable|string',
            'is_urgent' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.estimated_price' => 'nullable|numeric|min:0',
            'items.*.specifications' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_urgent'] = $request->has('is_urgent');

        $purchaseRequest->update($data);

        // Update items
        $purchaseRequest->items()->delete();
        foreach ($request->items as $itemData) {
            $itemData['total_estimated'] = $itemData['quantity'] * ($itemData['estimated_price'] ?? 0);
            $purchaseRequest->items()->create($itemData);
        }

        // Update estimated total
        $purchaseRequest->update([
            'estimated_total' => $purchaseRequest->items()->sum('total_estimated')
        ]);

        return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
            ->with('success', 'تم تحديث طلب الشراء بنجاح');
    }

    /**
     * Remove the specified purchase request
     */
    public function destroy(PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $user = auth()->user();

        if ($purchaseRequest->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($purchaseRequest->status, ['draft', 'cancelled'])) {
            return redirect()->route('tenant.purchasing.purchase-requests.index')
                ->with('error', 'لا يمكن حذف طلب الشراء في هذه الحالة');
        }

        $purchaseRequest->delete();

        return redirect()->route('tenant.purchasing.purchase-requests.index')
            ->with('success', 'تم حذف طلب الشراء بنجاح');
    }

    /**
     * Approve purchase request
     */
    public function approve(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $user = auth()->user();

        if ($purchaseRequest->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'approval_notes' => 'nullable|string',
            'approved_budget' => 'nullable|numeric|min:0',
        ]);

        if ($purchaseRequest->approve($user->id, $request->approval_notes)) {
            if ($request->filled('approved_budget')) {
                $purchaseRequest->update(['approved_budget' => $request->approved_budget]);
            }

            return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
                ->with('success', 'تم اعتماد طلب الشراء بنجاح');
        }

        return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
            ->with('error', 'لا يمكن اعتماد طلب الشراء في هذه الحالة');
    }

    /**
     * Reject purchase request
     */
    public function reject(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $user = auth()->user();

        if ($purchaseRequest->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        if ($purchaseRequest->reject($user->id, $request->rejection_reason)) {
            return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
                ->with('success', 'تم رفض طلب الشراء');
        }

        return redirect()->route('tenant.purchasing.purchase-requests.show', $purchaseRequest)
            ->with('error', 'لا يمكن رفض طلب الشراء في هذه الحالة');
    }
}
