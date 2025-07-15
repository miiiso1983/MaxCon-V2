<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = collect([
            (object) ['id' => 1, 'name' => 'الإدارة العامة', 'code' => 'DEPT001', 'manager' => 'أحمد محمد', 'employees_count' => 5],
            (object) ['id' => 2, 'name' => 'الموارد البشرية', 'code' => 'DEPT002', 'manager' => 'سارة أحمد', 'employees_count' => 3],
            (object) ['id' => 3, 'name' => 'المالية والمحاسبة', 'code' => 'DEPT003', 'manager' => 'محمد علي', 'employees_count' => 4],
            (object) ['id' => 4, 'name' => 'المبيعات والتسويق', 'code' => 'DEPT004', 'manager' => 'فاطمة حسن', 'employees_count' => 8],
            (object) ['id' => 5, 'name' => 'تقنية المعلومات', 'code' => 'DEPT005', 'manager' => 'عمر خالد', 'employees_count' => 6],
        ]);
        
        return view('tenant.hr.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('tenant.hr.departments.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.departments.index')->with('success', 'تم إنشاء القسم بنجاح');
    }

    /**
     * Show organizational chart
     */
    public function chart()
    {
        return view('tenant.hr.departments.chart');
    }

    /**
     * Show departments reports
     */
    public function reports()
    {
        return view('tenant.hr.departments.reports');
    }

    /**
     * Display the specified department
     */
    public function show($id)
    {
        // For now, return a view with sample data since we don't have real departments yet
        return view('tenant.hr.departments.show');
    }

    /**
     * Show the form for editing the department
     */
    public function edit($id)
    {
        // For now, return a view with sample data since we don't have real departments yet
        return view('tenant.hr.departments.edit');
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, $id)
    {
        // For now, just redirect with success message since we don't have real departments yet
        return redirect()->route('tenant.hr.departments.show', $id)->with('success', 'تم تحديث بيانات القسم بنجاح');
    }

    /**
     * Remove the specified department
     */
    public function destroy($id)
    {
        // For now, just redirect with success message since we don't have real departments yet
        return redirect()->route('tenant.hr.departments.index')->with('success', 'تم حذف القسم بنجاح');
    }

    public function hierarchyChart()
    {
        return view('tenant.hr.departments.hierarchy');
    }
}
