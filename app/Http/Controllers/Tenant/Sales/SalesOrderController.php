<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of sales orders
     */
    public function index(Request $request): View
    {
        $query = SalesOrder::with(['customer', 'createdBy', 'assignedTo'])
            ->forTenant(auth()->user()->tenant_id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $salesOrders = $query->orderBy('created_at', 'desc')->paginate(15);

        $customers = Customer::forTenant(auth()->user()->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        $statusCounts = SalesOrder::forTenant(auth()->user()->tenant_id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('tenant.sales.orders.index', compact('salesOrders', 'customers', 'statusCounts'));
    }

    /**
     * Show the form for creating a new sales order
     */
    public function create(): View
    {
        $customers = Customer::forTenant(auth()->user()->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        $products = Product::forTenant(auth()->user()->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        return view('tenant.sales.orders.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created sales order
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'required_date' => 'nullable|date|after_or_equal:order_date',
            'priority' => 'required|in:low,normal,high,urgent',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'shipping_method' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create sales order
            $salesOrder = new SalesOrder();
            $salesOrder->tenant_id = auth()->user()->tenant_id;
            $salesOrder->order_number = $salesOrder->generateOrderNumber();
            $salesOrder->customer_id = $validated['customer_id'];
            $salesOrder->created_by = auth()->id();
            $salesOrder->order_date = $validated['order_date'];
            $salesOrder->required_date = $validated['required_date'];
            $salesOrder->priority = $validated['priority'];
            $salesOrder->shipping_address = $validated['shipping_address'];
            $salesOrder->billing_address = $validated['billing_address'];
            $salesOrder->shipping_method = $validated['shipping_method'];
            $salesOrder->payment_method = $validated['payment_method'];
            $salesOrder->notes = $validated['notes'];
            $salesOrder->status = 'draft';
            $salesOrder->save();

            // Create order items
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);

                $orderItem = new SalesOrderItem();
                $orderItem->sales_order_id = $salesOrder->id;
                $orderItem->product_id = $product->id;
                $orderItem->product_name = $product->name;
                $orderItem->product_code = $product->product_code;
                $orderItem->batch_number = $product->batch_number;
                $orderItem->expiry_date = $product->expiry_date;
                $orderItem->quantity = $itemData['quantity'];
                $orderItem->unit_price = $itemData['unit_price'];
                $orderItem->discount_percentage = $itemData['discount_percentage'] ?? 0;
                $orderItem->tax_rate = $itemData['tax_rate'] ?? $product->tax_rate;
                $orderItem->notes = $itemData['notes'] ?? null;
                $orderItem->save();

                $orderItem->calculateTotals();
            }

            // Calculate order totals
            $salesOrder->calculateTotals();

            DB::commit();

            return redirect()->route('tenant.sales.orders.show', $salesOrder)
                ->with('success', 'تم إنشاء طلب المبيعات بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء طلب المبيعات: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sales order
     */
    public function show(SalesOrder $salesOrder): View
    {
        // Check if order belongs to current tenant
        if ($salesOrder->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $salesOrder->load(['customer', 'items.product', 'createdBy', 'assignedTo', 'invoice']);

        return view('tenant.sales.orders.show', compact('salesOrder'));
    }

    /**
     * Show the form for editing the specified sales order
     */
    public function edit(SalesOrder $salesOrder): View
    {
        // Check if order belongs to current tenant
        if ($salesOrder->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Check if order can be edited
        if (!$salesOrder->canBeEdited()) {
            return redirect()->route('tenant.sales.orders.show', $salesOrder)
                ->with('error', 'لا يمكن تعديل هذا الطلب في حالته الحالية');
        }

        $customers = Customer::forTenant(auth()->user()->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        $products = Product::forTenant(auth()->user()->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        $salesOrder->load('items.product');

        return view('tenant.sales.orders.edit', compact('salesOrder', 'customers', 'products'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, SalesOrder $salesOrder): RedirectResponse
    {
        // Check if order belongs to current tenant
        if ($salesOrder->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,pending,confirmed,processing,shipped,delivered,cancelled,returned',
            'tracking_info' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        try {
            $oldStatus = $salesOrder->status;
            $newStatus = $validated['status'];

            // Handle specific status changes
            if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
                $salesOrder->markAsShipped($validated['tracking_info'] ?? null);
            } elseif ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                $salesOrder->markAsDelivered();
            } else {
                $salesOrder->status = $newStatus;
                if (isset($validated['tracking_info'])) {
                    $salesOrder->tracking_info = $validated['tracking_info'];
                }
                $salesOrder->save();
            }

            return redirect()->route('tenant.sales.orders.show', $salesOrder)
                ->with('success', 'تم تحديث حالة الطلب بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث حالة الطلب: ' . $e->getMessage());
        }
    }
}
