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
use Illuminate\Support\Facades\Storage;
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

        // Load relations needed for display and calculations
        $invoice->load(['customer','payments','salesRep','items']);

        // Ensure totals are computed if missing
        if ((float)($invoice->total_amount ?? 0) <= 0 && $invoice->items && $invoice->items->count() > 0) {
            try { $invoice->calculateTotals(); } catch (\Throwable $e) { /* ignore */ }
            $invoice->refresh();
        }

        // Sync paid and remaining with actual payments sum
        $paidSum = (float) $invoice->payments->sum('amount');
        $expectedRemaining = max(((float)$invoice->total_amount) - $paidSum, 0);
        $needsUpdate = false;
        if ((float)($invoice->paid_amount ?? 0) !== $paidSum) { $invoice->paid_amount = $paidSum; $needsUpdate = true; }
        if ((float)($invoice->remaining_amount ?? 0) !== $expectedRemaining) { $invoice->remaining_amount = $expectedRemaining; $needsUpdate = true; }
        if ($needsUpdate) { $invoice->save(); $invoice->refresh(); }

        return view('tenant.accounting.receivables.invoice-show', compact('invoice'));
    }

    public function storePayment(Request $request, Invoice $invoice, ReceivablesService $service, ReceiptService $receiptService)
    {
        $this->authorizeAccess($invoice);
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Cap amount at remaining on server-side to prevent validation confusion with decimals/rounding
        $amount = (float) $validated['amount'];
        $remaining = (float) ($invoice->remaining_amount ?? 0);
        if ($remaining > 0 && $amount > $remaining + 0.0001) {
            $amount = $remaining; // clamp silently
        }

        $payment = $invoice->addPayment(
            $amount,
            $validated['payment_method'],
            $validated['reference_number'] ?? null,
            $validated['notes'] ?? null
        );

        // Generate receipt number and PDF
        $payment->receipt_number = InvoicePayment::generateReceiptNumber($invoice->tenant_id);

        try {
            $payment->pdf_path = app(ReceiptService::class)->generatePdf($payment);
        } catch (\Throwable $e) {
            \Log::error('Failed to generate receipt PDF: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id
            ]);
            // Continue without PDF - user can still access web receipt
        }

        $payment->save();

        return redirect()->route('tenant.inventory.accounting.receivables.invoice', $invoice)
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

        $companyName = Auth::user()->tenant->name ?? 'شركة ماكس كون';
        $receiptUrl = $payment->pdf_path ? Storage::url($payment->pdf_path) : '';
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

    /**
     * Web receipt view (unified design with PDF)
     */
    public function showWebReceipt(InvoicePayment $payment): View
    {
        $invoice = $payment->invoice()->with(['customer','salesRep','tenant'])->firstOrFail();
        $this->authorizeAccess($invoice);

        $companyName = optional($invoice->tenant)->company_name ?? optional($invoice->tenant)->name ?? config('app.name','MaxCon');
        $salesRepName = optional($invoice->salesRep)->name ?? '-';
        $customerName = optional($invoice->customer)->name ?? '-';
        $receiptNo = $payment->receipt_number ?? ('RC-' . str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT));
        $invNo = $invoice->invoice_number ?? ('INV-' . $invoice->id);
        $dateStr = $payment->payment_date ? \Illuminate\Support\Carbon::parse($payment->payment_date)->format('Y-m-d') : now()->format('Y-m-d');
        $paymentMethod = method_exists($payment,'getPaymentMethodLabel') ? $payment->getPaymentMethodLabel() : ($payment->payment_method ?? '-');

        // QR (SVG data URL) - Professional formatted text
        $qrUrl = null;
        $paymentMethodLabel = $this->getPaymentMethodLabel($payment->payment_method);
        $formattedAmount = number_format((float) $payment->amount, 2);

        $qrText = "🧾 سند استلام\n";
        $qrText .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $qrText .= "📋 رقم السند: {$payment->receipt_number}\n";
        $qrText .= "📄 رقم الفاتورة: {$invoice->invoice_number}\n";
        $qrText .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $qrText .= "🏢 الشركة: {$companyName}\n";
        $qrText .= "👤 العميل: {$customerName}\n";
        if ($salesRepName !== '-') {
            $qrText .= "👨‍💼 المندوب: {$salesRepName}\n";
        }
        $qrText .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $qrText .= "💰 المبلغ المستلم: {$formattedAmount} د.ع\n";
        $qrText .= "💳 طريقة الدفع: {$paymentMethodLabel}\n";
        $qrText .= "📅 التاريخ: {$dateStr}\n";
        $qrText .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $qrText .= "✅ تم الاستلام بنجاح\n";
        $qrText .= "🔒 مصدق من نظام ماكس كون";

        // Try multiple methods to generate QR code
        try {
            // Method 1: SimpleSoftwareIO QrCode (SVG)
            if (class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode')) {
                $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(220)->margin(1)->generate($qrText);
                $qrUrl = 'data:image/svg+xml;base64,' . base64_encode($svg);
            }
        } catch (\Throwable $e) {
            \Log::warning('QR Code SVG generation failed: ' . $e->getMessage());
        }

        // Method 2: Fallback to PNG via external API
        if (!$qrUrl) {
            try {
                $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&format=png&data=' . urlencode($qrText);
                $qrUrl = $qrApiUrl; // Direct URL for img src
            } catch (\Throwable $e) {
                \Log::warning('QR Code API fallback failed: ' . $e->getMessage());
            }
        }

        // Method 3: Simple text fallback
        if (!$qrUrl) {
            try {
                $simpleData = "سند استلام رقم: {$payment->receipt_number}\nالمبلغ: " . number_format((float)$payment->amount, 2) . " د.ع\nالتاريخ: {$dateStr}";
                $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&format=png&data=' . urlencode($simpleData);
                $qrUrl = $qrApiUrl;
            } catch (\Throwable $e) {
                \Log::error('All QR Code generation methods failed for web receipt: ' . $e->getMessage());
            }
        }

        // Logo URL
        $logoUrl = null;
        try {
            $logoPath = $invoice->tenant->logo ?? null;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                $logoUrl = Storage::url($logoPath);
            }
        } catch (\Throwable $e) {}

        return view('tenant.accounting.receivables.receipt-web', compact(
            'invoice','payment','companyName','salesRepName','customerName','receiptNo','invNo','dateStr','paymentMethod','qrUrl','logoUrl'
        ));
    }

    /**
     * Get Arabic label for payment method
     */
    private function getPaymentMethodLabel($method): string
    {
        $methods = [
            'cash' => 'نقداً',
            'credit_card' => 'بطاقة ائتمان',
            'debit_card' => 'بطاقة خصم',
            'bank_transfer' => 'تحويل بنكي',
            'check' => 'شيك',
            'online' => 'دفع إلكتروني',
            'installment' => 'تقسيط',
            'other' => 'أخرى'
        ];

        return $methods[$method] ?? $method ?? 'نقداً';
    }
}
