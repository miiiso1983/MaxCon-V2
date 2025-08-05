<?php

namespace App\Imports;

use App\Models\Tenant\Regulatory\CompanyRegistration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CompaniesCollectionImport implements ToCollection, WithHeadingRow
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
        foreach ($collection as $index => $row) {
            try {
                $this->processRow($row->toArray(), $index + 2); // +2 because of header row and 0-based index
            } catch (\Exception $e) {
                $this->errors[] = "الصف " . ($index + 2) . ": " . $e->getMessage();
                \Illuminate\Support\Facades\Log::error('CompaniesCollectionImport: Error processing row', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => $row->toArray()
                ]);
            }
        }
    }

    private function processRow(array $row, int $rowNumber)
    {
        // Skip empty rows
        $companyName = $row['company_name'] ?? $row['اسم الشركة'] ?? null;
        $registrationNumber = $row['registration_number'] ?? $row['رقم التسجيل'] ?? null;
        $licenseNumber = $row['license_number'] ?? $row['رقم الترخيص'] ?? null;

        if (empty($companyName) || empty($registrationNumber) || empty($licenseNumber)) {
            return;
        }

        // Check if company already exists
        $existingCompany = CompanyRegistration::where('tenant_id', $this->tenantId)
            ->where(function($query) use ($registrationNumber, $licenseNumber) {
                $query->where('registration_number', $registrationNumber)
                      ->orWhere('license_number', $licenseNumber);
            })
            ->first();

        if ($existingCompany) {
            \Illuminate\Support\Facades\Log::info('CompaniesCollectionImport: Company already exists, skipping', [
                'company_name' => $companyName,
                'registration_number' => $registrationNumber,
                'row' => $rowNumber
            ]);
            return;
        }

        $companyData = [
            'tenant_id' => $this->tenantId,
            'company_name' => trim($companyName),
            'company_type' => $row['company_type'] ?? $row['نوع الشركة'] ?? 'pharmaceutical',
            'registration_number' => trim($registrationNumber),
            'license_number' => trim($licenseNumber),
            'registration_date' => !empty($row['registration_date']) ? $row['registration_date'] : now(),
            'license_expiry_date' => !empty($row['license_expiry_date']) ? $row['license_expiry_date'] : null,
            'address' => $row['address'] ?? $row['العنوان'] ?? null,
            'phone' => $row['phone'] ?? $row['الهاتف'] ?? null,
            'email' => $row['email'] ?? $row['البريد الالكتروني'] ?? null,
            'website' => $row['website'] ?? $row['الموقع الالكتروني'] ?? null,
            'contact_person' => $row['contact_person'] ?? $row['شخص الاتصال'] ?? null,
            'status' => $row['status'] ?? $row['الحالة'] ?? 'active',
            'notes' => $row['notes'] ?? $row['ملاحظات'] ?? null,
        ];

        // Create the company
        $company = CompanyRegistration::create($companyData);
        $this->importedCount++;

        \Illuminate\Support\Facades\Log::info('CompaniesCollectionImport: Company created successfully', [
            'company_id' => $company->id,
            'company_name' => $company->company_name,
            'registration_number' => $company->registration_number,
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
}
