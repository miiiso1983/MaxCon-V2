@extends('layouts.modern')

@section('title', 'إنشاء تقرير تنظيمي')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إنشاء تقرير تنظيمي</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إنشاء تقرير تنظيمي جديد للجهات الرقابية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.reports.index') }}" style="background: rgba(255,255,255,0.2); color: #4ecdc4; padding: 15px 25px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
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
        <form action="{{ route('tenant.inventory.regulatory.reports.store') }}" method="POST" id="reportForm">
            @csrf
            
            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات التقرير الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-heading" style="margin-left: 8px; color: #4ecdc4;"></i>
                            عنوان التقرير *
                        </label>
                        <input type="text" name="report_title" value="{{ old('report_title', $templateData['report_title'] ?? '') }}" required
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: تقرير الامتثال الربعي"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #4ecdc4;"></i>
                            نوع التقرير *
                        </label>
                        <select name="report_type" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4ecdc4'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع التقرير</option>
                            <option value="periodic" {{ old('report_type', $templateData['report_type'] ?? '') == 'periodic' ? 'selected' : '' }}>دوري</option>
                            <option value="incident" {{ old('report_type', $templateData['report_type'] ?? '') == 'incident' ? 'selected' : '' }}>حادث</option>
                            <option value="compliance" {{ old('report_type', $templateData['report_type'] ?? '') == 'compliance' ? 'selected' : '' }}>امتثال</option>
                            <option value="audit" {{ old('report_type', $templateData['report_type'] ?? '') == 'audit' ? 'selected' : '' }}>تدقيق</option>
                            <option value="inspection" {{ old('report_type', $templateData['report_type'] ?? '') == 'inspection' ? 'selected' : '' }}>تفتيش</option>
                            <option value="adverse_event" {{ old('report_type', $templateData['report_type'] ?? '') == 'adverse_event' ? 'selected' : '' }}>حدث سلبي</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-alt" style="margin-left: 8px; color: #4ecdc4;"></i>
                            فترة التقرير
                        </label>
                        <select name="report_period" 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4ecdc4'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر فترة التقرير</option>
                            <option value="monthly" {{ old('report_period') == 'monthly' ? 'selected' : '' }}>شهري</option>
                            <option value="quarterly" {{ old('report_period') == 'quarterly' ? 'selected' : '' }}>ربعي</option>
                            <option value="semi_annual" {{ old('report_period') == 'semi_annual' ? 'selected' : '' }}>نصف سنوي</option>
                            <option value="annual" {{ old('report_period') == 'annual' ? 'selected' : '' }}>سنوي</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-university" style="margin-left: 8px; color: #4ecdc4;"></i>
                            الجهة المستلمة *
                        </label>
                        <input type="text" name="submission_authority" value="{{ old('submission_authority') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: وزارة الصحة العراقية"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-times" style="margin-left: 8px; color: #4ecdc4;"></i>
                            تاريخ الاستحقاق *
                        </label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-check" style="margin-left: 8px; color: #48bb78;"></i>
                            تاريخ التقديم
                        </label>
                        <input type="date" name="submission_date" value="{{ old('submission_date') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-clipboard-list" style="margin-left: 8px; color: #4ecdc4;"></i>
                            حالة التقرير *
                        </label>
                        <select name="report_status" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4ecdc4'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر حالة التقرير</option>
                            <option value="draft" {{ old('report_status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="pending_review" {{ old('report_status') == 'pending_review' ? 'selected' : '' }}>في انتظار المراجعة</option>
                            <option value="submitted" {{ old('report_status') == 'submitted' ? 'selected' : '' }}>مقدم</option>
                            <option value="approved" {{ old('report_status') == 'approved' ? 'selected' : '' }}>معتمد</option>
                            <option value="rejected" {{ old('report_status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-star" style="margin-left: 8px; color: #4ecdc4;"></i>
                            مستوى الأولوية
                        </label>
                        <select name="priority_level" 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4ecdc4'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر مستوى الأولوية</option>
                            <option value="low" {{ old('priority_level') == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="medium" {{ old('priority_level') == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="high" {{ old('priority_level') == 'high' ? 'selected' : '' }}>عالي</option>
                            <option value="critical" {{ old('priority_level') == 'critical' ? 'selected' : '' }}>حرج</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Personnel Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-users" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات الأشخاص المسؤولين
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user-edit" style="margin-left: 8px; color: #4ecdc4;"></i>
                            معد التقرير *
                        </label>
                        <input type="text" name="prepared_by" value="{{ old('prepared_by') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم معد التقرير"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user-check" style="margin-left: 8px; color: #4ecdc4;"></i>
                            مراجع التقرير
                        </label>
                        <input type="text" name="reviewed_by" value="{{ old('reviewed_by') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم مراجع التقرير"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user-shield" style="margin-left: 8px; color: #4ecdc4;"></i>
                            معتمد التقرير
                        </label>
                        <input type="text" name="approved_by" value="{{ old('approved_by') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم معتمد التقرير"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-hashtag" style="margin-left: 8px; color: #4ecdc4;"></i>
                            المرجع التنظيمي
                        </label>
                        <input type="text" name="regulatory_reference" value="{{ old('regulatory_reference') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: REG-2024-001"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Report Content -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-file-alt" style="margin-left: 10px; color: #4ecdc4;"></i>
                    محتوى التقرير
                </h3>

                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-align-left" style="margin-left: 8px; color: #4ecdc4;"></i>
                            ملخص التقرير
                        </label>
                        <textarea name="report_summary" rows="4"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="ملخص موجز لمحتوى التقرير..."
                                  onfocus="this.style.borderColor='#4ecdc4'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('report_summary') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-search" style="margin-left: 8px; color: #4ecdc4;"></i>
                            النتائج الرئيسية
                        </label>
                        <textarea name="key_findings" rows="4"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="النتائج والملاحظات الرئيسية من التقرير..."
                                  onfocus="this.style.borderColor='#4ecdc4'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('key_findings') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-lightbulb" style="margin-left: 8px; color: #4ecdc4;"></i>
                            التوصيات
                        </label>
                        <textarea name="recommendations" rows="4"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="التوصيات والإجراءات المقترحة..."
                                  onfocus="this.style.borderColor='#4ecdc4'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('recommendations') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tasks" style="margin-left: 8px; color: #4ecdc4;"></i>
                            إجراءات المتابعة
                        </label>
                        <textarea name="follow_up_actions" rows="4"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="الإجراءات المطلوبة للمتابعة..."
                                  onfocus="this.style.borderColor='#4ecdc4'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('follow_up_actions') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-sticky-note" style="margin-left: 8px; color: #4ecdc4;"></i>
                            ملاحظات إضافية
                        </label>
                        <textarea name="notes" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أي ملاحظات إضافية..."
                                  onfocus="this.style.borderColor='#4ecdc4'"
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                <button type="submit"
                        style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-file-alt"></i>
                    إنشاء التقرير
                </button>

                <button type="button" onclick="resetForm()"
                        style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 40px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>

                <a href="{{ route('tenant.inventory.regulatory.reports.index') }}"
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
        document.getElementById('reportForm').reset();
        alert('تم إعادة تعيين النموذج');
    }
}

// Form submission
document.getElementById('reportForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
});

// Set minimum date to today for due date
document.addEventListener('DOMContentLoaded', function() {
    const dueDateInput = document.querySelector('input[name="due_date"]');
    if (dueDateInput) {
        const today = new Date().toISOString().split('T')[0];
        dueDateInput.min = today;
    }
});
</script>

@endsection
