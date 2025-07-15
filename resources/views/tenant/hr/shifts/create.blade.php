@extends('layouts.modern')

@section('title', 'إضافة مناوبة جديدة')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة مناوبة جديدة</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إنشاء مناوبة عمل جديدة وتعيين الموظفين</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.shifts.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للمناوبات
                </a>
            </div>
        </div>
    </div>

    <!-- Shift Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form action="{{ route('tenant.hr.shifts.store') }}" method="POST">
            @csrf
            
            <!-- Basic Information Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #48bb78;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- Shift Name -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">اسم المناوبة <span style="color: #f56565;">*</span></label>
                        <input type="text" name="name" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: المناوبة الصباحية"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Shift Code -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">كود المناوبة <span style="color: #f56565;">*</span></label>
                        <input type="text" name="code" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: SHIFT001"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Shift Type -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع المناوبة <span style="color: #f56565;">*</span></label>
                        <select name="type" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#48bb78'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع المناوبة</option>
                            <option value="morning">صباحية</option>
                            <option value="evening">مسائية</option>
                            <option value="night">ليلية</option>
                            <option value="flexible">مرنة</option>
                            <option value="rotating">دوارة</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">القسم</label>
                        <select name="department_id" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#48bb78'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">جميع الأقسام</option>
                            <option value="1">الإدارة العامة</option>
                            <option value="2">الموارد البشرية</option>
                            <option value="3">المالية والمحاسبة</option>
                            <option value="4">المبيعات والتسويق</option>
                            <option value="5">تقنية المعلومات</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وصف المناوبة</label>
                        <textarea name="description" rows="4" 
                                  style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أدخل وصف مفصل للمناوبة وقواعدها"
                                  onfocus="this.style.borderColor='#48bb78'" 
                                  onblur="this.style.borderColor='#e2e8f0'"></textarea>
                    </div>
                </div>
            </div>

            <!-- Time Settings Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-clock" style="margin-left: 10px; color: #4299e1;"></i>
                    إعدادات الوقت
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
                    
                    <!-- Start Time -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وقت البداية <span style="color: #f56565;">*</span></label>
                        <input type="time" name="start_time" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- End Time -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وقت النهاية <span style="color: #f56565;">*</span></label>
                        <input type="time" name="end_time" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Duration -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">عدد الساعات</label>
                        <input type="number" name="duration" min="1" max="24" step="0.5" 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="8"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Break Duration -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">مدة الاستراحة (دقيقة)</label>
                        <input type="number" name="break_duration" min="0" max="120" 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="60"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Work Days Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-calendar-week" style="margin-left: 10px; color: #ed8936;"></i>
                    أيام العمل
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                    
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; background: #f7fafc; border-radius: 10px; transition: background 0.3s;"
                           onmouseover="this.style.background='#edf2f7'" 
                           onmouseout="this.style.background='#f7fafc'">
                        <input type="checkbox" name="work_days[]" value="sunday" checked style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">الأحد</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; background: #f7fafc; border-radius: 10px; transition: background 0.3s;"
                           onmouseover="this.style.background='#edf2f7'" 
                           onmouseout="this.style.background='#f7fafc'">
                        <input type="checkbox" name="work_days[]" value="monday" checked style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">الاثنين</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; background: #f7fafc; border-radius: 10px; transition: background 0.3s;"
                           onmouseover="this.style.background='#edf2f7'" 
                           onmouseout="this.style.background='#f7fafc'">
                        <input type="checkbox" name="work_days[]" value="tuesday" checked style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">الثلاثاء</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; background: #f7fafc; border-radius: 10px; transition: background 0.3s;"
                           onmouseover="this.style.background='#edf2f7'" 
                           onmouseout="this.style.background='#f7fafc'">
                        <input type="checkbox" name="work_days[]" value="wednesday" checked style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">الأربعاء</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; background: #f7fafc; border-radius: 10px; transition: background 0.3s;"
                           onmouseover="this.style.background='#edf2f7'" 
                           onmouseout="this.style.background='#f7fafc'">
                        <input type="checkbox" name="work_days[]" value="thursday" checked style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">الخميس</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; background: #f7fafc; border-radius: 10px; transition: background 0.3s;"
                           onmouseover="this.style.background='#edf2f7'" 
                           onmouseout="this.style.background='#f7fafc'">
                        <input type="checkbox" name="work_days[]" value="friday" style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">الجمعة</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; background: #f7fafc; border-radius: 10px; transition: background 0.3s;"
                           onmouseover="this.style.background='#edf2f7'" 
                           onmouseout="this.style.background='#f7fafc'">
                        <input type="checkbox" name="work_days[]" value="saturday" style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">السبت</span>
                    </label>
                </div>
            </div>

            <!-- Employee Settings Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-users" style="margin-left: 10px; color: #9f7aea;"></i>
                    إعدادات الموظفين
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
                    
                    <!-- Max Employees -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأقصى للموظفين</label>
                        <input type="number" name="max_employees" min="1" 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="20"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Min Employees -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأدنى للموظفين</label>
                        <input type="number" name="min_employees" min="1" 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="5"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Overtime Rate -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">معدل الساعات الإضافية (%)</label>
                        <input type="number" name="overtime_rate" min="0" max="200" step="0.1" 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="150"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Status -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">حالة المناوبة</label>
                        <select name="is_active" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#9f7aea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="1" selected>نشطة</option>
                            <option value="0">غير نشطة</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 20px; justify-content: center; padding-top: 30px; border-top: 2px solid #e2e8f0;">
                <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ المناوبة
                </button>
                
                <a href="{{ route('tenant.hr.shifts.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
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
// Auto-generate shift code based on name
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const codeInput = document.querySelector('input[name="code"]');
    
    nameInput.addEventListener('blur', function() {
        if (this.value && !codeInput.value) {
            // Generate code from name
            const name = this.value.trim();
            const words = name.split(' ');
            let code = 'SHIFT';
            
            if (words.length >= 2) {
                code += words[0].charAt(0).toUpperCase() + words[1].charAt(0).toUpperCase();
            } else {
                code += name.substring(0, 2).toUpperCase();
            }
            
            // Add random number
            code += Math.floor(Math.random() * 100).toString().padStart(2, '0');
            codeInput.value = code;
        }
    });
});

// Calculate duration automatically
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');
    const durationInput = document.querySelector('input[name="duration"]');
    
    function calculateDuration() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date('2000-01-01 ' + startTimeInput.value);
            let end = new Date('2000-01-01 ' + endTimeInput.value);
            
            // Handle overnight shifts
            if (end <= start) {
                end.setDate(end.getDate() + 1);
            }
            
            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            durationInput.value = diffHours;
        }
    }
    
    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#f56565';
            isValid = false;
        } else {
            field.style.borderColor = '#48bb78';
        }
    });
    
    // Check if at least one work day is selected
    const workDays = this.querySelectorAll('input[name="work_days[]"]:checked');
    if (workDays.length === 0) {
        alert('يرجى اختيار يوم واحد على الأقل من أيام العمل');
        isValid = false;
        e.preventDefault();
        return;
    }
    
    if (!isValid) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
});
</script>

@endsection
