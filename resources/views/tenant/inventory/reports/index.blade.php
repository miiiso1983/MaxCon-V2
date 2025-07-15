@extends('layouts.modern')

@section('page-title', 'تقارير المخزون')
@section('page-description', 'تقارير شاملة وتحليلات المخزون')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-chart-bar" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            تقارير المخزون 📊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تقارير شاملة وتحليلات المخزون
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تحليلات متقدمة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-download" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">تصدير Excel/PDF</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">تقارير فورية</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reports Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
    
    <!-- Stock Levels Report -->
    <div class="content-card" style="transition: all 0.3s ease; cursor: pointer;" 
         onclick="window.location.href='{{ route('tenant.inventory.reports.stock-levels') }}'"
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 15px; margin-left: 15px;">
                <i class="fas fa-boxes" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">مستويات المخزون</h3>
                <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">تقرير شامل لمستويات المخزون الحالية</p>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                عرض تفصيلي لجميع المنتجات والكميات المتاحة والمحجوزة في كل مستودع مع القيم المالية.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-warehouse"></i> حسب المستودع
            </span>
            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-dollar-sign"></i> القيم المالية
            </span>
            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-chart-pie"></i> تحليل تفصيلي
            </span>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;">انقر للعرض</span>
                <i class="fas fa-arrow-left" style="color: #3b82f6;"></i>
            </div>
        </div>
    </div>

    <!-- Movement History Report -->
    <div class="content-card" style="transition: all 0.3s ease; cursor: pointer;" 
         onclick="window.location.href='{{ route('tenant.inventory.reports.movement-history') }}'"
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 15px; margin-left: 15px;">
                <i class="fas fa-exchange-alt" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">تاريخ الحركات</h3>
                <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">تقرير مفصل لجميع حركات المخزون</p>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                تتبع جميع حركات المخزون الواردة والصادرة مع التواريخ والأسباب والمسؤولين.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-arrow-down"></i> حركات واردة
            </span>
            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-arrow-up"></i> حركات صادرة
            </span>
            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-calendar"></i> فترة زمنية
            </span>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;">انقر للعرض</span>
                <i class="fas fa-arrow-left" style="color: #10b981;"></i>
            </div>
        </div>
    </div>

    <!-- Low Stock Report -->
    <div class="content-card" style="transition: all 0.3s ease; cursor: pointer;" 
         onclick="window.location.href='{{ route('tenant.inventory.reports.low-stock') }}'"
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 15px; margin-left: 15px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">المخزون المنخفض</h3>
                <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">المنتجات التي تحتاج إعادة تموين</p>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                قائمة بالمنتجات التي وصلت إلى الحد الأدنى للمخزون وتحتاج إعادة طلب.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-exclamation"></i> تحذيرات
            </span>
            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-times-circle"></i> نفاد مخزون
            </span>
            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-shopping-cart"></i> إعادة طلب
            </span>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;">انقر للعرض</span>
                <i class="fas fa-arrow-left" style="color: #f59e0b;"></i>
            </div>
        </div>
    </div>

    <!-- Expiring Items Report -->
    <div class="content-card" style="transition: all 0.3s ease; cursor: pointer;" 
         onclick="window.location.href='{{ route('tenant.inventory.reports.expiring-items') }}'"
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 15px; margin-left: 15px;">
                <i class="fas fa-clock" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">انتهاء الصلاحية</h3>
                <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">المنتجات قريبة أو منتهية الصلاحية</p>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                تقرير بالمنتجات التي ستنتهي صلاحيتها قريباً أو انتهت بالفعل لاتخاذ الإجراءات المناسبة.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-ban"></i> منتهية الصلاحية
            </span>
            <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-clock"></i> تنتهي قريباً
            </span>
            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-calendar"></i> تواريخ الانتهاء
            </span>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;">انقر للعرض</span>
                <i class="fas fa-arrow-left" style="color: #ef4444;"></i>
            </div>
        </div>
    </div>

    <!-- Custom Reports -->
    <div class="content-card" style="transition: all 0.3s ease; cursor: pointer;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 15px; margin-left: 15px;">
                <i class="fas fa-cogs" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">تقارير مخصصة</h3>
                <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">إنشاء تقارير حسب احتياجاتك</p>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                أنشئ تقارير مخصصة بمعايير محددة وفلاتر متقدمة حسب احتياجاتك الخاصة.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <span style="background: #f3e8ff; color: #7c3aed; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-filter"></i> فلاتر متقدمة
            </span>
            <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-save"></i> حفظ التقارير
            </span>
            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-share"></i> مشاركة
            </span>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;">قريباً</span>
                <i class="fas fa-arrow-left" style="color: #8b5cf6;"></i>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard -->
    <div class="content-card" style="transition: all 0.3s ease; cursor: pointer;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border-radius: 15px; padding: 15px; margin-left: 15px;">
                <i class="fas fa-chart-line" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">لوحة التحليلات</h3>
                <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 14px;">تحليلات متقدمة ومؤشرات الأداء</p>
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                لوحة تحكم تفاعلية مع مؤشرات الأداء الرئيسية وتحليلات متقدمة للمخزون.
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <span style="background: #cffafe; color: #0f766e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-chart-pie"></i> مؤشرات KPI
            </span>
            <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-chart-area"></i> رسوم بيانية
            </span>
            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                <i class="fas fa-trending-up"></i> اتجاهات
            </span>
        </div>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 14px;">قريباً</span>
                <i class="fas fa-arrow-left" style="color: #06b6d4;"></i>
            </div>
        </div>
    </div>
</div>
@endsection
