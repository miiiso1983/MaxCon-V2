@extends('layouts.modern')

@section('title', 'إدارة الموظفين')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة الموظفين</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة ملفات الموظفين والبيانات الشخصية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.employees.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-user-plus"></i>
                    إضافة موظف جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form method="GET" action="{{ route('tenant.hr.employees.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث بالاسم، الكود، البريد..."
                       style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
            </div>
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">القسم</label>
                <select name="department_id" style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <option value="">جميع الأقسام</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المنصب</label>
                <select name="position_id" style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <option value="">جميع المناصب</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                            {{ $position->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">حالة التوظيف</label>
                <select name="employment_status" style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('employment_status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="probation" {{ request('employment_status') == 'probation' ? 'selected' : '' }}>تحت التجربة</option>
                    <option value="suspended" {{ request('employment_status') == 'suspended' ? 'selected' : '' }}>موقوف</option>
                    <option value="terminated" {{ request('employment_status') == 'terminated' ? 'selected' : '' }}>منتهي الخدمة</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-search"></i> بحث
                </button>
                <a href="{{ route('tenant.hr.employees.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; display: flex; align-items: center;">
                    <i class="fas fa-times"></i> مسح
                </a>
            </div>
        </form>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.employees.create') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-user-plus"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">إضافة موظف</h4>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s;"
             onclick="showImportModal()"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
            <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px; transition: transform 0.3s;">
                <i class="fas fa-upload"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">استيراد من Excel</h4>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: all 0.3s;"
             onclick="exportToExcel()"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 40px rgba(0,0,0,0.15)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
            <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px; transition: transform 0.3s;">
                <i class="fas fa-download"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">تصدير إلى Excel</h4>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.departments.index') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-building"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">إدارة الأقسام</h4>
        </div>
    </div>

    <!-- Employees List -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-list" style="margin-left: 10px; color: #667eea;"></i>
                قائمة الموظفين ({{ $employees->total() }})
            </h3>
        </div>

        @if($employees->isNotEmpty())
            <!-- Employees Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
                @foreach($employees as $employee)
                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 15px; padding: 20px; transition: transform 0.3s, box-shadow 0.3s;" 
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'" 
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                        
                        <!-- Employee Header -->
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700;">
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                            </div>
                            <div style="flex: 1;">
                                <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 18px; font-weight: 700;">{{ $employee->full_name }}</h4>
                                <p style="color: #718096; margin: 0; font-size: 14px;">{{ $employee->employee_code }}</p>
                            </div>
                            <div style="display: flex; gap: 5px;">
                                @if($employee->employment_status === 'active')
                                    <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">نشط</span>
                                @elseif($employee->employment_status === 'probation')
                                    <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">تجربة</span>
                                @elseif($employee->employment_status === 'suspended')
                                    <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">موقوف</span>
                                @elseif($employee->employment_status === 'terminated')
                                    <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">منتهي الخدمة</span>
                                @else
                                    <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">غير محدد</span>
                                @endif
                            </div>
                        </div>

                        <!-- Employee Details -->
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <i class="fas fa-building" style="color: #667eea; width: 16px;"></i>
                                <span style="color: #4a5568; font-size: 14px;">{{ $employee->department->name ?? 'غير محدد' }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <i class="fas fa-briefcase" style="color: #667eea; width: 16px;"></i>
                                <span style="color: #4a5568; font-size: 14px;">{{ $employee->position->title ?? 'غير محدد' }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <i class="fas fa-envelope" style="color: #667eea; width: 16px;"></i>
                                <span style="color: #4a5568; font-size: 14px;">{{ $employee->email }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <i class="fas fa-phone" style="color: #667eea; width: 16px;"></i>
                                <span style="color: #4a5568; font-size: 14px;">{{ $employee->mobile }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fas fa-calendar" style="color: #667eea; width: 16px;"></i>
                                <span style="color: #4a5568; font-size: 14px;">تاريخ التوظيف: {{ $employee->hire_date->format('Y-m-d') }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div style="display: flex; gap: 10px; justify-content: center; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                            <a href="{{ route('tenant.hr.employees.show', $employee->id) }}" style="background: #4299e1; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px; transition: background 0.3s;"
                               onmouseover="this.style.background='#3182ce'"
                               onmouseout="this.style.background='#4299e1'">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                            <a href="{{ route('tenant.hr.employees.edit', $employee->id) }}" style="background: #ed8936; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; display: flex; align-items: center; gap: 5px; transition: background 0.3s;"
                               onmouseover="this.style.background='#dd6b20'"
                               onmouseout="this.style.background='#ed8936'">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            <button onclick="deleteEmployee({{ $employee->id }})" style="background: #f56565; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: background 0.3s;"
                                    onmouseover="this.style.background='#e53e3e'"
                                    onmouseout="this.style.background='#f56565'">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $employees->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #718096;">
                <i class="fas fa-users" style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;"></i>
                <h3 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">لا يوجد موظفون</h3>
                <p style="margin: 0 0 20px 0; font-size: 16px;">لم يتم العثور على موظفين بالمعايير المحددة</p>
                <a href="{{ route('tenant.hr.employees.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-plus"></i>
                    إضافة أول موظف
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function showImportModal() {
    // Show loading message
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
    event.target.style.pointerEvents = 'none';

    // Navigate to import page
    setTimeout(() => {
        window.location.href = '{{ route("tenant.hr.employees.import.form") }}';
    }, 500);
}

function exportToExcel() {
    // Get current filters
    const searchParams = new URLSearchParams();

    // Add current filter values
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && searchInput.value) {
        searchParams.append('search', searchInput.value);
    }

    const departmentSelect = document.querySelector('select[name="department_id"]');
    if (departmentSelect && departmentSelect.value) {
        searchParams.append('department_id', departmentSelect.value);
    }

    const positionSelect = document.querySelector('select[name="position_id"]');
    if (positionSelect && positionSelect.value) {
        searchParams.append('position_id', positionSelect.value);
    }

    const statusSelect = document.querySelector('select[name="employment_status"]');
    if (statusSelect && statusSelect.value) {
        searchParams.append('employment_status', statusSelect.value);
    }

    // Show loading message
    const button = event.target.closest('div');
    const originalContent = button.innerHTML;
    button.innerHTML = `
        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px; transition: transform 0.3s;">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
        <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">جاري التصدير...</h4>
    `;
    button.style.pointerEvents = 'none';

    // Build export URL with filters
    const exportUrl = '{{ route("tenant.hr.employees.export") }}' + (searchParams.toString() ? '?' + searchParams.toString() : '');

    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Reset button after a short delay
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.style.pointerEvents = 'auto';

        // Show success message
        showNotification('تم تصدير البيانات بنجاح!', 'success');
    }, 2000);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#48bb78' : type === 'error' ? '#f56565' : '#4299e1'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 10000;
        font-weight: 600;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;

    // Add animation keyframes
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function deleteEmployee(employeeId) {
    if (confirm('هل أنت متأكد من حذف هذا الموظف؟\n\nسيتم حذف جميع البيانات المرتبطة به نهائياً.\nهذا الإجراء لا يمكن التراجع عنه.')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحذف...';
        button.disabled = true;

        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenant/hr/employees/${employeeId}`;

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
