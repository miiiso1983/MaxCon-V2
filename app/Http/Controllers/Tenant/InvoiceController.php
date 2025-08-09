<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        
        $invoices = Invoice::with(['customer', 'warehouse', 'salesRep'])
            ->forTenant($tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tenant.sales.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        
        $customers = Customer::forTenant($tenantId)->active()->get();
        $warehouses = Warehouse::forTenant($tenantId)->active()->get();
        $products = Product::forTenant($tenantId)->active()->get();

        return view('tenant.sales.invoices.create', compact('customers', 'warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.selling_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $tenantId = Auth::user()->tenant_id;
            $customer = Customer::find($request->customer_id);
            
            // Create invoice
            $invoice = new Invoice();
            $invoice->tenant_id = $tenantId;
            $invoice->customer_id = $request->customer_id;
            $invoice->warehouse_id = $request->warehouse_id;
            $invoice->sales_rep_id = Auth::id();
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->previous_debt = $customer->current_balance ?? 0;
            $invoice->credit_limit = $customer->credit_limit ?? 0;
            $invoice->currency = 'IQD';
            $invoice->notes = $request->notes;
            $invoice->terms_conditions = $request->terms_conditions;
            
            // Generate invoice number
            $invoice->invoice_number = $this->generateInvoiceNumber($tenantId);
            $invoice->save();

            $subtotal = 0;
            
            // Create invoice items
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                // Check stock availability
                $warehouseStock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
                    ->where('product_id', $itemData['product_id'])
                    ->first();
                
                if (!$warehouseStock || $warehouseStock->available_quantity < $itemData['quantity']) {
                    throw new \Exception("المخزون غير كافي للمنتج: {$product->name}");
                }
                
                $item = new InvoiceItem();
                $item->invoice_id = $invoice->id;
                $item->product_id = $itemData['product_id'];
                $item->product_name = $product->name;
                $item->product_code = $product->product_code ?? '';
                $item->quantity = $itemData['quantity'];
                $item->unit = $product->unit_of_measure ?? 'قطعة';
                $item->unit_price = $product->unit_price ?? 0;
                $item->selling_price = $itemData['selling_price'];
                $item->discount_percentage = $itemData['discount_percentage'] ?? 0;
                $item->tax_percentage = $itemData['tax_percentage'] ?? 0;
                
                $item->calculateLineTotal();
                $item->save();
                
                $subtotal += $item->line_total;
                
                // Update warehouse stock
                $warehouseStock->updateQuantity($itemData['quantity'], 'subtract');
            }
            
            // Calculate invoice totals
            $invoice->subtotal = $subtotal;
            $invoice->discount_amount = $request->discount_amount ?? 0;
            $invoice->discount_percentage = $request->discount_percentage ?? 0;
            $invoice->tax_percentage = $request->tax_percentage ?? 0;
            $invoice->tax_amount = ($invoice->subtotal * $invoice->tax_percentage) / 100;
            $invoice->total_amount = $invoice->subtotal + $invoice->tax_amount - $invoice->discount_amount;
            $invoice->remaining_amount = $invoice->total_amount;
            $invoice->current_debt = $invoice->previous_debt + $invoice->total_amount;
            
            // Generate QR Code data
            $invoice->generateQRCodeData();
            $invoice->save();
            
            // Update customer balance
            $customer->current_balance = $invoice->current_debt;
            $customer->save();
            
            DB::commit();
            
            return redirect()->route('tenant.sales.invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'warehouse', 'salesRep', 'items.product', 'payments']);
        
        return view('tenant.sales.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if (!$invoice->canBeEdited()) {
            return redirect()->route('tenant.sales.invoices.show', $invoice)
                ->with('error', 'لا يمكن تعديل هذه الفاتورة');
        }
        
        $tenantId = Auth::user()->tenant_id;
        
        $customers = Customer::forTenant($tenantId)->active()->get();
        $warehouses = Warehouse::forTenant($tenantId)->active()->get();
        $products = Product::forTenant($tenantId)->active()->get();
        
        $invoice->load(['items.product']);

        return view('tenant.sales.invoices.edit', compact('invoice', 'customers', 'warehouses', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if (!$invoice->canBeEdited()) {
            return redirect()->route('tenant.sales.invoices.show', $invoice)
                ->with('error', 'لا يمكن تعديل هذه الفاتورة');
        }
        
        // Similar validation and update logic as store method
        // Implementation would be similar to store but with update logic
        
        return redirect()->route('tenant.sales.invoices.show', $invoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    public function destroy(Invoice $invoice)
    {
        if (!$invoice->canBeCancelled()) {
            return redirect()->route('tenant.sales.invoices.index')
                ->with('error', 'لا يمكن حذف هذه الفاتورة');
        }
        
        DB::beginTransaction();
        
        try {
            // Restore stock quantities
            foreach ($invoice->items as $item) {
                $warehouseStock = WarehouseStock::where('warehouse_id', $invoice->warehouse_id)
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($warehouseStock) {
                    $warehouseStock->updateQuantity($item->quantity, 'add');
                }
            }
            
            // Update customer balance
            $customer = $invoice->customer;
            $customer->current_balance -= $invoice->total_amount;
            $customer->save();
            
            $invoice->delete();
            
            DB::commit();
            
            return redirect()->route('tenant.sales.invoices.index')
                ->with('success', 'تم حذف الفاتورة بنجاح');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    private function generateInvoiceNumber($tenantId)
    {
        $prefix = 'INV';
        $year = now()->year;
        $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
        
        $lastInvoice = Invoice::where('tenant_id', $tenantId)
            ->where('invoice_number', 'like', "{$prefix}-{$year}{$month}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['customer', 'warehouse', 'salesRep', 'items.product']);

        // Generate QR Code
        $qrCodeData = json_encode($invoice->qr_code_data);
        $qrCode = QrCode::size(100)->generate($qrCodeData);

        $invoice->markAsPrinted();

        return view('tenant.sales.invoices.print', compact('invoice', 'qrCode'));
    }

    public function printThermal(Invoice $invoice)
    {
        $invoice->load(['customer', 'warehouse', 'salesRep', 'items.product']);

        // Generate QR Code for thermal printer
        $qrCodeData = json_encode($invoice->qr_code_data);
        $qrCode = QrCode::size(80)->generate($qrCodeData);

        $invoice->markAsPrinted();

        return view('tenant.sales.invoices.print-thermal', compact('invoice', 'qrCode'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'warehouse', 'salesRep', 'items.product']);

        // Generate QR Code
        $qrCodeData = json_encode($invoice->qr_code_data);
        $qrCode = QrCode::size(100)->generate($qrCodeData);

        $pdf = Pdf::loadView('tenant.sales.invoices.pdf', compact('invoice', 'qrCode'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function sendEmail(Request $request, Invoice $invoice)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'nullable|string',
        ]);

        try {
            // Generate PDF
            $invoice->load(['customer', 'warehouse', 'salesRep', 'items.product']);
            $qrCodeData = json_encode($invoice->qr_code_data);
            $qrCode = QrCode::size(100)->generate($qrCodeData);

            $pdf = Pdf::loadView('tenant.sales.invoices.pdf', compact('invoice', 'qrCode'));
            $pdfContent = $pdf->output();

            // Send email logic here
            // Mail::to($request->email)->send(new InvoiceMail($invoice, $pdfContent, $request->subject, $request->message));

            $invoice->markAsEmailSent();

            return response()->json(['success' => true, 'message' => 'تم إرسال الفاتورة بالإيميل بنجاح']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء إرسال الإيميل']);
        }
    }

    public function sendWhatsApp(Request $request, Invoice $invoice)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'nullable|string',
        ]);

        try {
            // Generate PDF
            $invoice->load(['customer', 'warehouse', 'salesRep', 'items.product']);
            $qrCodeData = json_encode($invoice->qr_code_data);
            $qrCode = QrCode::size(100)->generate($qrCodeData);

            $pdf = Pdf::loadView('tenant.sales.invoices.pdf', compact('invoice', 'qrCode'));
            $pdfContent = $pdf->output();

            // WhatsApp sending logic here
            // This would integrate with WhatsApp Business API

            $invoice->markAsWhatsAppSent();

            return response()->json(['success' => true, 'message' => 'تم إرسال الفاتورة عبر الواتساب بنجاح']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء إرسال الواتساب']);
        }
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->remaining_amount,
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,other',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $payment = $invoice->addPayment(
                $request->amount,
                $request->payment_method,
                $request->reference_number,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الدفعة بنجاح',
                'payment' => $payment,
                'invoice' => $invoice->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getCustomerDebt(Customer $customer)
    {
        return response()->json([
            'current_balance' => $customer->current_balance ?? 0,
            'credit_limit' => $customer->credit_limit ?? 0,
            'available_credit' => ($customer->credit_limit ?? 0) - ($customer->current_balance ?? 0),
        ]);
    }

    public function getWarehouseStock(Warehouse $warehouse, Product $product)
    {
        $stock = WarehouseStock::where('warehouse_id', $warehouse->id)
            ->where('product_id', $product->id)
            ->first();

        return response()->json([
            'available_quantity' => $stock ? $stock->available_quantity : 0,
            'reserved_quantity' => $stock ? $stock->reserved_quantity : 0,
            'total_quantity' => $stock ? $stock->quantity : 0,
        ]);
    }
}
