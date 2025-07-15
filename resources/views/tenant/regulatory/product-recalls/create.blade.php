@extends('layouts.modern')

@section('title', 'بدء عملية سحب منتج')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">بدء عملية سحب منتج</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إنشاء عملية سحب منتج جديدة من السوق</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.product-recalls.index') }}" style="background: rgba(255,255,255,0.2); color: #ff9a9e; padding: 15px 25px; border: 2px solid #ff9a9e; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div style="background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="color: #48bb78; font-size: 20px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-times-circle" style="color: #f56565; font-size: 20px;"></i>
                <strong>{{ session('error') }}</strong>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-times-circle" style="color: #f56565; font-size: 20px;"></i>
                <strong>يرجى تصحيح الأخطاء التالية:</strong>
            </div>
            <ul style="margin: 0 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form action="{{ route('tenant.inventory.regulatory.product-recalls.store') }}" method="POST" id="recallForm">
            @csrf

            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #ff9a9e; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #ff9a9e;"></i>
                    معلومات السحب الأساسية
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-heading" style="margin-left: 8px; color: #ff9a9e;"></i>
                            عنوان السحب *
                        </label>
                        <input type="text" name="recall_title" value="{{ old('recall_title') }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: سحب دواء باراسيتامول"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #ff9a9e;"></i>
                            نوع السحب *
                        </label>
                        <select name="recall_type" required
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#ff9a9e'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع السحب</option>
                            <option value="voluntary" {{ old('recall_type') == 'voluntary' ? 'selected' : '' }}>طوعي</option>
                            <option value="mandatory" {{ old('recall_type') == 'mandatory' ? 'selected' : '' }}>إجباري</option>
                            <option value="market_withdrawal" {{ old('recall_type') == 'market_withdrawal' ? 'selected' : '' }}>سحب من السوق</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-pills" style="margin-left: 8px; color: #ff9a9e;"></i>
                            اسم المنتج *
                        </label>
                        <input type="text" name="product_name" value="{{ old('product_name') }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: باراسيتامول 500 مجم"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-barcode" style="margin-left: 8px; color: #ff9a9e;"></i>
                            أرقام الدفعات *
                        </label>
                        <input type="text" name="batch_numbers" value="{{ old('batch_numbers') }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: BATCH001, BATCH002"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-exclamation-circle" style="margin-left: 8px; color: #ff9a9e;"></i>
                            مستوى المخاطر *
                        </label>
                        <select name="risk_level" required
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#ff9a9e'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر مستوى المخاطر</option>
                            <option value="class_1" {{ old('risk_level') == 'class_1' ? 'selected' : '' }}>الفئة الأولى</option>
                            <option value="class_2" {{ old('risk_level') == 'class_2' ? 'selected' : '' }}>الفئة الثانية</option>
                            <option value="class_3" {{ old('risk_level') == 'class_3' ? 'selected' : '' }}>الفئة الثالثة</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-clipboard-list" style="margin-left: 8px; color: #ff9a9e;"></i>
                            حالة السحب *
                        </label>
                        <select name="recall_status" required
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#ff9a9e'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر حالة السحب</option>
                            <option value="initiated" {{ old('recall_status') == 'initiated' ? 'selected' : '' }}>بدأ</option>
                            <option value="in_progress" {{ old('recall_status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                            <option value="completed" {{ old('recall_status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="terminated" {{ old('recall_status') == 'terminated' ? 'selected' : '' }}>منتهي</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dates and Quantities -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #ff9a9e; padding-bottom: 10px;">
                    <i class="fas fa-calendar" style="margin-left: 10px; color: #ff9a9e;"></i>
                    التواريخ والكميات
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #ff9a9e;"></i>
                            تاريخ البدء *
                        </label>
                        <input type="date" name="initiation_date" value="{{ old('initiation_date') }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-check" style="margin-left: 8px; color: #48bb78;"></i>
                            تاريخ الإنجاز
                        </label>
                        <input type="date" name="completion_date" value="{{ old('completion_date') }}"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-bell" style="margin-left: 8px; color: #ed8936;"></i>
                            تاريخ الإشعار
                        </label>
                        <input type="date" name="notification_date" value="{{ old('notification_date') }}"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-boxes" style="margin-left: 8px; color: #ff9a9e;"></i>
                            الكمية المتأثرة *
                        </label>
                        <input type="number" name="affected_quantity" value="{{ old('affected_quantity') }}" required min="1"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="عدد الوحدات المتأثرة"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-undo" style="margin-left: 8px; color: #48bb78;"></i>
                            الكمية المستردة
                        </label>
                        <input type="number" name="recovered_quantity" value="{{ old('recovered_quantity', 0) }}" min="0"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="عدد الوحدات المستردة"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px; padding-top: 30px;">
                        <input type="checkbox" name="public_notification" id="public_notification" value="1" {{ old('public_notification') ? 'checked' : '' }}
                               style="width: 20px; height: 20px; accent-color: #ff9a9e;">
                        <label for="public_notification" style="color: #2d3748; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-bullhorn" style="margin-left: 8px; color: #ff9a9e;"></i>
                            إشعار عام مطلوب
                        </label>
                    </div>
                </div>
            </div>

            <!-- Authority and Distribution -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #ff9a9e; padding-bottom: 10px;">
                    <i class="fas fa-map-marked-alt" style="margin-left: 10px; color: #ff9a9e;"></i>
                    الجهة التنظيمية والتوزيع
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-university" style="margin-left: 8px; color: #ff9a9e;"></i>
                            الجهة التنظيمية
                        </label>
                        <input type="text" name="regulatory_authority" value="{{ old('regulatory_authority') }}"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: وزارة الصحة العراقية"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user-tie" style="margin-left: 8px; color: #ff9a9e;"></i>
                            منسق السحب
                        </label>
                        <input type="text" name="recall_coordinator" value="{{ old('recall_coordinator') }}"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم المسؤول عن تنسيق السحب"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-globe" style="margin-left: 8px; color: #ff9a9e;"></i>
                            منطقة التوزيع *
                        </label>
                        <input type="text" name="distribution_area" value="{{ old('distribution_area') }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: العراق - بغداد والمحافظات"
                               onfocus="this.style.borderColor='#ff9a9e'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Reason and Actions -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #ff9a9e; padding-bottom: 10px;">
                    <i class="fas fa-clipboard-check" style="margin-left: 10px; color: #ff9a9e;"></i>
                    السبب والإجراءات
                </h3>

                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-question-circle" style="margin-left: 8px; color: #ff9a9e;"></i>
                            سبب السحب *
                        </label>
                        <textarea name="recall_reason" rows="3" required
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="وصف مفصل لسبب سحب المنتج..."
                                  onfocus="this.style.borderColor='#ff9a9e'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('recall_reason') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tools" style="margin-left: 8px; color: #ff9a9e;"></i>
                            الإجراءات التصحيحية
                        </label>
                        <textarea name="corrective_actions" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="الإجراءات المتخذة لتصحيح المشكلة..."
                                  onfocus="this.style.borderColor='#ff9a9e'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('corrective_actions') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-shield-alt" style="margin-left: 8px; color: #ff9a9e;"></i>
                            الإجراءات الوقائية
                        </label>
                        <textarea name="preventive_actions" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="الإجراءات الوقائية لمنع تكرار المشكلة..."
                                  onfocus="this.style.borderColor='#ff9a9e'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('preventive_actions') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-sticky-note" style="margin-left: 8px; color: #ff9a9e;"></i>
                            ملاحظات إضافية
                        </label>
                        <textarea name="notes" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أي ملاحظات إضافية..."
                                  onfocus="this.style.borderColor='#ff9a9e'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                <button type="submit"
                        style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-exclamation-triangle"></i>
                    بدء عملية السحب
                </button>

                <button type="button" onclick="resetForm()"
                        style="background: rgba(255, 154, 158, 0.1); color: #ff9a9e; padding: 15px 40px; border: 2px solid #ff9a9e; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>

                <a href="{{ route('tenant.inventory.regulatory.product-recalls.index') }}"
                   style="background: rgba(113, 128, 150, 0.1); color: #718096; padding: 15px 40px; border: 2px solid #718096; border-radius: 15px; font-weight: 600; font-size: 16px; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                   onmouseover="this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
        document.getElementById('recallForm').reset();
        alert('تم إعادة تعيين النموذج');
    }
}

// Form submission
document.getElementById('recallForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
});
</script>

@endsection