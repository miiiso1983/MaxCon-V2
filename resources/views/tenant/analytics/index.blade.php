@extends('layouts.tenant')

@section('title', 'الإحصائيات والتحليلات')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 15px 0; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-chart-line"></i>
            الإحصائيات والتحليلات
        </h1>
        <p style="font-size: 18px; opacity: 0.9; margin: 0;">
            تحليلات شاملة ومؤشرات أداء رئيسية لمتابعة نمو وأداء المؤسسة
        </p>
    </div>
    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"rgba(255,255,255,0.1)\"/></svg>') repeat; animation: float 20s infinite linear;"></div>
</div>

<!-- KPI Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <div style="font-size: 36px; margin-bottom: 10px;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div style="font-size: 28px; font-weight: 800; margin-bottom: 5px;">2,450,000</div>
            <div style="opacity: 0.9; font-size: 14px;">إجمالي المبيعات (د.ع)</div>
            <div style="font-size: 12px; margin-top: 5px; opacity: 0.8;">
                <i class="fas fa-arrow-up"></i> +15.3% من الشهر الماضي
            </div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <div style="font-size: 36px; margin-bottom: 10px;">
                <i class="fas fa-users"></i>
            </div>
            <div style="font-size: 28px; font-weight: 800; margin-bottom: 5px;">1,247</div>
            <div style="opacity: 0.9; font-size: 14px;">إجمالي العملاء</div>
            <div style="font-size: 12px; margin-top: 5px; opacity: 0.8;">
                <i class="fas fa-arrow-up"></i> +8.7% من الشهر الماضي
            </div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <div style="font-size: 36px; margin-bottom: 10px;">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div style="font-size: 28px; font-weight: 800; margin-bottom: 5px;">892</div>
            <div style="opacity: 0.9; font-size: 14px;">الفواتير هذا الشهر</div>
            <div style="font-size: 12px; margin-top: 5px; opacity: 0.8;">
                <i class="fas fa-arrow-up"></i> +12.1% من الشهر الماضي
            </div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; position: relative; overflow: hidden;">
        <div style="position: relative; z-index: 2;">
            <div style="font-size: 36px; margin-bottom: 10px;">
                <i class="fas fa-boxes"></i>
            </div>
            <div style="font-size: 28px; font-weight: 800; margin-bottom: 5px;">15,678</div>
            <div style="opacity: 0.9; font-size: 14px;">إجمالي المنتجات</div>
            <div style="font-size: 12px; margin-top: 5px; opacity: 0.8;">
                <i class="fas fa-arrow-down"></i> -2.3% من الشهر الماضي
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px; margin-bottom: 30px;">
    
    <!-- Sales Chart -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 20px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-chart-line" style="color: #10b981;"></i>
            مبيعات آخر 6 أشهر
        </h3>
        <div style="height: 300px; background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 2px dashed #10b981;">
            <div style="text-align: center; color: #065f46;">
                <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <div style="font-size: 16px; font-weight: 600;">رسم بياني للمبيعات</div>
                <div style="font-size: 12px; opacity: 0.7; margin-top: 5px;">سيتم عرض البيانات هنا</div>
            </div>
        </div>
    </div>

    <!-- Customer Growth Chart -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 20px 0; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-users" style="color: #3b82f6;"></i>
            نمو العملاء
        </h3>
        <div style="height: 300px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 2px dashed #3b82f6;">
            <div style="text-align: center; color: #1e3a8a;">
                <i class="fas fa-users" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <div style="font-size: 16px; font-weight: 600;">رسم بياني لنمو العملاء</div>
                <div style="font-size: 12px; opacity: 0.7; margin-top: 5px;">سيتم عرض البيانات هنا</div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Categories -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
    
    <!-- Sales Analytics -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #10b981;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-shopping-bag" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #065f46; margin: 0;">تحليلات المبيعات</h3>
                <p style="color: #047857; margin: 5px 0 0 0;">تحليل شامل لأداء المبيعات</p>
            </div>
        </div>
        
        <div style="display: grid; gap: 10px;">
            <button onclick="viewAnalytics('sales_trends')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                <i class="fas fa-trending-up" style="margin-left: 8px;"></i>
                اتجاهات المبيعات
            </button>
            <button onclick="viewAnalytics('top_products')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                <i class="fas fa-star" style="margin-left: 8px;"></i>
                أفضل المنتجات مبيعاً
            </button>
            <button onclick="viewAnalytics('sales_by_region')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                <i class="fas fa-map-marker-alt" style="margin-left: 8px;"></i>
                المبيعات حسب المنطقة
            </button>
        </div>
    </div>

    <!-- Customer Analytics -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #3b82f6;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-users" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">تحليلات العملاء</h3>
                <p style="color: #1d4ed8; margin: 5px 0 0 0;">فهم سلوك العملاء</p>
            </div>
        </div>
        
        <div style="display: grid; gap: 10px;">
            <button onclick="viewAnalytics('customer_segments')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <i class="fas fa-layer-group" style="margin-left: 8px;"></i>
                شرائح العملاء
            </button>
            <button onclick="viewAnalytics('customer_lifetime_value')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <i class="fas fa-gem" style="margin-left: 8px;"></i>
                القيمة الدائمة للعميل
            </button>
            <button onclick="viewAnalytics('customer_retention')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                <i class="fas fa-heart" style="margin-left: 8px;"></i>
                معدل الاحتفاظ بالعملاء
            </button>
        </div>
    </div>

    <!-- Financial Analytics -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #ef4444;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-chart-pie" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #7f1d1d; margin: 0;">التحليلات المالية</h3>
                <p style="color: #dc2626; margin: 5px 0 0 0;">تحليل الأداء المالي</p>
            </div>
        </div>
        
        <div style="display: grid; gap: 10px;">
            <button onclick="viewAnalytics('profit_margins')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-percentage" style="margin-left: 8px;"></i>
                هوامش الربح
            </button>
            <button onclick="viewAnalytics('cash_flow_analysis')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-money-bill-wave" style="margin-left: 8px;"></i>
                تحليل التدفق النقدي
            </button>
            <button onclick="viewAnalytics('cost_analysis')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-calculator" style="margin-left: 8px;"></i>
                تحليل التكاليف
            </button>
        </div>
    </div>

    <!-- Inventory Analytics -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #8b5cf6;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-warehouse" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #581c87; margin: 0;">تحليلات المخزون</h3>
                <p style="color: #7c3aed; margin: 5px 0 0 0;">تحسين إدارة المخزون</p>
            </div>
        </div>
        
        <div style="display: grid; gap: 10px;">
            <button onclick="viewAnalytics('inventory_turnover')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-sync-alt" style="margin-left: 8px;"></i>
                معدل دوران المخزون
            </button>
            <button onclick="viewAnalytics('stock_levels')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-boxes" style="margin-left: 8px;"></i>
                مستويات المخزون
            </button>
            <button onclick="viewAnalytics('demand_forecasting')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-crystal-ball" style="margin-left: 8px;"></i>
                توقع الطلب
            </button>
        </div>
    </div>
