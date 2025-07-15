@extends('layouts.modern')

@section('title', 'تعديل بيانات الموظف')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تعديل بيانات الموظف</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تحديث معلومات الموظف في النظام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.employees.show', 1) }}" style="background: #4299e1; color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-eye"></i>
                    عرض البيانات
                </a>
                <a href="{{ route('tenant.hr.employees.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Employee Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form action="{{ route('tenant.hr.employees.update', 1) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Personal Information Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-user" style="margin-left: 10px; color: #48bb78;"></i>
                    المعلومات الشخصية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- First Name -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الاسم الأول <span style="color: #f56565;">*</span></label>
                        <input type="text" name="first_name" value="أحمد" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الاسم الأخير <span style="color: #f56565;">*</span></label>
                        <input type="text" name="last_name" value="محمد" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- National ID -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">رقم الهوية <span style="color: #f56565;">*</span></label>
                        <input type="text" name="national_id" value="12345678901" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">تاريخ الميلاد <span style="color: #f56565;">*</span></label>
                        <input type="date" name="date_of_birth" value="1990-01-15" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الجنس <span style="color: #f56565;">*</span></label>
                        <select name="gender" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#48bb78'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="male" selected>ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>

                    <!-- Marital Status -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحالة الاجتماعية</label>
                        <select name="marital_status" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#48bb78'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="single">أعزب</option>
                            <option value="married" selected>متزوج</option>
                            <option value="divorced">مطلق</option>
                            <option value="widowed">أرمل</option>
                        </select>
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
                    
                    <!-- Email -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">البريد الإلكتروني <span style="color: #f56565;">*</span></label>
                        <input type="email" name="email" value="ahmed.mohamed@company.com" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Mobile -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">رقم الهاتف <span style="color: #f56565;">*</span></label>
                        <input type="tel" name="mobile" value="07901234567" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Address -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">العنوان</label>
                        <textarea name="address" rows="3" 
                                  style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  onfocus="this.style.borderColor='#4299e1'" 
                                  onblur="this.style.borderColor='#e2e8f0'">بغداد - الكرادة - شارع الرئيسي</textarea>
                    </div>
                </div>
            </div>

            <!-- Employment Information Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-briefcase" style="margin-left: 10px; color: #9f7aea;"></i>
                    معلومات التوظيف
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- Employee Code -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">كود الموظف</label>
                        <input type="text" name="employee_code" value="EMP0001" readonly
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; background: #f7fafc; color: #4a5568;">
                    </div>

                    <!-- Department -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">القسم <span style="color: #f56565;">*</span></label>
                        <select name="department_id" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#9f7aea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $department->id == 1 ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المنصب <span style="color: #f56565;">*</span></label>
                        <select name="position_id" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#9f7aea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ $position->id == 1 ? 'selected' : '' }}>{{ $position->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hire Date -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">تاريخ التوظيف <span style="color: #f56565;">*</span></label>
                        <input type="date" name="hire_date" value="2023-01-01" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Basic Salary -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الراتب الأساسي <span style="color: #f56565;">*</span></label>
                        <input type="number" name="basic_salary" value="2500000" required min="0" step="0.01"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع التوظيف <span style="color: #f56565;">*</span></label>
                        <select name="employment_type" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#9f7aea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="full_time" selected>دوام كامل</option>
                            <option value="part_time">دوام جزئي</option>
                            <option value="contract">عقد</option>
                            <option value="internship">تدريب</option>
                            <option value="consultant">استشاري</option>
                        </select>
                    </div>

                    <!-- Employment Status -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">حالة التوظيف <span style="color: #f56565;">*</span></label>
                        <select name="employment_status" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#9f7aea'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="active" selected>نشط</option>
                            <option value="probation">تحت التجربة</option>
                            <option value="suspended">موقوف</option>
                            <option value="terminated">منتهي الخدمة</option>
                        </select>
                    </div>

                    <!-- Skills -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المهارات</label>
                        <input type="text" name="skills" value="القيادة، الإدارة، التخطيط"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="أدخل المهارات مفصولة بفواصل"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 20px; justify-content: center; padding-top: 30px; border-top: 2px solid #e2e8f0;">
                <button type="submit" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ التعديلات
                </button>
                
                <a href="{{ route('tenant.hr.employees.show', 1) }}" style="background: #4299e1; color: white; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                   onmouseover="this.style.transform='translateY(-2px)'" 
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-eye"></i>
                    عرض البيانات
                </a>
                
                <a href="{{ route('tenant.hr.employees.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
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
