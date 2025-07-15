@extends('layouts.modern')

@section('title', 'اختبار أزرار الشؤون التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="text-align: center;">
            <h1 style="color: #2d3748; margin: 0 0 15px 0; font-size: 32px; font-weight: 700;">اختبار أزرار الشؤون التنظيمية</h1>
            <p style="color: #718096; margin: 0; font-size: 16px;">جميع الأزرار تعمل بشكل صحيح مع رسائل تأكيد</p>
        </div>
    </div>

    <!-- Test Buttons Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
        
        <!-- Company Registration Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-building" style="margin-left: 10px;"></i>
                تسجيل الشركات
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showAddCompanyModal()" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    إضافة شركة جديدة
                </button>
                <button onclick="showImportModal()" style="background: rgba(79, 172, 254, 0.1); color: #4facfe; padding: 12px 20px; border: 2px solid #4facfe; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-upload" style="margin-left: 8px;"></i>
                    استيراد من Excel
                </button>
                <button onclick="exportToExcel()" style="background: rgba(79, 172, 254, 0.1); color: #4facfe; padding: 12px 20px; border: 2px solid #4facfe; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تصدير التقرير
                </button>
            </div>
        </div>

        <!-- Product Registration Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-pills" style="margin-left: 10px;"></i>
                تسجيل المنتجات
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showAddProductModal()" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    إضافة منتج جديد
                </button>
                <button onclick="showImportProductsModal()" style="background: rgba(168, 237, 234, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #a8edea; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-upload" style="margin-left: 8px;"></i>
                    استيراد من Excel
                </button>
                <button onclick="exportProductsToExcel()" style="background: rgba(168, 237, 234, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #a8edea; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تصدير التقرير
                </button>
            </div>
        </div>

        <!-- Laboratory Tests Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-flask" style="margin-left: 10px;"></i>
                الفحوصات المخبرية
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showAddTestModal()" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    إضافة فحص جديد
                </button>
                <button onclick="showScheduleModal()" style="background: rgba(255, 236, 210, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #ffecd2; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                    جدولة الفحوصات
                </button>
                <button onclick="exportTestsToExcel()" style="background: rgba(255, 236, 210, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #ffecd2; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تصدير النتائج
                </button>
            </div>
        </div>

        <!-- Inspections Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-search" style="margin-left: 10px;"></i>
                التفتيش التنظيمي
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showScheduleInspectionModal()" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    جدولة تفتيش جديد
                </button>
                <button onclick="showCalendarView()" style="background: rgba(255, 154, 158, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #ff9a9e; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-calendar-alt" style="margin-left: 8px;"></i>
                    عرض التقويم
                </button>
                <button onclick="exportInspectionsToExcel()" style="background: rgba(255, 154, 158, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #ff9a9e; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تصدير التقارير
                </button>
            </div>
        </div>

        <!-- Certificates Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-certificate" style="margin-left: 10px;"></i>
                شهادات الجودة
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showAddCertificateModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    إضافة شهادة جديدة
                </button>
                <button onclick="showRenewalModal()" style="background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 12px 20px; border: 2px solid #667eea; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-sync" style="margin-left: 8px;"></i>
                    تجديد الشهادات
                </button>
                <button onclick="exportCertificatesToExcel()" style="background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 12px 20px; border: 2px solid #667eea; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تصدير الشهادات
                </button>
            </div>
        </div>

        <!-- Recalls Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-exclamation-triangle" style="margin-left: 10px;"></i>
                سحب المنتجات
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showInitiateRecallModal()" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    بدء عملية سحب
                </button>
                <button onclick="showNotificationsModal()" style="background: rgba(240, 147, 251, 0.1); color: #f093fb; padding: 12px 20px; border: 2px solid #f093fb; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-bell" style="margin-left: 8px;"></i>
                    إدارة الإشعارات
                </button>
                <button onclick="exportRecallsToExcel()" style="background: rgba(240, 147, 251, 0.1); color: #f093fb; padding: 12px 20px; border: 2px solid #f093fb; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تصدير التقارير
                </button>
            </div>
        </div>

        <!-- Reports Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-file-alt" style="margin-left: 10px;"></i>
                التقارير التنظيمية
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showCreateReportModal()" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    إنشاء تقرير جديد
                </button>
                <button onclick="showTemplatesModal()" style="background: rgba(250, 112, 154, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #fa709a; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-file-alt" style="margin-left: 8px;"></i>
                    قوالب التقارير
                </button>
                <button onclick="exportReportsToExcel()" style="background: rgba(250, 112, 154, 0.1); color: #2d3748; padding: 12px 20px; border: 2px solid #fa709a; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-download" style="margin-left: 8px;"></i>
                    تصدير التقارير
                </button>
            </div>
        </div>

        <!-- Documents Buttons -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-folder-open" style="margin-left: 10px;"></i>
                الوثائق التنظيمية
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button onclick="showAddDocumentModal()" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                    إضافة وثيقة جديدة
                </button>
                <button onclick="showBulkUploadModal()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 12px 20px; border: 2px solid #4ecdc4; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-upload" style="margin-left: 8px;"></i>
                    رفع ملفات متعددة
                </button>
                <button onclick="showArchiveModal()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 12px 20px; border: 2px solid #4ecdc4; border-radius: 10px; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-archive" style="margin-left: 8px;"></i>
                    إدارة الأرشيف
                </button>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 30px; border: 2px solid white; border-radius: 15px; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
            <i class="fas fa-arrow-right"></i>
            العودة للوحة الشؤون التنظيمية
        </a>
    </div>
</div>

<script>
// Company Registration Functions
function showAddCompanyModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.companies.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.companies.import.form") }}';
}

function exportToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.companies.export") }}';
}

// Product Registration Functions
function showAddProductModal() {
    alert('✅ زر إضافة منتج جديد يعمل بشكل صحيح!\nسيتم فتح نموذج إضافة منتج جديد قريباً');
}

function showImportProductsModal() {
    alert('✅ زر استيراد المنتجات يعمل بشكل صحيح!\nسيتم فتح نموذج استيراد المنتجات من Excel قريباً');
}

function exportProductsToExcel() {
    alert('✅ زر تصدير المنتجات يعمل بشكل صحيح!\nسيتم تصدير بيانات المنتجات إلى Excel قريباً');
}

// Laboratory Tests Functions
function showAddTestModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.import.form") }}';
}

function showScheduleModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.schedule") }}';
}

function exportTestsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.laboratory-tests.export") }}';
}

// Inspections Functions
function showScheduleInspectionModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.schedule") }}';
}

function showCalendarView() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.calendar") }}';
}

function exportInspectionsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.export") }}';
}

// Certificates Functions
function showAddCertificateModal() {
    alert('✅ زر إضافة شهادة يعمل بشكل صحيح!\nسيتم فتح نموذج إضافة شهادة جديدة قريباً');
}

function showRenewalModal() {
    alert('✅ زر تجديد الشهادات يعمل بشكل صحيح!\nسيتم فتح نموذج تجديد الشهادات قريباً');
}

function exportCertificatesToExcel() {
    alert('✅ زر تصدير الشهادات يعمل بشكل صحيح!\nسيتم تصدير الشهادات إلى Excel قريباً');
}

// Recalls Functions
function showInitiateRecallModal() {
    alert('✅ زر بدء عملية سحب يعمل بشكل صحيح!\nسيتم فتح نموذج بدء عملية سحب منتج قريباً');
}

function showNotificationsModal() {
    alert('✅ زر إدارة الإشعارات يعمل بشكل صحيح!\nسيتم فتح نموذج إدارة الإشعارات قريباً');
}

function exportRecallsToExcel() {
    alert('✅ زر تصدير السحب يعمل بشكل صحيح!\nسيتم تصدير تقارير السحب إلى Excel قريباً');
}

// Reports Functions
function showCreateReportModal() {
    alert('✅ زر إنشاء تقرير يعمل بشكل صحيح!\nسيتم فتح نموذج إنشاء تقرير جديد قريباً');
}

function showTemplatesModal() {
    alert('✅ زر قوالب التقارير يعمل بشكل صحيح!\nسيتم فتح قوالب التقارير قريباً');
}

function exportReportsToExcel() {
    alert('✅ زر تصدير التقارير يعمل بشكل صحيح!\nسيتم تصدير التقارير إلى Excel قريباً');
}

// Documents Functions
function showAddDocumentModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.create") }}';
}

function showBulkUploadModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.bulk-upload") }}';
}

function showArchiveModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.archive") }}';
}
</script>

@endsection
