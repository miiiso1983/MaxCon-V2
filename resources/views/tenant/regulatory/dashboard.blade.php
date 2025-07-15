@extends('layouts.modern')

@section('title', 'لوحة الشؤون التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">لوحة الشؤون التنظيمية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة الامتثال التنظيمي للصناعة الدوائية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة جديد
                </button>
                <button style="background: rgba(255,255,255,0.2); color: #667eea; padding: 15px 25px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-download"></i>
                    تصدير التقارير
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Companies Stats -->
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; opacity: 0.9;">تسجيل الشركات</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700;">{{ $stats['companies']['total'] ?? 0 }}</p>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.8;">{{ $stats['companies']['active'] ?? 0 }} نشط</p>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>

        <!-- Products Stats -->
        <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(168, 237, 234, 0.3);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; opacity: 0.8;">تسجيل المنتجات</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700;">{{ $stats['products']['total'] ?? 0 }}</p>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.7;">{{ $stats['products']['registered'] ?? 0 }} مسجل</p>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">
                    <i class="fas fa-pills"></i>
                </div>
            </div>
        </div>

        <!-- Laboratory Tests Stats -->
        <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(255, 236, 210, 0.3);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; opacity: 0.8;">الفحوصات المخبرية</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700;">{{ $stats['laboratory_tests']['total'] ?? 0 }}</p>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.7;">{{ $stats['laboratory_tests']['pending'] ?? 0 }} قيد الانتظار</p>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">
                    <i class="fas fa-flask"></i>
                </div>
            </div>
        </div>

        <!-- Inspections Stats -->
        <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(255, 154, 158, 0.3);">
            <div style="display: flex; align-items: center; justify-content: between;">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; opacity: 0.8;">التفتيش التنظيمي</h3>
                    <p style="margin: 0; font-size: 32px; font-weight: 700;">{{ $stats['inspections']['total'] ?? 0 }}</p>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.7;">{{ $stats['inspections']['scheduled'] ?? 0 }} مجدول</p>
                </div>
                <div style="font-size: 40px; opacity: 0.3;">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Modules Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
        <!-- Company Registration -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-building"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">تسجيل الشركات</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    إدارة تسجيل الشركات والتراخيص والامتثال التنظيمي
                </p>
                <a href="{{ route('tenant.inventory.regulatory.companies.index') }}" 
                   style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة الشركات
                </a>
            </div>
        </div>

        <!-- Product Registration -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-pills"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">تسجيل المنتجات</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    تصنيف وتسجيل المنتجات الدوائية والربط بالجهات الرقابية
                </p>
                <a href="{{ route('tenant.inventory.regulatory.products.index') }}" 
                   style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #2d3748; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة المنتجات
                </a>
            </div>
        </div>

        <!-- Laboratory Tests -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-flask"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">الفحوصات المخبرية</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    تتبع الفحوصات المخبرية والتقارير الرقابية
                </p>
                <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.index') }}" 
                   style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #2d3748; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة الفحوصات
                </a>
            </div>
        </div>

        <!-- Regulatory Inspections -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-search"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">التفتيش التنظيمي</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    إدارة عمليات التفتيش المجدولة والمتابعة
                </p>
                <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}" 
                   style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #2d3748; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة التفتيش
                </a>
            </div>
        </div>

        <!-- Quality Certificates -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">شهادات الجودة</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    دعم شهادات الجودة وتتبع الصلاحية
                </p>
                <a href="{{ route('tenant.inventory.regulatory.certificates.index') }}" 
                   style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة الشهادات
                </a>
            </div>
        </div>

        <!-- Product Recalls -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">سحب المنتجات</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    إدارة سحب المنتجات عند الحاجة والمتابعة
                </p>
                <a href="{{ route('tenant.inventory.regulatory.product-recalls.index') }}"
                   style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة السحب
                </a>
            </div>
        </div>

        <!-- Regulatory Documents -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">الوثائق التنظيمية</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    حفظ الوثائق القانونية والتنظيمية
                </p>
                <a href="{{ route('tenant.inventory.regulatory.documents.index') }}" 
                   style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة الوثائق
                </a>
            </div>
        </div>

        <!-- Regulatory Reports -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'">
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 22px; font-weight: 700;">التقارير التنظيمية</h3>
                <p style="color: #718096; margin: 0 0 25px 0; line-height: 1.6;">
                    إنشاء وإدارة التقارير التنظيمية المطلوبة
                </p>
                <a href="{{ route('tenant.inventory.regulatory.reports.index') }}" 
                   style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #2d3748; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i>
                    إدارة التقارير
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
