<?php

namespace App\Exports\Sheets;

use App\Models\Tenant\HR\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DepartmentsFinanceSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $tenantId;

    public function __construct($tenantId) { $this->tenantId = $tenantId; }

    public function collection()
    {
        return Department::where('tenant_id', $this->tenantId)
            ->orderBy('name')
            ->get(['id','name','budget']);
    }

    public function headings(): array
    {
        return ['ID','القسم','الميزانية'];
    }

    public function title(): string
    {
        return 'Finance';
    }
}

