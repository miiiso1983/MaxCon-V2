<?php

namespace App\Exports;

use App\Models\Tenant\HR\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartmentsHierarchyExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function collection()
    {
        return Department::where('tenant_id', $this->tenantId)
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'القسم', 'الكود', 'القسم الرئيسي', 'المدير', 'نشط', 'الموقع', 'الهاتف', 'البريد', 'الميزانية'
        ];
    }

    public function title(): string
    {
        return 'Org Chart';
    }

    public function map($dept): array
    {
        return [
            $dept->id,
            $dept->name,
            $dept->code,
            $dept->parent_id,
            $dept->manager_id,
            $dept->is_active ? 'نعم' : 'لا',
            $dept->location,
            $dept->phone,
            $dept->email,
            $dept->budget,
        ];
    }
}

