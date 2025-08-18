<?php

namespace App\Exports\Sheets;

use App\Models\Tenant\HR\Attendance;
use App\Models\Tenant\HR\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class AttendanceEmployeesSheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private $tenantId, private $from, private $to, private $status = null, private $employeeId = null) {}

    public function array(): array
    {
        $records = Attendance::where('tenant_id', $this->tenantId)
            ->whereBetween('date', [$this->from, $this->to])
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->employeeId, fn($q) => $q->where('employee_id', $this->employeeId))
            ->get(['employee_id','total_hours','status','late_minutes','early_leave_minutes']);

        $grouped = [];
        foreach ($records as $r) {
            $eid = $r->employee_id;
            if (!isset($grouped[$eid])) {
                $grouped[$eid] = ['present' => 0, 'absent' => 0, 'late' => 0, 'early_leave' => 0, 'half_day' => 0, 'holiday' => 0, 'leave' => 0, 'total_hours' => 0];
            }
            $grouped[$eid]['total_hours'] += (float)$r->total_hours;
            if (isset($grouped[$eid][$r->status])) {
                $grouped[$eid][$r->status] += 1;
            }
        }

        $employeeNames = Employee::whereIn('id', array_keys($grouped))->get(['id','first_name','last_name'])
            ->mapWithKeys(fn($e) => [$e->id => trim($e->first_name.' '.$e->last_name)])->toArray();

        $rows = [];
        foreach ($grouped as $eid => $vals) {
            $rows[] = [
                'employee_id' => $eid,
                'employee_name' => $employeeNames[$eid] ?? ('ID '.$eid),
                'present' => $vals['present'],
                'absent' => $vals['absent'],
                'late' => $vals['late'],
                'early_leave' => $vals['early_leave'],
                'half_day' => $vals['half_day'],
                'holiday' => $vals['holiday'],
                'leave' => $vals['leave'],
                'total_hours' => round($vals['total_hours'], 2),
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['employee_id','employee_name','present','absent','late','early_leave','half_day','holiday','leave','total_hours'];
    }

    public function title(): string
    {
        return 'Employees';
    }
}

