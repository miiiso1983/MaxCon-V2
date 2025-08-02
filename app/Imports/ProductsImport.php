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
        // Log the row data for debugging
        \Log::info('ProductsImport: Processing row', [
            'tenant_id' => $this->tenantId,
            'row_data' => $row
        ]);

        // Skip empty rows
        if (empty($row['name']) || empty(trim($row['name']))) {
            \Log::info('ProductsImport: Skipping empty row');
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
            \Log::info('ProductsImport: Product already exists', [
                'name' => trim($row['name']),
                'existing_id' => $existingProduct->id
            ]);
            $this->skippedCount++;
            return null;
        }

        $product = new Product();
        $product->tenant_id = $this->tenantId;

        // Generate product code using the model method
        $product->product_code = 'PRD' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

        $product->name = trim($row['name']);
        $product->description = !empty($row['description']) ? trim($row['description']) : null;
        $product->category = !empty($row['category']) ? trim($row['category']) : 'أخرى';
        $product->manufacturer = !empty($row['manufacturer']) ? trim($row['manufacturer']) : null;
        $product->barcode = !empty($row['barcode']) ? trim($row['barcode']) : null;

        // Use unit_of_measure instead of base_unit
        $product->unit_of_measure = !empty($row['unit']) ? trim($row['unit']) : 'قرص';

        // Use the correct field names from database
        $product->cost_price = !empty($row['purchase_price']) && is_numeric($row['purchase_price']) ? floatval($row['purchase_price']) : 0.00;
        $product->selling_price = !empty($row['selling_price']) && is_numeric($row['selling_price']) ? floatval($row['selling_price']) : 0.00;

        // Use min_stock_level and stock_quantity (current stock)
        $product->min_stock_level = !empty($row['min_stock_level']) && is_numeric($row['min_stock_level']) ? intval($row['min_stock_level']) : 10;
        $product->stock_quantity = !empty($row['current_stock']) && is_numeric($row['current_stock']) ? intval($row['current_stock']) : 0;

        // Handle dates
        if (!empty($row['expiry_date'])) {
            try {
                $product->expiry_date = Carbon::parse($row['expiry_date']);
            } catch (\Exception $e) {
                $product->expiry_date = null;
            }
        }

        // Note: manufacturing_date field doesn't exist in current table structure

        // Additional fields
        $product->notes = !empty($row['notes']) ? trim($row['notes']) : null;
        $product->is_active = true;

        // Set default values for required fields
        $product->is_taxable = true;
        $product->tax_rate = 15.00;
        $product->track_expiry = true;
        $product->track_batch = true;

        \Log::info('ProductsImport: Creating new product', [
            'name' => $product->name,
            'tenant_id' => $product->tenant_id,
            'category' => $product->category
        ]);

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
