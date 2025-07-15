<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        return view('tenant.hr.payroll.index');
    }

    public function create()
    {
        return view('tenant.hr.payroll.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم إنشاء كشف الراتب بنجاح');
    }

    public function show($id)
    {
        return view('tenant.hr.payroll.show');
    }

    public function edit($id)
    {
        return view('tenant.hr.payroll.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم تحديث كشف الراتب بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم حذف كشف الراتب بنجاح');
    }

    public function generatePayroll(Request $request)
    {
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم إنتاج كشوف الرواتب بنجاح');
    }

    public function approve($id)
    {
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم اعتماد كشف الراتب بنجاح');
    }

    public function markAsPaid(Request $request, $id)
    {
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم تسجيل دفع الراتب بنجاح');
    }

    public function reports()
    {
        return view('tenant.hr.payroll.reports');
    }

    public function payslip($id)
    {
        return view('tenant.hr.payroll.payslip');
    }

    public function export($period)
    {
        return redirect()->back()->with('info', 'ميزة التصدير قيد التطوير');
    }

    public function payrollReport()
    {
        return view('tenant.hr.reports.payroll');
    }
}
