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
            // Skip empty rows
            if (empty($row['كود_المنتج']) || empty($row['كود_المستودع']) || empty($row['الكمية'])) {
                return null;
            }

            // Find product by code
            $product = Product::where('tenant_id', $this->tenantId)
                ->where('code', $row['كود_المنتج'])
                ->first();

            if (!$product) {
                Log::warning("Product not found: " . $row['كود_المنتج']);
                $this->stats['errors']++;
                return null;
            }

            // Find warehouse by code
            $warehouse = Warehouse::where('tenant_id', $this->tenantId)
                ->where('code', $row['كود_المستودع'])
                ->first();

            if (!$warehouse) {
                Log::warning("Warehouse not found: " . $row['كود_المستودع']);
                $this->stats['errors']++;
                return null;
            }

            // Check if inventory item already exists
            $existingInventory = Inventory::where('tenant_id', $this->tenantId)
                ->where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->where('batch_number', $row['رقم_الدفعة'] ?? null)
                ->first();

            $quantity = floatval($row['الكمية']);
            $costPrice = !empty($row['سعر_التكلفة']) ? floatval($row['سعر_التكلفة']) : null;
            $locationCode = $row['رمز_الموقع'] ?? null;
            $batchNumber = $row['رقم_الدفعة'] ?? null;
            $status = $row['الحالة'] ?? 'active';
            
            // Parse expiry date
            $expiryDate = null;
            if (!empty($row['تاريخ_الانتهاء'])) {
                try {
                    $expiryDate = Carbon::parse($row['تاريخ_الانتهاء'])->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning("Invalid expiry date format: " . $row['تاريخ_الانتهاء']);
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
            
            return new Inventory([
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
            ]);

        } catch (\Exception $e) {
            Log::error("Error importing inventory row: " . $e->getMessage(), $row);
            $this->stats['errors']++;
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'كود_المنتج' => 'required|string',
            'كود_المستودع' => 'required|string',
            'الكمية' => 'required|numeric|min:0.001',
            'سعر_التكلفة' => 'nullable|numeric|min:0',
            'رمز_الموقع' => 'nullable|string|max:50',
            'رقم_الدفعة' => 'nullable|string|max:100',
            'تاريخ_الانتهاء' => 'nullable|date',
            'الحالة' => 'required|in:active,quarantine,damaged,expired',
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
