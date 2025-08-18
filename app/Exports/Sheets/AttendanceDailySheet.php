<?php

namespace App\Exports\Sheets;

use App\Models\Tenant\HR\Attendance;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class AttendanceDailySheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private $tenantId, private $from, private $to, private $status = null, private $employeeId = null) {}

    public function array(): array
    {
        $records = Attendance::where('tenant_id', $this->tenantId)
            ->whereBetween('date', [$this->from, $this->to])
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->employeeId, fn($q) => $q->where('employee_id', $this->employeeId))
            ->orderBy('date')
            ->get(['date','employee_id','check_in_time','check_out_time','total_hours','status','late_minutes','early_leave_minutes','notes']);

        return $records->map(function($r){
            return [
                'date' => $r->date?->format('Y-m-d'),
                'employee_id' => $r->employee_id,
                'check_in_time' => optional($r->check_in_time)->format('H:i'),
                'check_out_time' => optional($r->check_out_time)->format('H:i'),
                'total_hours' => $r->total_hours,
                'status' => $r->status,
                'late_minutes' => $r->late_minutes,
                'early_leave_minutes' => $r->early_leave_minutes,
                'notes' => $r->notes,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['date','employee_id','check_in','check_out','total_hours','status','late_minutes','early_leave_minutes','notes'];
    }

    public function title(): string
    {
        return 'Daily';
    }
}

