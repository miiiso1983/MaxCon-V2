@extends('layouts.modern')

@section('title', 'إضافة قسم جديد')

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
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة قسم جديد</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إنشاء قسم جديد في الهيكل التنظيمي</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.departments.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Department Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form action="{{ route('tenant.hr.departments.store') }}" method="POST">
            @csrf
            
            <!-- Basic Information Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #48bb78;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- Department Name -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">اسم القسم <span style="color: #f56565;">*</span></label>
                        <input type="text" name="name" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="أدخل اسم القسم"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Department Code -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">كود القسم <span style="color: #f56565;">*</span></label>
                        <input type="text" name="code" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: DEPT001"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Parent Department -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">القسم الرئيسي</label>
                        <select name="parent_id" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#48bb78'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">لا يوجد (قسم رئيسي)</option>
                            <option value="1">الإدارة العامة</option>
                            <option value="2">الموارد البشرية</option>
                            <option value="3">المالية والمحاسبة</option>
                            <option value="4">المبيعات والتسويق</option>
                            <option value="5">تقنية المعلومات</option>
                        </select>
                    </div>

                    <!-- Manager -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">مدير القسم</label>
                        <select name="manager_id" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#48bb78'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر مدير القسم</option>
                            <option value="1">أحمد محمد</option>
                            <option value="2">سارة أحمد</option>
                            <option value="3">محمد علي</option>
                            <option value="4">فاطمة حسن</option>
                            <option value="5">عمر خالد</option>
                        </select>
                    </div>

                    <!-- Budget -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الميزانية السنوية (دينار)</label>
                        <input type="number" name="budget" min="0" step="0.01"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="0.00"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Location -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الموقع</label>
                        <input type="text" name="location"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: الطابق الثاني - مكتب 201"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Description -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وصف القسم</label>
                        <textarea name="description" rows="4" 
                                  style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أدخل وصف مفصل لمهام ومسؤوليات القسم"
                                  onfocus="this.style.borderColor='#48bb78'" 
                                  onblur="this.style.borderColor='#e2e8f0'"></textarea>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-address-book" style="margin-left: 10px; color: #4299e1;"></i>
                    معلومات الاتصال
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- Phone -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">رقم الهاتف</label>
                        <input type="tel" name="phone"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="07901234567"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Email -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">البريد الإلكتروني</label>
                        <input type="email" name="email"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="department@company.com"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Extension -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">رقم التحويلة</label>
                        <input type="text" name="extension"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: 101"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-cogs" style="margin-left: 10px; color: #9f7aea;"></i>
                    الإعدادات
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
                    
                    <!-- Status -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">حالة القسم</label>
                        <select name="is_active" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#9f7aea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="1" selected>نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>

                    <!-- Cost Center -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">مركز التكلفة</label>
                        <input type="text" name="cost_center"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: CC001"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Max Employees -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأقصى للموظفين</label>
                        <input type="number" name="max_employees" min="1"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: 50"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 20px; justify-content: center; padding-top: 30px; border-top: 2px solid #e2e8f0;">
                <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ القسم
                </button>
                
                <a href="{{ route('tenant.hr.departments.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
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
// Auto-generate department code based on name
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const codeInput = document.querySelector('input[name="code"]');
    
    nameInput.addEventListener('blur', function() {
        if (this.value && !codeInput.value) {
            // Generate code from name
            const name = this.value.trim();
            const words = name.split(' ');
            let code = 'DEPT';
            
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
