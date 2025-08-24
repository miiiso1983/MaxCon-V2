<?php

namespace App\Exports\HR;

use App\Models\Tenant\HR\Incentive;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IncentivesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $tenantId;
    protected array $filters;

    public function __construct(int $tenantId, array $filters = [])
    {
        $this->tenantId = $tenantId;
        $this->filters = $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $query = Incentive::with('employee')
            ->where('tenant_id', $this->tenantId)
            ->orderByDesc('date');

        if (!empty($this->filters['employee_id'])) {
            $query->where('employee_id', $this->filters['employee_id']);
        }
        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }
        if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
            $query->whereBetween('date', [$this->filters['date_from'], $this->filters['date_to']]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'الموظف',
            'النوع',
            'المبلغ',
            'السبب',
        ];
    }

    public function map($row): array
    {
        $employeeName = optional($row->employee)->first_name . ' ' . optional($row->employee)->last_name;
        return [
            optional($row->date)->format('Y-m-d'),
            trim($employeeName),
            (string) $row->type,
            (float) $row->amount,
            (string) ($row->reason ?? ''),
        ];
    }
}

