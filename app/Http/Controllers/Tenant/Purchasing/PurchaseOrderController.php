<?php

namespace App\Http\Controllers\Tenant\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
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
        $query = PurchaseOrder::where('tenant_id', $tenantId)
            ->with(['supplier', 'purchaseRequest', 'createdBy', 'approvedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
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

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Get purchase orders with pagination
        $purchaseOrders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => PurchaseOrder::where('tenant_id', $tenantId)->count(),
            'pending' => PurchaseOrder::where('tenant_id', $tenantId)->where('status', 'draft')->count(),
            'confirmed' => PurchaseOrder::where('tenant_id', $tenantId)->where('status', 'confirmed')->count(),
            'completed' => PurchaseOrder::where('tenant_id', $tenantId)->where('status', 'completed')->count(),
        ];

        // Get suppliers for filter
        $suppliers = Supplier::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.purchasing.purchase-orders.index', compact('purchaseOrders', 'stats', 'suppliers'));
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

        // Get purchase requests that are approved but not yet converted to PO
        $purchaseRequests = PurchaseRequest::where('tenant_id', $tenantId)
            ->where('status', 'approved')
            ->whereDoesntHave('purchaseOrders')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get products
        $products = Product::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.purchasing.purchase-orders.create', compact('suppliers', 'purchaseRequests', 'products'));
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
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:order_date',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_45,credit_60,credit_90,custom',
            'payment_days' => 'nullable|integer|min:1',
            'currency' => 'required|in:IQD,USD,EUR,SAR,AED',
            'delivery_address' => 'required|string|max:500',
            'delivery_contact' => 'required|string|max:100',
            'delivery_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'terms_conditions' => 'nullable|string|max:2000',
            'is_urgent' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.description' => 'nullable|string|max:500',
        ]);

        try {
            // Generate PO number
            $poNumber = PurchaseOrder::generatePoNumber($tenantId);

            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($request->items as $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemDiscount = $item['discount_amount'] ?? 0;
                $itemTax = ($itemSubtotal - $itemDiscount) * (($item['tax_percentage'] ?? 0) / 100);

                $subtotal += $itemSubtotal;
                $totalDiscount += $itemDiscount;
                $totalTax += $itemTax;
            }

            $shippingCost = $request->shipping_cost ?? 0;
            $totalAmount = $subtotal - $totalDiscount + $totalTax + $shippingCost;

            // Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'tenant_id' => $tenantId,
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'purchase_request_id' => $request->purchase_request_id,
                'status' => 'draft',
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'currency' => $request->currency,
                'exchange_rate' => 1, // Default to 1, can be updated later
                'payment_terms' => $request->payment_terms,
                'payment_days' => $request->payment_days,
                'delivery_address' => $request->delivery_address,
                'delivery_contact' => $request->delivery_contact,
                'delivery_phone' => $request->delivery_phone,
                'delivery_instructions' => $request->delivery_instructions,
                'created_by' => $user->id,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
                'is_urgent' => $request->boolean('is_urgent'),
            ]);

            // Create purchase order items
            foreach ($request->items as $index => $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemDiscount = $item['discount_amount'] ?? 0;
                $itemTax = ($itemSubtotal - $itemDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                $itemTotal = $itemSubtotal - $itemDiscount + $itemTax;

                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $itemDiscount,
                    'tax_amount' => $itemTax,
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'total_amount' => $itemTotal,
                    'remaining_quantity' => $item['quantity'],
                    'description' => $item['description'] ?? null,
                    'sort_order' => $index + 1,
                    'status' => 'pending',
                ]);
            }

            return redirect()->route('tenant.purchasing.purchase-orders.show', $purchaseOrder)
                ->with('success', 'تم إنشاء أمر الشراء بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء أمر الشراء: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $user = auth()->user();

        if ($purchaseOrder->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $purchaseOrder->load(['supplier', 'purchaseRequest', 'createdBy', 'approvedBy', 'items.product']);

        return view('tenant.purchasing.purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $user = auth()->user();

        if ($purchaseOrder->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($purchaseOrder->status, ['draft'])) {
            return redirect()->route('tenant.purchasing.purchase-orders.show', $purchaseOrder)
                ->with('error', 'لا يمكن تعديل أمر الشراء في هذه الحالة');
        }

        // Get suppliers
        $suppliers = Supplier::where('tenant_id', $user->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get products
        $products = Product::where('tenant_id', $user->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $purchaseOrder->load('items');

        return view('tenant.purchasing.purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $user = auth()->user();

        if ($purchaseOrder->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($purchaseOrder->status, ['draft'])) {
            return redirect()->route('tenant.purchasing.purchase-orders.show', $purchaseOrder)
                ->with('error', 'لا يمكن تعديل أمر الشراء في هذه الحالة');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:order_date',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_45,credit_60,credit_90,custom',
            'payment_days' => 'nullable|integer|min:1',
            'currency' => 'required|in:IQD,USD,EUR,SAR,AED',
            'delivery_address' => 'required|string|max:500',
            'delivery_contact' => 'required|string|max:100',
            'delivery_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'terms_conditions' => 'nullable|string|max:2000',
            'is_urgent' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.description' => 'nullable|string|max:500',
        ]);

        try {
            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($request->items as $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemDiscount = $item['discount_amount'] ?? 0;
                $itemTax = ($itemSubtotal - $itemDiscount) * (($item['tax_percentage'] ?? 0) / 100);

                $subtotal += $itemSubtotal;
                $totalDiscount += $itemDiscount;
                $totalTax += $itemTax;
            }

            $shippingCost = $request->shipping_cost ?? 0;
            $totalAmount = $subtotal - $totalDiscount + $totalTax + $shippingCost;

            // Update purchase order
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'currency' => $request->currency,
                'payment_terms' => $request->payment_terms,
                'payment_days' => $request->payment_days,
                'delivery_address' => $request->delivery_address,
                'delivery_contact' => $request->delivery_contact,
                'delivery_phone' => $request->delivery_phone,
                'delivery_instructions' => $request->delivery_instructions,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
                'is_urgent' => $request->boolean('is_urgent'),
            ]);

            // Delete existing items and create new ones
            $purchaseOrder->items()->delete();

            foreach ($request->items as $index => $item) {
                $itemSubtotal = $item['quantity'] * $item['unit_price'];
                $itemDiscount = $item['discount_amount'] ?? 0;
                $itemTax = ($itemSubtotal - $itemDiscount) * (($item['tax_percentage'] ?? 0) / 100);
                $itemTotal = $itemSubtotal - $itemDiscount + $itemTax;

                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $itemDiscount,
                    'tax_amount' => $itemTax,
                    'tax_percentage' => $item['tax_percentage'] ?? 0,
                    'total_amount' => $itemTotal,
                    'remaining_quantity' => $item['quantity'],
                    'description' => $item['description'] ?? null,
                    'sort_order' => $index + 1,
                    'status' => 'pending',
                ]);
            }

            return redirect()->route('tenant.purchasing.purchase-orders.show', $purchaseOrder)
                ->with('success', 'تم تحديث أمر الشراء بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث أمر الشراء: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $user = auth()->user();

        if ($purchaseOrder->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if (!in_array($purchaseOrder->status, ['draft', 'cancelled'])) {
            return back()->with('error', 'لا يمكن حذف أمر الشراء في هذه الحالة');
        }

        try {
            $purchaseOrder->items()->delete();
            $purchaseOrder->delete();

            return redirect()->route('tenant.purchasing.purchase-orders.index')
                ->with('success', 'تم حذف أمر الشراء بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف أمر الشراء: ' . $e->getMessage());
        }
    }
}
