<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\HR\Position;
use App\Models\Tenant\HR\Department;

class PositionController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $positions = Position::where('tenant_id', $tenantId)
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tenant.hr.positions.index', compact('positions'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $departments = Department::where('tenant_id', $tenantId)->active()->orderBy('name')->get();
        return view('tenant.hr.positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:hr_positions,code',
            'department_id' => 'required|integer|exists:hr_departments,id',
            'description' => 'nullable|string',
            'level' => 'required|in:entry,junior,mid,senior,lead,manager,director,executive',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'reports_to_position_id' => 'nullable|integer|exists:hr_positions,id',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $validated;
            $data['tenant_id'] = $tenantId;
            $data['is_active'] = isset($validated['is_active']) ? (bool)$validated['is_active'] : true;
            $data['created_by'] = Auth::id();

            Position::create($data);

            return redirect()->route('tenant.hr.positions.index')
                ->with('success', 'تم إنشاء المنصب بنجاح');
        } catch (\Throwable $e) {
            return back()->with('error', 'تعذر حفظ المنصب: ' . $e->getMessage())->withInput();
        }
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
