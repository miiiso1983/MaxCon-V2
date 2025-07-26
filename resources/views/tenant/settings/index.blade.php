@extends('layouts.modern')

@section('title', 'إعدادات النظام')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 15px 0; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-cog"></i>
            إعدادات النظام
        </h1>
        <p style="font-size: 18px; opacity: 0.9; margin: 0;">
            إدارة شاملة لجميع إعدادات النظام والتخصيصات
        </p>
    </div>
    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"rgba(255,255,255,0.1)\"/></svg>') repeat; animation: float 20s infinite linear;"></div>
</div>

<!-- Settings Categories -->
<div class="settings-grid">

    <!-- General Settings -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #10b981;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-cogs" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #065f46; margin: 0;">الإعدادات العامة</h3>
                <p style="color: #047857; margin: 5px 0 0 0;">إعدادات أساسية للنظام</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="openSettings('company_info')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                <i class="fas fa-building" style="margin-left: 8px;"></i>
                معلومات الشركة
            </button>
            <button onclick="openSettings('system_preferences')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                <i class="fas fa-sliders-h" style="margin-left: 8px;"></i>
                تفضيلات النظام
            </button>
            <button onclick="openSettings('language_settings')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                <i class="fas fa-language" style="margin-left: 8px;"></i>
                إعدادات اللغة
            </button>
            <button onclick="openSettings('timezone_settings')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                <i class="fas fa-clock" style="margin-left: 8px;"></i>
                المنطقة الزمنية
            </button>
        </div>
    </div>

    <!-- Security Settings -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #ef4444;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-shield-alt" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #7f1d1d; margin: 0;">إعدادات الأمان</h3>
                <p style="color: #dc2626; margin: 5px 0 0 0;">حماية وأمان النظام</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="openSettings('password_policy')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-key" style="margin-left: 8px;"></i>
                سياسة كلمات المرور
            </button>
            <button onclick="openSettings('two_factor_auth')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-mobile-alt" style="margin-left: 8px;"></i>
                المصادقة الثنائية
            </button>
            <button onclick="openSettings('session_management')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-user-clock" style="margin-left: 8px;"></i>
                إدارة الجلسات
            </button>
            <button onclick="openSettings('audit_logs')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-history" style="margin-left: 8px;"></i>
                سجلات المراجعة
            </button>
        </div>
    </div>

    <!-- Email Settings -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #3b82f6;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-envelope" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">إعدادات البريد الإلكتروني</h3>
                <p style="color: #1d4ed8; margin: 5px 0 0 0;">تكوين خدمات البريد</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="openSettings('smtp_settings')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <i class="fas fa-server" style="margin-left: 8px;"></i>
                إعدادات SMTP
            </button>
            <button onclick="openSettings('email_templates')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <i class="fas fa-file-alt" style="margin-left: 8px;"></i>
                قوالب البريد الإلكتروني
            </button>
            <button onclick="openSettings('notification_settings')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <i class="fas fa-bell" style="margin-left: 8px;"></i>
                إعدادات الإشعارات
            </button>
            <button onclick="openSettings('email_queue')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <i class="fas fa-list" style="margin-left: 8px;"></i>
                طابور البريد الإلكتروني
            </button>
        </div>
    </div>

    <!-- Backup Settings -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #8b5cf6;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-database" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #581c87; margin: 0;">النسخ الاحتياطي</h3>
                <p style="color: #7c3aed; margin: 5px 0 0 0;">حفظ واستعادة البيانات</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="openSettings('backup_schedule')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-calendar-alt" style="margin-left: 8px;"></i>
                جدولة النسخ الاحتياطي
            </button>
            <button onclick="openSettings('backup_storage')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-cloud" style="margin-left: 8px;"></i>
                تخزين النسخ الاحتياطي
            </button>
            <button onclick="openSettings('restore_data')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-undo" style="margin-left: 8px;"></i>
                استعادة البيانات
            </button>
            <button onclick="openSettings('backup_history')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-history" style="margin-left: 8px;"></i>
                تاريخ النسخ الاحتياطي
            </button>
        </div>
    </div>

    <!-- Integration Settings -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #f59e0b;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-plug" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #92400e; margin: 0;">التكاملات الخارجية</h3>
                <p style="color: #d97706; margin: 5px 0 0 0;">ربط مع الأنظمة الخارجية</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="openSettings('api_settings')" style="background: #fefce8; border: 1px solid #f59e0b; color: #92400e; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fef3c7'" onmouseout="this.style.background='#fefce8'">
                <i class="fas fa-code" style="margin-left: 8px;"></i>
                إعدادات API
            </button>
            <button onclick="openSettings('payment_gateways')" style="background: #fefce8; border: 1px solid #f59e0b; color: #92400e; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fef3c7'" onmouseout="this.style.background='#fefce8'">
                <i class="fas fa-credit-card" style="margin-left: 8px;"></i>
                بوابات الدفع
            </button>
            <button onclick="openSettings('whatsapp_integration')" style="background: #fefce8; border: 1px solid #f59e0b; color: #92400e; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fef3c7'" onmouseout="this.style.background='#fefce8'">
                <i class="fab fa-whatsapp" style="margin-left: 8px;"></i>
                تكامل واتساب
            </button>
            <button onclick="openSettings('third_party_services')" style="background: #fefce8; border: 1px solid #f59e0b; color: #92400e; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fef3c7'" onmouseout="this.style.background='#fefce8'">
                <i class="fas fa-external-link-alt" style="margin-left: 8px;"></i>
                خدمات خارجية
            </button>
        </div>
    </div>

    <!-- System Maintenance -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #06b6d4;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-tools" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #164e63; margin: 0;">صيانة النظام</h3>
                <p style="color: #0891b2; margin: 5px 0 0 0;">أدوات الصيانة والتحسين</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="openSettings('cache_management')" style="background: #f0f9ff; border: 1px solid #06b6d4; color: #164e63; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                <i class="fas fa-memory" style="margin-left: 8px;"></i>
                إدارة التخزين المؤقت
            </button>
            <button onclick="openSettings('database_optimization')" style="background: #f0f9ff; border: 1px solid #06b6d4; color: #164e63; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                <i class="fas fa-database" style="margin-left: 8px;"></i>
                تحسين قاعدة البيانات
            </button>
            <button onclick="openSettings('system_logs')" style="background: #f0f9ff; border: 1px solid #06b6d4; color: #164e63; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                <i class="fas fa-file-alt" style="margin-left: 8px;"></i>
                سجلات النظام
            </button>
            <button onclick="openSettings('performance_monitoring')" style="background: #f0f9ff; border: 1px solid #06b6d4; color: #164e63; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                مراقبة الأداء
            </button>
        </div>
    </div>
