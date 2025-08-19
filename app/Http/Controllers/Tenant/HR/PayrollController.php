<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\HR\Employee;
use App\Models\Tenant\HR\Payroll;
use App\Models\Tenant\HR\Overtime;
use App\Services\HR\HrPdfService;

class PayrollController extends Controller
{
    public function index()
    {
        return view('tenant.hr.payroll.index');
    }

    public function create()
    {
        return view('tenant.hr.payroll.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم إنشاء كشف الراتب بنجاح');
    }

    public function show($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('tenant.hr.payroll.show', compact('payroll'));
    }

    public function edit($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('tenant.hr.payroll.edit', compact('payroll'));
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->fill($request->only([
            'bonus','loan_deduction','other_deductions','insurance_deduction'
        ]));
        $payroll->save();
        return back()->with('success', 'تم تحديث كشف الراتب بنجاح');
    }

    public function destroy($id)
    {
        Payroll::where('id', $id)->delete();
        return redirect()->route('tenant.hr.payroll.index')->with('success', 'تم حذف كشف الراتب بنجاح');
    }

    // معالجة الرواتب لفترة محددة (يدعم معالجة موظف واحد عبر employee_id)
    public function generatePayroll(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $period = $request->input('period', now()->format('Y-m'));
        [$year, $month] = explode('-', $period);
        $month = (int)$month; $year = (int)$year;

        $employeeId = $request->input('employee_id');
        $employeesQuery = Employee::where('tenant_id', $tenantId)->active();
        if ($employeeId) { $employeesQuery->where('id', $employeeId); }
        $employees = $employeesQuery->get();

        $processed = 0;
        foreach ($employees as $emp) {
            $basic = $emp->basic_salary ?? 0;

            // اجمع ساعات الإضافي المعتمدة
            $hours = Overtime::where('tenant_id', $tenantId)
                ->where('employee_id', $emp->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'approved')
                ->sum('hours_approved');

            // أنشئ أو حدّث سجل الراتب بدون حفظ فوري لضبط الحقول المطلوبة
            $payroll = Payroll::firstOrNew([
                'employee_id' => $emp->id,
                'month' => $month,
                'year' => $year,
            ]);

            $payroll->tenant_id = $tenantId;
            $payroll->payroll_period = $period;
            $payroll->basic_salary = $basic;
            $payroll->overtime_hours = $hours ?: 0;

            // تأكد من تهيئة الحقول العددية المطلوبة لتفادي null
            $payroll->bonus = $payroll->bonus ?? 0;
            $payroll->overtime_amount = $payroll->overtime_amount ?? 0;
            $payroll->gross_salary = $payroll->gross_salary ?? 0;
            $payroll->tax_amount = $payroll->tax_amount ?? 0;
            $payroll->social_security = $payroll->social_security ?? 0;
            $payroll->insurance_deduction = $payroll->insurance_deduction ?? 0;
            $payroll->loan_deduction = $payroll->loan_deduction ?? 0;
            $payroll->other_deductions = $payroll->other_deductions ?? 0;
            $payroll->total_deductions = $payroll->total_deductions ?? 0;
            $payroll->net_salary = $payroll->net_salary ?? 0;
            $payroll->late_hours = $payroll->late_hours ?? 0;
            $payroll->present_days = $payroll->present_days ?? 0;
            $payroll->absent_days = $payroll->absent_days ?? 0;
            $payroll->leave_days = $payroll->leave_days ?? 0;

            // JSON الحقول
            $payroll->allowances = $payroll->allowances ?? [];
            $payroll->deductions = $payroll->deductions ?? [];

            // احسب بيانات الحضور وأيام العمل قبل الحفظ لضمان ملء working_days
            $payroll->calculateAttendanceData();

            // احسب مبالغ الراتب
            $payroll->calculateOvertimeAmount();
            $payroll->calculateGrossSalary();
            $payroll->calculateTaxAmount();
            $payroll->calculateSocialSecurity();
            $payroll->calculateNetSalary();

            $payroll->status = 'calculated';
            $payroll->save();
            $processed++;
        }

        return response()->json([
            'success' => true,
            'message' => 'تمت معالجة الرواتب بنجاح للفترة ' . $period,
            'processed' => $processed,
            'period' => $period
        ]);
    }

