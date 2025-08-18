@extends('layouts.modern')

@section('title', 'أنواع الإجازات')

@section('content')
<div style="padding:30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height:100vh;">
    <div style="background: rgba(255,255,255,0.95); border-radius:20px; padding:24px; margin-bottom:20px; box-shadow:0 12px 24px rgba(0,0,0,0.08);">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
            <div>
                <h1 style="margin:0; color:#1f2937; font-size:24px; font-weight:800;">أنواع الإجازات</h1>
                <div style="color:#6b7280; font-size:14px;">إدارة أنواع الإجازات، عدد الأيام والقواعد</div>
            </div>
            <a href="{{ route('tenant.hr.leave-types.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-plus"></i> إنشاء نوع جديد
            </a>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:16px;">
        @forelse($leaveTypes as $type)
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="width:14px; height:14px; border-radius:50%; background: {{ $type->color ?? '#4299e1' }};"></span>
                        <div>
                            <div style="font-weight:800; color:#111827;">{{ $type->name }}</div>
                            <div style="color:#6b7280; font-size:12px;">{{ $type->code }}</div>
                        </div>
                    </div>
                    <span style="background: {{ $type->is_active ? '#48bb78' : '#e5e7eb' }}; color: white; padding:4px 8px; border-radius:8px; font-size:12px;">{{ $type->is_active ? 'مفعل' : 'متوقف' }}</span>
                </div>
                <div style="color:#374151; font-size:14px; margin-bottom:12px;">{{ Str::limit($type->description, 120) }}</div>
                <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:8px;">
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:10px; text-align:center;">
                        <div style="font-size:12px; color:#6b7280;">أيام/سنة</div>
                        <div style="font-weight:800; color:#111827;">{{ $type->days_per_year }}</div>
                    </div>
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:10px; text-align:center;">
                        <div style="font-size:12px; color:#6b7280;">متتالية كحد أقصى</div>
                        <div style="font-weight:800; color:#111827;">{{ $type->max_consecutive_days ?? '-' }}</div>
                    </div>
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:10px; text-align:center;">
                        <div style="font-size:12px; color:#6b7280;">إشعار مسبق (يوم)</div>
                        <div style="font-weight:800; color:#111827;">{{ $type->min_notice_days ?? 0 }}</div>
                    </div>
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:10px; text-align:center;">
                        <div style="font-size:12px; color:#6b7280;">محدّد بالجنـس</div>
                        <div style="font-weight:800; color:#111827;">{{ $type->gender_specific_label }}</div>
                    </div>
                </div>

                <div style="display:flex; gap:8px; margin-top:12px;">
                    <a href="{{ route('tenant.hr.leave-types.edit', $type) }}" style="background:#4299e1; color:white; padding:8px 12px; border-radius:10px; text-decoration:none; font-size:12px; display:flex; align-items:center; gap:6px;"><i class="fas fa-edit"></i> تعديل</a>
                    <form action="{{ route('tenant.hr.leave-types.destroy', $type) }}" method="POST" onsubmit="return confirm('تأكيد حذف النوع؟')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:#f56565; color:white; padding:8px 12px; border-radius:10px; font-size:12px; display:flex; align-items:center; gap:6px; border:none; cursor:pointer;"><i class="fas fa-trash"></i> حذف</button>
                    </form>
                </div>
            </div>
        @empty
            <div style="background:#fff; border:1px dashed #e5e7eb; border-radius:14px; padding:20px; text-align:center; color:#6b7280;">لا توجد أنواع إجازات بعد</div>
        @endforelse
    </div>

    <div style="margin-top:16px;">
        {{ $leaveTypes->links() }}
    </div>
</div>
@endsection

