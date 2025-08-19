<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Overtime;
use App\Models\Tenant\HR\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Tenant\HR\OvertimesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;

        $query = Overtime::where('tenant_id', $tenantId)
            ->with(['employee', 'requester', 'approver'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $overtimes = $query->paginate(15)->appends($request->query());

        // Get employees for filter dropdown
        $employees = Employee::where('tenant_id', $tenantId)
            ->active()
            ->orderBy('full_name_arabic')
            ->get();

        // Calculate statistics
        $stats = [
            'total_hours_month' => Overtime::where('tenant_id', $tenantId)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'approved')
                ->sum('hours_approved'),
            'pending_requests' => Overtime::where('tenant_id', $tenantId)
                ->where('status', 'pending')
                ->count(),
            'approved_requests' => Overtime::where('tenant_id', $tenantId)
                ->where('status', 'approved')
                ->whereMonth('date', now()->month)
                ->count(),
            'total_amount_month' => Overtime::where('tenant_id', $tenantId)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'approved')
                ->sum('total_amount')
        ];

        return view('tenant.hr.overtime.index', compact('overtimes', 'employees', 'stats'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;

        $employees = Employee::where('tenant_id', $tenantId)
            ->active()
            ->with(['department', 'position'])
            ->orderBy('full_name_arabic')
            ->get();

        return view('tenant.hr.overtime.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string|max:500',
            'hourly_rate' => 'required|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:1',
            'is_holiday_overtime' => 'boolean',
            'is_night_overtime' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;
            $userId = Auth::id();

            // Create overtime record
            $overtime = Overtime::create([
                'tenant_id' => $tenantId,
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'start_time' => Carbon::parse($request->date . ' ' . $request->start_time),
                'end_time' => Carbon::parse($request->date . ' ' . $request->end_time),
                'hourly_rate' => $request->hourly_rate,
                'overtime_rate' => $request->overtime_rate,
                'reason' => $request->reason,
                'status' => 'pending',
                'requested_by' => $userId,
                'is_holiday_overtime' => $request->boolean('is_holiday_overtime'),
                'is_night_overtime' => $request->boolean('is_night_overtime'),
                'notes' => $request->notes,
                'created_by' => $userId,
            ]);

            return redirect()->route('tenant.hr.overtime.index')
                ->with('success', 'تم تسجيل الساعات الإضافية بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تسجيل الساعات الإضافية: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;

        $overtime = Overtime::where('tenant_id', $tenantId)
            ->with(['employee.department', 'employee.position', 'requester', 'approver'])
            ->findOrFail($id);

        return view('tenant.hr.overtime.show', compact('overtime'));
    }

    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;

        $overtime = Overtime::where('tenant_id', $tenantId)->findOrFail($id);

        // Only allow editing if status is pending
        if ($overtime->status !== 'pending') {
            return redirect()->route('tenant.hr.overtime.index')
                ->with('error', 'لا يمكن تعديل الساعات الإضافية بعد الموافقة عليها أو رفضها');
        }

        $employees = Employee::where('tenant_id', $tenantId)
            ->active()
            ->with(['department', 'position'])
            ->orderBy('full_name_arabic')
            ->get();

        return view('tenant.hr.overtime.edit', compact('overtime', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string|max:500',
            'hourly_rate' => 'required|numeric|min:0',
            'overtime_rate' => 'required|numeric|min:1',
            'is_holiday_overtime' => 'boolean',
            'is_night_overtime' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $tenantId = tenant_id() ?? Auth::user()->tenant_id ?? 1;
            $overtime = Overtime::where('tenant_id', $tenantId)->findOrFail($id);

            // Only allow updating if status is pending
            if ($overtime->status !== 'pending') {
                return redirect()->route('tenant.hr.overtime.index')
                    ->with('error', 'لا يمكن تعديل الساعات الإضافية بعد الموافقة عليها أو رفضها');
            }

            $overtime->update([
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'start_time' => Carbon::parse($request->date . ' ' . $request->start_time),
                'end_time' => Carbon::parse($request->date . ' ' . $request->end_time),
                'hourly_rate' => $request->hourly_rate,
                'overtime_rate' => $request->overtime_rate,
                'reason' => $request->reason,
                'is_holiday_overtime' => $request->boolean('is_holiday_overtime'),
                'is_night_overtime' => $request->boolean('is_night_overtime'),
                'notes' => $request->notes,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('tenant.hr.overtime.index')
                ->with('success', 'تم تحديث الساعات الإضافية بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الساعات الإضافية: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;
            $overtime = Overtime::where('tenant_id', $tenantId)->findOrFail($id);

            // Only allow deletion if status is pending
            if ($overtime->status !== 'pending') {
                return redirect()->route('tenant.hr.overtime.index')
                    ->with('error', 'لا يمكن حذف الساعات الإضافية بعد الموافقة عليها أو رفضها');
            }

            $overtime->delete();

            return redirect()->route('tenant.hr.overtime.index')
                ->with('success', 'تم حذف سجل الساعات الإضافية بنجاح');

        } catch (\Exception $e) {
            return redirect()->route('tenant.hr.overtime.index')
                ->with('error', 'حدث خطأ أثناء حذف الساعات الإضافية: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;
            $overtime = Overtime::where('tenant_id', $tenantId)->findOrFail($id);

            if ($overtime->status !== 'pending') {
                return redirect()->route('tenant.hr.overtime.index')
                    ->with('error', 'هذا الطلب تم التعامل معه مسبقاً');
            }

            $overtime->approve(Auth::id());

            return redirect()->route('tenant.hr.overtime.index')
                ->with('success', 'تم الموافقة على الساعات الإضافية بنجاح');

        } catch (\Exception $e) {
            return redirect()->route('tenant.hr.overtime.index')
                ->with('error', 'حدث خطأ أثناء الموافقة على الساعات الإضافية: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500'
        ]);

        try {
            $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;
            $overtime = Overtime::where('tenant_id', $tenantId)->findOrFail($id);

            if ($overtime->status !== 'pending') {
                return redirect()->route('tenant.hr.overtime.index')
                    ->with('error', 'هذا الطلب تم التعامل معه مسبقاً');
            }

            $overtime->reject(Auth::id(), $request->rejected_reason);

            return redirect()->route('tenant.hr.overtime.index')
                ->with('success', 'تم رفض الساعات الإضافية');

        } catch (\Exception $e) {
            return redirect()->route('tenant.hr.overtime.index')
                ->with('error', 'حدث خطأ أثناء رفض الساعات الإضافية: ' . $e->getMessage());
        }
    }

    public function reports()
    {
        $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;

        // Get monthly overtime statistics
        $monthlyStats = Overtime::where('tenant_id', $tenantId)
            ->selectRaw('MONTH(date) as month, YEAR(date) as year,
                        SUM(hours_approved) as total_hours,
                        SUM(total_amount) as total_amount,
                        COUNT(*) as total_requests')
            ->where('status', 'approved')
            ->whereYear('date', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get employee overtime statistics
        $employeeStats = Overtime::where('tenant_id', $tenantId)
            ->with('employee')
            ->selectRaw('employee_id,
                        SUM(hours_approved) as total_hours,
                        SUM(total_amount) as total_amount,
                        COUNT(*) as total_requests')
            ->where('status', 'approved')
            ->whereYear('date', now()->year)
            ->groupBy('employee_id')
            ->orderBy('total_hours', 'desc')
            ->limit(10)
            ->get();

        return view('tenant.hr.overtime.reports', compact('monthlyStats', 'employeeStats'));
    }


    public function export(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? tenant_id() ?? 1;
        $format = $request->get('format', 'excel'); // excel|csv|pdf
        $period = $request->get('export_type', 'all'); // all|current_month|last_month|by_employee
        $employeeId = $request->get('employee_id');

        if (in_array($format, ['excel','csv'])) {
            $export = new OvertimesExport($tenantId, $period, $employeeId, $format);
            $fileName = 'overtimes_' . now()->format('Ymd_His');
            if ($format === 'csv') {
                return Excel::download($export, $fileName . '.csv', \Maatwebsite\Excel\Excel::CSV);
            }
            return Excel::download($export, $fileName . '.xlsx');
        }

        // PDF export
        $query = Overtime::with('employee')->where('tenant_id', $tenantId);
        if ($period === 'current_month') {
            $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
        } elseif ($period === 'last_month') {
            $query->whereMonth('date', now()->subMonth()->month)->whereYear('date', now()->subMonth()->year);
        }
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        $overtimes = $query->orderBy('date','desc')->get();

        $pdf = Pdf::loadView('tenant.hr.overtime.export-table', compact('overtimes'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('overtimes_' . now()->format('Ymd_His') . '.pdf');
    }

    public function overtimeReport()
    {
        return view('tenant.hr.reports.overtime');
    }
}
