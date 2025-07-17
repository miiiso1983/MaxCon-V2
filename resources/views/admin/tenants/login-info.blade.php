@extends('layouts.modern')

@section('page-title', 'بيانات تسجيل الدخول للمستأجرين')
@section('page-description', 'بيانات الدخول لجميع المستأجرين المنشأين')

@section('content')
<div class="content-card">
    <h2 style="color: #2d3748; margin-bottom: 30px; text-align: center;">🔐 بيانات تسجيل الدخول للمستأجرين</h2>

    @php
        $tenants = \App\Models\Tenant::with(['users' => function($query) {
            $query->whereHas('roles', function($q) {
                $q->where('name', 'tenant-admin');
            });
        }])->get();
    @endphp

    @if($tenants->count() > 0)
        @foreach($tenants as $tenant)
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; padding: 25px; margin-bottom: 25px; color: white; position: relative; overflow: hidden;">
                <!-- Background decorations -->
                <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -20px; left: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

                <div style="position: relative; z-index: 2;">
                    <!-- Header -->
                    <div style="display: flex; align-items: center; margin-bottom: 20px;">
                        <div style="background: rgba(255,255,255,0.2); border-radius: 12px; padding: 12px; margin-left: 15px;">
                            <i class="fas fa-building" style="font-size: 24px;"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 22px; font-weight: 700;">{{ $tenant->name }}</h3>
                            <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">
                                خطة {{ $tenant->plan }} - {{ $tenant->max_users }} مستخدم
                            </p>
                        </div>
                        <div style="margin-right: auto;">
                            <span style="background: {{ $tenant->is_active ? 'rgba(72, 187, 120, 0.3)' : 'rgba(245, 101, 101, 0.3)' }}; 
                                         color: {{ $tenant->is_active ? '#48bb78' : '#f56565' }}; 
                                         padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $tenant->is_active ? '✅ نشط' : '❌ معطل' }}
                            </span>
                        </div>
                    </div>

                    <!-- Login Info -->
                    @if($tenant->users->count() > 0)
                        @foreach($tenant->users as $admin)
                            <div style="background: rgba(255,255,255,0.15); border-radius: 12px; padding: 20px; margin-bottom: 15px; backdrop-filter: blur(10px);">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                    <!-- User Info -->
                                    <div>
                                        <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; display: flex; align-items: center;">
                                            <i class="fas fa-user-tie" style="margin-left: 8px;"></i>
                                            {{ $admin->name }}
                                        </h4>
                                        
                                        <div style="margin-bottom: 10px;">
                                            <label style="display: block; font-size: 12px; opacity: 0.8; margin-bottom: 4px;">البريد الإلكتروني</label>
                                            <div style="background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px; font-family: monospace; font-size: 14px; display: flex; align-items: center; justify-content: space-between;">
                                                <span>{{ $admin->email }}</span>
                                                <button onclick="copyToClipboard('{{ $admin->email }}')" style="background: none; border: none; color: white; cursor: pointer; opacity: 0.7; hover:opacity: 1;">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 10px;">
                                            <label style="display: block; font-size: 12px; opacity: 0.8; margin-bottom: 4px;">رقم الموبايل</label>
                                            <div style="background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px; font-size: 14px;">
                                                {{ $admin->phone ?? 'غير محدد' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Login Info -->
                                    <div>
                                        <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; display: flex; align-items: center;">
                                            <i class="fas fa-key" style="margin-left: 8px;"></i>
                                            بيانات تسجيل الدخول
                                        </h4>

                                        <div style="margin-bottom: 10px;">
                                            <label style="display: block; font-size: 12px; opacity: 0.8; margin-bottom: 4px;">كلمة المرور</label>
                                            <div style="background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px; font-family: monospace; font-size: 14px; display: flex; align-items: center; justify-content: space-between;">
                                                <span>AdminPass123!</span>
                                                <button onclick="copyToClipboard('AdminPass123!')" style="background: none; border: none; color: white; cursor: pointer; opacity: 0.7; hover:opacity: 1;">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 15px;">
                                            <label style="display: block; font-size: 12px; opacity: 0.8; margin-bottom: 4px;">رابط تسجيل الدخول</label>
                                            <div style="background: rgba(255,255,255,0.2); padding: 8px 12px; border-radius: 6px; font-size: 12px; display: flex; align-items: center; justify-content: space-between;">
                                                <span>{{ url('/login') }}</span>
                                                <button onclick="copyToClipboard('{{ url('/login') }}')" style="background: none; border: none; color: white; cursor: pointer; opacity: 0.7; hover:opacity: 1;">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Quick Actions -->
                                        <div style="display: flex; gap: 10px;">
                                            <a href="{{ url('/login') }}" target="_blank" 
                                               style="background: rgba(72, 187, 120, 0.3); color: #48bb78; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                                <i class="fas fa-external-link-alt"></i>
                                                تسجيل دخول
                                            </a>
                                            <a href="{{ route('admin.tenants.show', $tenant->id) }}" 
                                               style="background: rgba(66, 153, 225, 0.3); color: #4299e1; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                                <i class="fas fa-eye"></i>
                                                عرض التفاصيل
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="background: rgba(245, 101, 101, 0.2); border-radius: 8px; padding: 15px; text-align: center;">
                            <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                            لا يوجد مدير مؤسسة لهذا المستأجر
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 60px; color: #718096;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #f7fafc; color: #a0aec0; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-building"></i>
            </div>
            <h3 style="color: #2d3748; margin-bottom: 10px;">لا توجد مستأجرين</h3>
            <p style="margin-bottom: 20px;">لم يتم إنشاء أي مستأجرين بعد</p>
            <a href="{{ route('admin.tenants.create') }}" style="background: #667eea; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                إنشاء مستأجر جديد
            </a>
        </div>
    @endif

    <!-- Instructions -->
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 12px; padding: 20px; margin-top: 30px;">
        <h3 style="color: #856404; margin-bottom: 15px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="margin-left: 8px;"></i>
            تعليمات تسجيل الدخول
        </h3>
        <div style="color: #856404; line-height: 1.6;">
            <p><strong>1.</strong> انقر على رابط "تسجيل دخول" بجانب المستأجر المطلوب</p>
            <p><strong>2.</strong> أدخل البريد الإلكتروني وكلمة المرور المعروضة أعلاه</p>
            <p><strong>3.</strong> سيتم توجيهك إلى لوحة تحكم المؤسسة</p>
            <p><strong>4.</strong> يمكنك إدارة المستخدمين والأدوار من لوحة التحكم</p>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #48bb78;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            font-weight: 600;
        `;
        toast.textContent = '✅ تم النسخ بنجاح!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 2000);
    });
}
</script>
@endsection
