<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>كشف راتب</title>
    <style>
        body { font-family: 'dejavusans', 'DejaVu Sans', sans-serif; direction: rtl; color: #111827; }
        .section { margin-bottom: 10px; }
        .title { font-weight: 800; color: #1f2937; font-size: 16px; margin: 0 0 6px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; }
        thead th { background: #f1f5f9; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .box { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; }
    </style>
</head>
<body>
    <div class="section">
        <div class="title">بيانات الموظف</div>
        <div class="grid">
            <div class="box">الاسم: {{ $payroll->employee->full_name ?? '-' }}</div>
            <div class="box">الفترة: {{ $payroll->payroll_period }}</div>
            <div class="box">الراتب الأساسي: {{ number_format($payroll->basic_salary, 2) }}</div>
            <div class="box">الحالة: {{ $payroll->status_label ?? $payroll->status }}</div>
        </div>
    </div>

    <div class="section">
        <div class="title">تفاصيل الراتب</div>
        <table>
            <tbody>
                <tr>
                    <td>البدلات</td>
                    <td>{{ number_format(collect($payroll->allowances ?? [])->sum('amount'), 2) }}</td>
                </tr>
                <tr>
                    <td>ساعات إضافية</td>
                    <td>{{ number_format($payroll->overtime_hours, 2) }} ساعة ({{ number_format($payroll->overtime_amount, 2) }})</td>
                </tr>
                <tr>
                    <td>مكافآت</td>
                    <td>{{ number_format($payroll->bonus, 2) }}</td>
                </tr>
                <tr>
                    <td>إجمالي المستحقات</td>
                    <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>الضرائب + الضمان + خصومات أخرى</td>
                    <td>{{ number_format($payroll->total_deductions, 2) }}</td>
                </tr>
                <tr>
                    <td>صافي الراتب</td>
                    <td><strong>{{ number_format($payroll->net_salary, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="title">حضور وإجازات</div>
        <table>
            <tbody>
                <tr>
                    <td>أيام عمل</td>
                    <td>{{ (int)$payroll->working_days }}</td>
                </tr>
                <tr>
                    <td>حاضر</td>
                    <td>{{ (int)$payroll->present_days }}</td>
                </tr>
                <tr>
                    <td>غياب</td>
                    <td>{{ (int)$payroll->absent_days }}</td>
                </tr>
                <tr>
                    <td>إجازات</td>
                    <td>{{ (int)$payroll->leave_days }}</td>
                </tr>
                <tr>
                    <td>تأخيرات (ساعات)</td>
                    <td>{{ number_format($payroll->late_hours, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

