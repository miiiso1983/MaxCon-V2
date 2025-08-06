<?php

namespace App\Imports;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
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
                // Handle both array and Collection objects
                $rowData = is_array($row) ? $row : $row->toArray();

                \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Processing row', [
                    'row_number' => $index + 2,
                    'row_data' => $rowData,
                    'row_type' => gettype($row)
                ]);

                $this->processRow($rowData, $index + 2); // +2 because of header row and 0-based index
            } catch (\Exception $e) {
                $this->errors[] = "الصف " . ($index + 2) . ": " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error('SuppliersCollectionImport: Error processing row', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => is_array($row) ? $row : (method_exists($row, 'toArray') ? $row->toArray() : 'Cannot convert to array'),
                    'row_type' => gettype($row)
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
        \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Processing row start', [
            'row_number' => $rowNumber,
            'row_data' => $row,
            'tenant_id' => $this->tenantId
        ]);

        // Extract name from multiple possible sources - support new file format
        $name = $row['اسم المورد*'] ?? $row['اسم_المورد'] ?? $row['name'] ??
                $row['اسم الشركة'] ?? $row['شخص الاتصال'] ??
                $row['suppliers_template1'] ?? // New format support
                $row['13'] ?? null; // Contact person as fallback

        \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Extracted name', [
            'name' => $name,
            'row' => $rowNumber,
            'available_columns' => array_keys($row)
        ]);

        if (empty($name)) {
            \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Skipping empty row - no name found', [
                'row' => $rowNumber,
                'row_data' => $row
            ]);
            return;
        }

        // Generate code if not provided - support new format
        $code = $row['رمز المورد'] ?? $row['رمز_المورد'] ?? $row['code'] ??
                $row['رقم السجل التجاري'] ?? $row['1'] ?? // New format: column 1 is supplier code
                'SUP-' . str_pad($this->importedCount + 1, 3, '0', STR_PAD_LEFT);

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

        // Build supplier data with only safe fields - support new format
        $supplierData = [
            'tenant_id' => $this->tenantId,
            'name' => $name,
            'code' => $code,
            'type' => $this->mapType($row['نوع المورد'] ?? $row['نوع_المورد'] ?? $row['type'] ?? $row['2'] ?? 'distributor'),
            'status' => $this->mapStatus($row['الحالة'] ?? $row['status'] ?? $row['3'] ?? 'active'),
            'payment_terms' => $this->mapPaymentTerms($row['شروط الدفع'] ?? $row['شروط_الدفع'] ?? $row['payment_terms'] ?? $row['19'] ?? 'credit_30'),
            'credit_limit' => floatval($row['حد الائتمان'] ?? $row['حد_الائتمان'] ?? $row['credit_limit'] ?? 0),
        ];

        // Add optional fields only if they have values - support new file format
        $optionalFields = [
            'contact_person' => $row['شخص الاتصال'] ?? $row['شخص_الاتصال'] ?? $row['contact_person'] ?? $row['13'] ?? null,
            'phone' => $row['الهاتف'] ?? $row['هاتف شخص الاتصال'] ?? $row['phone'] ?? $row['6'] ?? null,
            'email' => $row['البريد الالكتروني'] ?? $row['بريد شخص الاتصال'] ?? $row['البريد_الالكتروني'] ?? $row['email'] ?? $row['5'] ?? $row['15'] ?? null,
            'address' => $row['العنوان'] ?? $row['address'] ?? $row['9'] ?? null,
            'city' => $row['المدينة'] ?? $row['city'] ?? $row['10'] ?? null,
            'country' => $row['البلد'] ?? $row['country'] ?? $row['12'] ?? 'العراق',
            'tax_number' => $row['الرقم الضريبي'] ?? $row['الرقم_الضريبي'] ?? $row['tax_number'] ?? null,
            'commercial_registration' => $row['رقم السجل التجاري'] ?? $row['17'] ?? null,
            'license_number' => $row['رقم الترخيص'] ?? $row['18'] ?? null,
            'website' => $row['الموقع الإلكتروني'] ?? $row['website'] ?? $row['8'] ?? null,
            'notes' => $row['ملاحظات'] ?? $row['المنتجات/الخدمات'] ?? $row['notes'] ?? $row['22'] ?? $row['21'] ?? null,
            'description' => $row['الوصف'] ?? $row['description'] ?? $row['4'] ?? null,
        ];

        foreach ($optionalFields as $field => $value) {
            if (!empty($value)) {
                $supplierData[$field] = $value;
            }
        }

        // Add currency and category only if columns exist and have values - support new format
        if (Schema::hasColumn('suppliers', 'currency')) {
            $currency = $row['العملة'] ?? $row['currency'] ?? $row['20'] ?? 'IQD';
            // Validate currency - only allow IQD and USD
            if (!empty($currency) && in_array($currency, ['IQD', 'USD'])) {
                $supplierData['currency'] = $currency;
            } else {
                $supplierData['currency'] = 'IQD'; // Default to IQD
            }
        }

        if (Schema::hasColumn('suppliers', 'category')) {
            $category = $this->mapCategory($row['الفئة'] ?? $row['category'] ?? null);
            if (!empty($category)) {
                $supplierData['category'] = $category;
            }
        }

        // Create the supplier with error handling
        \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: About to create supplier', [
            'supplier_data' => $supplierData,
            'row' => $rowNumber
        ]);

        try {
            $supplier = Supplier::create($supplierData);
            $this->importedCount++;

            \Illuminate\Support\Facades\Log::info('SuppliersCollectionImport: Supplier creation completed', [
                'supplier_id' => $supplier->id,
                'imported_count' => $this->importedCount,
                'row' => $rowNumber
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = "خطأ في قاعدة البيانات: " . $e->getMessage();
            $this->errors[] = "الصف {$rowNumber}: {$errorMessage}";

            \Illuminate\Support\Facades\Log::error('SuppliersCollectionImport: Database error', [
                'error' => $e->getMessage(),
                'supplier_data' => $supplierData,
                'row' => $rowNumber
            ]);

            throw $e; // Re-throw to be caught by the outer try-catch
        }

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
