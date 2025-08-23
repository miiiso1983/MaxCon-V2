<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Warning;
use App\Models\Tenant\HR\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WarningController extends Controller
{
    public function index(Request $request): View
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $query = Warning::where('tenant_id', $tenantId)->with('employee')->orderByDesc('date');
        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('date_from') && $request->filled('date_to')) $query->whereBetween('date', [$request->date_from, $request->date_to]);
        $warnings = $query->paginate(15)->appends($request->query());
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.warnings.index', compact('warnings','employees'));
    }

    public function create(): View
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.warnings.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'type' => 'required|string',
            'date' => 'required|date',
            'reason' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
        ]);
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        Warning::create([
            'tenant_id' => $tenantId,
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'date' => $request->date,
            'reason' => $request->reason,
            'severity' => $request->severity,
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('tenant.hr.warnings.index')->with('success', 'تم تسجيل الإنذار بنجاح');
    }

    public function edit(Warning $warning): View
    {
        $this->authorize('update', $warning);
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.warnings.edit', compact('warning','employees'));
    }

    public function update(Request $request, Warning $warning)
    {
        $request->validate([
            'type' => 'required|string',
            'date' => 'required|date',
            'reason' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'escalated' => 'nullable|boolean',
        ]);
        $warning->update($request->only(['type','date','reason','severity','escalated']) + ['updated_by' => auth()->id()]);
        return redirect()->route('tenant.hr.warnings.index')->with('success', 'تم تحديث الإنذار بنجاح');
    }

    public function destroy(Warning $warning)
    {
        $this->authorize('delete', $warning);
        $warning->delete();
        return back()->with('success', 'تم حذف السجل بنجاح');
    }

    public function reports(Request $request): View
    {
        return $this->index($request);
    }

    public function export(Request $request): BinaryFileResponse
    {
        // Placeholder: implement Excel export via Maatwebsite later
        abort(501, 'Export to Excel is under development');
    }
}

