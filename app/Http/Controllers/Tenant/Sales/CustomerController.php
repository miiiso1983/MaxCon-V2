<?php

namespace App\Http\Controllers\Tenant\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Imports\CustomersImport;
use App\Imports\CustomersCollectionImport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request): View
    {
        $query = Customer::forTenant(auth()->user()->tenant_id);

        // Apply filters
        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Customer::forTenant(auth()->user()->tenant_id)->count(),
            'active' => Customer::forTenant(auth()->user()->tenant_id)->active()->count(),
            'companies' => Customer::forTenant(auth()->user()->tenant_id)->where('customer_type', 'company')->count(),
            'individuals' => Customer::forTenant(auth()->user()->tenant_id)->where('customer_type', 'individual')->count(),
        ];

        return view('tenant.sales.customers.index', compact('customers', 'stats'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create(): View
    {
        return view('tenant.sales.customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:2',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'commercial_register' => 'nullable|string|max:50',
            'customer_type' => 'required|in:individual,company',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_60,credit_90',
            'credit_limit' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string',
        ]);

        try {
            $customer = new Customer();
            $customer->tenant_id = auth()->user()->tenant_id;
            $customer->customer_code = $customer->generateCustomerCode();
            $customer->fill($validated);
            $customer->is_active = true;
            $customer->save();

            return redirect()->route('tenant.sales.customers.index')
                ->with('success', 'تم إنشاء العميل بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء العميل: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer): View
    {
        // Check if customer belongs to current tenant
        if ($customer->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $customer->load(['salesOrders', 'invoices', 'payments']);

        return view('tenant.sales.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer): View
    {
        // Check if customer belongs to current tenant
        if ($customer->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return view('tenant.sales.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        // Check if customer belongs to current tenant
        if ($customer->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:2',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'commercial_register' => 'nullable|string|max:50',
            'customer_type' => 'required|in:individual,company',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_60,credit_90',
            'credit_limit' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            $customer->update($validated);

            return redirect()->route('tenant.sales.customers.show', $customer)
                ->with('success', 'تم تحديث بيانات العميل بنجاح');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث بيانات العميل: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        // Check if customer belongs to current tenant
        if ($customer->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Check if customer has any orders or invoices
        if ($customer->salesOrders()->count() > 0 || $customer->invoices()->count() > 0) {
            return redirect()->route('tenant.sales.customers.index')
                ->with('error', 'لا يمكن حذف العميل لأنه مرتبط بطلبات أو فواتير');
        }

        try {
            $customer->delete();

            return redirect()->route('tenant.sales.customers.index')
                ->with('success', 'تم حذف العميل بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('tenant.sales.customers.index')
                ->with('error', 'حدث خطأ أثناء حذف العميل: ' . $e->getMessage());
        }
    }

    /**
     * Show the import form
     */
    public function import(): View
    {
        return view('tenant.sales.customers.import');
    }

    /**
     * Process the Excel import
     */
    public function processImport(Request $request): RedirectResponse
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('excel_file');
            $tenantId = auth()->user()->tenant_id;

            // Use the Collection Import for better control
            $import = new CustomersCollectionImport($tenantId);
            Excel::import($import, $file);

            $imported = $import->getImportedCount();
            $errors = $import->getErrors();

            $message = "تم استيراد {$imported} عميل بنجاح";

            if (!empty($errors)) {
                $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " و " . (count($errors) - 3) . " أخطاء أخرى";
                }
            }

            // Log the import result
            \Illuminate\Support\Facades\Log::info('Customers import completed', [
                'tenant_id' => $tenantId,
                'imported_count' => $imported,
                'errors_count' => count($errors),
                'file_name' => $file->getClientOriginalName()
            ]);

            return redirect()->route('tenant.sales.customers.index')
                ->with($imported > 0 ? 'success' : 'warning', $message);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Customers import failed', [
                'tenant_id' => auth()->user()->tenant_id ?? null,
                'error' => $e->getMessage(),
                'file_name' => $request->file('excel_file')?->getClientOriginalName()
            ]);

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage());
        }
    }

    /**
     * Download sample Excel template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="customers_template.xlsx"',
        ];

        // Create sample data
        $sampleData = [
            [
                'name' => 'شركة الدواء الطبية',
                'email' => 'info@aldawa.com',
                'phone' => '011-234-5678',
                'mobile' => '0501234567',
                'address' => 'شارع الملك فهد، الرياض',
                'city' => 'الرياض',
                'state' => 'الرياض',
                'country' => 'SA',
                'postal_code' => '12345',
                'tax_number' => '123456789012345',
                'commercial_register' => '1010123456',
                'customer_type' => 'company',
                'payment_terms' => 'credit_30',
                'credit_limit' => '100000',
                'currency' => 'SAR',
                'notes' => 'عميل مميز'
            ],
            [
                'name' => 'صيدلية النهضة',
                'email' => 'contact@nahda.com',
                'phone' => '012-345-6789',
                'mobile' => '0509876543',
                'address' => 'شارع التحلية، جدة',
                'city' => 'جدة',
                'state' => 'مكة المكرمة',
                'country' => 'SA',
                'postal_code' => '21411',
                'tax_number' => '987654321098765',
                'commercial_register' => '4030987654',
                'customer_type' => 'company',
                'payment_terms' => 'credit_15',
                'credit_limit' => '50000',
                'currency' => 'SAR',
                'notes' => 'صيدلية معتمدة'
            ]
        ];

        return Excel::download(new class($sampleData) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;

            public function __construct($data) {
                $this->data = $data;
            }

            public function array(): array {
                return $this->data;
            }

            public function headings(): array {
                return [
                    'name', 'email', 'phone', 'mobile', 'address', 'city', 'state',
                    'country', 'postal_code', 'tax_number', 'commercial_register',
                    'customer_type', 'payment_terms', 'credit_limit', 'currency', 'notes'
                ];
            }
        }, 'customers_template.xlsx', \Maatwebsite\Excel\Excel::XLSX, $headers);
    }
}
