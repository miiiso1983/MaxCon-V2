<?php

namespace App\Exports;

use App\Models\Tenant\HR\Leave;
use App\Models\Tenant\HR\LeaveType;
use App\Models\Tenant\HR\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LeaveExport implements FromArray, WithHeadings
{
    public function __construct(private $tenantId, private $from = null, private $to = null, private $status = null, private $employeeId = null) {}

    public function array(): array
    {
        $q = Leave::with(['leaveType','employee'])
            ->where('tenant_id', $this->tenantId);
        if ($this->from && $this->to) {
            $q->whereBetween('start_date', [$this->from, $this->to]);
        }
        if ($this->status) {
            $q->where('status', $this->status);
        }
        if ($this->employeeId) {
            $q->where('employee_id', $this->employeeId);
        }
        $rows = [];
        foreach ($q->orderBy('start_date','desc')->get() as $lv) {
            $rows[] = [
                'employee' => $lv->employee?->full_name_english ?? ($lv->employee?->first_name.' '.$lv->employee?->last_name),
                'leave_type' => $lv->leaveType?->name,
                'start_date' => optional($lv->start_date)->format('Y-m-d'),
                'end_date' => optional($lv->end_date)->format('Y-m-d'),
                'days_requested' => $lv->days_requested,
                'days_approved' => $lv->days_approved,
                'status' => $lv->status,
                'reason' => $lv->reason,
                'applied_date' => optional($lv->applied_date)->format('Y-m-d'),
                'is_paid' => $lv->is_paid ? 'Yes' : 'No',
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return ['Employee','Leave Type','Start Date','End Date','Days Requested','Days Approved','Status','Reason','Applied Date','Paid'];
    }
}

