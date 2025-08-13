<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InventoryTemplateExport implements WithMultipleSheets
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function sheets(): array
    {
        return [
            'قالب المخزون' => new InventoryTemplateSheet($this->tenantId),
            'المنتجات المتاحة' => new ProductsReferenceSheet($this->tenantId),
            'المستودعات المتاحة' => new WarehousesReferenceSheet($this->tenantId),
            'التعليمات' => new InstructionsSheet(),
        ];
    }
}

class InventoryTemplateSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function array(): array
    {
        // Return sample data rows
        return [
            [
                'PROD001',
                'WH001', 
                100,
                25.50,
                'A-01-01',
                'BATCH001',
                '2025-12-31',
                'active',
                'منتج تجريبي'
            ],
            [
                'PROD002',
                'WH001',
                50,
                15.75,
                'A-01-02',
                'BATCH002',
                '2026-06-30',
                'active',
                'منتج تجريبي آخر'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'كود المنتج *',
            'كود المستودع *', 
            'الكمية *',
            'سعر التكلفة',
            'رمز الموقع',
            'رقم الدفعة',
            'تاريخ الانتهاء',
            'الحالة *',
            'ملاحظات'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
            'A:I' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // كود المنتج
            'B' => 15, // كود المستودع
            'C' => 12, // الكمية
            'D' => 15, // سعر التكلفة
            'E' => 15, // رمز الموقع
            'F' => 15, // رقم الدفعة
            'G' => 15, // تاريخ الانتهاء
            'H' => 12, // الحالة
            'I' => 25, // ملاحظات
        ];
    }
}

class ProductsReferenceSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function array(): array
    {
        try {
            $products = Product::where('tenant_id', $this->tenantId)->get();

            $result = [];
            foreach ($products as $product) {
                $result[] = [
                    $product->code ?? 'PROD' . str_pad($product->id, 3, '0', STR_PAD_LEFT),
                    $product->name ?? 'منتج غير محدد',
                    $product->category ?? 'عام',
                    $product->unit ?? 'قطعة'
                ];
            }

            // إذا لم توجد منتجات، أضف بيانات تجريبية
            if (empty($result)) {
                $result = [
                    ['PROD001', 'منتج تجريبي 1', 'أدوية', 'قطعة'],
                    ['PROD002', 'منتج تجريبي 2', 'مستلزمات', 'علبة'],
                ];
            }

            return $result;
        } catch (\Exception $e) {
            // في حالة الخطأ، أرجع بيانات تجريبية
            return [
                ['PROD001', 'منتج تجريبي 1', 'أدوية', 'قطعة'],
                ['PROD002', 'منتج تجريبي 2', 'مستلزمات', 'علبة'],
            ];
        }
    }

    public function headings(): array
    {
        return [
            'كود المنتج',
            'اسم المنتج',
            'الفئة',
            'الوحدة'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10b981']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 20,
            'D' => 15,
        ];
    }
}

class WarehousesReferenceSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function array(): array
    {
        try {
            $warehouses = Warehouse::where('tenant_id', $this->tenantId)->get();

            $result = [];
            foreach ($warehouses as $warehouse) {
                $result[] = [
                    $warehouse->code ?? 'WH' . str_pad($warehouse->id, 3, '0', STR_PAD_LEFT),
                    $warehouse->name ?? 'مستودع غير محدد',
                    $warehouse->location ?? 'غير محدد',
                    $warehouse->type ?? 'عام'
                ];
            }

            // إذا لم توجد مستودعات، أضف بيانات تجريبية
            if (empty($result)) {
                $result = [
                    ['WH001', 'المستودع الرئيسي', 'بغداد', 'رئيسي'],
                    ['WH002', 'مستودع الفرع', 'البصرة', 'فرع'],
                ];
            }

            return $result;
        } catch (\Exception $e) {
            // في حالة الخطأ، أرجع بيانات تجريبية
            return [
                ['WH001', 'المستودع الرئيسي', 'بغداد', 'رئيسي'],
                ['WH002', 'مستودع الفرع', 'البصرة', 'فرع'],
            ];
        }
    }

    public function headings(): array
    {
        return [
            'كود المستودع',
            'اسم المستودع',
            'الموقع',
            'النوع'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'f59e0b']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 20,
            'D' => 15,
        ];
    }
}

class InstructionsSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            ['الحقول المطلوبة', 'كود المنتج، كود المستودع، الكمية، الحالة'],
            ['الحقول الاختيارية', 'سعر التكلفة، رمز الموقع، رقم الدفعة، تاريخ الانتهاء، ملاحظات'],
            ['', ''],
            ['قيم الحالة المسموحة:', ''],
            ['active', 'نشط - المنتج متاح للبيع'],
            ['quarantine', 'حجر صحي - المنتج محجوز للفحص'],
            ['damaged', 'تالف - المنتج غير صالح للبيع'],
            ['expired', 'منتهي الصلاحية'],
            ['', ''],
            ['تنسيق التاريخ:', 'YYYY-MM-DD (مثال: 2025-12-31)'],
            ['تنسيق الكمية:', 'أرقام فقط (مثال: 100 أو 25.5)'],
            ['تنسيق السعر:', 'أرقام فقط (مثال: 25.50)'],
            ['', ''],
            ['ملاحظات مهمة:', ''],
            ['1. تأكد من وجود المنتج والمستودع في النظام قبل الاستيراد'],
            ['2. استخدم الأكواد الصحيحة من الأوراق المرجعية'],
            ['3. الحقول المميزة بـ * مطلوبة'],
            ['4. يمكن ترك الحقول الاختيارية فارغة'],
            ['5. سيتم تجاهل الصفوف الفارغة تلقائياً']
        ];
    }

    public function headings(): array
    {
        return [
            'التعليمات',
            'التفاصيل'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8b5cf6']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A:B' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 50,
        ];
    }
}
