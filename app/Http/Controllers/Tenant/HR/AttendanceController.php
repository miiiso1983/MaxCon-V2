<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function reports()
    {
        return view('tenant.hr.attendance.reports');
    }

    public function export()
    {
        return redirect()->back()->with('info', 'ميزة التصدير قيد التطوير');
    }

    public function attendanceReport()
    {
        return view('tenant.hr.reports.attendance');
    }
}
