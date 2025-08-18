<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeesTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new Sheets\EmployeesTemplateMainSheet(),
            new Sheets\EmployeesTemplateLookupsSheet(),
        ];
    }
}

