<?php

namespace App\Http\Controllers\Tenant\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // Build query
        $query = Quotation::where('tenant_id', $tenantId)
            ->with(['supplier', 'purchaseRequest', 'requestedBy', 'evaluatedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Get quotations with pagination
        $quotations = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => Quotation::where('tenant_id', $tenantId)->count(),
            'pending' => Quotation::where('tenant_id', $tenantId)->whereIn('status', ['draft', 'sent'])->count(),
            'received' => Quotation::where('tenant_id', $tenantId)->where('status', 'received')->count(),
            'accepted' => Quotation::where('tenant_id', $tenantId)->where('status', 'accepted')->count(),
        ];

        // Get suppliers for filter
        $suppliers = Supplier::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.purchasing.quotations.index', compact('quotations', 'stats', 'suppliers'));
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

        // Get suppliers
        $suppliers = Supplier::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get purchase requests that are approved
        $purchaseRequests = PurchaseRequest::where('tenant_id', $tenantId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get products
        $products = Product::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.purchasing.quotations.create', compact('suppliers', 'purchaseRequests', 'products'));
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

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quotation_date' => 'required|date',
            'valid_until' => 'required|date|after:quotation_date',
            'currency' => 'required|in:IQD,USD,EUR,SAR,AED',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_45,credit_60,credit_90,custom',
            'delivery_days' => 'nullable|integer|min:1',
            'delivery_terms' => 'nullable|string|max:500',
            'warranty_terms' => 'nullable|string|max:500',
            'special_conditions' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.specifications' => 'nullable|string|max:500',
            'items.*.brand' => 'nullable|string|max:100',
            'items.*.model' => 'nullable|string|max:100',
            'items.*.warranty_months' => 'nullable|integer|min:0',
        ]);

        try {
            // Generate quotation number
            $quotationNumber = Quotation::generateQuotationNumber($tenantId);

            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $taxAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discount_amount ?? 0;
            $totalAmount = $subtotal - $discountAmount + $taxAmount;

            // Create quotation
            $quotation = Quotation::create([
                'tenant_id' => $tenantId,
                'quotation_number' => $quotationNumber,
                'supplier_id' => $request->supplier_id,
                'purchase_request_id' => $request->purchase_request_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'draft',
                'quotation_date' => $request->quotation_date,
                'valid_until' => $request->valid_until,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => $request->currency,
                'payment_terms' => $request->payment_terms,
                'delivery_days' => $request->delivery_days,
                'delivery_terms' => $request->delivery_terms,
                'warranty_terms' => $request->warranty_terms,
                'requested_by' => $user->id,
                'special_conditions' => $request->special_conditions,
                'notes' => $request->notes,
            ]);

            // Create quotation items
            foreach ($request->items as $index => $item) {
                $totalPrice = $item['quantity'] * $item['unit_price'];

                $quotation->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'item_code' => $item['item_code'] ?? null,
                    'description' => $item['description'] ?? null,
                    'unit' => $item['unit'] ?? 'piece',
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                    'specifications' => $item['specifications'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'model' => $item['model'] ?? null,
                    'warranty_months' => $item['warranty_months'] ?? 0,
                    'sort_order' => $index + 1,
                ]);
            }

            return redirect()->route('tenant.purchasing.quotations.show', $quotation)
                ->with('success', 'تم إنشاء طلب عرض السعر بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء طلب عرض السعر: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quotation $quotation): View
    {
        $user = auth()->user();

        if ($quotation->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $quotation->load(['supplier', 'purchaseRequest', 'requestedBy', 'evaluatedBy', 'items.product']);

        return view('tenant.purchasing.quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation): View
    {
        $user = auth()->user();

        if ($quotation->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($quotation->status, ['draft'])) {
            return redirect()->route('tenant.purchasing.quotations.show', $quotation)
                ->with('error', 'لا يمكن تعديل طلب عرض السعر في هذه الحالة');
        }

        // Get suppliers
        $suppliers = Supplier::where('tenant_id', $user->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get purchase requests
        $purchaseRequests = PurchaseRequest::where('tenant_id', $user->tenant_id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get products
        $products = Product::where('tenant_id', $user->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $quotation->load('items');

        return view('tenant.purchasing.quotations.edit', compact('quotation', 'suppliers', 'purchaseRequests', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        $user = auth()->user();

        if ($quotation->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($quotation->status, ['draft'])) {
            return redirect()->route('tenant.purchasing.quotations.show', $quotation)
                ->with('error', 'لا يمكن تعديل طلب عرض السعر في هذه الحالة');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quotation_date' => 'required|date',
            'valid_until' => 'required|date|after:quotation_date',
            'currency' => 'required|in:IQD,USD,EUR,SAR,AED',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_45,credit_60,credit_90,custom',
            'delivery_days' => 'nullable|integer|min:1',
            'delivery_terms' => 'nullable|string|max:500',
            'warranty_terms' => 'nullable|string|max:500',
            'special_conditions' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.specifications' => 'nullable|string|max:500',
            'items.*.brand' => 'nullable|string|max:100',
            'items.*.model' => 'nullable|string|max:100',
            'items.*.warranty_months' => 'nullable|integer|min:0',
        ]);

        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $taxAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discount_amount ?? 0;
            $totalAmount = $subtotal - $discountAmount + $taxAmount;

            // Update quotation
            $quotation->update([
                'supplier_id' => $request->supplier_id,
                'purchase_request_id' => $request->purchase_request_id,
                'title' => $request->title,
                'description' => $request->description,
                'quotation_date' => $request->quotation_date,
                'valid_until' => $request->valid_until,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => $request->currency,
                'payment_terms' => $request->payment_terms,
                'delivery_days' => $request->delivery_days,
                'delivery_terms' => $request->delivery_terms,
                'warranty_terms' => $request->warranty_terms,
                'special_conditions' => $request->special_conditions,
                'notes' => $request->notes,
            ]);

            // Delete existing items and create new ones
            $quotation->items()->delete();

            foreach ($request->items as $index => $item) {
                $totalPrice = $item['quantity'] * $item['unit_price'];

                $quotation->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'item_code' => $item['item_code'] ?? null,
                    'description' => $item['description'] ?? null,
                    'unit' => $item['unit'] ?? 'piece',
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                    'specifications' => $item['specifications'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'model' => $item['model'] ?? null,
                    'warranty_months' => $item['warranty_months'] ?? 0,
                    'sort_order' => $index + 1,
                ]);
            }

            return redirect()->route('tenant.purchasing.quotations.show', $quotation)
                ->with('success', 'تم تحديث طلب عرض السعر بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث طلب عرض السعر: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation): RedirectResponse
    {
        $user = auth()->user();

        if ($quotation->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($quotation->status, ['draft', 'rejected', 'expired'])) {
            return back()->with('error', 'لا يمكن حذف طلب عرض السعر في هذه الحالة');
        }

        try {
            $quotation->items()->delete();
            $quotation->delete();

            return redirect()->route('tenant.purchasing.quotations.index')
                ->with('success', 'تم حذف طلب عرض السعر بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف طلب عرض السعر: ' . $e->getMessage());
        }
    }
}
