<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = collect([
            (object) ['id' => 1, 'title' => 'مدير عام', 'department' => 'الإدارة العامة', 'level' => 'executive'],
            (object) ['id' => 2, 'title' => 'مدير الموارد البشرية', 'department' => 'الموارد البشرية', 'level' => 'manager'],
            (object) ['id' => 3, 'title' => 'محاسب', 'department' => 'المالية والمحاسبة', 'level' => 'mid'],
            (object) ['id' => 4, 'title' => 'مطور برمجيات', 'department' => 'تقنية المعلومات', 'level' => 'senior'],
        ]);
        
        return view('tenant.hr.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('tenant.hr.positions.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.positions.index')->with('success', 'تم إنشاء المنصب بنجاح');
    }

    /**
     * Show positions reports
     */
    public function reports()
    {
        return view('tenant.hr.positions.reports');
    }

    public function show($id)
    {
        $position = (object) ['id' => $id, 'title' => 'منصب تجريبي'];
        return view('tenant.hr.positions.show', compact('position'));
    }

    public function edit($id)
    {
        $position = (object) ['id' => $id, 'title' => 'منصب تجريبي'];
        return view('tenant.hr.positions.edit', compact('position'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.hr.positions.index')->with('success', 'تم تحديث المنصب بنجاح');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.hr.positions.index')->with('success', 'تم حذف المنصب بنجاح');
    }
}
