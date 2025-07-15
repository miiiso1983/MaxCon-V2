@extends('layouts.modern')

@section('page-title', 'دليل QR كود المنتجات في الفواتير')
@section('page-description', 'كيفية استخدام QR كود المنتجات لتحفيز المبيعات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-qrcode" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            QR كود المنتجات في الفواتير 📱
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            دليل شامل لاستخدام QR كود المنتجات لتحفيز المبيعات
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.invoices.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للفواتير
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- What is QR Code for Products -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-question-circle" style="color: #6366f1;"></i>
            ما هو QR كود المنتجات؟
        </h3>
        
        <div style="margin-bottom: 20px;">
            <p style="color: #4a5568; line-height: 1.6; margin-bottom: 15px;">
                QR كود المنتجات هو رمز استجابة سريعة يحتوي على قائمة بجميع المنتجات المتوفرة في الصيدلية مع أسعارها وتفاصيلها، <strong>بدون الكشف عن كميات المخزون</strong>.
            </p>
            
            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                <h4 style="color: #0369a1; margin: 0 0 10px 0; font-size: 16px; font-weight: 600;">
                    <i class="fas fa-info-circle" style="margin-left: 8px;"></i>
                    محتويات QR كود المنتجات:
                </h4>
                <ul style="margin: 0; padding-right: 20px; color: #0369a1;">
                    <li>أسماء المنتجات المتوفرة</li>
                    <li>أرقام/رموز المنتجات</li>
                    <li>الأسعار الحالية</li>
                    <li>العلامات التجارية</li>
                    <li>فئات المنتجات</li>
                    <li>وحدات القياس</li>
                </ul>
            </div>
            
            <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 15px;">
                <h4 style="color: #92400e; margin: 0 0 10px 0; font-size: 16px; font-weight: 600;">
                    <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                    ما لا يحتويه QR كود:
                </h4>
                <ul style="margin: 0; padding-right: 20px; color: #92400e;">
                    <li>كميات المخزون المتوفرة</li>
                    <li>معلومات المخزون الحساسة</li>
                    <li>تكاليف الشراء</li>
                    <li>معلومات الموردين</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- How it Works -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-cogs" style="color: #10b981;"></i>
            كيف يعمل؟
        </h3>
        
        <div style="margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; border-right: 4px solid #6366f1;">
                <div style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">1</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2d3748; font-size: 16px; font-weight: 600;">طباعة الفاتورة</h4>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">يتم طباعة QR كود المنتجات تلقائياً في أسفل كل فاتورة</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; border-right: 4px solid #10b981;">
                <div style="background: #10b981; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">2</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2d3748; font-size: 16px; font-weight: 600;">مسح العميل للكود</h4>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">العميل يمسح QR كود بهاتفه لعرض كتالوج المنتجات</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; border-right: 4px solid #f59e0b;">
                <div style="background: #f59e0b; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">3</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2d3748; font-size: 16px; font-weight: 600;">تصفح المنتجات</h4>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">العميل يتصفح المنتجات المتوفرة مع الأسعار والتفاصيل</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8fafc; border-radius: 8px; border-right: 4px solid #ef4444;">
                <div style="background: #ef4444; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">4</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; color: #2d3748; font-size: 16px; font-weight: 600;">طلب إضافي</h4>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">العميل يطلب منتجات إضافية بالرمز أو الاسم</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-chart-line" style="color: #059669;"></i>
        فوائد QR كود المنتجات
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <!-- For Pharmacy -->
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px;">
            <h4 style="color: #059669; margin: 0 0 15px 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-store"></i>
                للصيدلية
            </h4>
            <ul style="margin: 0; padding-right: 20px; color: #065f46; line-height: 1.6;">
                <li><strong>زيادة المبيعات:</strong> تحفيز العملاء على شراء منتجات إضافية</li>
                <li><strong>تسويق رقمي:</strong> عرض كتالوج إلكتروني بدلاً من المطبوع</li>
                <li><strong>توفير التكاليف:</strong> تقليل طباعة الكتالوجات الورقية</li>
                <li><strong>تجربة عصرية:</strong> مواكبة التكنولوجيا الحديثة</li>
                <li><strong>معلومات محدثة:</strong> أسعار ومنتجات محدثة دائماً</li>
            </ul>
        </div>
        
        <!-- For Customers -->
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 20px;">
            <h4 style="color: #2563eb; margin: 0 0 15px 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-users"></i>
                للعملاء
            </h4>
            <ul style="margin: 0; padding-right: 20px; color: #1e40af; line-height: 1.6;">
                <li><strong>وصول سريع:</strong> عرض جميع المنتجات بمسح واحد</li>
                <li><strong>أسعار دقيقة:</strong> معلومات محدثة ودقيقة</li>
                <li><strong>بحث سهل:</strong> العثور على المنتجات المطلوبة بسرعة</li>
                <li><strong>طلب مريح:</strong> طلب المنتجات بالرمز أو الاسم</li>
                <li><strong>توفير الوقت:</strong> تصفح سريع بدلاً من السؤال</li>
            </ul>
        </div>
        
        <!-- For Sales -->
        <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 12px; padding: 20px;">
            <h4 style="color: #d97706; margin: 0 0 15px 0; font-size: 18px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-chart-bar"></i>
                للمبيعات
            </h4>
            <ul style="margin: 0; padding-right: 20px; color: #92400e; line-height: 1.6;">
                <li><strong>مبيعات إضافية:</strong> عرض منتجات قد يحتاجها العميل</li>
                <li><strong>تقليل الاستفسارات:</strong> العميل يجد المعلومات بنفسه</li>
                <li><strong>تحسين الخدمة:</strong> خدمة أسرع وأكثر كفاءة</li>
                <li><strong>بيانات دقيقة:</strong> تقليل الأخطاء في الطلبات</li>
                <li><strong>رضا العملاء:</strong> تجربة تسوق محسنة</li>
            </ul>
        </div>
    </div>
</div>

<!-- Usage Examples -->
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-lightbulb" style="color: #f59e0b;"></i>
        أمثلة الاستخدام
    </h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                <i class="fas fa-scenario" style="color: #6366f1; margin-left: 8px;"></i>
                سيناريوهات الاستخدام:
            </h4>
            <div style="background: #f8fafc; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.5;">
                    <strong>العميل يشتري دواء للصداع:</strong><br>
                    يمسح QR كود ويجد فيتامينات ومكملات غذائية قد يحتاجها، فيطلب فيتامين سي إضافي.
                </p>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.5;">
                    <strong>عميل يبحث عن منتج معين:</strong><br>
                    بدلاً من السؤال، يمسح الكود ويبحث في القائمة عن المنتج المطلوب.
                </p>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 15px;">
                <p style="margin: 0; color: #4a5568; font-size: 14px; line-height: 1.5;">
                    <strong>مقارنة الأسعار:</strong><br>
                    العميل يتصفح المنتجات المشابهة ويقارن الأسعار والعلامات التجارية.
                </p>
            </div>
        </div>
        
        <div>
            <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                <i class="fas fa-tips" style="color: #10b981; margin-left: 8px;"></i>
                نصائح للاستخدام الأمثل:
            </h4>
            <div style="background: #f0fdf4; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                <p style="margin: 0; color: #065f46; font-size: 14px; line-height: 1.5;">
                    <strong>اختر المنتجات المميزة:</strong><br>
                    ضع في QR كود المنتجات الأكثر طلباً أو العروض الخاصة.
                </p>
            </div>
            <div style="background: #f0fdf4; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                <p style="margin: 0; color: #065f46; font-size: 14px; line-height: 1.5;">
                    <strong>حدث الأسعار بانتظام:</strong><br>
                    تأكد من تحديث أسعار المنتجات في النظام لضمان دقة المعلومات.
                </p>
            </div>
            <div style="background: #f0fdf4; border-radius: 8px; padding: 15px;">
                <p style="margin: 0; color: #065f46; font-size: 14px; line-height: 1.5;">
                    <strong>اشرح للعملاء:</strong><br>
                    أخبر العملاء عن QR كود المنتجات وكيفية استخدامه.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
    <a href="{{ route('tenant.inventory.qr.index') }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 25px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-qrcode"></i>
        مولد QR كود
    </a>
    <a href="{{ route('tenant.inventory.invoice.qr.example') }}" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 15px 25px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-file-invoice"></i>
        مثال الفاتورة
    </a>
    <a href="{{ route('tenant.inventory.products.index') }}" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; padding: 15px 25px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-cube"></i>
        إدارة المنتجات
    </a>
</div>
@endsection
