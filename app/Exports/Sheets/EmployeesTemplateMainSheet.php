<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeesTemplateMainSheet implements FromArray, WithHeadings, WithTitle
{
    public function array(): array
    {
        // Provide a single example row with hints
        return [[
            'first_name' => 'مثال: أحمد',
            'last_name' => 'مثال: علي',
            'national_id' => '123456789',
            'email' => 'example@company.com',
            'mobile' => '07901234567',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male/female',
            'marital_status' => 'single/married/divorced/widowed',
            'department_id' => 'معرّف قسم صالح',
            'position_id' => 'معرّف منصب صالح',
            'hire_date' => '2024-01-01',
            'basic_salary' => '1000000',
            'employment_type' => 'full_time/part_time/contract/internship/consultant',
            'employee_code' => 'اختياري (يُنشأ تلقائياً عند تركه فارغاً)'
        ]];
    }

    public function headings(): array
    {
        return [
            'first_name', 'last_name', 'national_id', 'email', 'mobile',
            'date_of_birth', 'gender', 'marital_status', 'department_id', 'position_id',
            'hire_date', 'basic_salary', 'employment_type', 'employee_code'
        ];
    }

    public function title(): string
    {
        return 'Employees';
    }
}

