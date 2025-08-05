<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliersCollectionImport implements ToCollection, WithHeadingRow
{
    protected $tenantId;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Starting import', [
            'tenant_id' => $this->tenantId,
            'rows_count' => $collection->count()
        ]);

        foreach ($collection as $index => $row) {
            try {
                \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Processing row', [
                    'row_number' => $index + 2,
                    'row_data' => $row->toArray()
                ]);

                $this->processRow($row->toArray(), $index + 2); // +2 because of header row and 0-based index
            } catch (\Exception $e) {
                $this->errors[] = "الصف " . ($index + 2) . ": " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error('SuppliersCollectionImport: Error processing row', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => $row->toArray()
                ]);
            }
        }

        \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Import completed', [
            'tenant_id' => $this->tenantId,
            'imported_count' => $this->importedCount,
            'errors_count' => count($this->errors)
        ]);
    }

    private function processRow(array $row, int $rowNumber)
    {
        // Skip empty rows - check multiple possible column names
        $name = $row['اسم المورد*'] ?? $row['اسم_المورد'] ?? $row['name'] ?? null;
        if (empty($name)) {
            return;
        }

        // Generate code if not provided
        $code = $row['رمز المورد'] ?? $row['رمز_المورد'] ?? $row['code'] ?? 'SUP-' . str_pad($this->importedCount + 1, 3, '0', STR_PAD_LEFT);

        // Check if supplier already exists
        $existingSupplier = Supplier::where('tenant_id', $this->tenantId)
            ->where(function($query) use ($name, $code) {
                $query->where('name', $name)
                      ->orWhere('code', $code);
            })->first();

        if ($existingSupplier) {
            \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Supplier already exists, skipping', [
                'name' => $name,
                'code' => $code,
                'row' => $rowNumber
            ]);
            return;
        }

        $supplierData = [
            'tenant_id' => $this->tenantId,
            'name' => $name,
            'code' => $code,
            'type' => $this->mapType($row['نوع المورد'] ?? $row['نوع_المورد'] ?? $row['type'] ?? 'distributor'),
            'status' => $this->mapStatus($row['الحالة'] ?? $row['status'] ?? 'active'),
            'contact_person' => $row['شخص الاتصال'] ?? $row['شخص_الاتصال'] ?? $row['contact_person'] ?? null,
            'phone' => $row['الهاتف'] ?? $row['phone'] ?? null,
            'email' => $row['البريد الالكتروني'] ?? $row['البريد_الالكتروني'] ?? $row['email'] ?? null,
            'address' => $row['العنوان'] ?? $row['address'] ?? null,
            'tax_number' => $row['الرقم الضريبي'] ?? $row['الرقم_الضريبي'] ?? $row['tax_number'] ?? null,
            'payment_terms' => $this->mapPaymentTerms($row['شروط الدفع'] ?? $row['شروط_الدفع'] ?? $row['payment_terms'] ?? 'credit_30'),
            'credit_limit' => $row['حد الائتمان'] ?? $row['حد_الائتمان'] ?? $row['credit_limit'] ?? 0,
            'currency' => $row['العملة'] ?? $row['currency'] ?? 'IQD',
            'category' => $this->mapCategory($row['الفئة'] ?? $row['category'] ?? null),
            'notes' => $row['ملاحظات'] ?? $row['notes'] ?? null,
        ];

        // Create the supplier
        $supplier = Supplier::create($supplierData);
        $this->importedCount++;

        \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Supplier created successfully', [
            'supplier_id' => $supplier->id,
            'name' => $supplier->name,
            'code' => $supplier->code,
            'row' => $rowNumber,
            'imported_count' => $this->importedCount
        ]);
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function mapType($type)
    {
        $typeMap = [
            'مصنع' => 'manufacturer',
            'موزع' => 'distributor',
            'تاجر جملة' => 'wholesaler',
            'تاجر تجزئة' => 'retailer',
            'مقدم خدمة' => 'service_provider',
        ];

        return $typeMap[$type] ?? $type;
    }

    private function mapStatus($status)
    {
        $statusMap = [
            'نشط' => 'active',
            'غير نشط' => 'inactive',
            'معلق' => 'suspended',
            'محظور' => 'blacklisted',
        ];

        return $statusMap[$status] ?? $status;
    }

    private function mapPaymentTerms($terms)
    {
        $termsMap = [
            'نقداً' => 'cash',
            'آجل 7 أيام' => 'credit_7',
            'آجل 15 يوم' => 'credit_15',
            'آجل 30 يوم' => 'credit_30',
            'آجل 45 يوم' => 'credit_45',
            'آجل 60 يوم' => 'credit_60',
            'آجل 90 يوم' => 'credit_90',
            'مخصص' => 'custom',
        ];

        return $termsMap[$terms] ?? $terms;
    }

    private function mapCategory($category)
    {
        if (!$category) return null;

        $categoryMap = [
            'أدوية' => 'pharmaceutical',
            'معدات طبية' => 'medical_equipment',
            'مستحضرات تجميل' => 'cosmetics',
            'مكملات غذائية' => 'supplements',
            'أخرى' => 'other',
        ];

        return $categoryMap[$category] ?? $category;
    }
}
