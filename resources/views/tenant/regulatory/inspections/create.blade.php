@extends('layouts.modern')

@section('title', 'إضافة تفتيش جديد')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة تفتيش جديد</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إضافة تفتيش تنظيمي جديد إلى النظام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}" style="background: rgba(255,255,255,0.2); color: #f093fb; padding: 15px 25px; border: 2px solid #f093fb; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
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
        <form action="{{ route('tenant.inventory.regulatory.inspections.store') }}" method="POST" id="inspectionForm">
            @csrf
            
            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #f093fb; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #f093fb;"></i>
                    معلومات التفتيش الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-search" style="margin-left: 8px; color: #f093fb;"></i>
                            عنوان التفتيش *
                        </label>
                        <input type="text" name="inspection_title" value="{{ old('inspection_title', request('inspection_title')) }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: تفتيش دوري للجودة"
                               onfocus="this.style.borderColor='#f093fb'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #f093fb;"></i>
                            نوع التفتيش *
                        </label>
                        <select name="inspection_type" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#f093fb'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع التفتيش</option>
                            <option value="routine" {{ (old('inspection_type', request('inspection_type')) == 'routine') ? 'selected' : '' }}>روتيني</option>
                            <option value="complaint" {{ (old('inspection_type', request('inspection_type')) == 'complaint') ? 'selected' : '' }}>شكوى</option>
                            <option value="follow_up" {{ (old('inspection_type', request('inspection_type')) == 'follow_up') ? 'selected' : '' }}>متابعة</option>
                            <option value="pre_approval" {{ (old('inspection_type', request('inspection_type')) == 'pre_approval') ? 'selected' : '' }}>ما قبل الموافقة</option>
                            <option value="post_market" {{ (old('inspection_type', request('inspection_type')) == 'post_market') ? 'selected' : '' }}>ما بعد التسويق</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user-tie" style="margin-left: 8px; color: #f093fb;"></i>
                            اسم المفتش *
                        </label>
                        <input type="text" name="inspector_name" value="{{ old('inspector_name', request('inspector_name')) }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: د. أحمد محمد"
                               onfocus="this.style.borderColor='#f093fb'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-university" style="margin-left: 8px; color: #f093fb;"></i>
                            الجهة المفتشة *
                        </label>
                        <input type="text" name="inspection_authority" value="{{ old('inspection_authority', request('inspection_authority')) }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: وزارة الصحة العراقية"
                               onfocus="this.style.borderColor='#f093fb'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-clipboard-list" style="margin-left: 8px; color: #f093fb;"></i>
                            حالة التفتيش *
                        </label>
                        <select name="inspection_status" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#f093fb'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر حالة التفتيش</option>
                            <option value="scheduled" {{ (old('inspection_status', request('inspection_status')) == 'scheduled') ? 'selected' : '' }}>مجدول</option>
                            <option value="in_progress" {{ (old('inspection_status', request('inspection_status')) == 'in_progress') ? 'selected' : '' }}>قيد التنفيذ</option>
                            <option value="completed" {{ (old('inspection_status', request('inspection_status')) == 'completed') ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ (old('inspection_status', request('inspection_status')) == 'cancelled') ? 'selected' : '' }}>ملغي</option>
                            <option value="postponed" {{ (old('inspection_status', request('inspection_status')) == 'postponed') ? 'selected' : '' }}>مؤجل</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-star" style="margin-left: 8px; color: #f093fb;"></i>
                            تقييم الامتثال
                        </label>
                        <select name="compliance_rating" 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#f093fb'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر تقييم الامتثال</option>
                            <option value="excellent" {{ old('compliance_rating') == 'excellent' ? 'selected' : '' }}>ممتاز</option>
                            <option value="good" {{ old('compliance_rating') == 'good' ? 'selected' : '' }}>جيد</option>
                            <option value="satisfactory" {{ old('compliance_rating') == 'satisfactory' ? 'selected' : '' }}>مرضي</option>
                            <option value="needs_improvement" {{ old('compliance_rating') == 'needs_improvement' ? 'selected' : '' }}>يحتاج تحسين</option>
                            <option value="non_compliant" {{ old('compliance_rating') == 'non_compliant' ? 'selected' : '' }}>غير ملتزم</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #f093fb; padding-bottom: 10px;">
                    <i class="fas fa-calendar" style="margin-left: 10px; color: #f093fb;"></i>
                    التواريخ
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #f093fb;"></i>
                            التاريخ المجدول *
                        </label>
                        <input type="date" name="scheduled_date" value="{{ old('scheduled_date', request('scheduled_date')) }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#f093fb'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-check" style="margin-left: 8px; color: #48bb78;"></i>
                            تاريخ الإنجاز
                        </label>
                        <input type="date" name="completion_date" value="{{ old('completion_date') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#f093fb'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-times" style="margin-left: 8px; color: #ed8936;"></i>
                            تاريخ المتابعة
                        </label>
                        <input type="date" name="follow_up_date" value="{{ old('follow_up_date') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#f093fb'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 10px; padding-top: 30px;">
                        <input type="checkbox" name="follow_up_required" id="follow_up_required" value="1" {{ old('follow_up_required') ? 'checked' : '' }}
                               style="width: 20px; height: 20px; accent-color: #f093fb;">
                        <label for="follow_up_required" style="color: #2d3748; font-weight: 600; cursor: pointer;">
                            <i class="fas fa-redo" style="margin-left: 8px; color: #f093fb;"></i>
                            متابعة مطلوبة
                        </label>
                    </div>
                </div>
            </div>

            <!-- Facility Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #f093fb; padding-bottom: 10px;">
                    <i class="fas fa-building" style="margin-left: 10px; color: #f093fb;"></i>
                    معلومات المنشأة
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-industry" style="margin-left: 8px; color: #f093fb;"></i>
                            اسم المنشأة *
                        </label>
                        <input type="text" name="facility_name" value="{{ old('facility_name', request('facility_name')) }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: مصنع الأدوية المتقدمة"
                               onfocus="this.style.borderColor='#f093fb'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-map-marker-alt" style="margin-left: 8px; color: #f093fb;"></i>
                            عنوان المنشأة *
                        </label>
                        <textarea name="facility_address" rows="3" required
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="العنوان الكامل للمنشأة..."
                                  onfocus="this.style.borderColor='#f093fb'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('facility_address', request('facility_address')) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Technical Details -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #f093fb; padding-bottom: 10px;">
                    <i class="fas fa-clipboard-check" style="margin-left: 10px; color: #f093fb;"></i>
                    التفاصيل التقنية
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-list-alt" style="margin-left: 8px; color: #f093fb;"></i>
                            نطاق التفتيش
                        </label>
                        <textarea name="scope_of_inspection" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="وصف نطاق ومجال التفتيش..."
                                  onfocus="this.style.borderColor='#f093fb'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('scope_of_inspection', request('scope_of_inspection')) }}</textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-search-plus" style="margin-left: 8px; color: #f093fb;"></i>
                            النتائج والملاحظات
                        </label>
                        <textarea name="findings" rows="4"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="نتائج التفتيش والملاحظات المهمة..."
                                  onfocus="this.style.borderColor='#f093fb'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('findings') }}</textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-lightbulb" style="margin-left: 8px; color: #f093fb;"></i>
                            التوصيات
                        </label>
                        <textarea name="recommendations" rows="4"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="التوصيات والإجراءات المطلوبة..."
                                  onfocus="this.style.borderColor='#f093fb'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('recommendations') }}</textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-sticky-note" style="margin-left: 8px; color: #f093fb;"></i>
                            ملاحظات إضافية
                        </label>
                        <textarea name="notes" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أي ملاحظات إضافية..."
                                  onfocus="this.style.borderColor='#f093fb'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('notes', request('notes')) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                <button type="submit" 
                        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ التفتيش
                </button>
                
                <button type="button" onclick="resetForm()" 
                        style="background: rgba(240, 147, 251, 0.1); color: #f093fb; padding: 15px 40px; border: 2px solid #f093fb; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>
                
                <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}" 
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
        document.getElementById('inspectionForm').reset();
        alert('تم إعادة تعيين النموذج');
    }
}

// Form submission
document.getElementById('inspectionForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
});
</script>

@endsection
