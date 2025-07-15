<?php

namespace App\Exports;

use App\Models\Tenant\HR\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $tenantId;
    protected $filters;

    public function __construct($tenantId = null, $filters = [])
    {
        $this->tenantId = $tenantId ?? (function_exists('tenant') && tenant() ? tenant()->id : 1);
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // For now, return sample data since we don't have real employees yet
        return collect([
            (object) [
                'id' => 1,
                'employee_code' => 'EMP0001',
                'first_name' => 'أحمد',
                'last_name' => 'محمد',
                'full_name' => 'أحمد محمد',
                'email' => 'ahmed.mohamed@company.com',
                'mobile' => '07901234567',
                'national_id' => '12345678901',
                'date_of_birth' => '1990-01-15',
                'gender' => 'male',
                'marital_status' => 'married',
                'address' => 'بغداد - الكرادة',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => '2023-01-01',
                'basic_salary' => 2500000,
                'department' => (object) ['name' => 'الإدارة العامة'],
                'position' => (object) ['title' => 'مدير عام'],
                'created_at' => now(),
            ],
            (object) [
                'id' => 2,
                'employee_code' => 'EMP0002',
                'first_name' => 'سارة',
                'last_name' => 'أحمد',
                'full_name' => 'سارة أحمد',
                'email' => 'sara.ahmed@company.com',
                'mobile' => '07901234568',
                'national_id' => '12345678902',
                'date_of_birth' => '1992-05-20',
                'gender' => 'female',
                'marital_status' => 'single',
                'address' => 'بغداد - الجادرية',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => '2023-03-15',
                'basic_salary' => 2000000,
                'department' => (object) ['name' => 'الموارد البشرية'],
                'position' => (object) ['title' => 'مدير الموارد البشرية'],
                'created_at' => now(),
            ],
            (object) [
                'id' => 3,
                'employee_code' => 'EMP0003',
                'first_name' => 'محمد',
                'last_name' => 'علي',
                'full_name' => 'محمد علي',
                'email' => 'mohamed.ali@company.com',
                'mobile' => '07901234569',
                'national_id' => '12345678903',
                'date_of_birth' => '1988-12-10',
                'gender' => 'male',
                'marital_status' => 'married',
                'address' => 'بغداد - المنصور',
                'employment_status' => 'probation',
                'employment_type' => 'full_time',
                'hire_date' => '2024-01-01',
                'basic_salary' => 1500000,
                'department' => (object) ['name' => 'المالية والمحاسبة'],
                'position' => (object) ['title' => 'محاسب'],
                'created_at' => now(),
            ],
            (object) [
                'id' => 4,
                'employee_code' => 'EMP0004',
                'first_name' => 'فاطمة',
                'last_name' => 'حسن',
                'full_name' => 'فاطمة حسن',
                'email' => 'fatima.hassan@company.com',
                'mobile' => '07901234570',
                'national_id' => '12345678904',
                'date_of_birth' => '1985-08-25',
                'gender' => 'female',
                'marital_status' => 'married',
                'address' => 'بغداد - الدورة',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => '2022-06-01',
                'basic_salary' => 1800000,
                'department' => (object) ['name' => 'المبيعات والتسويق'],
                'position' => (object) ['title' => 'مدير المبيعات'],
                'created_at' => now(),
            ],
            (object) [
                'id' => 5,
                'employee_code' => 'EMP0005',
                'first_name' => 'عمر',
                'last_name' => 'خالد',
                'full_name' => 'عمر خالد',
                'email' => 'omar.khaled@company.com',
                'mobile' => '07901234571',
                'national_id' => '12345678905',
                'date_of_birth' => '1991-03-18',
                'gender' => 'male',
                'marital_status' => 'single',
                'address' => 'بغداد - الكاظمية',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => '2021-09-15',
                'basic_salary' => 2200000,
                'department' => (object) ['name' => 'تقنية المعلومات'],
                'position' => (object) ['title' => 'مطور برمجيات'],
                'created_at' => now(),
            ],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'كود الموظف',
            'الاسم الكامل',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'رقم الهوية',
            'تاريخ الميلاد',
            'الجنس',
            'الحالة الاجتماعية',
            'العنوان',
            'القسم',
            'المنصب',
            'تاريخ التوظيف',
            'الراتب الأساسي (دينار)',
            'نوع التوظيف',
            'حالة التوظيف',
            'تاريخ الإنشاء',
        ];
    }

    /**
     * @param mixed $employee
     * @return array
     */
    public function map($employee): array
    {
        return [
            $employee->employee_code,
            $employee->full_name,
            $employee->email,
            $employee->mobile,
            $employee->national_id,
            $employee->date_of_birth,
            $employee->gender === 'male' ? 'ذكر' : 'أنثى',
            $this->getMaritalStatusLabel($employee->marital_status),
            $employee->address,
            $employee->department->name ?? 'غير محدد',
            $employee->position->title ?? 'غير محدد',
            $employee->hire_date,
            number_format($employee->basic_salary, 0, '.', ','),
            $this->getEmploymentTypeLabel($employee->employment_type),
            $this->getEmploymentStatusLabel($employee->employment_status),
            $employee->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4299E1'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Style all cells
            'A:P' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 11,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // كود الموظف
            'B' => 25, // الاسم الكامل
            'C' => 30, // البريد الإلكتروني
            'D' => 15, // رقم الهاتف
            'E' => 15, // رقم الهوية
            'F' => 15, // تاريخ الميلاد
            'G' => 10, // الجنس
            'H' => 15, // الحالة الاجتماعية
            'I' => 30, // العنوان
            'J' => 20, // القسم
            'K' => 20, // المنصب
            'L' => 15, // تاريخ التوظيف
            'M' => 20, // الراتب الأساسي
            'N' => 15, // نوع التوظيف
            'O' => 15, // حالة التوظيف
            'P' => 20, // تاريخ الإنشاء
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'قائمة الموظفين';
    }

    /**
     * Get marital status label in Arabic
     */
    private function getMaritalStatusLabel($status)
    {
        $labels = [
            'single' => 'أعزب',
            'married' => 'متزوج',
            'divorced' => 'مطلق',
            'widowed' => 'أرمل',
        ];

        return $labels[$status] ?? 'غير محدد';
    }

    /**
     * Get employment type label in Arabic
     */
    private function getEmploymentTypeLabel($type)
    {
        $labels = [
            'full_time' => 'دوام كامل',
            'part_time' => 'دوام جزئي',
            'contract' => 'عقد',
            'internship' => 'تدريب',
            'consultant' => 'استشاري',
        ];

        return $labels[$type] ?? 'غير محدد';
    }

    /**
     * Get employment status label in Arabic
     */
    private function getEmploymentStatusLabel($status)
    {
        $labels = [
            'active' => 'نشط',
            'probation' => 'تحت التجربة',
            'suspended' => 'موقوف',
            'terminated' => 'منتهي الخدمة',
        ];

        return $labels[$status] ?? 'غير محدد';
    }
}
