<?php

namespace App\Http\Controllers\Tenant\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;

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
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $file = $request->file('excel_file');
            $data = $this->parseExcelFile($file);

            $imported = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                try {
                    // Skip header row
                    if ($index === 0) continue;

                    // Validate required fields
                    if (empty($row[0])) {
                        $errors[] = "الصف " . ($index + 1) . ": اسم المورد مطلوب";
                        continue;
                    }

                    // Generate code if not provided
                    $code = !empty($row[1]) ? $row[1] : 'SUP-' . str_pad(
                        Supplier::where('tenant_id', $tenantId)->count() + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                    // Check if supplier already exists
                    $existingSupplier = Supplier::where('tenant_id', $tenantId)
                        ->where(function($query) use ($row, $code) {
                            $query->where('name', $row[0])
                                  ->orWhere('code', $code);
                        })->first();

                    if ($existingSupplier) {
                        $errors[] = "الصف " . ($index + 1) . ": المورد موجود مسبقاً";
                        continue;
                    }

                    Supplier::create([
                        'tenant_id' => $tenantId,
                        'name' => $row[0],
                        'code' => $code,
                        'type' => !empty($row[2]) ? $row[2] : 'distributor',
                        'status' => !empty($row[3]) ? $row[3] : 'active',
                        'description' => $row[4] ?? null,
                        'email' => $row[5] ?? null,
                        'phone' => $row[6] ?? null,
                        'fax' => $row[7] ?? null,
                        'website' => $row[8] ?? null,
                        'address' => $row[9] ?? null,
                        'city' => $row[10] ?? null,
                        'state' => $row[11] ?? null,
                        'country' => $row[12] ?? 'العراق',
                        'contact_person' => $row[13] ?? null,
                        'contact_title' => $row[14] ?? null,
                        'contact_email' => $row[15] ?? null,
                        'contact_phone' => $row[16] ?? null,
                        'tax_number' => $row[17] ?? null,
                        'license_number' => $row[18] ?? null,
                        'payment_terms' => $row[19] ?? 'cash',
                        'currency' => $row[20] ?? 'IQD',
                        'products_services' => $row[21] ?? null,
                        'notes' => $row[22] ?? null,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "الصف " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            $message = "تم استيراد {$imported} مورد بنجاح";
            if (!empty($errors)) {
                $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " و " . (count($errors) - 5) . " أخطاء أخرى";
                }
            }

            return redirect()->route('tenant.purchasing.suppliers.index')
                ->with($imported > 0 ? 'success' : 'warning', $message);

        } catch (\Exception $e) {
            return redirect()->route('tenant.purchasing.suppliers.index')
                ->with('error', 'خطأ في استيراد الملف: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel template for suppliers
     */
    public function exportTemplate()
    {
        $headers = [
            'اسم المورد*',
            'رمز المورد',
            'نوع المورد',
            'الحالة',
            'الوصف',
            'البريد الإلكتروني',
            'الهاتف',
            'الفاكس',
            'الموقع الإلكتروني',
            'العنوان',
            'المدينة',
            'المحافظة',
            'البلد',
            'شخص الاتصال',
            'منصب شخص الاتصال',
            'بريد شخص الاتصال',
            'هاتف شخص الاتصال',
            'رقم السجل التجاري',
            'رقم الترخيص',
            'شروط الدفع',
            'العملة',
            'المنتجات/الخدمات',
            'ملاحظات'
        ];

        $sampleData = [
            'شركة الأدوية المتحدة',
            'SUP-001',
            'manufacturer',
            'active',
            'شركة متخصصة في تصنيع الأدوية',
            'info@pharma-united.com',
            '+964 770 123 4567',
            '+964 1 234 5678',
            'https://www.pharma-united.com',
            'شارع الكندي، منطقة الكرادة',
            'بغداد',
            'بغداد',
            'العراق',
            'أحمد محمد',
            'مدير المبيعات',
            'ahmed@pharma-united.com',
            '+964 770 987 6543',
            '12345678',
            'PH-2024-001',
            'cash',
            'IQD',
            'أدوية، مستلزمات طبية',
            'مورد موثوق'
        ];

        $csvContent = implode(',', $headers) . "\n";
        $csvContent .= implode(',', array_map(function($value) {
            return '"' . str_replace('"', '""', $value) . '"';
        }, $sampleData));

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="suppliers_template.csv"')
            ->header('Content-Transfer-Encoding', 'binary');
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
            // For Excel files, you would need PhpSpreadsheet
            // For now, we'll just handle CSV
            throw new \Exception('يرجى استخدام ملف CSV');
        }

        return $data;
    }
}
