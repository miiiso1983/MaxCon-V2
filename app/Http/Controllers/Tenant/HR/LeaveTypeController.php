<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        return view('tenant.hr.leave-types.index');
    }

    public function create()
    {
        return view('tenant.hr.leave-types.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.leave-types.index')->with('success', 'تم إنشاء نوع الإجازة بنجاح');
    }

    public function edit($id)
    {
        return view('tenant.hr.leave-types.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.hr.leave-types.index')->with('success', 'تم تحديث نوع الإجازة بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.hr.leave-types.index')->with('success', 'تم حذف نوع الإجازة بنجاح');
    }
}
