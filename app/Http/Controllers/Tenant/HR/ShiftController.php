<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        return view('tenant.hr.shifts.index');
    }

    public function create()
    {
        return view('tenant.hr.shifts.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.shifts.index')->with('success', 'تم إنشاء المناوبة بنجاح');
    }

    public function show($id)
    {
        return view('tenant.hr.shifts.show');
    }

    public function edit($id)
    {
        return view('tenant.hr.shifts.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.hr.shifts.show', $id)->with('success', 'تم تحديث المناوبة بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.hr.shifts.index')->with('success', 'تم حذف المناوبة بنجاح');
    }

    public function assignments()
    {
        return view('tenant.hr.shifts.assignments');
    }

    public function assignEmployees(Request $request)
    {
        return redirect()->route('tenant.hr.shifts.assignments')->with('success', 'تم تعيين الموظفين للمناوبة بنجاح');
    }

    public function schedule()
    {
        return view('tenant.hr.shifts.schedule');
    }
}
