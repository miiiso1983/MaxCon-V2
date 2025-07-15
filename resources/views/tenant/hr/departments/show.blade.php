@extends('layouts.modern')

@section('title', 'عرض تفاصيل القسم')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">عرض تفاصيل القسم</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">معلومات شاملة عن القسم والموظفين</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.departments.edit', 1) }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-edit"></i>
                    تعديل القسم
                </a>
                <a href="{{ route('tenant.hr.departments.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Department Profile Card -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 40px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        
        <!-- Department Header -->
        <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e2e8f0;">
            <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 20px; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 700;">
                <i class="fas fa-users"></i>
            </div>
            <div style="flex: 1;">
                <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 36px; font-weight: 700;">الموارد البشرية</h2>
                <p style="color: #4299e1; margin: 0 0 10px 0; font-size: 20px; font-weight: 600;">DEPT-HR-001</p>
                <div style="display: flex; gap: 15px; margin-top: 15px;">
                    <span style="background: #48bb78; color: white; padding: 8px 15px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                        نشط
                    </span>
                    <span style="background: #4299e1; color: white; padding: 8px 15px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-users" style="margin-left: 5px;"></i>
                        15 موظف
                    </span>
                    <span style="background: #9f7aea; color: white; padding: 8px 15px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-chart-line" style="margin-left: 5px;"></i>
                        95% أداء
                    </span>
                </div>
            </div>
        </div>

        <!-- Department Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            
            <!-- Basic Information -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: #48bb78;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">اسم القسم</label>
                        <div style="color: #2d3748; font-size: 16px; font-weight: 600;">الموارد البشرية</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">كود القسم</label>
                        <div style="color: #2d3748; font-size: 16px;">DEPT-HR-001</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">القسم الرئيسي</label>
                        <div style="color: #2d3748; font-size: 16px;">الإدارة العامة</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">مدير القسم</label>
                        <div style="color: #2d3748; font-size: 16px;">سارة أحمد محمد</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">الموقع</label>
                        <div style="color: #2d3748; font-size: 16px;">الطابق الثاني - مكتب 201</div>
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
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">رقم الهاتف</label>
                        <div style="color: #2d3748; font-size: 16px;">07901234567</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">البريد الإلكتروني</label>
                        <div style="color: #2d3748; font-size: 16px;">hr@company.com</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">رقم التحويلة</label>
                        <div style="color: #2d3748; font-size: 16px;">201</div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-money-bill-wave" style="color: #ed8936;"></i>
                    المعلومات المالية
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">الميزانية السنوية</label>
                        <div style="color: #2d3748; font-size: 16px; font-weight: 600;">50,000,000 دينار</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">المصروف الحالي</label>
                        <div style="color: #2d3748; font-size: 16px;">42,500,000 دينار</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">النسبة المستخدمة</label>
                        <div style="color: #ed8936; font-size: 16px; font-weight: 600;">85%</div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">مركز التكلفة</label>
                        <div style="color: #2d3748; font-size: 16px;">CC-HR-001</div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-line" style="color: #9f7aea;"></i>
                    مؤشرات الأداء
                </h3>
                
                <div style="space-y: 15px;">
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">نسبة الحضور</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="color: #48bb78; font-size: 16px; font-weight: 600;">95%</div>
                            <div style="background: #e2e8f0; border-radius: 10px; height: 6px; flex: 1;">
                                <div style="background: #48bb78; border-radius: 10px; height: 100%; width: 95%;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">الإنتاجية</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="color: #4299e1; font-size: 16px; font-weight: 600;">88%</div>
                            <div style="background: #e2e8f0; border-radius: 10px; height: 6px; flex: 1;">
                                <div style="background: #4299e1; border-radius: 10px; height: 100%; width: 88%;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #4a5568; font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">رضا الموظفين</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="color: #ed8936; font-size: 16px; font-weight: 600;">92%</div>
                            <div style="background: #e2e8f0; border-radius: 10px; height: 6px; flex: 1;">
                                <div style="background: #ed8936; border-radius: 10px; height: 100%; width: 92%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div style="margin-top: 30px; background: #f7fafc; border-radius: 15px; padding: 25px;">
            <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-file-alt" style="color: #4299e1;"></i>
                وصف القسم
            </h3>
            <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0;">
                قسم الموارد البشرية مسؤول عن إدارة جميع شؤون الموظفين في الشركة، بما في ذلك التوظيف والتدريب وإدارة الأداء والرواتب والمزايا. يعمل القسم على تطوير السياسات والإجراءات التي تضمن بيئة عمل إيجابية ومحفزة لجميع الموظفين.
            </p>
        </div>
    </div>

    <!-- Department Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">15</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي الموظفين</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-briefcase"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">5</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">عدد المناصب</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">95%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">نسبة الحضور</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-star"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">4.8</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">تقييم الأداء</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">42.5M</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي الرواتب</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-cogs" style="margin-left: 10px; color: #667eea;"></i>
            الإجراءات المتاحة
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            
            <a href="{{ route('tenant.hr.departments.edit', 1) }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-edit" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تعديل القسم</div>
            </a>

            <button onclick="alert('ميزة عرض الموظفين قيد التطوير')" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-users" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">عرض الموظفين</div>
            </button>

            <button onclick="alert('ميزة تقرير القسم قيد التطوير')" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-chart-bar" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تقرير القسم</div>
            </button>

            <button onclick="confirmDelete()" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-trash" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">حذف القسم</div>
            </button>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('هل أنت متأكد من حذف هذا القسم؟\n\nسيتم حذف جميع البيانات المرتبطة به نهائياً.\nهذا الإجراء لا يمكن التراجع عنه.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("tenant.hr.departments.destroy", 1) }}';
        
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
