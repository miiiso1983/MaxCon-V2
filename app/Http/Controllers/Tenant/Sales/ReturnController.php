<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Http\Controllers\Controller;
use App\Models\ReturnOrder;
use App\Models\ReturnItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ReturnController extends Controller
{
    /**
     * Display a listing of returns
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = ReturnOrder::forTenant($tenantId)->with(['customer', 'invoice', 'processedBy']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('invoice', function($invoiceQuery) use ($search) {
                      $invoiceQuery->where('invoice_number', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => ReturnOrder::forTenant($tenantId)->count(),
            'pending' => ReturnOrder::forTenant($tenantId)->pending()->count(),
            'approved' => ReturnOrder::forTenant($tenantId)->approved()->count(),
            'completed' => ReturnOrder::forTenant($tenantId)->completed()->count(),
            'total_amount' => ReturnOrder::forTenant($tenantId)->sum('total_amount'),
            'refund_amount' => ReturnOrder::forTenant($tenantId)->sum('refund_amount'),
        ];

        $customers = Customer::forTenant($tenantId)->orderBy('name')->get();

        return view('tenant.sales.returns.index', compact('returns', 'stats', 'customers'));
    }

    /**
     * Show the form for creating a new return
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $invoice = null;
        if ($request->filled('invoice_id')) {
            $invoice = Invoice::forTenant($tenantId)
                ->with(['customer', 'items.product'])
                ->findOrFail($request->invoice_id);
        }

        $customers = Customer::forTenant($tenantId)->orderBy('name')->get();
        $products = Product::forTenant($tenantId)->active()->orderBy('name')->get();

        return view('tenant.sales.returns.create', compact('invoice', 'customers', 'products'));
    }

    /**
     * Lightweight AJAX invoice lookup: returns invoice header + items for selection
     */
    public function findInvoice(Request $request): JsonResponse
    {
        $request->validate([
            'invoice_number' => 'required|string|min:1',
        ]);

        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;
        if (!$tenantId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invoice = Invoice::forTenant($tenantId)
            ->with(['customer', 'items'])
            ->where('invoice_number', $request->invoice_number)
            ->first();

        if (!$invoice) {
            return response()->json(['found' => false], 200);
        }

        return response()->json([
            'found' => true,
            'invoice' => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'invoice_date' => optional($invoice->invoice_date)->format('Y-m-d'),
                'customer' => [
                    'id' => $invoice->customer_id,
                    'name' => optional($invoice->customer)->name,
                ],
                'items' => $invoice->items->map(function ($it) {
                    return [
                        'id' => $it->id,
                        'product_name' => $it->product_name,
                        'product_code' => $it->product_code,
                        'unit_price' => (float) $it->unit_price,
                        'quantity' => (int) $it->quantity,
                    ];
                })->values(),
            ],
        ]);
    }

    /**
     * Store a newly created return
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $tenantId = $user ? $user->tenant_id : null;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'return_date' => 'required|date',
            'type' => 'required|in:return,exchange',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'refund_method' => 'nullable|string|in:cash,credit,bank_transfer',
            'return_scope' => 'nullable|in:full,partial',
            'items' => 'required|array|min:1',
            'items.*.invoice_item_id' => 'required|exists:invoice_items,id',
            'items.*.quantity_returned' => 'required|integer|min:1',
            'items.*.condition' => 'required|in:good,damaged,expired',
            'items.*.reason' => 'nullable|string',
            'items.*.notes' => 'nullable|string',
            'items.*.exchange_product_id' => 'nullable|exists:products,id',
            'items.*.exchange_quantity' => 'nullable|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Create return order
            $returnOrder = new ReturnOrder();
            $returnOrder->tenant_id = $tenantId;
            $returnOrder->return_number = $returnOrder->generateReturnNumber();
            $returnOrder->invoice_id = $validated['invoice_id'];
            $returnOrder->customer_id = $validated['customer_id'];
            $returnOrder->return_date = $validated['return_date'];
            $returnOrder->type = $validated['type'];
            $returnOrder->reason = $validated['reason'];
            $returnOrder->notes = $validated['notes'] ?? null;
            $returnOrder->refund_method = $validated['refund_method'] ?? null;
            $returnOrder->save();

            // If full return: prefill items by all invoice items when not explicitly posted
            if (($request->input('return_scope') === 'full') && empty($validated['items'])) {
                $allInvoiceItems = InvoiceItem::where('invoice_id', $validated['invoice_id'])->get();
                $validated['items'] = [];
                foreach ($allInvoiceItems as $idx => $invItem) {
                    $validated['items'][] = [
                        'invoice_item_id' => $invItem->id,
                        'quantity_returned' => (int) $invItem->quantity,
                        'condition' => 'good',
                        'reason' => $validated['reason'] ?? null,
                    ];
                }
            }

            // Create return items
            foreach ($validated['items'] as $itemData) {
                $invoiceItem = InvoiceItem::findOrFail($itemData['invoice_item_id']);

                $returnItem = new ReturnItem();
                $returnItem->return_id = $returnOrder->id;
                $returnItem->invoice_item_id = $invoiceItem->id;
                $returnItem->product_id = $invoiceItem->product_id;
                $returnItem->product_name = $invoiceItem->product_name;
                $returnItem->product_code = $invoiceItem->product_code;
                $returnItem->batch_number = $invoiceItem->batch_number;
                $returnItem->expiry_date = $invoiceItem->expiry_date;
                $returnItem->quantity_returned = $itemData['quantity_returned'];
                $returnItem->quantity_original = $invoiceItem->quantity;
                $returnItem->unit_price = $invoiceItem->unit_price;
                $returnItem->condition = $itemData['condition'];
                $returnItem->reason = $itemData['reason'] ?? null;
                $returnItem->notes = $itemData['notes'] ?? null;

                // Handle exchange items
                if ($validated['type'] === 'exchange' && !empty($itemData['exchange_product_id'])) {
                    $exchangeProduct = Product::findOrFail($itemData['exchange_product_id']);
                    $returnItem->exchange_product_id = $exchangeProduct->id;
                    $returnItem->exchange_quantity = $itemData['exchange_quantity'];
                    $returnItem->exchange_unit_price = $exchangeProduct->selling_price;
                    $returnItem->calculateExchangeTotal();
                }

                $returnItem->calculateTotal();
                $returnItem->save();
            }

            // Calculate totals
            $returnOrder->calculateTotals();

            DB::commit();

            return redirect()->route('tenant.sales.returns.show', $returnOrder)
                ->with('success', 'تم إنشاء طلب الإرجاع بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء طلب الإرجاع: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified return
     */
    public function show(ReturnOrder $return): View
    {
        $user = auth()->user();

        if ($return->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $return->load(['customer', 'invoice', 'items.product', 'items.exchangeProduct', 'processedBy']);

        return view('tenant.sales.returns.show', compact('return'));
    }

    /**
     * Show the form for editing the specified return
     */
    public function edit(ReturnOrder $return): View
    {
        $user = auth()->user();

        if ($return->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if ($return->status !== 'pending') {
            return redirect()->route('tenant.sales.returns.show', $return)
                ->with('error', 'لا يمكن تعديل طلب الإرجاع بعد معالجته');
        }

        $return->load(['customer', 'invoice', 'items.product', 'items.exchangeProduct']);
        $customers = Customer::forTenant($user->tenant_id)->orderBy('name')->get();
        $products = Product::forTenant($user->tenant_id)->active()->orderBy('name')->get();

        return view('tenant.sales.returns.edit', compact('return', 'customers', 'products'));
    }

    /**
     * Update the specified return
     */
    public function update(Request $request, ReturnOrder $return): RedirectResponse
    {
        $user = auth()->user();

        if ($return->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if ($return->status !== 'pending') {
            return redirect()->route('tenant.sales.returns.show', $return)
                ->with('error', 'لا يمكن تعديل طلب الإرجاع بعد معالجته');
        }

        $validated = $request->validate([
            'return_date' => 'required|date',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'refund_method' => 'nullable|string|in:cash,credit,bank_transfer',
        ]);

        $return->update($validated);

        return redirect()->route('tenant.sales.returns.show', $return)
            ->with('success', 'تم تحديث طلب الإرجاع بنجاح');
    }

    /**
     * Remove the specified return
     */
    public function destroy(ReturnOrder $return): RedirectResponse
    {
        $user = auth()->user();

        if ($return->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if ($return->status !== 'pending') {
            return redirect()->route('tenant.sales.returns.index')
                ->with('error', 'لا يمكن حذف طلب الإرجاع بعد معالجته');
        }

        $return->delete();

        return redirect()->route('tenant.sales.returns.index')
            ->with('success', 'تم حذف طلب الإرجاع بنجاح');
    }

    /**
     * Approve a return
     */
    public function approve(ReturnOrder $return): RedirectResponse
    {
        $user = auth()->user();

        if ($return->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if ($return->status !== 'pending') {
            return redirect()->route('tenant.sales.returns.show', $return)
                ->with('error', 'طلب الإرجاع تم معالجته مسبقاً');
        }

        $return->approve($user->id);

        return redirect()->route('tenant.sales.returns.show', $return)
            ->with('success', 'تم الموافقة على طلب الإرجاع وتحديث المخزون');
    }

    /**
     * Complete a return
     */
    public function complete(ReturnOrder $return): RedirectResponse
    {
        $user = auth()->user();

        if ($return->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if ($return->status !== 'approved') {
            return redirect()->route('tenant.sales.returns.show', $return)
                ->with('error', 'يجب الموافقة على طلب الإرجاع أولاً');
        }

        $return->complete($user->id);

        return redirect()->route('tenant.sales.returns.show', $return)
            ->with('success', 'تم إكمال طلب الإرجاع بنجاح');
    }

    /**
     * Reject a return
     */
    public function reject(ReturnOrder $return): RedirectResponse
    {
        $user = auth()->user();

        if ($return->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        if ($return->status !== 'pending') {
            return redirect()->route('tenant.sales.returns.show', $return)
                ->with('error', 'طلب الإرجاع تم معالجته مسبقاً');
        }

        $return->reject($user->id);

        return redirect()->route('tenant.sales.returns.show', $return)
            ->with('success', 'تم رفض طلب الإرجاع');
    }
}
