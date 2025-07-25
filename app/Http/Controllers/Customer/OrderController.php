<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Customer Order Controller
 * 
 * تحكم في طلبيات العملاء
 */
class OrderController extends Controller
{
    // Middleware is handled in routes

    /**
     * Display customer orders
     */
    public function index(Request $request): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->canPlaceOrders() && !$customer->hasPermissionTo('view_own_orders')) {
            abort(403, 'ليس لديك صلاحية لعرض الطلبيات');
        }

        $query = CustomerOrder::forCustomer($customer->id)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->withStatus($request->input('status'));
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->dateRange($request->input('date_from'), $request->input('date_to'));
        }

        $orders = $query->paginate(15);

        $statistics = [
            'total_orders' => CustomerOrder::forCustomer($customer->id)->count(),
            'pending_orders' => CustomerOrder::forCustomer($customer->id)->withStatus('pending')->count(),
            'completed_orders' => CustomerOrder::forCustomer($customer->id)->withStatus('completed')->count(),
            'total_amount' => CustomerOrder::forCustomer($customer->id)->sum('total_amount'),
        ];

        return view('customer.orders.index', compact('orders', 'statistics'));
    }

    /**
     * Show order details
     */
    public function show(CustomerOrder $order): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Check if order belongs to customer
        if ($order->getAttribute('customer_id') !== $customer->id) {
            abort(403, 'ليس لديك صلاحية لعرض هذا الطلب');
        }

        $order->load(['items.product', 'approvedBy']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Show create order form
     */
    public function create(): View|\Illuminate\Http\RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->canPlaceOrders()) {
            abort(403, 'ليس لديك صلاحية لإنشاء طلبيات');
        }

        // Check credit limit
        if ($customer->isOverCreditLimit()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'لا يمكنك إنشاء طلب جديد لأنك تجاوزت الحد الائتماني');
        }

        $products = Product::where('tenant_id', $customer->tenant_id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('customer.orders.create', compact('products'));
    }

    /**
     * Store new order
     */
    public function store(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->canPlaceOrders()) {
            abort(403, 'ليس لديك صلاحية لإنشاء طلبيات');
        }

        $request->validate([
            'required_date' => 'required|date|after:today',
            'delivery_address' => 'required|string|max:500',
            'delivery_city' => 'required|string|max:100',
            'delivery_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,normal,high,urgent',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create order
            $order = CustomerOrder::create([
                'customer_id' => $customer->id,
                'tenant_id' => $customer->tenant_id,
                'status' => CustomerOrder::STATUS_PENDING,
                'required_date' => $request->input('required_date'),
                'delivery_address' => $request->input('delivery_address'),
                'delivery_city' => $request->input('delivery_city'),
                'delivery_phone' => $request->input('delivery_phone'),
                'notes' => $request->input('notes'),
                'priority' => $request->input('priority'),
                'currency' => 'IQD',
                'payment_status' => CustomerOrder::PAYMENT_PENDING,
            ]);

            // Create order items
            foreach ($request->input('items', []) as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                CustomerOrderItem::create([
                    'customer_order_id' => $order->getAttribute('id'),
                    'product_id' => $product->getAttribute('id'),
                    'product_name' => $product->getAttribute('name'),
                    'product_code' => $product->getAttribute('code'),
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Calculate totals
            $order->calculateTotals();

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'تم إنشاء الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage());
        }
    }

    /**
     * Show edit order form
     */
    public function edit(CustomerOrder $order): View|\Illuminate\Http\RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        
        // Check if order belongs to customer
        if ($order->getAttribute('customer_id') !== $customer->id) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الطلب');
        }

        // Check if order can be modified
        if (!$order->canBeModified()) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'لا يمكن تعديل هذا الطلب في حالته الحالية');
        }

        $order->load('items.product');
        
        $products = Product::where('tenant_id', $customer->tenant_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('customer.orders.edit', compact('order', 'products'));
    }

    /**
     * Update order
     */
    public function update(Request $request, CustomerOrder $order): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        
        // Check if order belongs to customer
        if ($order->getAttribute('customer_id') !== $customer->id) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الطلب');
        }

        // Check if order can be modified
        if (!$order->canBeModified()) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'لا يمكن تعديل هذا الطلب في حالته الحالية');
        }

        $request->validate([
            'required_date' => 'required|date|after:today',
            'delivery_address' => 'required|string|max:500',
            'delivery_city' => 'required|string|max:100',
            'delivery_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,normal,high,urgent',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Update order
            $order->update([
                'required_date' => $request->required_date,
                'delivery_address' => $request->delivery_address,
                'delivery_city' => $request->delivery_city,
                'delivery_phone' => $request->delivery_phone,
                'notes' => $request->notes,
                'priority' => $request->priority,
            ]);

            // Delete existing items
            $order->items()->delete();

            // Create new items
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                CustomerOrderItem::create([
                    'customer_order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Calculate totals
            $order->calculateTotals();

            DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'تم تحديث الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الطلب: ' . $e->getMessage());
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, CustomerOrder $order): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();
        
        // Check if order belongs to customer
        if ($order->getAttribute('customer_id') !== $customer->id) {
            abort(403, 'ليس لديك صلاحية لإلغاء هذا الطلب');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if ($order->cancel($request->input('cancellation_reason'))) {
            return redirect()->route('customer.orders.index')
                ->with('success', 'تم إلغاء الطلب بنجاح');
        }

        return back()->with('error', 'لا يمكن إلغاء هذا الطلب في حالته الحالية');
    }
}
