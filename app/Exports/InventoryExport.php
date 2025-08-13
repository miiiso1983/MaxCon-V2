<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InventoryExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    protected $inventoryItems;

    public function __construct($inventoryItems)
    {
        $this->inventoryItems = $inventoryItems;
    }

    public function collection()
    {
        return $this->inventoryItems;
    }

    public function map($inventory): array
    {
        return [
            $inventory->product->code ?? '',
            $inventory->product->name ?? '',
            $inventory->warehouse->code ?? '',
            $inventory->warehouse->name ?? '',
            $inventory->quantity,
            $inventory->available_quantity,
            $inventory->reserved_quantity,
            $inventory->cost_price ?? 0,
            $inventory->location_code ?? '',
            $inventory->batch_number ?? '',
            $inventory->expiry_date ? $inventory->expiry_date->format('Y-m-d') : '',
            $this->getStatusText($inventory->status),
            $inventory->created_at->format('Y-m-d H:i:s'),
            $inventory->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'كود المنتج',
            'اسم المنتج',
            'كود المستودع',
            'اسم المستودع',
            'الكمية الإجمالية',
            'الكمية المتاحة',
            'الكمية المحجوزة',
            'سعر التكلفة',
            'رمز الموقع',
            'رقم الدفعة',
            'تاريخ الانتهاء',
            'الحالة',
            'تاريخ الإنشاء',
            'تاريخ التحديث',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
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
            // All data cells
            'A:N' => [
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
            'B' => 25, // اسم المنتج
            'C' => 15, // كود المستودع
            'D' => 20, // اسم المستودع
            'E' => 15, // الكمية الإجمالية
            'F' => 15, // الكمية المتاحة
            'G' => 15, // الكمية المحجوزة
            'H' => 15, // سعر التكلفة
            'I' => 15, // رمز الموقع
            'J' => 15, // رقم الدفعة
            'K' => 15, // تاريخ الانتهاء
            'L' => 12, // الحالة
            'M' => 20, // تاريخ الإنشاء
            'N' => 20, // تاريخ التحديث
        ];
    }

    private function getStatusText($status): string
    {
        $statusMap = [
            'active' => 'نشط',
            'quarantine' => 'حجر صحي',
            'damaged' => 'تالف',
            'expired' => 'منتهي الصلاحية'
        ];

        return $statusMap[$status] ?? $status;
    }
}
