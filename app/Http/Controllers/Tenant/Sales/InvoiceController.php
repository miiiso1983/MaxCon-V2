<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use TCPDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
use App\Helpers\InvoiceQRHelper;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        if (!$user || !$user->tenant_id) {
            abort(403, 'غير مصرح لك بالوصول');
        }

        $query = Invoice::with(['customer', 'createdBy', 'salesOrder'])
            ->forTenant($user->tenant_id);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->where('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('invoice_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(15);

        $customers = Customer::forTenant($user->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        $statusCounts = Invoice::forTenant($user->tenant_id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('tenant.sales.invoices.index', compact('invoices', 'customers', 'statusCounts'));
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create(Request $request): View
    {
        $user = Auth::user();
        if (!$user || !$user->tenant_id) {
            abort(403, 'غير مصرح لك بالوصول');
        }

        $customers = Customer::forTenant($user->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        $products = Product::forTenant($user->tenant_id)
            ->active()
            ->orderBy('name')
            ->get();

        $salesOrders = SalesOrder::forTenant($user->tenant_id)
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->whereDoesntHave('invoice')
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedOrder = null;
        if ($request->filled('sales_order_id')) {
            $selectedOrder = SalesOrder::with(['customer', 'items.product'])
                ->where('tenant_id', $user->tenant_id)
                ->find($request->sales_order_id);
        }

        return view('tenant.sales.invoices.create', compact('customers', 'products', 'salesOrders', 'selectedOrder'));
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request): RedirectResponse
    {
        // Debug: Log the request data
        Log::info('Invoice store request:', $request->all());

        // Check if user is authenticated and has tenant_id
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        $user = Auth::user();
        if (!$user->tenant_id) {
            return redirect()->route('tenant.dashboard')->with('error', 'لا يمكن تحديد المؤسسة');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_order_id' => 'nullable|exists:sales_orders,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'type' => 'required|in:sales,proforma',
            'currency' => 'required|string|max:20',
            'sales_representative' => 'required|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
            'subtotal_amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'previous_balance' => 'nullable|numeric|min:0',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'free_samples' => 'nullable|string',
            'ignore_stock_check' => 'nullable|boolean',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.discount_type' => 'required|in:fixed,percentage',
            'items.*.total_amount' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        // Check stock availability for each item
        foreach ($validated['items'] as $index => $itemData) {
            $product = Product::find($itemData['product_id']);

            if (!$product) {
                return back()->withErrors([
                    "items.{$index}.product_id" => 'المنتج غير موجود'
                ])->withInput();
            }

            // Check if product belongs to current tenant
            if ($product->tenant_id !== $user->tenant_id) {
                return back()->withErrors([
                    "items.{$index}.product_id" => 'المنتج غير متاح لهذا المستأجر'
                ])->withInput();
            }

            // Check stock availability (only for sales invoices, not proforma)
            // You can disable this check by setting ENABLE_STOCK_CHECK=false in .env
            $enableStockCheck = config('app.enable_stock_check', true);
            $ignoreStockCheck = $validated['ignore_stock_check'] ?? false;

            if ($enableStockCheck && !$ignoreStockCheck && $validated['type'] === 'sales' && $product->current_stock < $itemData['quantity']) {
                return back()->withErrors([
                    "items.{$index}.quantity" => "المخزون المتاح للمنتج '{$product->name}' هو {$product->current_stock} {$product->unit} فقط. الكمية المطلوبة: {$itemData['quantity']}"
                ])->withInput();
            }
        }

        try {
            DB::beginTransaction();

            Log::info('Starting invoice creation', ['user_id' => $user->id, 'tenant_id' => $user->tenant_id]);

            // Create invoice
            $invoice = new Invoice();
            $invoice->tenant_id = $user->tenant_id;
            $invoice->invoice_number = $invoice->generateInvoiceNumber();
            $invoice->customer_id = $validated['customer_id'];
            $invoice->sales_order_id = $validated['sales_order_id'];
            $invoice->created_by = $user->id;
            $invoice->invoice_date = $validated['invoice_date'];
            $invoice->due_date = $validated['due_date'];
            $invoice->type = $validated['type'];
            $invoice->notes = $validated['notes'];
            $invoice->free_samples = $validated['free_samples'];
            $invoice->currency = $validated['currency'];
            $invoice->sales_representative = $validated['sales_representative'];
            $invoice->exchange_rate = 1.0; // Default exchange rate
            $invoice->shipping_cost = $validated['shipping_cost'] ?? 0;
            $invoice->additional_charges = $validated['additional_charges'] ?? 0;
            $invoice->discount_amount = $validated['discount_amount'] ?? 0;
            $invoice->discount_type = $validated['discount_type'];
            $invoice->subtotal_amount = $validated['subtotal_amount'];
            $invoice->tax_amount = $validated['tax_amount'];
            $invoice->total_amount = $validated['total_amount'];
            $invoice->previous_balance = $validated['previous_balance'] ?? 0;
            $invoice->credit_limit = $validated['credit_limit'] ?? 0;

            // Set status based on action
            $invoice->status = $request->input('action') === 'finalize' ? 'pending' : 'draft';

            Log::info('About to save invoice', ['invoice_data' => $invoice->toArray()]);
            $invoice->save();
            Log::info('Invoice saved successfully', ['invoice_id' => $invoice->id]);

            // Generate QR Code
            $invoice->generateQrCode();
            Log::info('QR Code generated', ['qr_code_length' => strlen($invoice->qr_code ?? '')]);

            // Create invoice items
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);

                $invoiceItem = new InvoiceItem();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $product->id;
                $invoiceItem->product_name = $product->name;
                $invoiceItem->product_code = $product->product_code;
                $invoiceItem->batch_number = $product->batch_number;
                $invoiceItem->expiry_date = $product->expiry_date;
                $invoiceItem->quantity = $itemData['quantity'];
                $invoiceItem->unit_price = $itemData['unit_price'];
                $invoiceItem->discount_amount = $itemData['discount_amount'] ?? 0;
                $invoiceItem->discount_type = $itemData['discount_type'];
                $invoiceItem->total_amount = $itemData['total_amount'];
                $invoiceItem->line_total = $itemData['total_amount']; // Set line_total same as total_amount
                $invoiceItem->notes = $itemData['notes'] ?? null;
                $invoiceItem->save();

                // Update product stock if invoice is finalized
                if ($invoice->status === 'pending') {
                    $product->current_stock -= $itemData['quantity'];
                    $product->save();
                }
            }

            // Generate QR Code if finalized
            if ($invoice->status === 'pending') {
                $invoice->generateQrCode();
            }

            DB::commit();

            $message = $invoice->status === 'pending' ? 'تم إنشاء الفاتورة وإنهاؤها بنجاح' : 'تم حفظ الفاتورة كمسودة';

            return redirect()->route('tenant.sales.invoices.show', $invoice)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice): View
    {
        $user = Auth::user();

        // Check if invoice belongs to current tenant
        if ($invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $invoice->load(['customer', 'items.product', 'createdBy', 'salesOrder', 'payments']);

        // Generate products QR code for this invoice
        $productsQRData = $this->generateProductsQRCode($user->tenant_id);

        return view('tenant.sales.invoices.show_professional', compact('invoice'))
            ->with('productsQRData', $productsQRData);
    }

    /**
     * Generate and download PDF using mPDF (better Arabic support)
     */
    public function downloadPdf(Invoice $invoice)
    {
        $user = Auth::user();

        // Check if invoice belongs to current tenant
        if ($invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $invoice->load(['customer', 'items.product', 'tenant']);

        try {
            // Generate QR code if not exists
            if (!$invoice->qr_code) {
                $invoice->generateQrCode();
            }

            // Generate products QR code
            $productsQRData = $this->generateProductsQRCode($user->tenant_id);

            // Simple HTML response as fallback
            return view('tenant.sales.invoices.pdf_simple', compact('invoice'))
                ->with('download', true)
                ->with('productsQRCode', $productsQRData['qr_code'] ?? null)
                ->with('productsCount', $productsQRData['count'] ?? 0);

        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('tenant.sales.invoices.index')
                ->with('error', 'فشل في إنشاء ملف PDF: ' . $e->getMessage());
        }
    }

    /**
     * View PDF in browser using mPDF
     */
    public function viewPdf(Invoice $invoice)
    {
        $user = Auth::user();

        // Check if invoice belongs to current tenant
        if ($invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $invoice->load(['customer', 'items.product', 'tenant']);

        try {
            // Generate QR code if not exists
            if (!$invoice->qr_code) {
                $invoice->generateQrCode();
            }

            // Generate products QR code
            $productsQRData = $this->generateProductsQRCode($user->tenant_id);

            // Return HTML view for now
            return view('tenant.sales.invoices.pdf_simple', compact('invoice'))
                ->with('productsQRCode', $productsQRData['qr_code'] ?? null)
                ->with('productsCount', $productsQRData['count'] ?? 0);

        } catch (\Exception $e) {
            Log::error('PDF view failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback to HTML view
            return view('tenant.sales.invoices.pdf_simple', compact('invoice'));
        }
    }

    /**
     * Generate HTML content for PDF
     */
    private function generateInvoiceHtml(Invoice $invoice)
    {
        $currencySymbol = $this->getCurrencySymbol($invoice->currency);

        $html = '
        <style>
            body {
                font-family: cairo, dejavusans;
                direction: rtl;
                text-align: right;
                margin: 0;
                padding: 20px;
                background: #f8fafc;
                color: #2d3748;
            }
            .invoice-container {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-align: center;
                padding: 40px 20px;
                margin-bottom: 0;
                position: relative;
            }
            .company-logo {
                width: 80px;
                height: 80px;
                background: rgba(255,255,255,0.2);
                border-radius: 50%;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                font-weight: bold;
            }
            .company-name {
                font-size: 32px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .invoice-title {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 5px;
                opacity: 0.9;
            }
            .invoice-number {
                font-size: 16px;
                opacity: 0.8;
            }
            .content-section {
                padding: 30px;
            }
            .info-grid {
                display: table;
                width: 100%;
                margin-bottom: 30px;
            }
            .info-column {
                display: table-cell;
                width: 50%;
                vertical-align: top;
                padding: 20px;
                background: #f8fafc;
                border-radius: 10px;
                margin: 0 10px;
            }
            .info-column h4 {
                color: #ed8936;
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 15px;
                border-bottom: 2px solid #ed8936;
                padding-bottom: 5px;
            }
            .info-row {
                margin-bottom: 12px;
                display: table;
                width: 100%;
            }
            .info-label {
                font-weight: bold;
                color: #4a5568;
                display: table-cell;
                width: 40%;
            }
            .info-value {
                display: table-cell;
                color: #2d3748;
                font-weight: 500;
            }
            .items-section {
                margin-bottom: 30px;
            }
            .section-title {
                font-size: 22px;
                font-weight: bold;
                color: #2d3748;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 3px solid #ed8936;
            }
            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .items-table th {
                background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
                color: white;
                padding: 15px 10px;
                text-align: center;
                font-weight: bold;
                font-size: 14px;
            }
            .items-table td {
                padding: 12px 10px;
                border-bottom: 1px solid #e2e8f0;
                text-align: center;
            }
            .items-table tbody tr:nth-child(even) {
                background: #f7fafc;
            }
            .product-info {
                text-align: right;
                padding-right: 15px;
            }
            .product-name {
                font-weight: bold;
                color: #2d3748;
                font-size: 14px;
                margin-bottom: 3px;
            }
            .product-code {
                font-size: 11px;
                color: #718096;
                background: #edf2f7;
                padding: 2px 6px;
                border-radius: 8px;
                display: inline-block;
            }
            .totals-section {
                background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
                padding: 25px;
                border-radius: 10px;
                margin-top: 20px;
                border: 2px solid #e2e8f0;
            }
            .total-row {
                margin-bottom: 12px;
                display: table;
                width: 100%;
                padding-bottom: 8px;
                border-bottom: 1px solid #e2e8f0;
            }
            .total-label {
                display: table-cell;
                font-weight: bold;
                color: #4a5568;
                width: 70%;
            }
            .total-value {
                display: table-cell;
                font-weight: bold;
                color: #2d3748;
                text-align: left;
            }
            .total-final {
                background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
                color: white;
                padding: 15px;
                font-weight: bold;
                text-align: center;
                border-radius: 8px;
                margin-top: 15px;
                font-size: 18px;
            }
            .qr-section {
                text-align: center;
                margin-top: 30px;
                padding: 20px;
                background: #e6fffa;
                border-radius: 10px;
                border: 2px solid #38b2ac;
            }
            .notes-section {
                background: #fff5f5;
                padding: 20px;
                border-radius: 10px;
                border-left: 5px solid #f56565;
                margin-top: 20px;
            }
            .footer {
                text-align: center;
                margin-top: 30px;
                padding-top: 20px;
                border-top: 2px solid #e2e8f0;
                color: #718096;
                font-size: 12px;
            }
        </style>

        <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-logo">' . substr($invoice->tenant->name ?? 'ماكس', 0, 2) . '</div>
            <div class="company-name">' . ($invoice->tenant->name ?? 'شركة ماكس كون') . '</div>
            <div class="invoice-title">فاتورة ' . ($invoice->type === 'sales' ? 'مبيعات' : 'أولية') . '</div>
            <div class="invoice-number">رقم الفاتورة: ' . $invoice->invoice_number . '</div>
        </div>

        <div class="content-section">';

        $html .= '
        <!-- Invoice Info -->
        <div class="info-grid">
            <div class="info-column">
                <h4>معلومات العميل</h4>
                <div class="info-row">
                    <span class="info-label">العميل:</span>
                    <span class="info-value">' . $invoice->customer->name . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">الهاتف:</span>
                    <span class="info-value">' . ($invoice->customer->phone ?? 'غير محدد') . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">البريد:</span>
                    <span class="info-value">' . ($invoice->customer->email ?? 'غير محدد') . '</span>
                </div>
            </div>
            <div class="info-column">
                <h4>تفاصيل الفاتورة</h4>
                <div class="info-row">
                    <span class="info-label">تاريخ الفاتورة:</span>
                    <span class="info-value">' . $invoice->invoice_date->format('Y/m/d') . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">تاريخ الاستحقاق:</span>
                    <span class="info-value">' . $invoice->due_date->format('Y/m/d') . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">العملة:</span>
                    <span class="info-value">' . $invoice->currency . '</span>
                </div>';

        if ($invoice->sales_representative) {
            $html .= '<div class="info-row"><span class="info-label">المندوب:</span> ' . $invoice->sales_representative . '</div>';
        }

        $html .= '
                    </td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40%;">المنتج</th>
                    <th style="width: 10%;">الكمية</th>
                    <th style="width: 15%;">سعر الوحدة</th>
                    <th style="width: 15%;">الخصم</th>
                    <th style="width: 20%;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($invoice->items as $item) {
            $html .= '
                <tr>
                    <td>
                        <div style="font-weight: bold;">' . $item->product_name . '</div>
                        <div style="font-size: 10px; color: #666;">' . $item->product_code . '</div>
                    </td>
                    <td>' . $item->quantity . '</td>
                    <td>' . number_format($item->unit_price, 2) . ' ' . $currencySymbol . '</td>
                    <td>' . number_format($item->discount_amount, 2) . ' ' . $currencySymbol . '</td>
                    <td>' . number_format($item->line_total, 2) . ' ' . $currencySymbol . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row"><span class="info-label">المجموع الفرعي:</span> ' . number_format($invoice->subtotal_amount, 2) . ' ' . $currencySymbol . '</div>
            <div class="total-row"><span class="info-label">الخصم:</span> ' . number_format($invoice->discount_amount, 2) . ' ' . $currencySymbol . '</div>
            <div class="total-row"><span class="info-label">الشحن والرسوم:</span> ' . number_format($invoice->shipping_cost + $invoice->additional_charges, 2) . ' ' . $currencySymbol . '</div>
            <div class="total-row"><span class="info-label">ضريبة القيمة المضافة:</span> ' . number_format($invoice->tax_amount, 2) . ' ' . $currencySymbol . '</div>';

        if ($invoice->previous_balance > 0) {
            $html .= '
            <div class="total-row"><span class="info-label">المديونية السابقة:</span> ' . number_format($invoice->previous_balance, 2) . ' ' . $currencySymbol . '</div>
            <div class="total-row"><span class="info-label">إجمالي الفاتورة:</span> ' . number_format($invoice->total_amount, 2) . ' ' . $currencySymbol . '</div>
            <div class="total-final">إجمالي المديونية: ' . number_format($invoice->total_amount + $invoice->previous_balance, 2) . ' ' . $currencySymbol . '</div>';
        } else {
            $html .= '<div class="total-final">الإجمالي النهائي: ' . number_format($invoice->total_amount, 2) . ' ' . $currencySymbol . '</div>';
        }

        $html .= '</div>';

        // QR Code section
        if ($invoice->qr_code) {
            $html .= '
            <div style="margin-top: 20px; text-align: center; border: 1px solid #ddd; padding: 15px;">
                <div style="font-weight: bold; margin-bottom: 10px;">رمز QR للتحقق من الفاتورة</div>
                <div style="font-size: 10px; color: #666; margin-bottom: 10px;">امسح الرمز للحصول على معلومات الفاتورة كاملة</div>
                <img src="data:image/png;base64,' . $invoice->qr_code . '" style="width: 120px; height: 120px; margin: 10px auto; display: block;" />
            </div>';
        }

        if ($invoice->notes) {
            $html .= '
            <div style="margin-top: 20px; padding: 10px; background: #f5f5f5; border-right: 4px solid #ed8936;">
                <div style="font-weight: bold; margin-bottom: 5px;">ملاحظات:</div>
                <div>' . $invoice->notes . '</div>
            </div>';
        }

        $html .= '
        <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #666;">
            تم إنشاء هذه الفاتورة إلكترونياً بواسطة نظام ماكس كون
        </div>';

        return $html;
    }

    /**
     * Get currency symbol
     */
    private function getCurrencySymbol($currency)
    {
        $symbols = [
            'IQD' => 'د.ع',
            'USD' => '$',
            'SAR' => 'ر.س',
            'AED' => 'د.إ',
            'EUR' => '€'
        ];
        return $symbols[$currency] ?? $currency;
    }

    /**
     * Send invoice via email
     */
    public function sendEmail(Request $request, Invoice $invoice)
    {
        $user = Auth::user();

        // Check if invoice belongs to current tenant
        if ($invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        try {
            // Mark as sent
            $invoice->markAsSent();

            // Add to email history
            $emailHistory = $invoice->email_history ?? [];
            $emailHistory[] = [
                'sent_at' => now(),
                'sent_by' => $user->name,
                'email' => $validated['email'],
                'subject' => "فاتورة رقم {$invoice->invoice_number}"
            ];
            $invoice->email_history = $emailHistory;
            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الفاتورة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الفاتورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update invoice status
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $user = Auth::user();

        // Check if invoice belongs to current tenant
        if ($invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,sent,viewed,partial_paid,paid,overdue,cancelled,refunded'
        ]);

        try {
            $invoice->status = $validated['status'];

            if ($validated['status'] === 'sent' && !$invoice->sent_at) {
                $invoice->sent_at = now();
            } elseif ($validated['status'] === 'viewed' && !$invoice->viewed_at) {
                $invoice->viewed_at = now();
            } elseif ($validated['status'] === 'paid' && !$invoice->paid_at) {
                $invoice->paid_at = now();
                $invoice->paid_amount = $invoice->total_amount;
                $invoice->remaining_amount = 0;
            }

            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الفاتورة بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة الفاتورة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create invoice from sales order
     */
    public function createFromOrder(SalesOrder $salesOrder): RedirectResponse
    {
        $user = Auth::user();

        // Check if order belongs to current tenant
        if ($salesOrder->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        // Check if order already has an invoice
        if ($salesOrder->invoice) {
            return redirect()->route('tenant.sales.invoices.show', $salesOrder->invoice)
                ->with('info', 'هذا الطلب له فاتورة موجودة بالفعل');
        }

        return redirect()->route('tenant.sales.invoices.create', ['sales_order_id' => $salesOrder->id]);
    }

    /**
     * Send invoice via WhatsApp
     */
    public function sendWhatsApp(Request $request, Invoice $invoice)
    {
        $user = Auth::user();

        // Check if invoice belongs to current tenant
        if ($invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'nullable|string'
        ]);

        try {
            // Clean phone number
            $phone = preg_replace('/[^\d+]/', '', $validated['phone']);

            // Add country code if not present
            if (!str_starts_with($phone, '+')) {
                if (str_starts_with($phone, '00')) {
                    $phone = '+' . substr($phone, 2);
                } elseif (str_starts_with($phone, '0')) {
                    $phone = '+964' . substr($phone, 1); // Iraq
                } else {
                    $phone = '+' . $phone;
                }
            }

            // Create WhatsApp message
            $companyName = $invoice->tenant->name ?? 'شركة ماكس كون';
            $invoiceUrl = url("/tenant/sales/invoices/{$invoice->id}");

            $defaultMessage = "مرحباً،\n\nتم إصدار فاتورة جديدة لكم من {$companyName}\n\n📄 رقم الفاتورة: {$invoice->invoice_number}\n🔗 رابط الفاتورة: {$invoiceUrl}\n\nيمكنكم عرض تفاصيل الفاتورة وتحميلها من الرابط أعلاه.\n\nشكراً لتعاملكم معنا.";

            $message = $validated['message'] ?? $defaultMessage;

            // Create WhatsApp URL
            $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

            // Log the WhatsApp send
            $whatsappHistory = $invoice->whatsapp_history ?? [];
            $whatsappHistory[] = [
                'sent_at' => now(),
                'sent_by' => $user->name,
                'phone' => $phone,
                'message' => $message
            ];
            $invoice->whatsapp_history = $whatsappHistory;
            $invoice->save();

            return response()->json([
                'success' => true,
                'whatsapp_url' => $whatsappUrl,
                'message' => 'تم إنشاء رابط واتساب بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء رابط واتساب: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test QR Code content
     */
    public function testQrCode(Invoice $invoice)
    {
        $user = Auth::user();

        // Check if invoice belongs to current tenant
        if ($invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $invoice->load(['customer', 'items.product', 'tenant']);

        // Force regenerate QR code
        $qrCode = $invoice->generateQrCode();

        // Create QR text for display
        $qrText = "شركة: " . ($invoice->tenant->name ?? 'شركة ماكس كون') . "\n";
        $qrText .= "رقم الفاتورة: " . $invoice->invoice_number . "\n";
        $qrText .= "العميل: " . $invoice->customer->name . "\n";
        $qrText .= "التاريخ: " . $invoice->invoice_date->format('Y-m-d') . "\n";
        $qrText .= "المبلغ: " . number_format($invoice->total_amount, 2) . " دينار عراقي\n";

        if ($invoice->previous_balance > 0) {
            $qrText .= "المديونية السابقة: " . number_format($invoice->previous_balance, 2) . " دينار عراقي\n";
            $qrText .= "إجمالي المديونية: " . number_format($invoice->total_amount + $invoice->previous_balance, 2) . " دينار عراقي\n";
        }

        if ($invoice->sales_representative) {
            $qrText .= "المندوب: " . $invoice->sales_representative . "\n";
        }

        $qrText .= "الرابط: " . url("/tenant/sales/invoices/{$invoice->id}");

        return response()->json([
            'qr_code_exists' => !empty($qrCode),
            'qr_code_length' => strlen($qrCode ?? ''),
            'qr_text' => $qrText,
            'qr_code_base64' => $qrCode,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number
        ]);
    }

    /**
     * Generate QR code for products catalog
     */
    private function generateProductsQRCode(int $tenantId): array
    {
        try {
            // Generate QR data for ALL available products (not just featured)
            $qrData = InvoiceQRHelper::generateInvoiceQRData($tenantId, [
                'type' => 'all',  // Changed from 'featured' to 'all'
                'limit' => 50     // Increased limit for more products
            ]);

            // Convert to JSON
            $qrJson = json_encode($qrData);

            // Check if data is too large for QR code
            if (strlen($qrJson) > 2000) {
                // If too large, use optimized version
                $qrData = InvoiceQRHelper::generateOptimizedInvoiceQR($tenantId, [
                    'type' => 'all',
                    'limit' => 30
                ]);
                $qrJson = json_encode($qrData);
            }

            // Generate QR code image using a simple QR library or service
            // For now, we'll return the data and let the frontend generate the QR
            return [
                'qr_data' => $qrJson,
                'qr_code' => null, // Will be generated on frontend
                'count' => $qrData['count'] ?? 0,
                'size' => strlen($qrJson),
                'type' => 'all_products'
            ];
        } catch (\Exception $e) {
            \Log::error('Error generating products QR code: ' . $e->getMessage());
            return [
                'qr_data' => null,
                'qr_code' => null,
                'count' => 0,
                'size' => 0,
                'type' => 'error'
            ];
        }
    }
}
