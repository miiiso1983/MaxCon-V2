<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\HR\LeaveType;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $leaveTypes = LeaveType::where('tenant_id', $tenantId)->orderBy('name')->paginate(20);
        return view('tenant.hr.leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('tenant.hr.leave-types.create');
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_english' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'days_per_year' => 'required|integer|min:0|max:365',
            'max_consecutive_days' => 'nullable|integer|min:0|max:365',
            'min_notice_days' => 'nullable|integer|min:0|max:60',
            'is_paid' => 'nullable|boolean',
            'requires_approval' => 'nullable|boolean',
            'requires_attachment' => 'nullable|boolean',
            'carry_forward' => 'nullable|boolean',
            'gender_specific' => 'required|in:all,male,female',
            'applicable_after_months' => 'nullable|integer|min:0|max:60',
            'color' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
        ]);

        $code = $data['code'] ?? LeaveType::generateLeaveTypeCode($tenantId);

        LeaveType::create(array_merge($data, [
            'tenant_id' => $tenantId,
            'code' => $code,
            'is_paid' => (bool)($data['is_paid'] ?? true),
            'requires_approval' => (bool)($data['requires_approval'] ?? true),
            'requires_attachment' => (bool)($data['requires_attachment'] ?? false),
            'carry_forward' => (bool)($data['carry_forward'] ?? false),
            'is_active' => (bool)($data['is_active'] ?? true),
        ]));

        return redirect()->route('tenant.hr.leave-types.index')->with('success', 'تم إنشاء نوع الإجازة بنجاح');
    }

    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $leaveType = LeaveType::where('tenant_id', $tenantId)->findOrFail($id);
        return view('tenant.hr.leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $leaveType = LeaveType::where('tenant_id', $tenantId)->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_english' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'days_per_year' => 'required|integer|min:0|max:365',
            'max_consecutive_days' => 'nullable|integer|min:0|max:365',
            'min_notice_days' => 'nullable|integer|min:0|max:60',
            'is_paid' => 'nullable|boolean',
            'requires_approval' => 'nullable|boolean',
            'requires_attachment' => 'nullable|boolean',
            'carry_forward' => 'nullable|boolean',
            'gender_specific' => 'required|in:all,male,female',
            'applicable_after_months' => 'nullable|integer|min:0|max:60',
            'color' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
        ]);

        $leaveType->update(array_merge($data, [
            'is_paid' => (bool)($data['is_paid'] ?? $leaveType->is_paid),
            'requires_approval' => (bool)($data['requires_approval'] ?? $leaveType->requires_approval),
            'requires_attachment' => (bool)($data['requires_attachment'] ?? $leaveType->requires_attachment),
            'carry_forward' => (bool)($data['carry_forward'] ?? $leaveType->carry_forward),
            'is_active' => (bool)($data['is_active'] ?? $leaveType->is_active),
        ]));

        return redirect()->route('tenant.hr.leave-types.index')->with('success', 'تم تحديث نوع الإجازة بنجاح');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $leaveType = LeaveType::where('tenant_id', $tenantId)->findOrFail($id);
        $leaveType->delete();
        return redirect()->route('tenant.hr.leave-types.index')->with('success', 'تم حذف نوع الإجازة بنجاح');
    }
}
