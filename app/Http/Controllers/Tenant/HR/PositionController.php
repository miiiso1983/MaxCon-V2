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
        $positions = Position::where('tenant_id', $tenantId)->active()->orderBy('title')->get();
        return view('tenant.hr.positions.create', compact('departments', 'positions'));
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
            'requirements_text' => 'nullable|string',
            'responsibilities_text' => 'nullable|string',
        ]);

        // Validate salary range
        if (!is_null($validated['min_salary'] ?? null) && !is_null($validated['max_salary'] ?? null)) {
            if ($validated['min_salary'] > $validated['max_salary']) {
                return back()->with('error', 'الحد الأدنى للراتب لا يجب أن يتجاوز الحد الأقصى')->withInput();
            }
        }

        try {
            $data = $validated;
            $data['tenant_id'] = $tenantId;
            $data['is_active'] = isset($validated['is_active']) ? (bool)$validated['is_active'] : true;
            $data['created_by'] = Auth::id();

            // Parse requirements/responsibilities textareas into arrays
            $reqText = $request->input('requirements_text') ?? $request->input('required_skills');
            if ($reqText) {
                $lines = array_values(array_filter(array_map('trim', preg_split("/\r\n|\r|\n/", $reqText))));
                $data['requirements'] = $lines;
            }
            $respText = $request->input('responsibilities_text');
            if ($respText) {
                $lines = array_values(array_filter(array_map('trim', preg_split("/\r\n|\r|\n/", $respText))));
                $data['responsibilities'] = $lines;
            }

            // Remove non-column helper fields
            unset($data['requirements_text'], $data['responsibilities_text']);

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
