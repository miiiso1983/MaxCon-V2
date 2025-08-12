@extends('layouts.modern')

@section('page-title', 'تعديل طلب الإرجاع')
@section('page-description', 'تعديل بيانات طلب الإرجاع')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-edit" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            تعديل طلب الإرجاع #{{ $return->return_number ?? $return->id }}
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تحديث بيانات الطلب (التاريخ، السبب، الملاحظات، طريقة الاسترداد)
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.returns.show', $return) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-eye"></i>
                    عرض الطلب
                </a>
                <a href="{{ route('tenant.sales.returns.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.sales.returns.update', $return) }}">
    @csrf
    @method('PUT')

    @if ($errors->any())
        <div style="margin-bottom: 20px; padding: 12px; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 8px;">
            <div style="font-weight:700; margin-bottom:8px; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-exclamation-triangle"></i>
                يوجد أخطاء في النموذج، يرجى المراجعة:
            </div>
            <ul style="list-style: inside; margin: 0; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li style="margin: 4px 0;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #a78bfa; margin-left: 10px;"></i>
            تفاصيل الإرجاع
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الإرجاع *</label>
                <input type="date" name="return_date" value="{{ old('return_date', optional($return->return_date)->format('Y-m-d')) }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                @error('return_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع العملية *</label>
                <input type="text" value="{{ $return->type === 'exchange' ? 'استبدال' : 'إرجاع' }}" readonly style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طريقة الاسترداد</label>
                <select name="refund_method" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر طريقة الاسترداد</option>
                    <option value="cash" {{ old('refund_method', $return->refund_method) === 'cash' ? 'selected' : '' }}>نقداً</option>
                    <option value="bank_transfer" {{ old('refund_method', $return->refund_method) === 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                    <option value="credit_note" {{ old('refund_method', $return->refund_method) === 'credit_note' ? 'selected' : '' }}>رصيد إشعار دائن</option>
                    <option value="exchange" {{ old('refund_method', $return->refund_method) === 'exchange' ? 'selected' : '' }}>استبدال</option>
                </select>
                @error('refund_method')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سبب الإرجاع *</label>
            <textarea name="reason" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" required>{{ old('reason', $return->reason_description ?? $return->reason) }}</textarea>
            @error('reason')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات إضافية</label>
            <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 60px;">{{ old('notes', $return->notes) }}</textarea>
            @error('notes')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div style="display:flex; gap:10px;">
        <button type="submit" style="background: #4f46e5; color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
            حفظ التغييرات
        </button>
        <a href="{{ route('tenant.sales.returns.show', $return) }}" style="background: #e2e8f0; color: #1f2937; padding: 12px 20px; border-radius: 10px; font-weight: 700; text-decoration: none;">
            إلغاء
        </a>
    </div>
</form>
@endsection

