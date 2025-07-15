@extends('layouts.modern')

@section('title', 'تسجيل الشركات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تسجيل الشركات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة تسجيل الشركات والتراخيص والامتثال التنظيمي</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="window.location.href='#'" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة شركة جديدة
                </button>
                <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: #4facfe; padding: 15px 25px; border: 2px solid #4facfe; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوحة الرئيسية
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي الشركات</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">0</p>
                </div>
                <div style="font-size: 40px; color: #4facfe; opacity: 0.3;">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">الشركات النشطة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #48bb78;">0</p>
                </div>
                <div style="font-size: 40px; color: #48bb78; opacity: 0.3;">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">تنتهي قريباً</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ed8936;">0</p>
                </div>
                <div style="font-size: 40px; color: #ed8936; opacity: 0.3;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">منتهية الصلاحية</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #f56565;">0</p>
                </div>
                <div style="font-size: 40px; color: #f56565; opacity: 0.3;">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-building"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة تسجيل الشركات</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك إدارة تسجيل الشركات والتراخيص والامتثال التنظيمي بطريقة شاملة ومتقدمة. 
                يمكنك تتبع حالة التراخيص، مواعيد التجديد، والامتثال للمعايير التنظيمية.
            </p>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showAddCompanyModal()" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إضافة شركة جديدة
                </button>
                <button onclick="showImportModal()" style="background: rgba(79, 172, 254, 0.1); color: #4facfe; padding: 15px 30px; border: 2px solid #4facfe; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-upload"></i>
                    استيراد من Excel
                </button>
                <button onclick="exportToExcel()" style="background: rgba(79, 172, 254, 0.1); color: #4facfe; padding: 15px 30px; border: 2px solid #4facfe; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير التقرير
                </button>
            </div>
        </div>

        <!-- Features Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-certificate" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">إدارة التراخيص</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تتبع جميع أنواع التراخيص (تصنيع، استيراد، تصدير، توزيع) مع تنبيهات انتهاء الصلاحية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-shield-alt" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">الامتثال التنظيمي</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    مراقبة حالة الامتثال للمعايير التنظيمية والإجراءات التصحيحية المطلوبة
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-search" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">جدولة التفتيش</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تنظيم وتتبع عمليات التفتيش المجدولة والمتابعة مع الجهات الرقابية
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showAddCompanyModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.companies.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.companies.import.form") }}';
}

function exportToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.companies.export") }}';
}
</script>

@endsection
