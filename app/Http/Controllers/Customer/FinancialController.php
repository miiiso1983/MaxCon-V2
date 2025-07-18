<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerPayment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Customer Financial Controller
 * 
 * تحكم في المعلومات المالية للعملاء
 */
class FinancialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Display financial dashboard
     */
    public function index(): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->canViewFinancialInfo()) {
            abort(403, 'ليس لديك صلاحية لعرض المعلومات المالية');
        }

        $financialData = $this->getFinancialSummary($customer);

        return view('customer.financial.index', compact('financialData'));
    }

    /**
     * Display payment history
     */
    public function payments(Request $request): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->hasPermissionTo('view_payment_history')) {
            abort(403, 'ليس لديك صلاحية لعرض تاريخ الدفعات');
        }

        $query = CustomerPayment::where('customer_id', $customer->id)
            ->with(['invoice'])
            ->orderBy('payment_date', 'desc');

        // Apply filters
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('payment_date', [
                $request->date_from,
                $request->date_to
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->paginate(15);

        $statistics = [
            'total_payments' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->sum('amount'),
            'pending_payments' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'pending')
                ->sum('amount'),
            'this_month_payments' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'last_payment_date' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->max('payment_date'),
        ];

        return view('customer.financial.payments', compact('payments', 'statistics'));
    }

    /**
     * Display debt details
     */
    public function debt(): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->hasPermissionTo('view_debt_details')) {
            abort(403, 'ليس لديك صلاحية لعرض تفاصيل المديونية');
        }

        $debtData = $this->getDebtDetails($customer);

        return view('customer.financial.debt', compact('debtData'));
    }

    /**
     * Display credit limit information
     */
    public function creditLimit(): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->hasPermissionTo('view_credit_limit')) {
            abort(403, 'ليس لديك صلاحية لعرض الحد الائتماني');
        }

        $creditData = [
            'credit_limit' => $customer->credit_limit ?? 0,
            'current_debt' => $customer->total_debt,
            'available_credit' => $customer->available_credit,
            'credit_usage_percentage' => $customer->credit_limit > 0 
                ? ($customer->total_debt / $customer->credit_limit) * 100 
                : 0,
            'is_over_limit' => $customer->isOverCreditLimit(),
            'recent_orders_total' => $customer->salesOrders()
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('total_amount'),
        ];

        return view('customer.financial.credit-limit', compact('creditData'));
    }

    /**
     * Display invoices
     */
    public function invoices(Request $request): View
    {
        $customer = Auth::guard('customer')->user();
        
        // Check permission
        if (!$customer->hasPermissionTo('view_own_invoices')) {
            abort(403, 'ليس لديك صلاحية لعرض الفواتير');
        }

        $query = Invoice::where('customer_id', $customer->id)
            ->with(['items.product'])
            ->orderBy('invoice_date', 'desc');

        // Apply filters
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('invoice_date', [
                $request->date_from,
                $request->date_to
            ]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(15);

        $statistics = [
            'total_invoices' => Invoice::where('customer_id', $customer->id)->count(),
            'paid_invoices' => Invoice::where('customer_id', $customer->id)
                ->where('status', 'paid')
                ->count(),
            'pending_invoices' => Invoice::where('customer_id', $customer->id)
                ->where('status', 'pending')
                ->count(),
            'overdue_invoices' => Invoice::where('customer_id', $customer->id)
                ->where('status', 'overdue')
                ->count(),
            'total_amount' => Invoice::where('customer_id', $customer->id)->sum('total_amount'),
        ];

        return view('customer.financial.invoices', compact('invoices', 'statistics'));
    }

    /**
     * Download invoice PDF
     */
    public function downloadInvoice(Invoice $invoice)
    {
        $customer = Auth::guard('customer')->user();
        
        // Check if invoice belongs to customer
        if ($invoice->customer_id !== $customer->id) {
            abort(403, 'ليس لديك صلاحية لتحميل هذه الفاتورة');
        }

        // Check permission
        if (!$customer->hasPermissionTo('download_invoices')) {
            abort(403, 'ليس لديك صلاحية لتحميل الفواتير');
        }

        // Generate PDF and return download
        // This would integrate with your existing PDF generation logic
        return response()->download(
            storage_path("app/invoices/invoice_{$invoice->id}.pdf"),
            "invoice_{$invoice->invoice_number}.pdf"
        );
    }

    /**
     * Get financial summary
     */
    private function getFinancialSummary($customer): array
    {
        return [
            'current_balance' => $customer->current_balance ?? 0,
            'previous_debt' => $customer->previous_debt ?? 0,
            'total_debt' => $customer->total_debt,
            'credit_limit' => $customer->credit_limit ?? 0,
            'available_credit' => $customer->available_credit,
            'is_over_limit' => $customer->isOverCreditLimit(),
            
            // Recent activity
            'recent_payments' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get(),
            
            'recent_invoices' => Invoice::where('customer_id', $customer->id)
                ->orderBy('invoice_date', 'desc')
                ->limit(5)
                ->get(),
            
            // Monthly statistics
            'this_month_payments' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            
            'this_month_invoices' => Invoice::where('customer_id', $customer->id)
                ->whereMonth('invoice_date', now()->month)
                ->whereYear('invoice_date', now()->year)
                ->sum('total_amount'),
            
            // Payment statistics
            'total_payments_count' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->count(),
            
            'average_payment_amount' => CustomerPayment::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->avg('amount') ?? 0,
        ];
    }

    /**
     * Get debt details
     */
    private function getDebtDetails($customer): array
    {
        $unpaidInvoices = Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date')
            ->get();

        $overdueInvoices = $unpaidInvoices->where('due_date', '<', now());
        
        return [
            'previous_debt' => $customer->previous_debt ?? 0,
            'current_balance' => $customer->current_balance ?? 0,
            'total_debt' => $customer->total_debt,
            
            'unpaid_invoices' => $unpaidInvoices,
            'unpaid_invoices_total' => $unpaidInvoices->sum('total_amount'),
            'unpaid_invoices_count' => $unpaidInvoices->count(),
            
            'overdue_invoices' => $overdueInvoices,
            'overdue_invoices_total' => $overdueInvoices->sum('total_amount'),
            'overdue_invoices_count' => $overdueInvoices->count(),
            
            'aging_analysis' => [
                '0-30' => $unpaidInvoices->filter(function ($invoice) {
                    return $invoice->due_date >= now()->subDays(30);
                })->sum('total_amount'),
                
                '31-60' => $unpaidInvoices->filter(function ($invoice) {
                    return $invoice->due_date >= now()->subDays(60) && 
                           $invoice->due_date < now()->subDays(30);
                })->sum('total_amount'),
                
                '61-90' => $unpaidInvoices->filter(function ($invoice) {
                    return $invoice->due_date >= now()->subDays(90) && 
                           $invoice->due_date < now()->subDays(60);
                })->sum('total_amount'),
                
                '90+' => $unpaidInvoices->filter(function ($invoice) {
                    return $invoice->due_date < now()->subDays(90);
                })->sum('total_amount'),
            ],
        ];
    }
}
