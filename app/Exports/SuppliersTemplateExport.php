<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuppliersTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function array(): array
    {
        return [
            [
                'شركة الأدوية المتحدة',
                'SUP-001',
                'distributor',
                'active',
                'أحمد محمد',
                '07901234567',
                'supplier@example.com',
                'بغداد - الكرادة',
                '123456789',
                'credit_30',
                '50000',
                'IQD',
                'pharmaceutical',
                'مورد موثوق للأدوية'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'اسم المورد*',
            'رمز المورد',
            'نوع المورد',
            'الحالة',
            'شخص الاتصال',
            'الهاتف',
            'البريد الالكتروني',
            'العنوان',
            'الرقم الضريبي',
            'شروط الدفع',
            'حد الائتمان',
            'العملة',
            'الفئة',
            'ملاحظات'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A1:N1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE2E8F0']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // اسم المورد
            'B' => 15, // رمز المورد
            'C' => 15, // نوع المورد
            'D' => 12, // الحالة
            'E' => 20, // شخص الاتصال
            'F' => 15, // الهاتف
            'G' => 25, // البريد الالكتروني
            'H' => 30, // العنوان
            'I' => 15, // الرقم الضريبي
            'J' => 15, // شروط الدفع
            'K' => 15, // حد الائتمان
            'L' => 10, // العملة
            'M' => 15, // الفئة
            'N' => 30, // ملاحظات
        ];
    }

    public function title(): string
    {
        return 'قالب الموردين';
    }
}
