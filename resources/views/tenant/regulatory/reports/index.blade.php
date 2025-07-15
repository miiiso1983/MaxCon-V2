@extends('layouts.modern')

@section('title', 'التقارير التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">التقارير التنظيمية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إنشاء وإدارة التقارير التنظيمية المطلوبة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showCreateReportModal()" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إنشاء تقرير جديد
                </button>
                <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: #2d3748; padding: 15px 25px; border: 2px solid #2d3748; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
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
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي التقارير</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">0</p>
                </div>
                <div style="font-size: 40px; color: #fa709a; opacity: 0.3;">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">قيد المراجعة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ed8936;">0</p>
                </div>
                <div style="font-size: 40px; color: #ed8936; opacity: 0.3;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">معتمدة</h3>
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
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">متأخرة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #f56565;">0</p>
                </div>
                <div style="font-size: 40px; color: #f56565; opacity: 0.3;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-file-alt"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة التقارير التنظيمية</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك إنشاء وإدارة التقارير التنظيمية المطلوبة. 
                يمكنك إعداد تقارير التفتيش، التقارير المخبرية، تقارير الأحداث الضارة، والتقارير الدورية.
            </p>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showCreateReportModal()" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إنشاء تقرير جديد
                </button>
                <button onclick="showTemplatesModal()" style="background: rgba(250, 112, 154, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #fa709a; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-file-alt"></i>
                    قوالب التقارير
                </button>
                <button onclick="exportReportsToExcel()" style="background: rgba(250, 112, 154, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #fa709a; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير التقارير
                </button>
            </div>
        </div>

        <!-- Report Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-search" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقرير تفتيش</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير شاملة لنتائج التفتيش والملاحظات والإجراءات التصحيحية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-flask" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقرير مخبري</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير نتائج الفحوصات المخبرية والتحاليل والمطابقة للمواصفات
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقرير حدث ضار</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير الأحداث الضارة والآثار الجانبية للمنتجات الدوائية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-calendar-alt" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تقارير دورية</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    التقارير الشهرية والربع سنوية والسنوية المطلوبة تنظيمياً
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showCreateReportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.import.form") }}';
}

function showTemplatesModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.templates") }}';
}

function exportReportsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.reports.export") }}';
}
</script>

@endsection
