@extends('layouts.modern')

@section('title', 'إضافة منصب جديد')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة منصب جديد</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إنشاء منصب جديد في الهيكل الوظيفي</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.positions.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Position Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form action="{{ route('tenant.hr.positions.store') }}" method="POST">
            @csrf
            
            <!-- Basic Information Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #4299e1;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- Position Title -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">مسمى المنصب <span style="color: #f56565;">*</span></label>
                        <input type="text" name="title" required 
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="أدخل مسمى المنصب"
                               onfocus="this.style.borderColor='#4299e1'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Position Code -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">كود المنصب <span style="color: #f56565;">*</span></label>
                        <input type="text" name="code"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: POS001 (اتركه فارغاً للتوليد التلقائي)"
                               onfocus="this.style.borderColor='#4299e1'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Department -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">القسم <span style="color: #f56565;">*</span></label>
                        <select name="department_id" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4299e1'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر القسم</option>
                            @foreach(($departments ?? []) as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position Level -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">مستوى المنصب <span style="color: #f56565;">*</span></label>
                        <select name="level" required 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4299e1'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر المستوى</option>
                            <option value="executive">تنفيذي</option>
                            <option value="manager">إداري</option>
                            <option value="senior">أول</option>
                            <option value="mid">متوسط</option>
                            <option value="junior">مبتدئ</option>
                        </select>
                    </div>

                    <!-- Reports To -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">يرفع تقارير إلى</label>
                        <select name="reports_to_position_id"
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4299e1'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">لا يوجد (منصب أعلى)</option>
                            @foreach(($positions ?? []) as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع التوظيف</label>
                        <select name="employment_type" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4299e1'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="full_time" selected>دوام كامل</option>
                            <option value="part_time">دوام جزئي</option>
                            <option value="contract">عقد</option>
                            <option value="consultant">استشاري</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وصف المنصب</label>
                        <textarea name="description" rows="4" 
                                  style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أدخل وصف مفصل لمهام ومسؤوليات المنصب"
                                  onfocus="this.style.borderColor='#4299e1'" 
                                  onblur="this.style.borderColor='#e2e8f0'"></textarea>
                    </div>
                </div>
            </div>

            <!-- Salary & Benefits Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-money-bill-wave" style="margin-left: 10px; color: #48bb78;"></i>
                    الراتب والمزايا
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- Min Salary -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأدنى للراتب (دينار)</label>
                        <input type="number" name="min_salary" min="0" step="0.01"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="0.00"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Max Salary -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأقصى للراتب (دينار)</label>
                        <input type="number" name="max_salary" min="0" step="0.01"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="0.00"
                               onfocus="this.style.borderColor='#48bb78'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Benefits -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المزايا والحوافز</label>
                        <textarea name="benefits" rows="3" 
                                  style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أدخل المزايا والحوافز المرتبطة بالمنصب"
                                  onfocus="this.style.borderColor='#48bb78'" 
                                  onblur="this.style.borderColor='#e2e8f0'"></textarea>
                    </div>
                </div>
            </div>

            <!-- Requirements Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-graduation-cap" style="margin-left: 10px; color: #9f7aea;"></i>
                    المتطلبات والمؤهلات
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                    
                    <!-- Education Level -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المستوى التعليمي المطلوب</label>
                        <select name="education_level" data-custom-select data-placeholder="اختر المستوى التعليمي..." data-searchable="false"
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#9f7aea'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">غير محدد</option>
                            <option value="elementary">ابتدائية</option>
                            <option value="middle_school">متوسطة</option>
                            <option value="high_school">الثانوية العامة</option>
                            <option value="vocational">مهني</option>
                            <option value="diploma">دبلوم</option>
                            <option value="bachelor">بكالوريوس</option>
                            <option value="master">ماجستير</option>
                            <option value="phd">دكتوراه</option>
                            <option value="postdoc">ما بعد الدكتوراه</option>
                        </select>
                    </div>

                    <!-- Experience Years -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">سنوات الخبرة المطلوبة</label>
                        <input type="number" name="experience_years" min="0"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="0"
                               onfocus="this.style.borderColor='#9f7aea'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Required Skills -->
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المهام والمسؤوليات</label>
                        <textarea name="responsibilities_text" rows="3"
                                  style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أدخل المهام والمسؤوليات، كل سطر عنصر"
                                  onfocus="this.style.borderColor='#9f7aea'"
                                  onblur="this.style.borderColor='#e2e8f0'"></textarea>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div style="margin-bottom: 40px;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <i class="fas fa-cogs" style="margin-left: 10px; color: #ed8936;"></i>
                    الإعدادات
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;">
                    
                    <!-- Status -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">حالة المنصب</label>
                        <select name="is_active" 
                                style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#ed8936'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="1" selected>نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>

                    <!-- Max Positions -->
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">العدد الأقصى للمناصب</label>
                        <input type="number" name="max_positions" min="1" value="1"
                               style="width: 100%; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#ed8936'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <!-- Is Management -->
                    <div>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: #2d3748; font-weight: 600;">
                            <input type="checkbox" name="is_management" value="1" style="width: 18px; height: 18px;">
                            <span>منصب إداري</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 20px; justify-content: center; padding-top: 30px; border-top: 2px solid #e2e8f0;">
                <button type="submit" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ المنصب
                </button>
                
                <a href="{{ route('tenant.hr.positions.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 40px; border: none; border-radius: 15px; font-size: 18px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
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
// Auto-generate position code based on title
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.querySelector('input[name="title"]');
    const codeInput = document.querySelector('input[name="code"]');
    
    titleInput.addEventListener('blur', function() {
        if (this.value && !codeInput.value) {
            // Generate code from title
            const title = this.value.trim();
            const words = title.split(' ');
            let code = 'POS';
            
            if (words.length >= 2) {
                code += words[0].charAt(0).toUpperCase() + words[1].charAt(0).toUpperCase();
            } else {
                code += title.substring(0, 2).toUpperCase();
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
            field.style.borderColor = '#4299e1';
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
