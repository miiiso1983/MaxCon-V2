<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Deduction;
use App\Models\Tenant\HR\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DeductionController extends Controller
{
    public function index(Request $request): View
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $query = Deduction::where('tenant_id', $tenantId)->with('employee')->orderByDesc('date');
        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('date_from') && $request->filled('date_to')) $query->whereBetween('date', [$request->date_from, $request->date_to]);
        $deductions = $query->paginate(15)->appends($request->query());
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.deductions.index', compact('deductions','employees'));
    }

    public function create(): View
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.deductions.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        Deduction::create([
            'tenant_id' => $tenantId,
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'reason' => $request->reason,
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('tenant.hr.deductions.index')->with('success', 'تم إضافة الخصم بنجاح');
    }

    public function edit(Deduction $deduction): View
    {
        $this->authorize('update', $deduction);
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.deductions.edit', compact('deduction','employees'));
    }

    public function update(Request $request, Deduction $deduction)
    {
        $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);
        $deduction->update($request->only(['type','amount','date','reason']) + ['updated_by' => auth()->id()]);
        return redirect()->route('tenant.hr.deductions.index')->with('success', 'تم تحديث الخصم بنجاح');
    }

    public function destroy(Deduction $deduction)
    {
        $this->authorize('delete', $deduction);
        $deduction->delete();
        return back()->with('success', 'تم حذف الخصم بنجاح');
    }

    public function reports(Request $request): View
    {
        return $this->index($request);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $filters = $request->only(['employee_id', 'type', 'date_from', 'date_to']);
        $export = new \App\Exports\HR\DeductionsExport($tenantId, $filters);
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'deductions_'.now()->format('Ymd_His').'.xlsx');
    }
}