</div>

<script>
function openSettings(settingType) {
    const settingNames = {
        'company_info': 'معلومات الشركة',
        'system_preferences': 'تفضيلات النظام',
        'language_settings': 'إعدادات اللغة',
        'timezone_settings': 'المنطقة الزمنية',
        'password_policy': 'سياسة كلمات المرور',
        'two_factor_auth': 'المصادقة الثنائية',
        'session_management': 'إدارة الجلسات',
        'audit_logs': 'سجلات المراجعة',
        'smtp_settings': 'إعدادات SMTP',
        'email_templates': 'قوالب البريد الإلكتروني',
        'notification_settings': 'إعدادات الإشعارات',
        'email_queue': 'طابور البريد الإلكتروني',
        'backup_schedule': 'جدولة النسخ الاحتياطي',
        'backup_storage': 'تخزين النسخ الاحتياطي',
        'restore_data': 'استعادة البيانات',
        'backup_history': 'تاريخ النسخ الاحتياطي',
        'api_settings': 'إعدادات API',
        'payment_gateways': 'بوابات الدفع',
        'whatsapp_integration': 'تكامل واتساب',
        'third_party_services': 'خدمات خارجية',
        'cache_management': 'إدارة التخزين المؤقت',
        'database_optimization': 'تحسين قاعدة البيانات',
        'system_logs': 'سجلات النظام',
        'performance_monitoring': 'مراقبة الأداء'
    };

    alert(`فتح إعدادات: ${settingNames[settingType]}\n\nسيتم فتح صفحة الإعدادات المحددة مع جميع الخيارات المتاحة للتخصيص والتكوين.`);
}

// Add animation effects
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Settings page loaded successfully!');
});
</script>

<style>
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
}
</style>

@endsection