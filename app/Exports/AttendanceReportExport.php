<?php

namespace App\Exports;

use App\Models\Tenant\HR\Attendance;
use App\Models\Tenant\HR\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class AttendanceReportExport implements WithMultipleSheets
{
    protected $tenantId;
    protected $from;
    protected $to;
    protected $status;
    protected $employeeId;

    public function __construct($tenantId, string $from, string $to, ?string $status = null, $employeeId = null)
    {
        $this->tenantId = $tenantId;
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;
        $this->employeeId = $employeeId;
    }

    public function sheets(): array
    {
        return [
            new Sheets\AttendanceSummarySheet($this->tenantId, $this->from, $this->to, $this->status, $this->employeeId),
            new Sheets\AttendanceDailySheet($this->tenantId, $this->from, $this->to, $this->status, $this->employeeId),
            new Sheets\AttendanceEmployeesSheet($this->tenantId, $this->from, $this->to, $this->status, $this->employeeId),
        ];
    }
}

