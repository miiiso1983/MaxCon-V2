<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\HR\Leave;
use App\Models\Tenant\HR\Employee;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $leaves = Leave::with('leaveType')
            ->where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->paginate(10);
        $leaveTypes = \App\Models\Tenant\HR\LeaveType::where('tenant_id', $tenantId)->where('is_active', true)->orderBy('name')->get(['id','name']);
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name','full_name_arabic','full_name_english']);
        return view('tenant.hr.leaves.index', compact('leaves','leaveTypes','employees'));
    }

    public function create()
    {
        return view('tenant.hr.leaves.create');
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $user = Auth::user();

        $data = $request->validate([
            'leave_type_id' => 'required|exists:hr_leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_requested' => 'nullable|integer|min:1|max:365',
            'reason' => 'required|string|max:2000',
            'attachments.*' => 'nullable|file|max:5120',
            'employee_id' => 'nullable|integer|exists:hr_employees,id',
        ]);

        // Resolve employee (allow HR to submit on behalf of another employee)
        if ($request->filled('employee_id') && Auth::user()->can('manage hr leaves')) {
            $employee = Employee::where('tenant_id', $tenantId)->find($request->integer('employee_id'));
            if (!$employee) {
                return redirect()->back()->with('error', 'الموظف المحدد غير موجود');
            }
        } else {
            // Resolve by current user
            $employee = Employee::where('tenant_id', $tenantId)
                ->where('email', $user->email)
                ->first();
        }

        if (!$employee) {
            $candidates = Employee::where('tenant_id', $tenantId)
                ->where(function($q) use ($user) {
                    $q->whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$user->name])
                      ->orWhere('full_name_english', $user->name)
                      ->orWhere('full_name_arabic', $user->name);
                    if (!empty($user->phone)) {
                        $q->orWhere('mobile', $user->phone)
                          ->orWhere('phone', $user->phone);
                    }
                })->get();
            if ($candidates->count() === 1) {
                $employee = $candidates->first();
                // اختياري: مزامنة بريد الموظف مع بريد المستخدم إذا كان متاحاً وفريداً
                if (!empty($user->email)) {
                    $exists = Employee::where('tenant_id', $tenantId)
                        ->where('email', $user->email)
                        ->where('id', '!=', $employee->id)
                        ->exists();
                    if (!$exists) {
                        $employee->email = $user->email;
                        $employee->save();
                    }
                }
            } elseif ($candidates->count() > 1) {
                return redirect()->back()->with('error', 'تم العثور على أكثر من موظف يطابق اسم/هاتف المستخدم. يرجى تحديث بيانات الموظف لتكون فريدة.');
            } else {
                return redirect()->back()->with('error', 'لا يوجد موظف مرتبط بحساب المستخدم الحالي. يرجى ربط المستخدم بسجل موظف.');
            }
        }

        // Compute working days if not provided
        $start = \Carbon\Carbon::parse($data['start_date']);
        $end = \Carbon\Carbon::parse($data['end_date']);
        $daysRequested = $data['days_requested'] ?? $this->calculateWorkingDays($start, $end);
        if ($daysRequested < 1) {
            return redirect()->back()->with('error', 'عدد الأيام غير صالح');
        }

        // Prevent overlap
        $overlap = Leave::where('tenant_id', $tenantId)
            ->where('employee_id', $employee->id)
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start->toDateString(), $end->toDateString()])
                  ->orWhereBetween('end_date', [$start->toDateString(), $end->toDateString()])
                  ->orWhere(function ($qq) use ($start, $end) {
                      $qq->where('start_date', '<=', $start->toDateString())
                         ->where('end_date', '>=', $end->toDateString());
                  });
            })->exists();
        if ($overlap) {
            return redirect()->back()->with('error', 'الفترة المحددة تتداخل مع طلب إجازة آخر.');
        }

        $leaveType = \App\Models\Tenant\HR\LeaveType::findOrFail($data['leave_type_id']);

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file) {
                    $path = $file->store('leaves/attachments', 'public');
                    $attachments[] = $path;
                }
            }
        }

        $leave = Leave::create([
            'tenant_id' => $tenantId,
            'employee_id' => $employee->id,
            'leave_type_id' => $data['leave_type_id'],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'days_requested' => $daysRequested,
            'reason' => $data['reason'],
            'status' => 'pending',
            'applied_date' => now()->toDateString(),
            'is_paid' => (bool)$leaveType->is_paid,
            'attachments' => $attachments,
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'تم إرسال طلب الإجازة بنجاح', 'leave_id' => $leave->id]);
        }

        return redirect()->route('tenant.hr.leaves.index')->with('success', 'تم إرسال طلب الإجازة بنجاح');
    }

    private function calculateWorkingDays(\Carbon\Carbon $start, \Carbon\Carbon $end): int
    {
        $workingDays = 0;
        $date = $start->copy();
        while ($date->lte($end)) {
            if (!$date->isFriday() && !$date->isSaturday()) {
                $workingDays++;
            }
            $date->addDay();
        }
        return $workingDays;
    }

    public function show($id)
    {
        return view('tenant.hr.leaves.show');
    }

    public function edit($id)
    {
        return view('tenant.hr.leaves.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.hr.leaves.index')->with('success', 'تم تحديث طلب الإجازة بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.hr.leaves.index')->with('success', 'تم حذف طلب الإجازة بنجاح');
    }

    public function approve($id)
    {
        try {
            $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
            $leave = Leave::where('tenant_id', $tenantId)->where('id', $id)->first();
            if (!$leave) {
                return redirect()->back()->with('error', 'طلب الإجازة غير موجود');
            }
            if (!in_array($leave->status, ['pending'])) {
                return redirect()->back()->with('info', 'لا يمكن الموافقة، حالة الطلب الحالية: ' . $leave->status);
            }

            $leave->status = 'approved';
            $leave->approved_date = Carbon::now()->toDateString();
            // approved_by يتطلب id من hr_employees، غير متاح حالياً من المستخدم
            $leave->approved_by = $leave->approved_by ?? null;
            if (empty($leave->days_approved)) {
                $leave->days_approved = $leave->days_requested;
            }
            $leave->save();

            return redirect()->back()->with('success', 'تمت الموافقة على الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الموافقة: ' . $e->getMessage());
        }
    }

        // Persist reject reason if provided later

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:2000'
        ]);
        try {
            $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
            $leave = Leave::where('tenant_id', $tenantId)->where('id', $id)->first();
            if (!$leave) {
                return redirect()->back()->with('error', 'طلب الإجازة غير موجود');
            }
            if (!in_array($leave->status, ['pending'])) {
                return redirect()->back()->with('info', 'لا يمكن الرفض، حالة الطلب الحالية: ' . $leave->status);
            }

            $leave->status = 'rejected';
            $leave->rejected_reason = $request->input('reason');
            $leave->approved_date = null;
            $leave->approved_by = null;
            $leave->save();

            return redirect()->back()->with('success', 'تم رفض الإجازة بنجاح');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الرفض: ' . $e->getMessage());
        }
    }

    public function calendar()
    {
        return view('tenant.hr.leaves.calendar');
    }

    public function balance($employeeId)
    {
        return view('tenant.hr.leaves.balance');
    }

    public function leaveReport()
    {
        return view('tenant.hr.reports.leaves');
    }

    public function export(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $from = $request->get('from_date');
        $to = $request->get('to_date');
        $status = $request->get('status');
        $employeeId = $request->get('employee_id');
        $fileName = 'leaves_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LeaveExport($tenantId, $from, $to, $status, $employeeId), $fileName);
    }

}
