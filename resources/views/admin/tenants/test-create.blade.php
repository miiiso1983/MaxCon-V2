@extends('layouts.modern')

@section('page-title', 'اختبار إنشاء مستأجر')
@section('page-description', 'صفحة اختبار لإنشاء مستأجر جديد')

@section('content')
<div class="content-card">
    <h2 style="color: #2d3748; margin-bottom: 30px;">🧪 اختبار إنشاء مستأجر جديد</h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            ❌ {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tenants.store') }}" style="max-width: 800px;">
        @csrf

        <!-- معلومات المؤسسة -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
            <h3 style="color: #495057; margin-bottom: 20px;">🏢 معلومات المؤسسة</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">اسم المؤسسة *</label>
                    <input type="text" name="name" value="صيدلية النور الطبية" required
                           style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">الخطة</label>
                    <select name="plan" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                        <option value="basic">أساسي</option>
                        <option value="premium" selected>متقدم</option>
                        <option value="enterprise">مؤسسي</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">عدد المستخدمين</label>
                    <input type="number" name="max_users" value="50" min="1" required
                           style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">حد التخزين (GB)</label>
                    <input type="number" name="storage_limit" value="20" min="1" required
                           style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                </div>
            </div>
        </div>

        <!-- معلومات المدير -->
        <div style="background: #e8f5e8; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
            <h3 style="color: #495057; margin-bottom: 20px;">👤 معلومات مدير المؤسسة</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">اسم المدير *</label>
                    <input type="text" name="admin_name" value="محمد علي الصيدلاني" required
                           style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">البريد الإلكتروني *</label>
                    <input type="email" name="admin_email" value="admin@alnoor-pharmacy.com" required
                           style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">رقم الموبايل *</label>
                    <input type="text" name="admin_phone" value="+964 750 555 1234" required
                           style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">كلمة المرور *</label>
                    <input type="password" name="admin_password" value="AdminPass123!" required
                           style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px;">
                </div>
            </div>
        </div>

        <!-- أزرار الحفظ -->
        <div style="display: flex; gap: 15px; justify-content: center;">
            <button type="submit" style="background: #28a745; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                🚀 إنشاء المستأجر
            </button>
            <a href="{{ route('admin.tenants.index') }}" style="background: #6c757d; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                🔙 العودة
            </a>
        </div>
    </form>

    <!-- معلومات الاختبار -->
    <div style="background: #fff3cd; padding: 20px; border-radius: 12px; margin-top: 30px; border: 1px solid #ffeaa7;">
        <h3 style="color: #856404; margin-bottom: 15px;">📋 معلومات الاختبار</h3>
        <div style="color: #856404;">
            <p><strong>🏢 اسم المؤسسة:</strong> صيدلية النور الطبية</p>
            <p><strong>📧 بريد المدير:</strong> admin@alnoor-pharmacy.com</p>
            <p><strong>🔑 كلمة المرور:</strong> AdminPass123!</p>
            <p><strong>📱 رقم الموبايل:</strong> +964 750 555 1234</p>
            <p><strong>💼 الخطة:</strong> متقدم (50 مستخدم - 20GB)</p>
        </div>
    </div>

    <!-- المستأجرين الموجودين -->
    <div style="background: #e7f3ff; padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px solid #b3d9ff;">
        <h3 style="color: #004085; margin-bottom: 15px;">📊 المستأجرين الموجودين</h3>
        <div style="color: #004085;">
            @php
                $tenants = \App\Models\Tenant::latest()->take(5)->get();
            @endphp
            
            @if($tenants->count() > 0)
                @foreach($tenants as $tenant)
                    <div style="background: white; padding: 10px; border-radius: 6px; margin-bottom: 10px; border: 1px solid #b3d9ff;">
                        <strong>{{ $tenant->name }}</strong> 
                        <span style="color: #6c757d;">({{ $tenant->plan }} - {{ $tenant->max_users }} مستخدم)</span>
                        <span style="float: left; color: {{ $tenant->is_active ? '#28a745' : '#dc3545' }};">
                            {{ $tenant->is_active ? '✅ نشط' : '❌ معطل' }}
                        </span>
                    </div>
                @endforeach
            @else
                <p style="color: #6c757d; font-style: italic;">لا توجد مستأجرين حالياً</p>
            @endif
        </div>
    </div>
</div>

<script>
// تحديث البريد الإلكتروني تلقائياً عند تغيير اسم المؤسسة
document.querySelector('input[name="name"]').addEventListener('input', function() {
    const name = this.value;
    const email = name.toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]/g, '')
        + '@pharmacy.com';
    document.querySelector('input[name="admin_email"]').value = 'admin@' + email.split('@')[1];
});
</script>
@endsection
