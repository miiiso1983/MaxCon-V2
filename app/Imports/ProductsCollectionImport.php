<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class ProductsCollectionImport implements ToCollection, WithHeadingRow
{
    protected $tenantId;
    protected $importedCount = 0;
    protected $skippedCount = 0;
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
                \Illuminate\Support\Facades\Log::error('ProductsCollectionImport: Error processing row', [
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
        $name = $row['name'] ?? $row['اسم المنتج'] ?? null;
        if (empty($name)) {
            $this->skippedCount++;
            return;
        }

        // Check if product already exists (by name and barcode)
        $productName = trim($name);
        $productBarcode = !empty($row['barcode']) ? trim((string)$row['barcode']) : null;

        $existingProduct = Product::where('tenant_id', $this->tenantId)
            ->where(function($query) use ($productName, $productBarcode) {
                $query->where('name', $productName);
                if ($productBarcode) {
                    $query->orWhere('barcode', $productBarcode);
                }
            })
            ->first();

        if ($existingProduct) {
            $this->skippedCount++;
            \Illuminate\Support\Facades\Log::info('ProductsCollectionImport: Product already exists, skipping', [
                'name' => $productName,
                'barcode' => $productBarcode,
                'row' => $rowNumber
            ]);
            return;
        }

        $productData = [
            'tenant_id' => $this->tenantId,
            'product_code' => 'PRD' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'name' => trim($name),
            'description' => !empty($row['description']) ? trim($row['description']) : null,
            'category' => !empty($row['category']) ? trim($row['category']) : 'أخرى',
            'manufacturer' => !empty($row['manufacturer']) ? trim($row['manufacturer']) : null,
            'barcode' => $productBarcode,
            'unit_of_measure' => !empty($row['unit']) ? trim($row['unit']) : 'قرص',
            'cost_price' => !empty($row['purchase_price']) ? (float)$row['purchase_price'] : 0.00,
            'selling_price' => !empty($row['selling_price']) ? (float)$row['selling_price'] : 0.00,
            'min_stock_level' => !empty($row['min_stock_level']) ? (int)$row['min_stock_level'] : 10,
            'stock_quantity' => !empty($row['current_stock']) ? (float)$row['current_stock'] : 0.00,
            'batch_number' => !empty($row['batch_number']) ? trim($row['batch_number']) : null,
            'notes' => !empty($row['notes']) ? trim($row['notes']) : null,
            'is_active' => true,
            'is_taxable' => 1,
            'tax_rate' => 15.00,
            'track_expiry' => 1,
            'track_batch' => 1,
            'status' => 'active',
            'currency' => 'IQD',
            'base_unit' => 'piece',
        ];

        // Handle dates
        if (!empty($row['expiry_date'])) {
            try {
                $productData['expiry_date'] = Carbon::parse($row['expiry_date']);
            } catch (\Exception) {
                $productData['expiry_date'] = null;
            }
        }

        // Create the product
        $product = Product::create($productData);
        $this->importedCount++;

        \Illuminate\Support\Facades\Log::info('ProductsCollectionImport: Product created successfully', [
            'product_id' => $product->id,
            'name' => $product->name,
            'code' => $product->product_code,
            'row' => $rowNumber,
            'imported_count' => $this->importedCount
        ]);
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
