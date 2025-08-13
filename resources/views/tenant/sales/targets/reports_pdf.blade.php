<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير تحليلات أهداف البيع</title>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
        body { direction: rtl; font-family: 'Cairo', 'Amiri', 'DejaVu Sans', sans-serif; }
        h1 { font-size: 18px; margin: 0 0 15px 0; text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; font-size: 12px; text-align: right; }
        th { background: #f3f4f6; }
        /* أرقام داخل خلايا تبقى LTR عند الحاجة */
        .ltr { direction: ltr; unicode-bidi: embed; }

    </style>
</head>
<body>
    <h1>تقرير تحليلات أهداف البيع - {{ $year }}</h1>
    <table>
        <thead>
            <tr>
                <th>العنوان</th>
                <th>الجهة</th>
                <th>النوع</th>
                <th>الفترة</th>
                <th>من</th>
                <th>إلى</th>
                <th>القياس</th>
                <th>الكمية المستهدفة</th>
                <th>القيمة المستهدفة</th>
                <th>النسبة المحققة</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($targets as $t)
            <tr>
                <td>{{ $t->title }}</td>
                <td>{{ $t->target_entity_name }}</td>
                <td>{{ $t->target_type }}</td>
                <td>{{ $t->period_type }}</td>
                <td>{{ optional($t->start_date)->format('Y-m-d') }}</td>
                <td>{{ optional($t->end_date)->format('Y-m-d') }}</td>
                <td>{{ $t->measurement_type }}</td>
                <td>{{ $t->target_quantity }}</td>
                <td>{{ $t->target_value }}</td>
                <td>{{ $t->progress_percentage }}%</td>
                <td>{{ $t->status_text }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

