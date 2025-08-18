@extends('layouts.modern')

@section('title', 'عرض المنصب')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <h1 style="margin:0; color:#1f2937; font-size:24px; font-weight:800;">{{ $position->title }}</h1>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('tenant.hr.positions.edit', $position->id) }}" style="background:#ed8936; color:#fff; padding:10px 12px; border-radius:10px; text-decoration:none;">تعديل</a>
                <a href="{{ route('tenant.hr.positions.index') }}" style="background:#e5e7eb; color:#111827; padding:10px 12px; border-radius:10px; text-decoration:none;">رجوع</a>
            </div>
        </div>
        <div style="margin-top:8px; color:#6b7280;">الكود: {{ $position->code }} | القسم: {{ optional($position->department)->name }}</div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:16px;">
            <div style="color:#374151; font-weight:700; margin-bottom:8px;">معلومات أساسية</div>
            <div>المستوى: {{ \App\Models\Tenant\HR\Position::POSITION_LEVELS[$position->level] ?? $position->level }}</div>
            <div>يرفع تقاريره إلى: {{ optional($position->reportsTo)->title ?? '-' }}</div>
            <div>الحالة: {{ $position->is_active ? 'نشط' : 'غير نشط' }}</div>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:16px;">
            <div style="color:#374151; font-weight:700; margin-bottom:8px;">الرواتب</div>
            <div>الأدنى: {{ $position->min_salary }}</div>
            <div>الأعلى: {{ $position->max_salary }}</div>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:16px; grid-column: 1 / -1;">
            <div style="color:#374151; font-weight:700; margin-bottom:8px;">الوصف الوظيفي</div>
            <div style="white-space:pre-line; color:#374151;">{{ $position->description ?: '-' }}</div>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:16px;">
            <div style="color:#374151; font-weight:700; margin-bottom:8px;">المتطلبات</div>
            <ul style="margin:0; padding-right:18px; color:#374151;">
                @forelse(($position->requirements ?? []) as $item)
                    <li>{{ $item }}</li>
                @empty
                    <li>-</li>
                @endforelse
            </ul>
        </div>
        <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:16px;">
            <div style="color:#374151; font-weight:700; margin-bottom:8px;">المهام والمسؤوليات</div>
            <ul style="margin:0; padding-right:18px; color:#374151;">
                @forelse(($position->responsibilities ?? []) as $item)
                    <li>{{ $item }}</li>
                @empty
                    <li>-</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

