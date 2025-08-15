<?php

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Accounting\ReceivablesService;
use App\Services\Accounting\ReceiptService;

class ReceivablesController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $query = Invoice::with(['customer','salesRep'])
            ->where('tenant_id', $tenantId);

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('sales_rep_id')) {
            $query->where('sales_rep_id', $request->sales_rep_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('invoice_date','desc')->paginate(20);
        $customers = Customer::forTenant($tenantId)->orderBy('name')->get();

        return view('tenant.accounting.receivables.index', compact('invoices','customers'));
    }

    public function showInvoice(Invoice $invoice): View
    {
        $this->authorizeAccess($invoice);
        $invoice->load(['customer','payments','salesRep']);
        return view('tenant.accounting.receivables.invoice-show', compact('invoice'));
    }

    public function storePayment(Request $request, Invoice $invoice, ReceivablesService $service, ReceiptService $receiptService)
    {
        $this->authorizeAccess($invoice);
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->remaining_amount,
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment = $invoice->addPayment(
            $validated['amount'],
            $validated['payment_method'],
            $validated['reference_number'] ?? null,
            $validated['notes'] ?? null
        );

        // Generate receipt number and PDF
        $payment->receipt_number = InvoicePayment::generateReceiptNumber($invoice->tenant_id);
        $payment->pdf_path = app(ReceiptService::class)->generatePdf($payment);
        $payment->save();

        return redirect()->route('tenant.accounting.receivables.invoice', $invoice)
            ->with('success', 'تم تسجيل الدفعة وإنشاء سند الاستلام');
    }

    public function sendReceiptWhatsApp(Request $request, InvoicePayment $payment)
    {
        $invoice = $payment->invoice()->with('customer')->first();
        $this->authorizeAccess($invoice);

        $request->validate([
            'phone' => 'nullable|string'
        ]);

        // Phone: request or customer phone
        $phone = $request->input('phone') ?: ($invoice->customer->phone ?? $invoice->customer->mobile ?? '');
        $phone = preg_replace('/[^\d+]/', '', $phone ?? '');
        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '00')) {
                $phone = '+' . substr($phone, 2);
            } elseif (str_starts_with($phone, '0')) {
                $phone = '+964' . substr($phone, 1); // Iraq
            } elseif ($phone) {
                $phone = '+' . $phone;
            }
        }

        $companyName = auth()->user()->tenant->name ?? 'شركة ماكس كون';
        $receiptUrl = $payment->pdf_path ? \Storage::url($payment->pdf_path) : '';
        $amount = number_format((float)$payment->amount, 2);
        $message = "مرحباً،\n\nتم استلام دفعة مقابل فاتورتكم من {$companyName}.\n\nرقم الفاتورة: {$invoice->invoice_number}\nرقم السند: " . ($payment->receipt_number ?? '-') . "\nالمبلغ المستلم: {$amount} د.ع\n\nرابط سند الاستلام (PDF): {$receiptUrl}\n\nشكراً لتعاملكم معنا.";

        $whatsappUrl = 'https://wa.me/' . ltrim($phone, '+') . '?text=' . urlencode($message);

        // Mark as sent (log time)
        $payment->whatsapp_sent_at = now();
        $payment->save();

        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl
        ]);
    }

    protected function authorizeAccess(Invoice $invoice): void
    {
        $user = Auth::user();
        if (!$user || $invoice->tenant_id !== $user->tenant_id) {
            abort(403);
        }
    }
}

