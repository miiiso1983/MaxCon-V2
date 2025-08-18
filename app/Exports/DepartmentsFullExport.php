<?php

namespace App\Exports;

use App\Models\Tenant\HR\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DepartmentsFullExport implements WithMultipleSheets
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function sheets(): array
    {
        return [
            new Sheets\DepartmentsListSheet($this->tenantId),
            new Sheets\DepartmentsContactSheet($this->tenantId),
            new Sheets\DepartmentsFinanceSheet($this->tenantId),
            new Sheets\DepartmentsPerformanceSheet($this->tenantId),
        ];
    }
}

