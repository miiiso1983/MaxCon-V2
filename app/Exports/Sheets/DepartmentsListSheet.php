<?php

namespace App\Exports\Sheets;

use App\Models\Tenant\HR\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DepartmentsListSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $tenantId;

    public function __construct($tenantId) { $this->tenantId = $tenantId; }

    public function collection()
    {
        return Department::where('tenant_id', $this->tenantId)
            ->orderBy('name')
            ->get(['id','code','name','parent_id','manager_id','is_active','created_at']);
    }

    public function headings(): array
    {
        return ['ID','الكود','الاسم','القسم الرئيسي','المدير','نشط','تاريخ الإنشاء'];
    }

    public function title(): string
    {
        return 'All Departments';
    }
}

