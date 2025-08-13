<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $tenantId;
    protected $skipDuplicates;
    protected $updateExisting;
    protected $stats = [
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => 0
    ];

    public function __construct($tenantId, $skipDuplicates = true, $updateExisting = false)
    {
        $this->tenantId = $tenantId;
        $this->skipDuplicates = $skipDuplicates;
        $this->updateExisting = $updateExisting;
    }

    public function model(array $row)
    {
        try {
            // Log the incoming row for debugging
            Log::info('Processing row: ', $row);
            Log::info('Row keys: ', array_keys($row));

            // Handle both Arabic and English headers (with and without underscores)
            $productCode = $row['كود المنتج'] ?? $row['كود_المنتج'] ?? $row['product_code'] ?? $row[0] ?? null;
            $warehouseCode = $row['كود المستودع'] ?? $row['كود_المستودع'] ?? $row['warehouse_code'] ?? $row[1] ?? null;
            $quantity = $row['الكمية'] ?? $row['quantity'] ?? $row[2] ?? null;

            Log::info('Extracted values: ', [
                'product_code' => $productCode,
                'warehouse_code' => $warehouseCode,
                'quantity' => $quantity
            ]);

            // Skip empty rows
            if (empty($productCode) || empty($warehouseCode) || empty($quantity)) {
                Log::info('Skipping empty row');
                return null;
            }

            // Find product by code (support both 'code' and 'product_code')
            $product = Product::where('tenant_id', $this->tenantId)
                ->where(function($q) use ($productCode) {
                    $q->where('code', $productCode)
                      ->orWhere('product_code', $productCode);
                })
                ->first();

            if (!$product) {
                // محاولة إنشاء منتج جديد إذا لم يكن موجود
                Log::info("Product not found, attempting to create: " . $productCode);

                $productName = $row['اسم المنتج'] ?? $row['اسم_المنتج'] ?? $row['name'] ?? $productCode;
                $costPrice = $row['سعر التكلفة'] ?? $row['سعر_التكلفة'] ?? $row['cost_price'] ?? 0;
                $sellingPrice = $row['سعر البيع'] ?? $row['سعر_البيع'] ?? $row['selling_price'] ?? 0;

                try {
                    $product = Product::create([
                        'tenant_id' => $this->tenantId,
                        'name' => $productName,
                        'product_code' => $productCode,
                        'code' => null,
                        'cost_price' => $costPrice,
                        'selling_price' => $sellingPrice,
                        'stock_quantity' => 0,
                        'min_stock_level' => 10,
                        'unit_of_measure' => 'قرص',
                        'category' => 'أخرى',
                        'is_active' => true,
                        'status' => 'active',
                        'currency' => 'IQD',
                        'base_unit' => 'piece',
                        'is_taxable' => true,
                        'tax_rate' => 15.00,
                        'track_expiry' => true,
                        'track_batch' => true,
                        'created_by' => optional(auth()->user())->id,
                    ]);

                    Log::info("Created new product: " . $product->id . " - " . $product->name);
                } catch (\Exception $e) {
                    Log::error("Failed to create product: " . $e->getMessage());
                    $this->stats['errors']++;
                    return null;
                }
            }

            // Find warehouse by code
            $warehouse = Warehouse::where('tenant_id', $this->tenantId)
                ->where('code', $warehouseCode)
                ->first();

            if (!$warehouse) {
                Log::warning("Warehouse not found: " . $warehouseCode);
                $this->stats['errors']++;
                return null;
            }

            $quantity = floatval($quantity);
            $costPrice = !empty($row['سعر التكلفة'] ?? $row['سعر_التكلفة'] ?? $row[4]) ? floatval($row['سعر التكلفة'] ?? $row['سعر_التكلفة'] ?? $row[4]) : null;
            $locationCode = $row['رمز الموقع'] ?? $row['رمز_الموقع'] ?? $row[6] ?? null;
            $batchNumber = $row['رقم الدفعة'] ?? $row['رقم_الدفعة'] ?? $row[7] ?? null;
            $status = $row['الحالة'] ?? $row[9] ?? 'active';

            // Check if inventory item already exists
            $existingInventory = Inventory::where('tenant_id', $this->tenantId)
                ->where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->when($batchNumber, function($query) use ($batchNumber) {
                    return $query->where('batch_number', $batchNumber);
                })
                ->when(!$batchNumber, function($query) {
                    return $query->whereNull('batch_number');
                })
                ->first();
            
            // Parse expiry date
            $expiryDate = null;
            $expiryDateValue = $row['تاريخ الانتهاء'] ?? $row['تاريخ_الانتهاء'] ?? $row[8] ?? null;
            if (!empty($expiryDateValue)) {
                try {
                    $expiryDate = Carbon::parse($expiryDateValue)->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning("Invalid expiry date format: " . $expiryDateValue);
                }
            }

            if ($existingInventory) {
                if ($this->skipDuplicates && !$this->updateExisting) {
                    $this->stats['skipped']++;
                    return null;
                }

                if ($this->updateExisting) {
                    // Update existing inventory
                    $existingInventory->quantity += $quantity;
                    $existingInventory->available_quantity += $quantity;
                    
                    if ($costPrice !== null) {
                        $existingInventory->cost_price = $costPrice;
                    }
                    if ($locationCode !== null) {
                        $existingInventory->location_code = $locationCode;
                    }
                    if ($expiryDate !== null) {
                        $existingInventory->expiry_date = $expiryDate;
                    }
                    
                    $existingInventory->status = $status;
                    $existingInventory->save();
                    
                    $this->stats['updated']++;
                    return null;
                } else {
                    $this->stats['skipped']++;
                    return null;
                }
            }

            // Create new inventory item
            $this->stats['created']++;

            $inventoryData = [
                'tenant_id' => $this->tenantId,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity' => $quantity,
                'available_quantity' => $quantity,
                'reserved_quantity' => 0,
                'cost_price' => $costPrice,
                'location_code' => $locationCode,
                'batch_number' => $batchNumber,
                'expiry_date' => $expiryDate,
                'status' => $status,
            ];

            Log::info('Creating inventory item: ', $inventoryData);

            return new Inventory($inventoryData);

        } catch (\Exception $e) {
            Log::error("Error importing inventory row: " . $e->getMessage(), $row);
            $this->stats['errors']++;
            return null;
        }
    }

    public function rules(): array
    {
        return [
            // Support both Arabic headers with and without underscores
            'كود المنتج' => 'nullable|string',
            'كود_المنتج' => 'nullable|string',
            'كود المستودع' => 'nullable|string',
            'كود_المستودع' => 'nullable|string',
            'الكمية' => 'nullable|numeric|min:0.001',
            'سعر التكلفة' => 'nullable|numeric|min:0',
            'سعر_التكلفة' => 'nullable|numeric|min:0',
            'رمز الموقع' => 'nullable|string|max:50',
            'رمز_الموقع' => 'nullable|string|max:50',
            'رقم الدفعة' => 'nullable|string|max:100',
            'رقم_الدفعة' => 'nullable|string|max:100',
            'تاريخ الانتهاء' => 'nullable|date',
            'تاريخ_الانتهاء' => 'nullable|date',
            'الحالة' => 'nullable|in:active,quarantine,damaged,expired',
            'ملاحظات' => 'nullable|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'كود_المنتج.required' => 'كود المنتج مطلوب',
            'كود_المستودع.required' => 'كود المستودع مطلوب',
            'الكمية.required' => 'الكمية مطلوبة',
            'الكمية.numeric' => 'الكمية يجب أن تكون رقم',
            'الكمية.min' => 'الكمية يجب أن تكون أكبر من صفر',
            'سعر_التكلفة.numeric' => 'سعر التكلفة يجب أن يكون رقم',
            'سعر_التكلفة.min' => 'سعر التكلفة يجب أن يكون أكبر من أو يساوي صفر',
            'رمز_الموقع.max' => 'رمز الموقع لا يجب أن يتجاوز 50 حرف',
            'رقم_الدفعة.max' => 'رقم الدفعة لا يجب أن يتجاوز 100 حرف',
            'تاريخ_الانتهاء.date' => 'تاريخ الانتهاء يجب أن يكون تاريخ صحيح',
            'الحالة.required' => 'الحالة مطلوبة',
            'الحالة.in' => 'الحالة يجب أن تكون: active, quarantine, damaged, expired',
        ];
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
