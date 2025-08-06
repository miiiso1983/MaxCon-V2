<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class CustomersImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $tenantId;
    protected $importedCount = 0;
    protected $skippedCount = 0;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['name']) || empty(trim($row['name']))) {
            $this->skippedCount++;
            return null;
        }

        // Check if customer already exists
        $existingCustomer = Customer::where('tenant_id', $this->tenantId)
            ->where(function($query) use ($row) {
                $query->where('name', trim($row['name']))
                      ->orWhere('email', trim($row['email'] ?? ''))
                      ->orWhere('tax_number', trim($row['tax_number'] ?? ''));
            })
            ->first();

        if ($existingCustomer) {
            $this->skippedCount++;
            return null;
        }

        $customer = new Customer();
        $customer->tenant_id = $this->tenantId;
        $customer->customer_code = $customer->generateCustomerCode();
        $customer->name = trim($row['name']);
        $customer->email = !empty($row['email']) ? trim($row['email']) : null;
        $customer->phone = !empty($row['phone']) ? trim($row['phone']) : null;
        $customer->mobile = !empty($row['mobile']) ? trim($row['mobile']) : null;
        $customer->address = !empty($row['address']) ? trim($row['address']) : null;
        $customer->city = !empty($row['city']) ? trim($row['city']) : null;
        $customer->state = !empty($row['state']) ? trim($row['state']) : null;
        $customer->country = !empty($row['country']) ? strtoupper(trim($row['country'])) : 'SA';
        $customer->postal_code = !empty($row['postal_code']) ? trim($row['postal_code']) : null;
        $customer->tax_number = !empty($row['tax_number']) ? trim($row['tax_number']) : null;
        $customer->commercial_register = !empty($row['commercial_register']) ? trim($row['commercial_register']) : null;
        $customer->customer_type = !empty($row['customer_type']) && in_array(strtolower($row['customer_type']), ['individual', 'company'])
            ? strtolower($row['customer_type']) : 'individual';
        $customer->payment_terms = !empty($row['payment_terms']) && in_array($row['payment_terms'], ['cash', 'credit_7', 'credit_15', 'credit_30', 'credit_60', 'credit_90'])
            ? $row['payment_terms'] : 'cash';
        $customer->credit_limit = !empty($row['credit_limit']) && is_numeric($row['credit_limit']) ? floatval($row['credit_limit']) : 0;
        $customer->currency = !empty($row['currency']) && in_array(strtoupper($row['currency']), ['SAR', 'AED', 'USD', 'EUR'])
            ? strtoupper($row['currency']) : 'SAR';
        $customer->notes = !empty($row['notes']) ? trim($row['notes']) : null;
        $customer->is_active = true;

        $this->importedCount++;
        return $customer;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'customer_type' => 'nullable|in:individual,company',
            'payment_terms' => 'nullable|in:cash,credit_7,credit_15,credit_30,credit_60,credit_90',
            'credit_limit' => 'nullable|numeric|min:0',
            'currency' => 'nullable|in:IQD,USD',
            'country' => 'nullable|string|max:2',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Get import statistics
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}
