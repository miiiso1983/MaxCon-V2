<?php

namespace App\Http\Controllers\Tenant\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Exports\SuppliersExport;
use App\Exports\SuppliersTemplateExport;
use App\Imports\SuppliersImport;
use App\Imports\SuppliersCollectionImport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = Supplier::where('tenant_id', $tenantId);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('preferred')) {
            $query->where('is_preferred', $request->preferred === '1');
        }

        $suppliers = $query->orderBy('name')->paginate(15);

        // Statistics
        $stats = [
            'total' => Supplier::where('tenant_id', $tenantId)->count(),
            'active' => Supplier::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'preferred' => Supplier::where('tenant_id', $tenantId)->where('is_preferred', true)->count(),
            'total_value' => Supplier::where('tenant_id', $tenantId)->sum('total_amount'),
        ];

        return view('tenant.purchasing.suppliers.index', compact('suppliers', 'stats'));
    }

    /**
     * Show the form for creating a new supplier
     */
    public function create(): View
    {
        return view('tenant.purchasing.suppliers.create');
    }

    /**
     * Store a newly created supplier
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:suppliers,code',
            'type' => 'required|in:manufacturer,distributor,wholesaler,retailer,service_provider',
            'status' => 'required|in:active,inactive,suspended,blacklisted',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            'commercial_registration' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:50',
            'license_expiry' => 'nullable|date',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:50',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_45,credit_60,credit_90,custom',
            'credit_days' => 'nullable|integer|min:0',
            'credit_limit' => 'nullable|numeric|min:0',
            'categories' => 'nullable|array',
            'certifications' => 'nullable|array',
            'notes' => 'nullable|string',
            'is_preferred' => 'boolean',
        ]);

        $data = $request->all();
        $data['tenant_id'] = $tenantId;
        $data['is_preferred'] = $request->has('is_preferred');

        Supplier::create($data);

        return redirect()->route('tenant.purchasing.suppliers.index')
            ->with('success', 'تم إضافة المورد بنجاح');
    }

    /**
     * Display the specified supplier
     */
    public function show(Supplier $supplier): View
    {
        $user = auth()->user();

        if ($supplier->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        // Load relationships
        $supplier->load(['purchaseOrders', 'quotations', 'contracts']);

        // Recent orders
        $recentOrders = $supplier->purchaseOrders()
            ->with(['items'])
            ->orderBy('order_date', 'desc')
            ->limit(5)
            ->get();

        // Performance metrics
        $metrics = [
            'total_orders' => $supplier->purchaseOrders()->count(),
            'completed_orders' => $supplier->purchaseOrders()->where('status', 'completed')->count(),
            'total_value' => $supplier->purchaseOrders()->sum('total_amount'),
            'average_order_value' => $supplier->purchaseOrders()->avg('total_amount'),
            'on_time_delivery' => $this->calculateOnTimeDelivery($supplier),
            'quality_rating' => $supplier->quality_score,
        ];

        return view('tenant.purchasing.suppliers.show', compact('supplier', 'recentOrders', 'metrics'));
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function edit(Supplier $supplier): View
    {
        $user = auth()->user();

        if ($supplier->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        return view('tenant.purchasing.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $user = auth()->user();

        if ($supplier->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:suppliers,code,' . $supplier->id,
            'type' => 'required|in:manufacturer,distributor,wholesaler,retailer,service_provider',
            'status' => 'required|in:active,inactive,suspended,blacklisted',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_45,credit_60,credit_90,custom',
            'credit_limit' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'category' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only([
            'name', 'code', 'type', 'status', 'contact_person',
            'phone', 'email', 'address', 'tax_number', 'payment_terms',
            'credit_limit', 'notes'
        ]);

        // Add currency and category only if columns exist
        if (Schema::hasColumn('suppliers', 'currency')) {
            $data['currency'] = $request->input('currency', 'IQD');
        }

        if (Schema::hasColumn('suppliers', 'category')) {
            $data['category'] = $request->input('category');
        }

        $supplier->update($data);

        return redirect()->route('tenant.purchasing.suppliers.show', $supplier)
            ->with('success', 'تم تحديث بيانات المورد بنجاح');
    }

    /**
     * Remove the specified supplier
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $user = auth()->user();

        if ($supplier->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        // Check if supplier has orders
        if ($supplier->purchaseOrders()->count() > 0) {
            return redirect()->route('tenant.purchasing.suppliers.index')
                ->with('error', 'لا يمكن حذف المورد لوجود أوامر شراء مرتبطة به');
        }

        $supplier->delete();

        return redirect()->route('tenant.purchasing.suppliers.index')
            ->with('success', 'تم حذف المورد بنجاح');
    }

    /**
     * Calculate on-time delivery percentage
     */
    private function calculateOnTimeDelivery(Supplier $supplier): float
    {
        $completedOrders = $supplier->purchaseOrders()
            ->where('status', 'completed')
            ->whereNotNull('actual_delivery_date')
            ->get();

        if ($completedOrders->count() === 0) {
            return 0;
        }

        $onTimeOrders = $completedOrders->filter(function ($order) {
            return $order->actual_delivery_date <= $order->expected_delivery_date;
        });

        return ($onTimeOrders->count() / $completedOrders->count()) * 100;
    }

    /**
     * Import suppliers from Excel file
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $file = $request->file('excel_file');
            $tenantId = auth()->user()->tenant_id;

            // Use the Collection Import for better control
            $import = new SuppliersCollectionImport($tenantId);
            Excel::import($import, $file);

            $imported = $import->getImportedCount();
            $errors = $import->getErrors();

            $message = "تم استيراد {$imported} مورد بنجاح";

            if (!empty($errors)) {
                $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " و " . (count($errors) - 3) . " أخطاء أخرى";
                }
            }

            // Log the import result
            \Illuminate\Support\Facades\Log::info('Suppliers import completed', [
                'tenant_id' => $tenantId,
                'imported_count' => $imported,
                'errors_count' => count($errors),
                'file_name' => $file->getClientOriginalName()
            ]);

            return redirect()->route('tenant.purchasing.suppliers.index')
                ->with($imported > 0 ? 'success' : 'warning', $message);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Suppliers import failed', [
                'tenant_id' => auth()->user()->tenant_id ?? null,
                'error' => $e->getMessage(),
                'file_name' => $request->file('excel_file')?->getClientOriginalName()
            ]);

            return redirect()->route('tenant.purchasing.suppliers.index')
                ->with('error', 'خطأ في استيراد الملف: ' . $e->getMessage());
        }
    }

    /**
     * Export suppliers to Excel
     */
    public function export(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        // Get filters from request
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'type' => $request->get('type'),
        ];

        $filename = 'suppliers_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new SuppliersExport($tenantId, $filters), $filename);
    }

    /**
     * Export Excel template for suppliers
     */
    public function exportTemplate()
    {
        $filename = 'suppliers_template_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new SuppliersTemplateExport(), $filename);
    }

    /**
     * Parse Excel file and return data array
     */
    private function parseExcelFile($file): array
    {
        $extension = $file->getClientOriginalExtension();
        $data = [];

        if ($extension === 'csv') {
            $handle = fopen($file->getPathname(), 'r');
            while (($row = fgetcsv($handle)) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        } else {
            // Use Laravel Excel to read Excel files
            try {
                $collection = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
                if (!empty($collection) && !empty($collection[0])) {
                    $data = $collection[0]; // Get first sheet
                }
            } catch (\Exception $e) {
                throw new \Exception('خطأ في قراءة ملف Excel: ' . $e->getMessage());
            }
        }

        return $data;
    }
}
