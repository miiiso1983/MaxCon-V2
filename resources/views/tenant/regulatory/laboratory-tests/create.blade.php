@extends('layouts.modern')

@section('title', 'إضافة فحص مخبري جديد')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة فحص مخبري جديد</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إضافة فحص مخبري جديد إلى النظام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.index') }}" style="background: rgba(255,255,255,0.2); color: #667eea; padding: 15px 25px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
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
        <form action="{{ route('tenant.inventory.regulatory.laboratory-tests.store') }}" method="POST" id="testForm">
            @csrf
            
            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #667eea;"></i>
                    معلومات الفحص الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-flask" style="margin-left: 8px; color: #667eea;"></i>
                            اسم الفحص *
                        </label>
                        <input type="text" name="test_name" value="{{ old('test_name') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: فحص جودة الأقراص"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #667eea;"></i>
                            نوع الفحص *
                        </label>
                        <select name="test_type" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#667eea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع الفحص</option>
                            <option value="quality_control" {{ old('test_type') == 'quality_control' ? 'selected' : '' }}>مراقبة الجودة</option>
                            <option value="stability" {{ old('test_type') == 'stability' ? 'selected' : '' }}>اختبار الثبات</option>
                            <option value="microbiological" {{ old('test_type') == 'microbiological' ? 'selected' : '' }}>فحص ميكروبيولوجي</option>
                            <option value="chemical" {{ old('test_type') == 'chemical' ? 'selected' : '' }}>فحص كيميائي</option>
                            <option value="physical" {{ old('test_type') == 'physical' ? 'selected' : '' }}>فحص فيزيائي</option>
                            <option value="bioequivalence" {{ old('test_type') == 'bioequivalence' ? 'selected' : '' }}>التكافؤ الحيوي</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-pills" style="margin-left: 8px; color: #667eea;"></i>
                            اسم المنتج *
                        </label>
                        <input type="text" name="product_name" value="{{ old('product_name') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: باراسيتامول 500 مجم"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-barcode" style="margin-left: 8px; color: #667eea;"></i>
                            رقم الدفعة *
                        </label>
                        <input type="text" name="batch_number" value="{{ old('batch_number') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: BATCH001"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-building" style="margin-left: 8px; color: #667eea;"></i>
                            اسم المختبر *
                        </label>
                        <input type="text" name="laboratory_name" value="{{ old('laboratory_name') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: مختبر الجودة المركزي"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-clipboard-list" style="margin-left: 8px; color: #667eea;"></i>
                            حالة الفحص *
                        </label>
                        <select name="test_status" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#667eea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر حالة الفحص</option>
                            <option value="pending" {{ old('test_status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                            <option value="in_progress" {{ old('test_status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                            <option value="completed" {{ old('test_status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="failed" {{ old('test_status') == 'failed' ? 'selected' : '' }}>فاشل</option>
                            <option value="cancelled" {{ old('test_status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dates and Method -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                    <i class="fas fa-calendar" style="margin-left: 10px; color: #667eea;"></i>
                    التواريخ والطريقة
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #667eea;"></i>
                            تاريخ الفحص *
                        </label>
                        <input type="date" name="test_date" value="{{ old('test_date') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-check" style="margin-left: 8px; color: #48bb78;"></i>
                            تاريخ الإنجاز المتوقع
                        </label>
                        <input type="date" name="completion_date" value="{{ old('completion_date') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-cogs" style="margin-left: 8px; color: #667eea;"></i>
                            طريقة الفحص
                        </label>
                        <input type="text" name="test_method" value="{{ old('test_method') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: USP Method"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-dollar-sign" style="margin-left: 8px; color: #667eea;"></i>
                            التكلفة (دينار)
                        </label>
                        <input type="number" name="cost" value="{{ old('cost') }}" step="0.01" min="0"
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="0.00"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Personnel -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                    <i class="fas fa-users" style="margin-left: 10px; color: #667eea;"></i>
                    الموظفون
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user" style="margin-left: 8px; color: #667eea;"></i>
                            اسم الفني
                        </label>
                        <input type="text" name="technician_name" value="{{ old('technician_name') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم الفني المسؤول"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user-tie" style="margin-left: 8px; color: #667eea;"></i>
                            اسم المشرف
                        </label>
                        <input type="text" name="supervisor_name" value="{{ old('supervisor_name') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم المشرف"
                               onfocus="this.style.borderColor='#667eea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Technical Details -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                    <i class="fas fa-microscope" style="margin-left: 10px; color: #667eea;"></i>
                    التفاصيل التقنية
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-list-alt" style="margin-left: 8px; color: #667eea;"></i>
                            المواصفات المطلوبة
                        </label>
                        <textarea name="specifications" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="المواصفات والمعايير المطلوبة للفحص..."
                                  onfocus="this.style.borderColor='#667eea'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('specifications') }}</textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-chart-line" style="margin-left: 8px; color: #667eea;"></i>
                            النتائج
                        </label>
                        <textarea name="results" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="نتائج الفحص (إن وجدت)..."
                                  onfocus="this.style.borderColor='#667eea'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('results') }}</textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-clipboard-check" style="margin-left: 8px; color: #667eea;"></i>
                            الخلاصة والتوصيات
                        </label>
                        <textarea name="conclusion" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="خلاصة الفحص والتوصيات..."
                                  onfocus="this.style.borderColor='#667eea'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('conclusion') }}</textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-sticky-note" style="margin-left: 8px; color: #667eea;"></i>
                            ملاحظات إضافية
                        </label>
                        <textarea name="notes" rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أي ملاحظات إضافية..."
                                  onfocus="this.style.borderColor='#667eea'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                <button type="submit" 
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ الفحص
                </button>
                
                <button type="button" onclick="resetForm()" 
                        style="background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 15px 40px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>
                
                <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.index') }}" 
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
        document.getElementById('testForm').reset();
        alert('تم إعادة تعيين النموذج');
    }
}

// Form submission
document.getElementById('testForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
});
</script>

@endsection
