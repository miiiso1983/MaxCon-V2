@extends('layouts.modern')

@section('title', 'التفتيش التنظيمي')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-search"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">التفتيش التنظيمي</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة عمليات التفتيش المجدولة والمتابعة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showScheduleInspectionModal()" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    جدولة تفتيش جديد
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
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي التفتيشات</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">0</p>
                </div>
                <div style="font-size: 40px; color: #ff9a9e; opacity: 0.3;">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">مجدولة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #ed8936;">0</p>
                </div>
                <div style="font-size: 40px; color: #ed8936; opacity: 0.3;">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">مكتملة</h3>
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
            <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-search"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة التفتيش التنظيمي</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك إدارة عمليات التفتيش المجدولة والمتابعة مع الجهات الرقابية. 
                يمكنك تنظيم التفتيشات الروتينية، تفتيشات GMP، وتتبع النتائج والإجراءات التصحيحية.
            </p>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showScheduleInspectionModal()" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    جدولة تفتيش جديد
                </button>
                <button onclick="showCalendarView()" style="background: rgba(255, 154, 158, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #ff9a9e; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-calendar-alt"></i>
                    عرض التقويم
                </button>
                <button onclick="exportInspectionsToExcel()" style="background: rgba(255, 154, 158, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #ff9a9e; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير التقارير
                </button>
            </div>
        </div>

        <!-- Inspection Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-clipboard-check" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">التفتيش الروتيني</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تفتيشات دورية للتأكد من الامتثال المستمر للمعايير التنظيمية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-industry" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تفتيش GMP</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تفتيش ممارسات التصنيع الجيدة لضمان جودة الإنتاج
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-truck" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تفتيش GDP</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تفتيش ممارسات التوزيع الجيدة وسلسلة التوريد
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-user-md" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">تفتيش GCP</h3>
                </div>
                <p style="margin: 0; opacity: 0.8; line-height: 1.6;">
                    تفتيش ممارسات الأبحاث السريرية والتجارب الطبية
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showAddInspectionModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.create") }}';
}

function showImportModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.import.form") }}';
}

function showScheduleInspectionModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.schedule") }}';
}

function showCalendarView() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.calendar") }}';
}

function exportInspectionsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.export") }}';
}
</script>

@endsection
