<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class SuppliersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $tenantId;
    protected $importedCount = 0;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows - check multiple possible column names
        $name = $row['اسم المورد*'] ?? $row['اسم_المورد'] ?? $row['name'] ?? null;
        if (empty($name)) {
            return null;
        }

        $this->importedCount++;

        // Generate code if not provided
        $code = $row['رمز المورد'] ?? $row['رمز_المورد'] ?? $row['code'] ?? 'SUP-' . str_pad($this->importedCount, 3, '0', STR_PAD_LEFT);

        // Check if supplier already exists
        $existingSupplier = Supplier::where('tenant_id', $this->tenantId)
            ->where(function($query) use ($name, $code) {
                $query->where('name', $name)
                      ->orWhere('code', $code);
            })->first();

        if ($existingSupplier) {
            // Skip if already exists
            return null;
        }

        $supplier = new Supplier([
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
        ]);

        \Illuminate\Support\Facades\Log::info('SuppliersImport: Creating supplier', [
            'name' => $name,
            'code' => $code,
            'tenant_id' => $this->tenantId
        ]);

        return $supplier;
    }

    public function rules(): array
    {
        return [
            // Use either Arabic or English column names
            'اسم_المورد' => 'sometimes|required|string|max:255',
            'اسم المورد*' => 'sometimes|required|string|max:255',
            'name' => 'sometimes|required|string|max:255',
            'رمز_المورد' => 'nullable|string|max:50',
            'رمز المورد' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:50',
        ];
    }

    public function getImportedCount()
    {
        return $this->importedCount;
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
