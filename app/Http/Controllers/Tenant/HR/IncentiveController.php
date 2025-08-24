<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Incentive;
use App\Models\Tenant\HR\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IncentiveController extends Controller
{
    public function index(Request $request): View
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $query = Incentive::where('tenant_id', $tenantId)->with('employee')->orderByDesc('date');
        if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('date_from') && $request->filled('date_to')) $query->whereBetween('date', [$request->date_from, $request->date_to]);
        $incentives = $query->paginate(15)->appends($request->query());
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.incentives.index', compact('incentives','employees'));
    }

    public function create(): View
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.incentives.create', compact('employees'));
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
        Incentive::create([
            'tenant_id' => $tenantId,
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'reason' => $request->reason,
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('tenant.hr.incentives.index')->with('success', 'تم إضافة الحافز/المكافأة بنجاح');
    }

    public function edit(Incentive $incentive): View
    {
        $this->authorize('update', $incentive);
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employees = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get(['id','first_name','last_name']);
        return view('tenant.hr.incentives.edit', compact('incentive','employees'));
    }

    public function update(Request $request, Incentive $incentive)
    {
        $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'reason' => 'nullable|string',
        ]);
        $incentive->update($request->only(['type','amount','date','reason']) + ['updated_by' => auth()->id()]);
        return redirect()->route('tenant.hr.incentives.index')->with('success', 'تم تحديث الحافز/المكافأة بنجاح');
    }

    public function destroy(Incentive $incentive)
    {
        $this->authorize('delete', $incentive);
        $incentive->delete();
        return back()->with('success', 'تم حذف السجل بنجاح');
    }

    public function reports(Request $request): View
    {
        return $this->index($request);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $filters = $request->only(['employee_id', 'type', 'date_from', 'date_to']);
        $export = new \App\Exports\HR\IncentivesExport($tenantId, $filters);
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'incentives_'.now()->format('Ymd_His').'.xlsx');
    }
}

