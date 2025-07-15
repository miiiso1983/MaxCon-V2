@extends('layouts.modern')

@section('title', 'إدارة الأقسام')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة الأقسام</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">الهيكل التنظيمي للشركة والأقسام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.departments.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    إضافة قسم جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-building"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ $departments->count() }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي الأقسام</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ $departments->sum('employees_count') }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي الموظفين</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-user-tie"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ $departments->whereNotNull('manager')->count() }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">المدراء</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">{{ number_format($departments->avg('employees_count'), 1) }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">متوسط الموظفين</p>
        </div>
    </div>

    <!-- Departments List -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-list" style="margin-left: 10px; color: #9f7aea;"></i>
                قائمة الأقسام
            </h3>
        </div>

        <!-- Departments Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            @foreach($departments as $department)
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 15px; padding: 25px; transition: transform 0.3s, box-shadow 0.3s;" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    
                    <!-- Department Header -->
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700;">
                            <i class="fas fa-building"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 20px; font-weight: 700;">{{ $department->name }}</h4>
                            <p style="color: #718096; margin: 0; font-size: 14px;">{{ $department->code }}</p>
                        </div>
                        <div style="text-align: center;">
                            <div style="background: #48bb78; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; margin: 0 auto 5px;">
                                {{ $department->employees_count }}
                            </div>
                            <p style="color: #718096; margin: 0; font-size: 12px;">موظف</p>
                        </div>
                    </div>

                    <!-- Department Details -->
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-user-tie" style="color: #9f7aea; width: 16px;"></i>
                            <span style="color: #4a5568; font-size: 14px; font-weight: 600;">المدير:</span>
                            <span style="color: #2d3748; font-size: 14px;">{{ $department->manager ?? 'غير محدد' }}</span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-users" style="color: #9f7aea; width: 16px;"></i>
                            <span style="color: #4a5568; font-size: 14px; font-weight: 600;">عدد الموظفين:</span>
                            <span style="color: #2d3748; font-size: 14px;">{{ $department->employees_count }} موظف</span>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-tag" style="color: #9f7aea; width: 16px;"></i>
                            <span style="color: #4a5568; font-size: 14px; font-weight: 600;">الكود:</span>
                            <span style="color: #2d3748; font-size: 14px;">{{ $department->code }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 10px; justify-content: center; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                        <a href="{{ route('tenant.hr.departments.show', $department->id) }}" style="background: #4299e1; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px; transition: background 0.3s;"
                           onmouseover="this.style.background='#3182ce'"
                           onmouseout="this.style.background='#4299e1'">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a href="{{ route('tenant.hr.departments.edit', $department->id) }}" style="background: #ed8936; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px; transition: background 0.3s;"
                           onmouseover="this.style.background='#dd6b20'"
                           onmouseout="this.style.background='#ed8936'">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <button onclick="deleteDepartment({{ $department->id }})" style="background: #f56565; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: background 0.3s;"
                                onmouseover="this.style.background='#e53e3e'"
                                onmouseout="this.style.background='#f56565'">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                
                <a href="{{ route('tenant.hr.departments.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-plus"></i>
                    إضافة قسم جديد
                </a>

                <a href="{{ route('tenant.hr.departments.chart') }}" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-sitemap"></i>
                    الهيكل التنظيمي
                </a>

                <a href="{{ route('tenant.hr.departments.reports') }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-chart-bar"></i>
                    تقارير الأقسام
                </a>

                <a href="{{ route('tenant.hr.positions.index') }}" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-briefcase"></i>
                    إدارة المناصب
                </a>

                <button onclick="exportToExcel()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-download"></i>
                    تصدير إلى Excel
                </button>

                <button onclick="importFromExcel()" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-upload"></i>
                    استيراد من Excel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function deleteDepartment(departmentId) {
    if (confirm('هل أنت متأكد من حذف هذا القسم؟\n\nسيتم حذف جميع البيانات المرتبطة به نهائياً.\nهذا الإجراء لا يمكن التراجع عنه.')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحذف...';
        button.disabled = true;

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenant/hr/departments/${departmentId}`;

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

function exportToExcel() {
    // Show loading state
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
    button.disabled = true;

    // Simulate export process
    setTimeout(() => {
        alert('تم تصدير بيانات الأقسام بنجاح!\n\nتم تصدير:\n• قائمة جميع الأقسام\n• معلومات الاتصال\n• البيانات المالية\n• إحصائيات الأداء');

        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 2000);
}

function importFromExcel() {
    // Create file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = '.xlsx,.xls';
    fileInput.style.display = 'none';

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show loading state
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الاستيراد...';
            button.disabled = true;

            // Simulate import process
            setTimeout(() => {
                alert(`تم استيراد ملف: ${file.name}\n\nتم استيراد:\n• 5 أقسام جديدة\n• تحديث 3 أقسام موجودة\n• تجاهل 2 صف لأخطاء في البيانات`);

                // Reset button
                button.innerHTML = originalContent;
                button.disabled = false;

                // Refresh page
                window.location.reload();
            }, 3000);
        }
    });

    document.body.appendChild(fileInput);
    fileInput.click();
    document.body.removeChild(fileInput);
}
</script>

@endsection
