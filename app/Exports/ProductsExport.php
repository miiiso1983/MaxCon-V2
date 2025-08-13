<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $products;
    protected $isTemplate;

    public function __construct($products, $isTemplate = false)
    {
        $this->products = $products;
        $this->isTemplate = $isTemplate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->isTemplate) {
            return collect($this->products);
        }

        return $this->products;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'اسم المنتج',
            'كود المنتج', 
            'الوصف',
            'الفئة',
            'الشركة المصنعة',
            'الباركود',
            'وحدة القياس',
            'سعر التكلفة',
            'سعر البيع',
            'الحد الأدنى للمخزون',
            'الكمية الحالية',
            'نشط'
        ];
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        if ($this->isTemplate) {
            return [
                $product['اسم المنتج'] ?? '',
                $product['كود المنتج'] ?? '',
                $product['الوصف'] ?? '',
                $product['الفئة'] ?? '',
                $product['الشركة المصنعة'] ?? '',
                $product['الباركود'] ?? '',
                $product['وحدة القياس'] ?? '',
                $product['سعر التكلفة'] ?? '',
                $product['سعر البيع'] ?? '',
                $product['الحد الأدنى للمخزون'] ?? '',
                $product['الكمية الحالية'] ?? '',
                $product['نشط'] ?? ''
            ];
        }

        return [
            $product->name,
            $product->product_code,
            $product->description,
            $product->category ? $product->category->name : '',
            $product->manufacturer,
            $product->barcode,
            $product->unit_of_measure,
            $product->cost_price,
            $product->selling_price,
            $product->min_stock_level,
            $product->stock_quantity ?? $product->current_stock,
            $product->is_active ? 'نعم' : 'لا'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE2E8F0',
                    ],
                ],
            ],
        ];
    }
}
