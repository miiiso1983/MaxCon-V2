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
    <div class="settings-card settings-card-general">
        <div class="settings-header">
            <div class="settings-icon settings-icon-general">
                <i class="fas fa-cogs"></i>
            </div>
            <div>
                <h3 class="settings-title settings-title-general">الإعدادات العامة</h3>
                <p class="settings-subtitle settings-subtitle-general">إعدادات أساسية للنظام</p>
            </div>
        </div>

        <div class="settings-buttons">
            <button onclick="openSettings('company_info')" class="settings-btn-general">
                <i class="fas fa-building"></i>
                معلومات الشركة
            </button>
            <button onclick="openSettings('system_preferences')" class="settings-btn-general">
                <i class="fas fa-sliders-h"></i>
                تفضيلات النظام
            </button>
            <button onclick="openSettings('language_settings')" class="settings-btn-general">
                <i class="fas fa-language"></i>
                إعدادات اللغة
            </button>
            <button onclick="openSettings('timezone_settings')" class="settings-btn-general">
                <i class="fas fa-clock"></i>
                المنطقة الزمنية
            </button>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="settings-card settings-card-security">
        <div class="settings-header">
            <div class="settings-icon settings-icon-security">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h3 class="settings-title settings-title-security">إعدادات الأمان</h3>
                <p class="settings-subtitle settings-subtitle-security">حماية وأمان النظام</p>
            </div>
        </div>

        <div class="settings-buttons">
            <button onclick="openSettings('password_policy')" class="settings-btn-security">
                <i class="fas fa-key"></i>
                سياسة كلمات المرور
            </button>
            <button onclick="openSettings('two_factor_auth')" class="settings-btn-security">
                <i class="fas fa-mobile-alt"></i>
                المصادقة الثنائية
            </button>
            <button onclick="openSettings('session_management')" class="settings-btn-security">
                <i class="fas fa-user-clock"></i>
                إدارة الجلسات
            </button>
            <button onclick="openSettings('audit_logs')" class="settings-btn-security">
                <i class="fas fa-history"></i>
                سجلات المراجعة
            </button>
        </div>
    </div>

    <!-- Email Settings -->
    <div class="settings-card settings-card-email">
        <div class="settings-flex-header">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-envelope" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">إعدادات البريد الإلكتروني</h3>
                <p style="color: #1d4ed8; margin: 5px 0 0 0;">تكوين خدمات البريد</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="openSettings('smtp_settings')" class="settings-btn-email">
                <i class="fas fa-server"></i>
                إعدادات SMTP
            </button>
            <button onclick="openSettings('email_templates')" class="settings-btn-email">
                <i class="fas fa-file-alt"></i>
                قوالب البريد الإلكتروني
            </button>
            <button onclick="openSettings('notification_settings')" class="settings-btn-email">
                <i class="fas fa-bell"></i>
                إعدادات الإشعارات
            </button>
            <button onclick="openSettings('email_queue')" class="settings-btn-email">
                <i class="fas fa-list"></i>
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
            <button onclick="openSettings('backup_schedule')" class="settings-btn-backup">
                <i class="fas fa-calendar-alt"></i>
                جدولة النسخ الاحتياطي
            </button>
            <button onclick="openSettings('backup_storage')" class="settings-btn-backup">
                <i class="fas fa-cloud"></i>
                تخزين النسخ الاحتياطي
            </button>
            <button onclick="openSettings('restore_data')" class="settings-btn-backup">
                <i class="fas fa-undo"></i>
                استعادة البيانات
            </button>
            <button onclick="openSettings('backup_history')" class="settings-btn-backup">
                <i class="fas fa-history"></i>
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
            <button onclick="openSettings('api_settings')" class="settings-btn-integration">
                <i class="fas fa-code"></i>
                إعدادات API
            </button>
            <button onclick="openSettings('payment_gateways')" class="settings-btn-integration">
                <i class="fas fa-credit-card"></i>
                بوابات الدفع
            </button>
            <button onclick="openSettings('whatsapp_integration')" class="settings-btn-integration">
                <i class="fab fa-whatsapp"></i>
                تكامل واتساب
            </button>
            <button onclick="openSettings('third_party_services')" class="settings-btn-integration">
                <i class="fas fa-external-link-alt"></i>
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
            <button onclick="openSettings('cache_management')" class="settings-btn-maintenance">
                <i class="fas fa-memory"></i>
                إدارة التخزين المؤقت
            </button>
            <button onclick="openSettings('database_optimization')" class="settings-btn-maintenance">
                <i class="fas fa-database"></i>
                تحسين قاعدة البيانات
            </button>
            <button onclick="openSettings('system_logs')" class="settings-btn-maintenance">
                <i class="fas fa-file-alt"></i>
                سجلات النظام
            </button>
            <button onclick="openSettings('performance_monitoring')" class="settings-btn-maintenance">
                <i class="fas fa-chart-line"></i>
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

