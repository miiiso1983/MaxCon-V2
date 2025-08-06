<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuppliersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $tenantId;
    protected $filters;

    public function __construct($tenantId = null, $filters = [])
    {
        $this->tenantId = $tenantId;
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // If no tenant ID provided, return empty collection
        if (!$this->tenantId) {
            return collect([]);
        }

        $query = Supplier::where('tenant_id', $this->tenantId);

        // Apply filters
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        $result = $query->orderBy('name')->get();

        // Debug: Log the result count and details
        \Illuminate\Support\Facades\Log::info('SuppliersExport: Query executed', [
            'tenant_id' => $this->tenantId,
            'filters' => $this->filters,
            'found_suppliers' => $result->count(),
            'sample_suppliers' => $result->take(3)->pluck('name', 'id')->toArray()
        ]);

        return $result;
    }

    public function headings(): array
    {
        return [
            'الكود',
            'اسم المورد',
            'نوع المورد',
            'الحالة',
            'الشخص المسؤول',
            'الهاتف',
            'البريد الإلكتروني',
            'العنوان',
            'الرقم الضريبي',
            'شروط الدفع',
            'حد الائتمان',
            'العملة',
            'الفئة',
            'التقييم',
            'إجمالي الطلبات',
            'القيمة الإجمالية',
            'تاريخ الإنشاء',
            'ملاحظات'
        ];
    }

    public function map($supplier): array
    {
        return [
            $supplier->code,
            $supplier->name,
            $this->getTypeLabel($supplier->type),
            $this->getStatusLabel($supplier->status),
            $supplier->contact_person,
            $supplier->phone,
            $supplier->email,
            $supplier->address,
            $supplier->tax_number,
            $this->getPaymentTermsLabel($supplier->payment_terms),
            $supplier->credit_limit,
            $supplier->currency ?? 'IQD',
            $this->getCategoryLabel($supplier->category),
            $supplier->rating,
            $supplier->total_orders,
            $supplier->total_amount,
            $supplier->created_at ? $supplier->created_at->format('Y-m-d') : '',
            $supplier->notes
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true, 'size' => 12]],

            // Set background color for header
            'A1:R1' => [
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
            'A' => 15, // Code
            'B' => 25, // Name
            'C' => 15, // Type
            'D' => 12, // Status
            'E' => 20, // Contact Person
            'F' => 15, // Phone
            'G' => 25, // Email
            'H' => 30, // Address
            'I' => 15, // Tax Number
            'J' => 15, // Payment Terms
            'K' => 15, // Credit Limit
            'L' => 10, // Currency
            'M' => 15, // Category
            'N' => 10, // Rating
            'O' => 12, // Total Orders
            'P' => 15, // Total Amount
            'Q' => 12, // Created At
            'R' => 30, // Notes
        ];
    }

    public function title(): string
    {
        return 'الموردين';
    }

    private function getTypeLabel($type)
    {
        return match($type) {
            'manufacturer' => 'مصنع',
            'distributor' => 'موزع',
            'wholesaler' => 'تاجر جملة',
            'retailer' => 'تاجر تجزئة',
            'service_provider' => 'مقدم خدمة',
            default => $type
        };
    }

    private function getStatusLabel($status)
    {
        return match($status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'suspended' => 'معلق',
            'blacklisted' => 'محظور',
            default => $status
        };
    }

    private function getPaymentTermsLabel($paymentTerms)
    {
        return match($paymentTerms) {
            'cash' => 'نقداً',
            'credit_7' => 'آجل 7 أيام',
            'credit_15' => 'آجل 15 يوم',
            'credit_30' => 'آجل 30 يوم',
            'credit_45' => 'آجل 45 يوم',
            'credit_60' => 'آجل 60 يوم',
            'credit_90' => 'آجل 90 يوم',
            'custom' => 'مخصص',
            default => $paymentTerms
        };
    }

    private function getCategoryLabel($category)
    {
        return match($category) {
            'pharmaceutical' => 'أدوية',
            'medical_equipment' => 'معدات طبية',
            'cosmetics' => 'مستحضرات تجميل',
            'supplements' => 'مكملات غذائية',
            'other' => 'أخرى',
            default => $category
        };
    }
}