    public function approve($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->approve(Auth::id());
        return response()->json(['success' => true, 'message' => 'تم اعتماد كشف الراتب']);
    }

    public function markAsPaid(Request $request, $id)
    {
        $method = $request->input('payment_method', 'bank_transfer');
        $ref = $request->input('bank_reference');
        $payroll = Payroll::findOrFail($id);
        $payroll->markAsPaid($method, $ref);
        return response()->json(['success' => true, 'message' => 'تم تسجيل دفع الراتب']);
    }

    public function reports()
    {
        return view('tenant.hr.payroll.reports');
    }

    // عرض كشف راتب HTML (للمعاينة داخل النظام)
    public function payslip($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('tenant.hr.payroll.payslip', compact('payroll'));
    }

    // طباعة/عرض PDF لكشف راتب موظف حسب الفترة
    public function printPayslip(Request $request, $employeeId)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $period = $request->input('period', now()->format('Y-m'));
        [$year, $month] = explode('-', $period); $month = (int)$month; $year = (int)$year;

        $payroll = Payroll::where('employee_id', $employeeId)->where('month', $month)->where('year', $year)->first();
        if (!$payroll) {
            // إن لم يوجد، أنشئ كشف راتب سريعًا لهذا الموظف
            $this->generatePayroll(new Request(['period' => $period, 'employee_id' => $employeeId]));
            $payroll = Payroll::where('employee_id', $employeeId)->where('month', $month)->where('year', $year)->first();
        }
        // تأكد من اكتمال الحقول المطلوبة (خاصة working_days) قبل الطباعة
        if ($payroll) {
            $payroll->calculateAttendanceData();
            $payroll->calculateOvertimeAmount();
            $payroll->calculateGrossSalary();
            $payroll->calculateTaxAmount();
            $payroll->calculateSocialSecurity();
            $payroll->calculateNetSalary();
            $payroll->save();
        } else {
            abort(404);
        }

        $pdfContent = app(HrPdfService::class)->render('tenant.hr.payroll.payslip-pdf', [
            'payroll' => $payroll->load('employee')
        ], 'كشف راتب', 'P');

        $disposition = $request->boolean('inline', true) ? 'inline' : 'attachment';
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="payslip_' . $period . '_emp' . $employeeId . '.pdf"'
        ]);
    }

    // إرسال كشف الراتب بالبريد (توليد PDF وتخزينه، التنفيذ الفعلي للبريد يُضاف لاحقًا)
    public function sendPayslip(Request $request, $employeeId)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $period = $request->input('period', now()->format('Y-m'));
        [$year, $month] = explode('-', $period); $month = (int)$month; $year = (int)$year;

        $payroll = Payroll::where('employee_id', $employeeId)->where('month', $month)->where('year', $year)->first();
        if (!$payroll) {
            $this->generatePayroll(new Request(['period' => $period, 'employee_id' => $employeeId]));
            $payroll = Payroll::where('employee_id', $employeeId)->where('month', $month)->where('year', $year)->first();
        }
        if ($payroll) {
            $payroll->calculateAttendanceData();
            $payroll->calculateOvertimeAmount();
            $payroll->calculateGrossSalary();
            $payroll->calculateTaxAmount();
            $payroll->calculateSocialSecurity();
            $payroll->calculateNetSalary();
            $payroll->save();
        } else {
            return response()->json(['success' => false, 'message' => 'تعذر العثور على كشف راتب للفترة المحددة'], 404);
        }

        $pdfContent = app(HrPdfService::class)->render('tenant.hr.payroll.payslip-pdf', [
            'payroll' => $payroll->load('employee')
        ], 'كشف راتب', 'P');

        $path = 'hr/payslips/payslip_' . $period . '_emp' . $employeeId . '.pdf';
        Storage::disk('public')->put($path, $pdfContent);

        // TODO: تنفيذ الإرسال عبر البريد/الواتساب
        Log::info('Payslip generated and stored', ['path' => $path, 'employee_id' => $employeeId, 'period' => $period]);

        return response()->json(['success' => true, 'message' => 'تم توليد كشف الراتب وإرساله (تجريبي)', 'file' => Storage::url($path)]);
    }

    public function export($period)
    {
        return redirect()->back()->with('info', 'ميزة التصدير قيد التطوير');
    }

    public function payrollReport()
    {
        return view('tenant.hr.reports.payroll');
    }
}
