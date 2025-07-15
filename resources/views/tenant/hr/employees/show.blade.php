@extends('layouts.modern')

@section('title', 'عرض بيانات الموظف')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">عرض بيانات الموظف</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تفاصيل ومعلومات الموظف الكاملة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.employees.edit', 1) }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-edit"></i>
                    تعديل البيانات
                </a>
                <a href="{{ route('tenant.hr.employees.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Employee Profile Card -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        
        <!-- Employee Header -->
        <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e2e8f0;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 700;">
                أم
            </div>
            <div style="flex: 1;">
                <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 36px; font-weight: 700;">أحمد محمد</h2>
                <p style="color: #4299e1; margin: 0 0 10px 0; font-size: 20px; font-weight: 600;">مدير عام - الإدارة العامة</p>
                <div style="display: flex; gap: 15px; margin-top: 15px;">
                    <span style="background: #48bb78; color: white; padding: 8px 15px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                        نشط
                    </span>
                    <span style="background: #4299e1; color: white; padding: 8px 15px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-briefcase" style="margin-left: 5px;"></i>
                        دوام كامل
                    </span>
                    <span style="background: #9f7aea; color: white; padding: 8px 15px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-id-badge" style="margin-left: 5px;"></i>
                        EMP0001
                    </span>
                </div>
            </div>
        </div>

        <!-- Employee Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            
            <!-- Personal Information -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user" style="color: #48bb78;"></i>
                    المعلومات الشخصية
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">الاسم الكامل</label>
                        <div style="color: #2d3748; font-size: 16px; font-weight: 600;">أحمد محمد علي</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">رقم الهوية</label>
                        <div style="color: #2d3748; font-size: 16px;">12345678901</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">تاريخ الميلاد</label>
                        <div style="color: #2d3748; font-size: 16px;">1990-01-15</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">الجنس</label>
                        <div style="color: #2d3748; font-size: 16px;">ذكر</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">الحالة الاجتماعية</label>
                        <div style="color: #2d3748; font-size: 16px;">متزوج</div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-address-book" style="color: #4299e1;"></i>
                    معلومات الاتصال
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">البريد الإلكتروني</label>
                        <div style="color: #2d3748; font-size: 16px;">ahmed.mohamed@company.com</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">رقم الهاتف</label>
                        <div style="color: #2d3748; font-size: 16px;">07901234567</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">العنوان</label>
                        <div style="color: #2d3748; font-size: 16px;">بغداد - الكرادة - شارع الرئيسي</div>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-briefcase" style="color: #9f7aea;"></i>
                    معلومات التوظيف
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">كود الموظف</label>
                        <div style="color: #2d3748; font-size: 16px; font-weight: 600;">EMP0001</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">القسم</label>
                        <div style="color: #2d3748; font-size: 16px;">الإدارة العامة</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">المنصب</label>
                        <div style="color: #2d3748; font-size: 16px;">مدير عام</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">تاريخ التوظيف</label>
                        <div style="color: #2d3748; font-size: 16px;">2023-01-01</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">الراتب الأساسي</label>
                        <div style="color: #2d3748; font-size: 16px; font-weight: 600;">2,500,000 دينار</div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: #ed8936;"></i>
                    معلومات إضافية
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">المهارات</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">القيادة</span>
                            <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">الإدارة</span>
                            <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">التخطيط</span>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">اللغات</label>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">العربية</span>
                            <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">الإنجليزية</span>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">تاريخ الإنشاء</label>
                        <div style="color: #2d3748; font-size: 16px;">{{ now()->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-cogs" style="margin-left: 10px; color: #667eea;"></i>
            الإجراءات المتاحة
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            
            <a href="{{ route('tenant.hr.employees.edit', 1) }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-edit" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تعديل البيانات</div>
            </a>

            <button onclick="alert('ميزة طباعة البيانات قيد التطوير')" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-print" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">طباعة البيانات</div>
            </button>

            <a href="{{ route('tenant.hr.employees.attendance', 1) }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s; display: block;"
               onmouseover="this.style.transform='translateY(-5px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-calendar-check" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">سجل الحضور</div>
            </a>

            <button onclick="confirmDelete()" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-trash" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">حذف الموظف</div>
            </button>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('هل أنت متأكد من حذف هذا الموظف؟\n\nسيتم حذف جميع البيانات المرتبطة به نهائياً.\nهذا الإجراء لا يمكن التراجع عنه.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("tenant.hr.employees.destroy", 1) }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection
