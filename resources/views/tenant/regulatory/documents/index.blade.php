@extends('layouts.modern')

@section('title', 'الوثائق التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">الوثائق التنظيمية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">حفظ الوثائق القانونية والتنظيمية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showAddDocumentModal()" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة وثيقة جديدة
                </button>
                <a href="{{ route('tenant.inventory.regulatory.dashboard') }}" style="background: rgba(255,255,255,0.2); color: #4ecdc4; padding: 15px 25px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
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
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي الوثائق</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">0</p>
                </div>
                <div style="font-size: 40px; color: #4ecdc4; opacity: 0.3;">
                    <i class="fas fa-folder-open"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">نافذة</h3>
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
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">تحتاج مراجعة</h3>
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
            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-folder-open"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة الوثائق التنظيمية</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك حفظ الوثائق القانونية والتنظيمية بطريقة آمنة ومنظمة. 
                يمكنك إدارة التراخيص، الشهادات، السياسات، والإجراءات مع تتبع تواريخ المراجعة والانتهاء.
            </p>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showAddDocumentModal()" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إضافة وثيقة جديدة
                </button>
                <button onclick="showBulkUploadModal()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 30px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-upload"></i>
                    رفع ملفات متعددة
                </button>
                <button onclick="showArchiveModal()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 30px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-archive"></i>
                    إدارة الأرشيف
                </button>
                <button onclick="exportDocumentsToExcel()" style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 30px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير التقرير
                </button>
            </div>
        </div>

        <!-- Document Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-certificate" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">التراخيص والشهادات</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تراخيص التشغيل، شهادات الجودة، والموافقات الرسمية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-file-contract" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">السياسات والإجراءات</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    إجراءات التشغيل المعيارية والسياسات الداخلية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #42a5f5 0%, #2196f3 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-balance-scale" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">الوثائق القانونية</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    العقود، الاتفاقيات، والمراسلات الرسمية
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-clipboard-list" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">وثائق الامتثال</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تقارير التفتيش، خطط الامتثال، والإجراءات التصحيحية
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showAddDocumentModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.create") }}';
}

function showBulkUploadModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.bulk-upload") }}';
}

function showArchiveModal() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.archive") }}';
}

function exportDocumentsToExcel() {
    window.location.href = '{{ route("tenant.inventory.regulatory.documents.export") }}';
}
</script>

@endsection
