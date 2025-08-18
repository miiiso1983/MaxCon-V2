<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $departments = Department::where('tenant_id', $tenantId)
            ->withCount('employees')
            ->with('manager')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tenant.hr.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('tenant.hr.departments.create');
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:hr_departments,code',
            'parent_id' => 'nullable|integer|exists:hr_departments,id',
            'manager_id' => 'nullable|integer',
            'budget' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $validated;
            $data['tenant_id'] = $tenantId;
            $data['is_active'] = isset($validated['is_active']) ? (bool)$validated['is_active'] : true;
            $data['created_by'] = Auth::id();

            // If code empty, model boot() will generate one based on tenant
            Department::create($data);

            return redirect()->route('tenant.hr.departments.index')
                ->with('success', 'تم إنشاء القسم بنجاح');
        } catch (\Throwable $e) {
            return back()->with('error', 'تعذر حفظ القسم: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Export full departments workbook (list, contacts, finance, performance)
     */
    public function exportFull()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $export = new \App\Exports\DepartmentsFullExport($tenantId);
        $filename = 'Departments-' . now()->format('Y-m-d_H-i') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
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

    /**
     * Export org chart to PDF
     */
    public function exportChartPdf()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $departments = Department::where('tenant_id', $tenantId)->active()->orderBy('name')->get();
        $root = $departments->firstWhere('parent_id', null);
        $companyName = config('app.name', 'MaxCon');

        $html = view('tenant.hr.departments.chart-pdf', compact('departments', 'root', 'companyName'))->render();

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('a4', 'portrait');

        $filename = 'OrgChart-' . now()->format('Y-m-d_H-i') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export org chart to Excel
     */
    public function exportChartExcel()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $export = new \App\Exports\DepartmentsHierarchyExport($tenantId);
        $filename = 'OrgChart-' . now()->format('Y-m-d_H-i') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
    }

    /**
     * Export interactive HTML
     */
    public function exportChartHtml()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $departments = Department::where('tenant_id', $tenantId)->active()->orderBy('name')->get();
        $html = view('tenant.hr.departments.chart')->with(compact('departments'))->render();
        $filename = 'OrgChart-' . now()->format('Y-m-d_H-i') . '.html';
        return response($html)->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
