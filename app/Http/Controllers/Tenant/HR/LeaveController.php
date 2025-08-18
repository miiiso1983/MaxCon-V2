<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return redirect()->route('tenant.hr.leaves.index')->with('success', 'تم الموافقة على الإجازة بنجاح');
    }

        // Persist reject reason if provided later

    public function reject(Request $request, $id)
    {
        return redirect()->route('tenant.hr.leaves.index')->with('success', 'تم رفض الإجازة');
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
