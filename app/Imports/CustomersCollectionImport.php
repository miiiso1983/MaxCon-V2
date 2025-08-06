<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersCollectionImport implements ToCollection, WithHeadingRow
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
                \Illuminate\Support\Facades\Log::error('CustomersCollectionImport: Error processing row', [
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
        $name = $row['name'] ?? $row['اسم العميل'] ?? null;
        if (empty($name)) {
            return;
        }

        // Check if customer already exists
        $existingCustomer = Customer::where('tenant_id', $this->tenantId)
            ->where(function($query) use ($row, $name) {
                $query->where('name', trim($name))
                      ->orWhere('email', trim($row['email'] ?? ''))
                      ->orWhere('tax_number', trim($row['tax_number'] ?? ''));
            })
            ->first();

        if ($existingCustomer) {
            \Illuminate\Support\Facades\Log::info('CustomersCollectionImport: Customer already exists, skipping', [
                'name' => $name,
                'row' => $rowNumber
            ]);
            return;
        }

        $customerData = [
            'tenant_id' => $this->tenantId,
            'customer_code' => 'CUST-' . str_pad($this->importedCount + 1, 4, '0', STR_PAD_LEFT),
            'name' => trim($name),
            'email' => !empty($row['email']) ? trim($row['email']) : null,
            'phone' => !empty($row['phone']) ? trim($row['phone']) : null,
            'mobile' => !empty($row['mobile']) ? trim($row['mobile']) : null,
            'address' => !empty($row['address']) ? trim($row['address']) : null,
            'city' => !empty($row['city']) ? trim($row['city']) : null,
            'state' => !empty($row['state']) ? trim($row['state']) : null,
            'country' => !empty($row['country']) ? strtoupper(trim($row['country'])) : 'IQ',
            'postal_code' => !empty($row['postal_code']) ? trim($row['postal_code']) : null,
            'tax_number' => !empty($row['tax_number']) ? trim($row['tax_number']) : null,
            'customer_type' => !empty($row['customer_type']) ? trim($row['customer_type']) : 'individual',
            'payment_terms' => !empty($row['payment_terms']) ? trim($row['payment_terms']) : 'cash',
            'credit_limit' => !empty($row['credit_limit']) ? (float)$row['credit_limit'] : 0.00,
            'currency' => !empty($row['currency']) ? strtoupper(trim($row['currency'])) : 'IQD',
            'notes' => !empty($row['notes']) ? trim($row['notes']) : null,
            'is_active' => true,
            'status' => 'active'
        ];

        // Create the customer
        $customer = Customer::create($customerData);
        $this->importedCount++;

        \Illuminate\Support\Facades\Log::info('CustomersCollectionImport: Customer created successfully', [
            'customer_id' => $customer->id,
            'name' => $customer->name,
            'code' => $customer->customer_code,
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
