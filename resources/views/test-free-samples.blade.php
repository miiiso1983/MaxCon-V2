@extends('layouts.modern')

@section('page-title', 'اختبار العينات المجانية')
@section('page-description', 'صفحة اختبار لعرض حقل العينات المجانية')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 style="font-size: 24px; font-weight: 700; color: #2d3748; margin-bottom: 30px;">
        اختبار العينات المجانية
    </h1>

    <!-- Test Form -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-sticky-note" style="color: #6366f1; margin-left: 10px;"></i>
            ملاحظات ومعلومات إضافية
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Notes -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات الفاتورة</label>
                <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="ملاحظات إضافية للفاتورة..."></textarea>
            </div>

            <!-- Free Samples -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                    <i class="fas fa-gift" style="color: #10b981; margin-left: 5px;"></i>
                    العينات المجانية
                </label>
                <textarea name="free_samples" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="قائمة العينات المجانية المرفقة مع الفاتورة..."></textarea>
                <div style="font-size: 12px; color: #718096; margin-top: 5px;">
                    مثال: عينة دواء A - 5 حبات، عينة كريم B - أنبوب واحد
                </div>
            </div>
        </div>
    </div>

    <!-- Test Display -->
    <div style="background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%); border-radius: 15px; padding: 25px; margin-bottom: 30px; border-left: 5px solid #10b981;">
        <h4 style="margin: 0 0 15px 0; color: #2d3748; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-gift" style="color: #10b981;"></i>
            العينات المجانية المرفقة (مثال)
        </h4>
        <div style="background: white; border-radius: 10px; padding: 15px; border: 1px solid #d1fae5;">
            <p style="margin: 0; color: #4a5568; line-height: 1.6; white-space: pre-line;">عينة دواء الضغط - 10 حبات
عينة كريم للجلد - أنبوب واحد 15 جرام
عينة فيتامينات - 5 كبسولات
عينة شامبو طبي - زجاجة صغيرة 50 مل</p>
        </div>
        <div style="margin-top: 10px; font-size: 12px; color: #059669; display: flex; align-items: center; gap: 5px;">
            <i class="fas fa-info-circle"></i>
            <span>هذه العينات مجانية ولا تحتسب ضمن قيمة الفاتورة</span>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('tenant.sales.invoices.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
            العودة لإنشاء الفاتورة
        </a>
    </div>
</div>
@endsection
