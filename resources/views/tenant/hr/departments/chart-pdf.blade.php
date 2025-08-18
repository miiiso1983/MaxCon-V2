@php($dir='rtl')
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20px; }
        body { font-family: 'dejavu sans', 'DejaVu Sans', sans-serif; direction: rtl; color:#111827; }
        .title { text-align:center; font-size:20px; margin-bottom:10px; }
        .subtitle { text-align:center; font-size:12px; color:#6b7280; margin-bottom:15px; }
        .box { border:1px solid #e5e7eb; border-radius:8px; padding:12px; margin-bottom:8px; }
        .node { display:inline-block; background:#8b5cf6; color:#fff; padding:8px 12px; border-radius:10px; font-size:12px; }
        .grid { display:flex; flex-wrap:wrap; gap:10px; }
    </style>
</head>
<body>
    <div class="title">الهيكل التنظيمي - {{ $companyName ?? 'الشركة' }}</div>
    <div class="subtitle">تاريخ التوليد: {{ now()->format('Y-m-d H:i') }}</div>

    <div class="box">
        <strong>الإدارة العليا</strong>
        <div class="grid" style="margin-top:6px;">
            <div class="node">{{ $root->name ?? 'المدير العام' }}</div>
        </div>
    </div>

    <div class="box">
        <strong>الأقسام</strong>
        <div class="grid" style="margin-top:6px;">
            @foreach($departments as $dept)
                <div class="node">{{ $dept->name }}</div>
            @endforeach
        </div>
    </div>
</body>
</html>

