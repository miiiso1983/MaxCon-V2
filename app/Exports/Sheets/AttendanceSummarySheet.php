<?php

namespace App\Exports\Sheets;

use App\Models\Tenant\HR\Attendance;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class AttendanceSummarySheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private $tenantId, private $from, private $to, private $status = null, private $employeeId = null) {}

    public function array(): array
    {
        $query = Attendance::where('tenant_id', $this->tenantId)
            ->whereBetween('date', [$this->from, $this->to]);
        if ($this->status) $query->where('status', $this->status);
        if ($this->employeeId) $query->where('employee_id', $this->employeeId);
        $records = $query->get();

        $total = $records->count();
        $totalHours = (float) $records->sum('total_hours');
        $late = (int) $records->sum('late_minutes');
        $early = (int) $records->sum('early_leave_minutes');

        $statusCounts = [];
        foreach (array_keys(\App\Models\Tenant\HR\Attendance::STATUS_OPTIONS) as $st) {
            $statusCounts[$st] = $records->where('status', $st)->count();
        }

        return [[
            'from' => $this->from,
            'to' => $this->to,
            'total_records' => $total,
            'total_hours' => round($totalHours, 2),
            'avg_hours' => $total ? round($totalHours / $total, 2) : 0,
            'late_minutes' => $late,
            'early_leave_minutes' => $early,
            'present' => $statusCounts['present'] ?? 0,
            'absent' => $statusCounts['absent'] ?? 0,
            'late' => $statusCounts['late'] ?? 0,
            'early_leave' => $statusCounts['early_leave'] ?? 0,
            'half_day' => $statusCounts['half_day'] ?? 0,
            'holiday' => $statusCounts['holiday'] ?? 0,
            'leave' => $statusCounts['leave'] ?? 0,
        ]];
    }

    public function headings(): array
    {
        return ['from','to','total_records','total_hours','avg_hours','late_minutes','early_leave_minutes','present','absent','late','early_leave','half_day','holiday','leave'];
    }

    public function title(): string
    {
        return 'Summary';
    }
}

