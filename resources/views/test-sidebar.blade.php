@extends('layouts.modern')

@section('page-title', 'اختبار القائمة الجانبية الحديثة')
@section('page-description', 'صفحة اختبار للتأكد من عمل القائمة الجانبية الحديثة')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-sidebar" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            اختبار القائمة الجانبية الحديثة
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تم تطبيق القائمة الجانبية الحديثة القابلة للطي بنجاح
                        </p>
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <div style="background: rgba(255,255,255,0.15); border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                    <i class="fas fa-check-circle" style="font-size: 40px; color: #10b981;"></i>
                </div>
                <span style="font-size: 14px; font-weight: 600;">تم بنجاح</span>
            </div>
        </div>
    </div>
</div>

<!-- Features Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- القائمة الجانبية الحديثة -->
    <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; padding: 12px; margin-left: 15px;">
                <i class="fas fa-bars" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <h3 style="margin: 0; color: #1f2937; font-size: 18px; font-weight: 700;">القائمة الجانبية الحديثة</h3>
                <p style="margin: 0; color: #6b7280; font-size: 14px;">قابلة للطي مع Alpine.js</p>
            </div>
        </div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-check text-green-500" style="margin-left: 10px; color: #10b981;"></i>
                <span style="color: #374151;">تصميم قابل للطي</span>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-check text-green-500" style="margin-left: 10px; color: #10b981;"></i>
                <span style="color: #374151;">حفظ الحالة في localStorage</span>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-check text-green-500" style="margin-left: 10px; color: #10b981;"></i>
                <span style="color: #374151;">تصميم متجاوب</span>
            </li>
            <li style="padding: 8px 0; display: flex; align-items: center;">
                <i class="fas fa-check text-green-500" style="margin-left: 10px; color: #10b981;"></i>
                <span style="color: #374151;">انتقالات سلسة</span>
            </li>
        </ul>
    </div>

    <!-- الوحدات المتاحة -->
    <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <div style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px; padding: 12px; margin-left: 15px;">
                <i class="fas fa-th-large" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <h3 style="margin: 0; color: #1f2937; font-size: 18px; font-weight: 700;">الوحدات المتاحة</h3>
                <p style="margin: 0; color: #6b7280; font-size: 14px;">جميع وحدات النظام</p>
            </div>
        </div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-shopping-cart" style="margin-left: 10px; color: #667eea;"></i>
                <span style="color: #374151;">إدارة المبيعات</span>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-boxes" style="margin-left: 10px; color: #667eea;"></i>
                <span style="color: #374151;">إدارة المخزون</span>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-calculator" style="margin-left: 10px; color: #667eea;"></i>
                <span style="color: #374151;">المحاسبة</span>
            </li>
            <li style="padding: 8px 0; display: flex; align-items: center;">
                <i class="fas fa-users-cog" style="margin-left: 10px; color: #667eea;"></i>
                <span style="color: #374151;">الموارد البشرية</span>
            </li>
        </ul>
    </div>

    <!-- التحديثات -->
    <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <div style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 10px; padding: 12px; margin-left: 15px;">
                <i class="fas fa-rocket" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <h3 style="margin: 0; color: #1f2937; font-size: 18px; font-weight: 700;">التحديثات الجديدة</h3>
                <p style="margin: 0; color: #6b7280; font-size: 14px;">آخر التحسينات</p>
            </div>
        </div>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-star" style="margin-left: 10px; color: #f59e0b;"></i>
                <span style="color: #374151;">تصميم محسن</span>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-star" style="margin-left: 10px; color: #f59e0b;"></i>
                <span style="color: #374151;">أداء أفضل</span>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center;">
                <i class="fas fa-star" style="margin-left: 10px; color: #f59e0b;"></i>
                <span style="color: #374151;">سهولة الاستخدام</span>
            </li>
            <li style="padding: 8px 0; display: flex; align-items: center;">
                <i class="fas fa-star" style="margin-left: 10px; color: #f59e0b;"></i>
                <span style="color: #374151;">تجربة مستخدم محسنة</span>
            </li>
        </ul>
    </div>
</div>

<!-- Instructions -->
<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
    <h3 style="color: #1f2937; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-info-circle" style="margin-left: 10px; color: #3b82f6;"></i>
        كيفية استخدام القائمة الجانبية الحديثة
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div style="padding: 15px; background: #f8fafc; border-radius: 10px; border-right: 4px solid #3b82f6;">
            <h4 style="margin: 0 0 10px 0; color: #1f2937;">الطي والتوسيع</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">انقر على زر الطي في أعلى القائمة لطيها أو توسيعها</p>
        </div>
        <div style="padding: 15px; background: #f0fdf4; border-radius: 10px; border-right: 4px solid #10b981;">
            <h4 style="margin: 0 0 10px 0; color: #1f2937;">حفظ الحالة</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">سيتم حفظ حالة القائمة (مطوية أم موسعة) تلقائياً</p>
        </div>
        <div style="padding: 15px; background: #fefce8; border-radius: 10px; border-right: 4px solid #f59e0b;">
            <h4 style="margin: 0 0 10px 0; color: #1f2937;">التصميم المتجاوب</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">تتكيف القائمة مع جميع أحجام الشاشات</p>
        </div>
        <div style="padding: 15px; background: #fdf2f8; border-radius: 10px; border-right: 4px solid #ec4899;">
            <h4 style="margin: 0 0 10px 0; color: #1f2937;">التنقل السريع</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">وصول سريع لجميع وحدات النظام</p>
        </div>
    </div>
</div>
@endsection
