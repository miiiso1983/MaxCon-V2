<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index()
    {
        return view('tenant.hr.overtime.index');
    }

    public function create()
    {
        return view('tenant.hr.overtime.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.overtime.index')->with('success', 'تم تسجيل الساعات الإضافية بنجاح');
    }

    public function show($id)
    {
        return view('tenant.hr.overtime.show');
    }

    public function edit($id)
    {
        return view('tenant.hr.overtime.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.hr.overtime.index')->with('success', 'تم تحديث الساعات الإضافية بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.hr.overtime.index')->with('success', 'تم حذف سجل الساعات الإضافية بنجاح');
    }

    public function approve($id)
    {
        return redirect()->route('tenant.hr.overtime.index')->with('success', 'تم الموافقة على الساعات الإضافية بنجاح');
    }

    public function reject(Request $request, $id)
    {
        return redirect()->route('tenant.hr.overtime.index')->with('success', 'تم رفض الساعات الإضافية');
    }

    public function reports()
    {
        return view('tenant.hr.overtime.reports');
    }

    public function overtimeReport()
    {
        return view('tenant.hr.reports.overtime');
    }
}
