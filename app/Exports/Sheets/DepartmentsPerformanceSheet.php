<?php

namespace App\Exports\Sheets;

use App\Models\Tenant\HR\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DepartmentsPerformanceSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $tenantId;

    public function __construct($tenantId) { $this->tenantId = $tenantId; }

    public function collection()
    {
        // Placeholder KPIs. Replace with real metrics when available.
        return Department::where('tenant_id', $this->tenantId)
            ->orderBy('name')
            ->get()
            ->map(function($d){
                return [
                    'id' => $d->id,
                    'name' => $d->name,
                    'employees_count' => method_exists($d, 'employees') ? $d->employees()->count() : null,
                    'budget_utilization' => null,
                    'projects_completed' => null,
                ];
            });
    }

    public function headings(): array
    {
        return ['ID','القسم','عدد الموظفين','نسبة استخدام الميزانية','المشاريع المكتملة'];
    }

    public function title(): string
    {
        return 'Performance';
    }
}

