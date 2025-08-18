<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Tenant\HR\Attendance;
use App\Models\Tenant\HR\Employee;
class AttendanceController extends Controller
{
    public function index()
    {
        return view('tenant.hr.attendance.index');
    }

    public function create()
    {
        return view('tenant.hr.attendance.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.attendance.index')->with('success', 'تم تسجيل الحضور بنجاح');
    }

    public function edit($id)
    {
        return view('tenant.hr.attendance.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.hr.attendance.index')->with('success', 'تم تحديث الحضور بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.hr.attendance.index')->with('success', 'تم حذف السجل بنجاح');
    }

    public function checkIn(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'تم تسجيل الدخول بنجاح']);
    }

    public function checkOut(Request $request)
    {
        return response()->json(['success' => true, 'message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function reports(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);

        // Period A
        $fromA = Carbon::parse($request->get('from_date', now()->startOfMonth()->toDateString()))->startOfDay();
        $toA = Carbon::parse($request->get('to_date', now()->toDateString()))->endOfDay();
        $statusFilter = $request->get('status');
        $employeeId = $request->get('employee_id');
        $stats = $this->buildStats($tenantId, $fromA, $toA, $statusFilter, $employeeId);

        // Optional Comparison Period B
        $fromBParam = $request->get('from_date_b');
        $toBParam = $request->get('to_date_b');
        $statsB = null;
        if ($fromBParam || $toBParam) {
            $fromB = Carbon::parse($fromBParam ?? $fromA->copy()->subMonth()->startOfMonth()->toDateString())->startOfDay();
            $toB = Carbon::parse($toBParam ?? $toA->copy()->subMonth()->endOfMonth()->toDateString())->endOfDay();
            $statsB = $this->buildStats($tenantId, $fromB, $toB, $statusFilter, $employeeId);
        }

        return view('tenant.hr.attendance.reports', compact('stats','statsB'));
    }

    private function buildStats($tenantId, Carbon $from, Carbon $to, ?string $statusFilter, $employeeId): array
    {
        $query = Attendance::where('tenant_id', $tenantId)
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()]);
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        $records = $query->get();

        $statusOptions = array_keys(Attendance::STATUS_OPTIONS);
        $statusCounts = array_fill_keys($statusOptions, 0);
        $totalHours = 0;
        $lateMinutes = 0;
        $earlyLeaveMinutes = 0;

        foreach ($records as $rec) {
            $statusCounts[$rec->status] = ($statusCounts[$rec->status] ?? 0) + 1;
            $totalHours += (float) $rec->total_hours;
            $lateMinutes += (int) $rec->late_minutes;
            $earlyLeaveMinutes += (int) $rec->early_leave_minutes;
        }

        // Daily breakdown
        $daily = [];
        $period = new \DatePeriod($from, new \DateInterval('P1D'), $to->copy()->addDay());
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $daily[$key] = [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'early_leave' => 0,
                'hours' => 0,
            ];
        }
        foreach ($records as $rec) {
            $key = Carbon::parse($rec->date)->format('Y-m-d');
            if (!isset($daily[$key])) continue;
            if (isset($daily[$key][$rec->status])) {
                $daily[$key][$rec->status] += 1;
            }
            $daily[$key]['hours'] += (float) $rec->total_hours;
        }

        // Day-of-week patterns
        $dow = array_fill(0, 7, 0);
        foreach ($records as $rec) {
            $d = Carbon::parse($rec->date)->dayOfWeek; // 0 Sunday .. 6 Saturday
            if ($rec->status === 'present' || $rec->status === 'late') {
                $dow[$d] += 1;
            }
        }

        // Employee performance (top 10 by attendance rate)
        $byEmp = [];
        foreach ($records as $rec) {
            $eid = $rec->employee_id;
            if (!isset($byEmp[$eid])) {
                $byEmp[$eid] = ['present' => 0, 'total' => 0];
            }
            $byEmp[$eid]['total'] += 1;
            if (in_array($rec->status, ['present','late'])) {
                $byEmp[$eid]['present'] += 1;
            }
        }
        arsort($byEmp);
        $empIds = array_keys($byEmp);
        $employees = Employee::whereIn('id', $empIds)->get(['id','first_name','last_name']);
        $nameMap = $employees->mapWithKeys(function($e){ return [$e->id => trim($e->first_name . ' ' . $e->last_name)]; })->toArray();
        $topEmployees = [];
        foreach ($byEmp as $eid => $vals) {
            $rate = $vals['total'] > 0 ? round($vals['present'] / $vals['total'] * 100, 1) : 0;
            $topEmployees[] = [
                'id' => $eid,
                'name' => $nameMap[$eid] ?? ('ID ' . $eid),
                'present' => $vals['present'],
                'total' => $vals['total'],
                'rate' => $rate,
            ];
        }
        $topEmployees = array_slice($topEmployees, 0, 10);

        return [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'status_counts' => $statusCounts,
            'total_hours' => round($totalHours, 2),
            'avg_hours' => count($records) ? round($totalHours / count($records), 2) : 0,
            'late_minutes' => $lateMinutes,
            'early_leave_minutes' => $earlyLeaveMinutes,
            'daily' => $daily,
            'dow' => $dow,
            'top_employees' => $topEmployees,
        ];
    }

    public function export(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $from = $request->get('from_date', now()->startOfMonth()->toDateString());
        $to = $request->get('to_date', now()->toDateString());
        $status = $request->get('status');
        $employeeId = $request->get('employee_id');

        $fileName = 'attendance_report_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AttendanceReportExport($tenantId, $from, $to, $status, $employeeId), $fileName);
    }

    public function attendanceReport()
    {
        return view('tenant.hr.reports.attendance');
    }
}
