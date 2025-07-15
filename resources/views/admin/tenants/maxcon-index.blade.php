@extends('layouts.modern')

@section('page-title', 'إدارة المستأجرين')
@section('page-description', 'إدارة شاملة لجميع المستأجرين في النظام')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-building" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة المستأجرين 🏢
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة لجميع المؤسسات في النظام
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-building" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">إدارة المؤسسات</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-bar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تتبع الأداء</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">إدارة متقدمة</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div style="display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
    <a href="{{ route('admin.tenants.export') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3); transition: all 0.3s ease;">
        <i class="fas fa-download"></i>
        تصدير البيانات
    </a>
    <a href="{{ route('admin.tenants.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
        <i class="fas fa-plus"></i>
        إضافة مستأجر جديد
    </a>
</div>

<div class="content-card">

    <!-- Search and Filters -->
    <div class="search-filters">
        <input type="text" class="search-box" placeholder="بحث">
        <select class="filter-select">
            <option>نوع الشركة</option>
            <option>صيدلية</option>
            <option>مستشفى</option>
            <option>عيادة</option>
        </select>
        <select class="filter-select">
            <option>جميع الأنواع</option>
            <option>نشط</option>
            <option>معطل</option>
        </select>
        <select class="filter-select">
            <option>الحالة</option>
            <option>نشط</option>
            <option>معطل</option>
        </select>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th>الإجراءات</th>
                <th>الحالة</th>
                <th>المستخدمين</th>
                <th>نوع الشركة</th>
                <th>تاريخ الانتهاء</th>
                <th>المستأجر</th>
                <th>النطاق</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <a href="{{ route('admin.tenants.show', 1) }}"
                           style="color: #4299e1; text-decoration: none; padding: 4px;"
                           title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.tenants.edit', 1) }}"
                           style="color: #4299e1; text-decoration: none; padding: 4px;"
                           title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteTenant(1)"
                                style="background: none; border: none; color: #e53e3e; cursor: pointer; padding: 4px;"
                                title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-active">نشط</span>
                </td>
                <td>1/10</td>
                <td>صيدلية</td>
                <td>2026-07-09<br><small style="color: #48bb78;">يوم متبقي 361 4793878903</small></td>
                <td>
                    <div>
                        <strong>mustafa</strong><br>
                        <small style="color: #718096;">أربيل أربيل</small>
                    </div>
                </td>
                <td>
                    <div>
                        <strong>Mustafa</strong><br>
                        <small style="color: #718096;">x@s.com</small>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <a href="{{ route('admin.tenants.show', 2) }}"
                           style="color: #4299e1; text-decoration: none; padding: 4px;"
                           title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.tenants.edit', 2) }}"
                           style="color: #4299e1; text-decoration: none; padding: 4px;"
                           title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deleteTenant(2)"
                                style="background: none; border: none; color: #e53e3e; cursor: pointer; padding: 4px;"
                                title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <span class="status-badge status-inactive">معطل</span>
                </td>
                <td>0/10</td>
                <td>صيدلية</td>
                <td>2026-07-09<br><small style="color: #48bb78;">يوم متبقي 361 61956146897</small></td>
                <td>
                    <div>
                        <strong>test</strong><br>
                        <small style="color: #718096;">Baghdad, Baghdad</small>
                    </div>
                </td>
                <td>
                    <div>
                        <strong>Test Pharmacy</strong><br>
                        <small style="color: #718096;">test@pharmacy.com</small>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-top: 30px;">
    <!-- إجمالي المستأجرين -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-building" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">2</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">مؤسسة</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">إجمالي المستأجرين</div>
            <div style="font-size: 14px; opacity: 0.9;">جميع المؤسسات المسجلة</div>
        </div>
    </div>

    <!-- المستأجرين النشطين -->
    <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(72, 187, 120, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">2</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">نشط</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المستأجرين النشطين</div>
            <div style="font-size: 14px; opacity: 0.9;">يستخدمون النظام حالياً</div>
        </div>
    </div>

    <!-- منتهي الصلاحية -->
    <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(237, 137, 54, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">0</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">منتهي</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">منتهي الصلاحية</div>
            <div style="font-size: 14px; opacity: 0.9;">يحتاج تجديد</div>
        </div>
    </div>

    <!-- يتبقى قريباً -->
    <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(245, 101, 101, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-clock" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">0</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">قريباً</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">يتبقى قريباً</div>
            <div style="font-size: 14px; opacity: 0.9;">ينتهي خلال أسبوع</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteTenant(tenantId) {
    if (confirm('هل أنت متأكد من حذف هذا المستأجر؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/tenants/${tenantId}`;

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