</div>

<script>
function viewAnalytics(analyticsType) {
    const analyticsNames = {
        'sales_trends': 'اتجاهات المبيعات',
        'top_products': 'أفضل المنتجات مبيعاً',
        'sales_by_region': 'المبيعات حسب المنطقة',
        'customer_segments': 'شرائح العملاء',
        'customer_lifetime_value': 'القيمة الدائمة للعميل',
        'customer_retention': 'معدل الاحتفاظ بالعملاء',
        'profit_margins': 'هوامش الربح',
        'cash_flow_analysis': 'تحليل التدفق النقدي',
        'cost_analysis': 'تحليل التكاليف',
        'inventory_turnover': 'معدل دوران المخزون',
        'stock_levels': 'مستويات المخزون',
        'demand_forecasting': 'توقع الطلب'
    };
    
    alert(`عرض تحليلات: ${analyticsNames[analyticsType]}\n\nسيتم فتح صفحة التحليلات المفصلة مع الرسوم البيانية التفاعلية والبيانات المحدثة.`);
}

// Add animation effects
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Analytics page loaded successfully!');
    
    // Animate KPI cards
    const kpiCards = document.querySelectorAll('[style*="linear-gradient"]');
    kpiCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.transform = 'translateY(0)';
            card.style.opacity = '1';
        }, index * 100);
    });
});
</script>

@endsection
