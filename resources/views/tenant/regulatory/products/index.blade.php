@extends('layouts.modern')

@section('title', 'تسجيل المنتجات الدوائية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-pills"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تسجيل المنتجات الدوائية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تصنيف وتسجيل المنتجات الدوائية والربط بالجهات الرقابية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="showAddProductModal()" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة منتج جديد
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
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">إجمالي المنتجات</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #2d3748;">0</p>
                </div>
                <div style="font-size: 40px; color: #a8edea; opacity: 0.3;">
                    <i class="fas fa-pills"></i>
                </div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">المنتجات المسجلة</h3>
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
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #718096;">المواد المراقبة</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700; color: #f56565;">0</p>
                </div>
                <div style="font-size: 40px; color: #f56565; opacity: 0.3;">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="text-align: center; padding: 60px 20px;">
            <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; border-radius: 50%; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; font-size: 48px;">
                <i class="fas fa-pills"></i>
            </div>
            <h2 style="color: #2d3748; margin: 0 0 15px 0; font-size: 28px; font-weight: 700;">مرحباً بك في وحدة تسجيل المنتجات</h2>
            <p style="color: #718096; margin: 0 0 30px 0; font-size: 18px; line-height: 1.6; max-width: 600px; margin-left: auto; margin-right: auto;">
                هذه الوحدة تتيح لك تصنيف وتسجيل المنتجات الدوائية وربطها بالجهات الرقابية. 
                يمكنك إدارة جميع أنواع المنتجات من أدوية ولقاحات وأجهزة طبية ومكملات غذائية.
            </p>
            
            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <button onclick="showAddProductModal()" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; padding: 15px 30px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-plus"></i>
                    إضافة منتج جديد
                </button>
                <button onclick="showImportProductsModal()" style="background: rgba(168, 237, 234, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #a8edea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-upload"></i>
                    استيراد من Excel
                </button>
                <button onclick="exportProductsToExcel()" style="background: rgba(168, 237, 234, 0.1); color: #2d3748; padding: 15px 30px; border: 2px solid #a8edea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 16px;">
                    <i class="fas fa-download"></i>
                    تصدير التقرير
                </button>
            </div>
        </div>

        <!-- Product Types Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 40px;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-pills" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">الأدوية</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تسجيل وإدارة الأدوية بجميع أشكالها الصيدلانية مع تتبع التراخيص والموافقات
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-syringe" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">اللقاحات</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    إدارة تسجيل اللقاحات مع متطلبات التخزين الخاصة وسلسلة التبريد
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-stethoscope" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">الأجهزة الطبية</h3>
                </div>
                <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                    تسجيل الأجهزة والمعدات الطبية مع شهادات الجودة والمطابقة
                </p>
            </div>

            <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 15px; padding: 25px;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <i class="fas fa-leaf" style="font-size: 24px; margin-left: 15px;"></i>
                    <h3 style="margin: 0; font-size: 20px; font-weight: 700;">المكملات الغذائية</h3>
                </div>
                <p style="margin: 0; opacity: 0.8; line-height: 1.6;">
                    إدارة المكملات الغذائية والمنتجات العشبية مع متطلبات السلامة
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function showAddProductModal() {
    alert('سيتم فتح نموذج إضافة منتج جديد قريباً');
    // TODO: إضافة modal لإضافة منتج جديد
}

function showImportProductsModal() {
    alert('سيتم فتح نموذج استيراد المنتجات من Excel قريباً');
    // TODO: إضافة modal لاستيراد Excel
}

function exportProductsToExcel() {
    alert('سيتم تصدير بيانات المنتجات إلى Excel قريباً');
    // TODO: إضافة وظيفة تصدير Excel
}
</script>

@endsection
