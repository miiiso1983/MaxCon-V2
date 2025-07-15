@extends('layouts.modern')

@section('title', 'إضافة شهادة جديدة')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة شهادة جديدة</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إضافة شهادة جودة جديدة إلى النظام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.certificates.index') }}" style="background: rgba(255,255,255,0.2); color: #4ecdc4; padding: 15px 25px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
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
        <form action="{{ route('tenant.inventory.regulatory.certificates.store') }}" method="POST" id="certificateForm">
            @csrf
            
            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات الشهادة الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-certificate" style="margin-left: 8px; color: #4ecdc4;"></i>
                            اسم الشهادة *
                        </label>
                        <input type="text" name="certificate_name" value="{{ old('certificate_name') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: شهادة ISO 9001"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #4ecdc4;"></i>
                            نوع الشهادة *
                        </label>
                        <select name="certificate_type" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4ecdc4'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع الشهادة</option>
                            <option value="gmp" {{ old('certificate_type') == 'gmp' ? 'selected' : '' }}>GMP</option>
                            <option value="iso" {{ old('certificate_type') == 'iso' ? 'selected' : '' }}>ISO</option>
                            <option value="haccp" {{ old('certificate_type') == 'haccp' ? 'selected' : '' }}>HACCP</option>
                            <option value="halal" {{ old('certificate_type') == 'halal' ? 'selected' : '' }}>حلال</option>
                            <option value="organic" {{ old('certificate_type') == 'organic' ? 'selected' : '' }}>عضوي</option>
                            <option value="fda" {{ old('certificate_type') == 'fda' ? 'selected' : '' }}>FDA</option>
                            <option value="ce_marking" {{ old('certificate_type') == 'ce_marking' ? 'selected' : '' }}>CE Marking</option>
                            <option value="other" {{ old('certificate_type') == 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-hashtag" style="margin-left: 8px; color: #4ecdc4;"></i>
                            رقم الشهادة *
                        </label>
                        <input type="text" name="certificate_number" value="{{ old('certificate_number') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: ISO9001-2024-001"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-university" style="margin-left: 8px; color: #4ecdc4;"></i>
                            الجهة المصدرة *
                        </label>
                        <input type="text" name="issuing_authority" value="{{ old('issuing_authority') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: منظمة المعايير الدولية"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-clipboard-list" style="margin-left: 8px; color: #4ecdc4;"></i>
                            حالة الشهادة *
                        </label>
                        <select name="certificate_status" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4ecdc4'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر حالة الشهادة</option>
                            <option value="active" {{ old('certificate_status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="expired" {{ old('certificate_status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                            <option value="suspended" {{ old('certificate_status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                            <option value="revoked" {{ old('certificate_status') == 'revoked' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-bell" style="margin-left: 8px; color: #4ecdc4;"></i>
                            أيام التذكير قبل الانتهاء
                        </label>
                        <input type="number" name="renewal_reminder_days" value="{{ old('renewal_reminder_days', 30) }}" min="1" max="365"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="30"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-calendar" style="margin-left: 10px; color: #4ecdc4;"></i>
                    التواريخ
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #4ecdc4;"></i>
                            تاريخ الإصدار *
                        </label>
                        <input type="date" name="issue_date" value="{{ old('issue_date') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-times" style="margin-left: 8px; color: #f56565;"></i>
                            تاريخ الانتهاء *
                        </label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-check" style="margin-left: 8px; color: #48bb78;"></i>
                            تاريخ التدقيق
                        </label>
                        <input type="date" name="audit_date" value="{{ old('audit_date') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-week" style="margin-left: 8px; color: #ed8936;"></i>
                            تاريخ التدقيق القادم
                        </label>
                        <input type="date" name="next_audit_date" value="{{ old('next_audit_date') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Product and Facility Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-building" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات المنتج والمنشأة
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-pills" style="margin-left: 8px; color: #4ecdc4;"></i>
                            اسم المنتج
                        </label>
                        <input type="text" name="product_name" value="{{ old('product_name') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم المنتج المشمول بالشهادة"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-industry" style="margin-left: 8px; color: #4ecdc4;"></i>
                            اسم المنشأة
                        </label>
                        <input type="text" name="facility_name" value="{{ old('facility_name') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم المنشأة المعتمدة"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-building-user" style="margin-left: 8px; color: #4ecdc4;"></i>
                            جهة الشهادة
                        </label>
                        <input type="text" name="certification_body" value="{{ old('certification_body') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="الجهة المسؤولة عن الشهادة"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-id-card" style="margin-left: 8px; color: #4ecdc4;"></i>
                            رقم الاعتماد
                        </label>
                        <input type="text" name="accreditation_number" value="{{ old('accreditation_number') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="رقم اعتماد الجهة المصدرة"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-info" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات إضافية
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-list-alt" style="margin-left: 8px; color: #4ecdc4;"></i>
                            نطاق الشهادة
                        </label>
                        <textarea name="scope_of_certification" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="وصف نطاق ومجال الشهادة..."
                                  onfocus="this.style.borderColor='#4ecdc4'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('scope_of_certification') }}</textarea>
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
                        style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ الشهادة
                </button>
                
                <button type="button" onclick="resetForm()" 
                        style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 40px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>
                
                <a href="{{ route('tenant.inventory.regulatory.certificates.index') }}" 
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
        document.getElementById('certificateForm').reset();
        alert('تم إعادة تعيين النموذج');
    }
}

// Form submission
document.getElementById('certificateForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
});
</script>

@endsection
