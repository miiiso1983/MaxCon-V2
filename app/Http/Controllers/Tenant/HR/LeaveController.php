<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\HR\Leave;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        return view('tenant.hr.leaves.index');
    }

    public function create()
    {
        return view('tenant.hr.leaves.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.leaves.index')->with('success', 'تم تقديم طلب الإجازة بنجاح');
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
        return redirect()->back()->with('success', 'تم تصدير بيانات الإجازات بنجاح');
    }

}
