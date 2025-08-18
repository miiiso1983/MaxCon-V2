<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeesTemplateLookupsSheet implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        return [
            ['gender', 'male', 'female'],
            ['marital_status', 'single', 'married', 'divorced', 'widowed'],
            ['employment_type', 'full_time', 'part_time', 'contract', 'internship', 'consultant'],
        ];
    }

    public function headings(): array
    {
        return ['Lookup', 'Allowed Values...'];
    }

    public function title(): string
    {
        return 'Lookups';
    }
}

