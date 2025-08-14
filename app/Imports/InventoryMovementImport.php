<?php

namespace App\Imports;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Carbon\Carbon;

class InventoryMovementImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected int $tenantId;
    protected array $stats = [
        'created' => 0,
        'skipped' => 0,
        'errors' => 0,
    ];

    public function __construct(int $tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function model(array $row)
    {
        try {
            Log::info('Movement Import - Processing row: ', $row);

            // Map headers (Arabic, underscored, transliterated)
            // Also support odd headers observed in logs (inventory_movements_template, noaa_alhrk)
            $productCode = $row['كود المنتج']
                ?? $row['كود_المنتج']
                ?? $row['product_code']
                ?? $row['inventory_movements_template']
                ?? $row['inventory_movements_template2']
                ?? $row['kod_almntg']
                ?? ($row['0'] ?? null)
                ?? ($row[0] ?? null)
                ?? null;
            $productName = $row['اسم المنتج'] ?? $row['اسم_المنتج'] ?? $row['name'] ?? $row['asm_almntg'] ?? null;
            $warehouseCode = $row['كود المستودع'] ?? $row['كود_المستودع'] ?? $row['warehouse_code'] ?? $row['kod_almstodaa'] ?? ($row['1'] ?? null) ?? ($row[1] ?? null) ?? null;
            $movementType = $row['نوع الحركة']
                ?? $row['نوع_الحركة']
                ?? $row['movement_type']
                ?? $row['noaa_alhrka']
                ?? $row['noaa_alhrk']
                ?? ($row['2'] ?? null)
                ?? ($row[2] ?? null)
                ?? null;
            $quantity = $row['الكمية'] ?? $row['quantity'] ?? $row['alkmy'] ?? $row[3] ?? null;
            $reason = $row['السبب'] ?? $row['reason'] ?? $row['alsbb'] ?? $row[4] ?? null;
            $movementDate = $row['التاريخ'] ?? $row['date'] ?? $row['altarykh'] ?? $row[5] ?? null;
            $notes = $row['ملاحظات'] ?? $row['notes'] ?? $row['mlahthat'] ?? $row[6] ?? null;

            if (!$productCode || !$warehouseCode || !$movementType || empty($quantity)) {
                Log::warning('Movement Import - Skipping row (missing required fields)', compact('productCode','warehouseCode','movementType','quantity'));
                $this->stats['skipped']++;
                return null;
            }

            // Normalize movement type with robust fallback
            $origType = $movementType;
            $normalize = function($v) {
                if ($v === null) return null;
                $v = trim(strtolower((string)$v));
                $map = [
                    'ادخال' => 'in', 'إدخال' => 'in', 'ادخال ','إدخال ' => 'in',
                    'اخراج' => 'out', 'إخراج' => 'out', 'اخراج ','إخراج ' => 'out',
                ];
                return $map[$v] ?? $v;
            };
            $movementType = $normalize($movementType);
            $allowedTypes = ['in','out','transfer_in','transfer_out','adjustment_in','adjustment_out','return_in','return_out'];

            if (!in_array($movementType, $allowedTypes)) {
                // try other candidates from row if misaligned
                $candidates = [];
                foreach (['نوع الحركة','نوع_الحركة','movement_type','noaa_alhrka','noaa_alhrk','2',2] as $k) {
                    if (isset($row[$k])) { $candidates[] = $row[$k]; }
                }
                // also scan any value equal to allowed types
                foreach ($row as $k => $v) {
                    $nv = $normalize($v);
                    if (in_array($nv, $allowedTypes)) { $candidates[] = $v; }
                }
                \Log::warning('Movement Import - Type candidates', ['orig' => $origType, 'candidates' => $candidates]);
                foreach ($candidates as $cand) {
                    $candN = $normalize($cand);
                    if (in_array($candN, $allowedTypes)) { $movementType = $candN; break; }
                }
            }

            if (!in_array($movementType, $allowedTypes)) {
                Log::warning('Movement Import - Invalid type', ['movementType' => $movementType]);
                $this->stats['skipped']++;
                return null;
            }

            // Find or create product
            $product = Product::where('tenant_id', $this->tenantId)
                ->where(function($q) use ($productCode){
                    $q->where('code', $productCode)->orWhere('product_code', $productCode);
                })->first();

            if (!$product) {
                $product = Product::create([
                    'tenant_id' => $this->tenantId,
                    'name' => $productName ?: $productCode,
                    'product_code' => $productCode,
                    'selling_price' => 0,
                    'cost_price' => 0,
                    'stock_quantity' => 0,
                    'is_active' => true,
                    'status' => 'active',
                ]);
            }

            // Find warehouse
            $warehouse = Warehouse::where('tenant_id', $this->tenantId)
                ->where('code', $warehouseCode)->first();
            if (!$warehouse) {
                Log::warning('Movement Import - Warehouse not found', ['warehouseCode' => $warehouseCode]);
                $this->stats['skipped']++;
                return null;
            }

            // Parse date
            try {
                $movementDateParsed = $movementDate ? Carbon::parse($movementDate) : now();
            } catch (\Exception $e) {
                $movementDateParsed = now();
            }

            // Create movement
            $movement = new InventoryMovement([
                'tenant_id' => $this->tenantId,
                'movement_number' => 'IMP-' . date('Ymd-His') . '-' . uniqid(),
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
                'movement_type' => $movementType,
                'movement_reason' => $reason ?: 'adjustment',
                'quantity' => (float)$quantity,
                'unit_cost' => 0,
                'total_cost' => 0,
                'movement_date' => $movementDateParsed,
                'reference_number' => 'ExcelImport',
                'notes' => $notes,
                'created_by' => optional(auth()->user())->id ?? null,
            ]);

            $this->stats['created']++;
            return $movement;
        } catch (\Throwable $e) {
            Log::error('Movement Import - Error: '.$e->getMessage());
            $this->stats['errors']++;
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'كود المنتج' => 'nullable|string',
            'اسم المنتج' => 'nullable|string',
            'كود المستودع' => 'nullable|string',
            'نوع الحركة' => 'nullable|string',
            'الكمية' => 'nullable|numeric|min:0.001',
            'السبب' => 'nullable|string',
            'التاريخ' => 'nullable|date',
            'ملاحظات' => 'nullable|string',
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

