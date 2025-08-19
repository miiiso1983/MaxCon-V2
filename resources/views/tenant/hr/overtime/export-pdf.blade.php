<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير الساعات الإضافية</title>
    <style>
        @page { size: A4 portrait; margin: 20mm 15mm; }
        body { font-family: 'XBRiyaz', 'XB Riyaz', 'dejavusans', 'DejaVu Sans', 'Amiri', sans-serif; direction: rtl; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h1 { font-size: 18px; margin: 0; }
        .meta { font-size: 11px; color: #555; text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        thead th { background: #f1f5f9; font-weight: bold; text-align: center; }
        tbody td { text-align: center; }
        .totals { margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الساعات الإضافية</h1>
    </div>
    <div class="meta">
        تم التوليد بتاريخ: {{ now()->format('Y-m-d H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>الموظف</th>
                <th>التاريخ</th>
                <th>الساعات</th>
                <th>معدل الإضافي</th>
                <th>المبلغ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($overtimes as $ot)
                <tr>
                    <td>{{ $ot->employee?->full_name ?? '-' }}</td>
                    <td>{{ optional($ot->date)->format('Y-m-d') }}</td>
                    <td>{{ number_format($ot->hours_approved ?? $ot->hours_requested, 2) }}</td>
                    <td>{{ number_format($ot->overtime_rate, 2) }}</td>
                    <td>{{ number_format($ot->total_amount, 2) }}</td>
                    <td>{{ $ot->status_label }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        الإجمالي: {{ number_format($overtimes->sum('total_amount'), 2) }}
    </div>
</body>
</html>

