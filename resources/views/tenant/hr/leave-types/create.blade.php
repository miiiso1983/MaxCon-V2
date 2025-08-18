@extends('layouts.modern')

@section('title', 'إنشاء نوع إجازة')

@section('content')
<div style="padding:30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height:100vh;">
    <div style="background: rgba(255,255,255,0.95); border-radius:20px; padding:24px; margin-bottom:20px; box-shadow:0 12px 24px rgba(0,0,0,0.08);">
        <h1 style="margin:0; color:#1f2937; font-size:24px; font-weight:800;">إنشاء نوع إجازة</h1>
    </div>

    <form action="{{ route('tenant.hr.leave-types.store') }}" method="POST" style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:16px;">
        @csrf
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
            <div>
                <label>الاسم</label>
                <input name="name" required style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label>الاسم بالإنجليزية</label>
                <input name="name_english" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label>الكود</label>
                <input name="code" placeholder="يُولد تلقائياً إن تُرك فارغاً" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label>الأيام المسموحة سنوياً</label>
                <input type="number" name="days_per_year" value="30" min="0" max="365" required style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label>أقصى أيام متتالية</label>
                <input type="number" name="max_consecutive_days" min="0" max="365" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label>إشعار مسبق (أيام)</label>
                <input type="number" name="min_notice_days" min="0" max="60" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label>النوع مخصص لجنس</label>
                <select name="gender_specific" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
                    @foreach(\App\Models\Tenant\HR\LeaveType::GENDER_SPECIFIC as $key=>$label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>تطبيق بعد (أشهر)</label>
                <input type="number" name="applicable_after_months" min="0" max="60" style="width:100%; padding:10px; border:1px solid #e5e7eb; border-radius:10px;">
            </div>
            <div>
                <label>لون</label>
                <input type="color" name="color" value="#4299e1" style="width:100%; padding:8px; border:1px solid #e5e7eb; border-radius:10px; height:42px;">
            </div>
        </div>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-top:14px;">
            <label><input type="checkbox" name="is_paid" value="1" checked> مدفوعة</label>
            <label><input type="checkbox" name="requires_approval" value="1" checked> تتطلب موافقة</label>
            <label><input type="checkbox" name="requires_attachment" value="1"> تتطلب مرفق</label>
            <label><input type="checkbox" name="carry_forward" value="1"> ترحيل الرصيد</label>
            <label><input type="checkbox" name="is_active" value="1" checked> مفعلة</label>
        </div>

        <div style="margin-top:16px; display:flex; gap:10px; justify-content:flex-end;">
            <a href="{{ route('tenant.hr.leave-types.index') }}" style="background:#e5e7eb; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none;">إلغاء</a>
            <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color:white; padding:10px 14px; border-radius:10px; border:none; font-weight:700;">حفظ</button>
        </div>
    </form>
</div>
@endsection

