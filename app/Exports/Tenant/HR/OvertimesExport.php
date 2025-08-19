<?php

namespace App\Exports\Tenant\HR;

use App\Models\Tenant\HR\Overtime;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;

class OvertimesExport implements FromView, WithTitle
{
    use Exportable;

    public function __construct(
        protected int $tenantId,
        protected ?string $period = null,
        protected ?int $employeeId = null,
        protected ?string $format = 'excel'
    ) {}

    public function view(): View
    {
        $query = Overtime::with('employee')
            ->where('tenant_id', $this->tenantId)
            ->orderBy('date','desc');

        if ($this->period === 'current_month') {
            $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
        } elseif ($this->period === 'last_month') {
            $query->whereMonth('date', now()->subMonth()->month)->whereYear('date', now()->subMonth()->year);
        }

        if ($this->employeeId) {
            $query->where('employee_id', $this->employeeId);
        }

        return view('tenant.hr.overtime.export-table', [
            'overtimes' => $query->get()
        ]);
    }

    public function title(): string
    {
        return 'Overtime Records';
    }
}

