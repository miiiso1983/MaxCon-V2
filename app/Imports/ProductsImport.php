<?php

namespace App\Imports;

use App\Models\Product;
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
use Carbon\Carbon;

class ProductsImport implements
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

        // Check if product already exists
        $existingProduct = Product::where('tenant_id', $this->tenantId)
            ->where(function($query) use ($row) {
                $query->where('name', trim($row['name']))
                      ->orWhere('barcode', trim($row['barcode'] ?? ''));
            })
            ->first();

        if ($existingProduct) {
            $this->skippedCount++;
            return null;
        }

        $product = new Product();
        $product->tenant_id = $this->tenantId;
        $product->product_code = $product->generateProductCode();
        $product->name = trim($row['name']);
        $product->generic_name = !empty($row['generic_name']) ? trim($row['generic_name']) : null;
        $product->category = !empty($row['category']) ? trim($row['category']) : 'أخرى';
        $product->manufacturer = !empty($row['manufacturer']) ? trim($row['manufacturer']) : null;
        $product->barcode = !empty($row['barcode']) ? trim($row['barcode']) : null;
        $product->unit = !empty($row['unit']) ? trim($row['unit']) : 'قرص';
        $product->purchase_price = !empty($row['purchase_price']) && is_numeric($row['purchase_price']) ? floatval($row['purchase_price']) : 0;
        $product->selling_price = !empty($row['selling_price']) && is_numeric($row['selling_price']) ? floatval($row['selling_price']) : 0;
        $product->min_stock_level = !empty($row['min_stock_level']) && is_numeric($row['min_stock_level']) ? intval($row['min_stock_level']) : 10;
        $product->current_stock = !empty($row['current_stock']) && is_numeric($row['current_stock']) ? intval($row['current_stock']) : 0;
        $product->batch_number = !empty($row['batch_number']) ? trim($row['batch_number']) : null;

        // Handle dates
        if (!empty($row['expiry_date'])) {
            try {
                $product->expiry_date = Carbon::parse($row['expiry_date']);
            } catch (\Exception $e) {
                $product->expiry_date = null;
            }
        }

        if (!empty($row['manufacturing_date'])) {
            try {
                $product->manufacturing_date = Carbon::parse($row['manufacturing_date']);
            } catch (\Exception $e) {
                $product->manufacturing_date = null;
            }
        }

        $product->storage_conditions = !empty($row['storage_conditions']) ? trim($row['storage_conditions']) : null;
        $product->description = !empty($row['description']) ? trim($row['description']) : null;
        $product->notes = !empty($row['notes']) ? trim($row['notes']) : null;
        $product->is_active = true;

        $this->importedCount++;
        return $product;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'current_stock' => 'nullable|integer|min:0',
            'batch_number' => 'nullable|string|max:50',
            'expiry_date' => 'nullable|date',
            'manufacturing_date' => 'nullable|date',
            'storage_conditions' => 'nullable|string',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
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