.settings-card {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.settings-card-general {
    border: 2px solid #10b981;
}

.settings-card-security {
    border: 2px solid #ef4444;
}

.settings-card-notifications {
    border: 2px solid #f59e0b;
}

.settings-card-backup {
    border: 2px solid #8b5cf6;
}

.settings-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.settings-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 15px;
    font-size: 24px;
}

.settings-icon-general {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.settings-icon-security {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.settings-icon-notifications {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.settings-icon-backup {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.settings-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.settings-title-general {
    color: #065f46;
}

.settings-title-security {
    color: #7f1d1d;
}

.settings-title-notifications {
    color: #92400e;
}

.settings-title-backup {
    color: #581c87;
}

.settings-subtitle {
    margin: 5px 0 0 0;
}

.settings-subtitle-general {
    color: #047857;
}

.settings-subtitle-security {
    color: #dc2626;
}

.settings-subtitle-notifications {
    color: #d97706;
}

.settings-subtitle-backup {
    color: #7c3aed;
}

.settings-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.settings-list li {
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.settings-list li:last-child {
    border-bottom: none;
}

.settings-list li span {
    color: #374151;
    font-weight: 500;
}

.settings-list li small {
    color: #6b7280;
    font-size: 12px;
}

.settings-btn {
    background: #f3f4f6;
    color: #374151;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.settings-btn:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

.settings-btn-primary {
    background: #10b981;
    color: white;
}

.settings-btn-primary:hover {
    background: #059669;
}

.settings-buttons {
    display: grid;
    gap: 10px;
}

.settings-btn-general {
    background: #f0fff4;
    border: 1px solid #10b981;
    color: #065f46;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: right;
    font-weight: 500;
}

.settings-btn-general:hover {
    background: #dcfce7;
}

.settings-btn-general i {
    margin-left: 8px;
}

.settings-btn-security {
    background: #eff6ff;
    border: 1px solid #3b82f6;
    color: #1e40af;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: right;
    font-weight: 500;
}

.settings-btn-security:hover {
    background: #dbeafe;
}

.settings-btn-security i {
    margin-left: 8px;
}

.settings-btn-notifications {
    background: #fffbeb;
    border: 1px solid #f59e0b;
    color: #92400e;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: right;
    font-weight: 500;
}

.settings-btn-notifications:hover {
    background: #fef3c7;
}

.settings-btn-notifications i {
    margin-left: 8px;
}

.settings-btn-backup {
    background: #faf5ff;
    border: 1px solid #8b5cf6;
    color: #7c3aed;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: right;
    font-weight: 500;
}

.settings-btn-backup:hover {
    background: #f3e8ff;
}

.settings-btn-backup i {
    margin-left: 8px;
}

.settings-btn-email {
    background: #eff6ff;
    border: 1px solid #3b82f6;
    color: #1e3a8a;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: right;
    font-weight: 500;
}

.settings-btn-email:hover {
    background: #dbeafe;
}

.settings-btn-email i {
    margin-left: 8px;
}

.settings-btn-integration {
    background: #fefce8;
    border: 1px solid #f59e0b;
    color: #92400e;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: right;
    font-weight: 500;
}

.settings-btn-integration:hover {
    background: #fef3c7;
}

.settings-btn-integration i {
    margin-left: 8px;
}

.settings-btn-maintenance {
    background: #f0f9ff;
    border: 1px solid #06b6d4;
    color: #164e63;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: right;
    font-weight: 500;
}

.settings-btn-maintenance:hover {
    background: #e0f2fe;
}

.settings-btn-maintenance i {
    margin-left: 8px;
}
.settings-card-email {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 2px solid #3b82f6;
}
.settings-flex-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
</style>

@endsection