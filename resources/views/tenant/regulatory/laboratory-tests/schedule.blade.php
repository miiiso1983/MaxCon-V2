@extends('layouts.modern')

@section('title', 'جدولة الفحوصات المخبرية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">جدولة الفحوصات المخبرية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تنظيم وجدولة الفحوصات المخبرية القادمة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.index') }}" style="background: rgba(255,255,255,0.2); color: #667eea; padding: 15px 25px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للفحوصات
                </a>
            </div>
        </div>
    </div>

    <!-- Coming Soon Message -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 60px 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
        
        <!-- Icon -->
        <div style="font-size: 120px; color: #667eea; margin-bottom: 30px; opacity: 0.7;">
            <i class="fas fa-calendar-plus"></i>
        </div>
        
        <!-- Title -->
        <h2 style="color: #2d3748; margin: 0 0 20px 0; font-size: 36px; font-weight: 700;">
            جدولة الفحوصات المخبرية
        </h2>
        
        <!-- Description -->
        <p style="color: #718096; margin: 0 0 40px 0; font-size: 18px; line-height: 1.8; max-width: 600px; margin-left: auto; margin-right: auto;">
            هذه الميزة ستتيح لك جدولة الفحوصات المخبرية، تحديد المواعيد، تعيين الفرق المسؤولة، 
            وإرسال التذكيرات التلقائية. سيتم إطلاقها قريباً مع المزيد من الميزات المتقدمة.
        </p>
        
        <!-- Features Preview -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin: 40px 0; max-width: 800px; margin-left: auto; margin-right: auto;">
            
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">جدولة ذكية</h4>
                <p style="margin: 0; opacity: 0.9; font-size: 14px; line-height: 1.6;">
                    جدولة تلقائية للفحوصات بناءً على الأولوية والموارد المتاحة
                </p>
            </div>
            
            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 15px; padding: 25px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">
                    <i class="fas fa-bell"></i>
                </div>
                <h4 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">تذكيرات تلقائية</h4>
                <p style="margin: 0; opacity: 0.9; font-size: 14px; line-height: 1.6;">
                    إرسال تذكيرات للفرق المسؤولة قبل موعد الفحص
                </p>
            </div>
            
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 25px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">
                    <i class="fas fa-users"></i>
                </div>
                <h4 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">إدارة الفرق</h4>
                <p style="margin: 0; opacity: 0.9; font-size: 14px; line-height: 1.6;">
                    تعيين الفرق والمختصين لكل نوع من أنواع الفحوصات
                </p>
            </div>
            
        </div>
        
        <!-- Timeline -->
        <div style="background: rgba(102, 126, 234, 0.05); border: 2px solid #667eea; border-radius: 15px; padding: 30px; margin: 40px 0; max-width: 600px; margin-left: auto; margin-right: auto;">
            <h4 style="color: #667eea; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-clock" style="margin-left: 10px;"></i>
                الجدول الزمني للتطوير
            </h4>
            
            <div style="display: grid; gap: 15px;">
                <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(72, 187, 120, 0.1); border-radius: 10px;">
                    <div style="background: #48bb78; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">
                        ✓
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: #2d3748;">المرحلة الأولى - مكتملة</div>
                        <div style="font-size: 14px; color: #718096;">إنشاء نظام إدارة الفحوصات الأساسي</div>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(237, 137, 54, 0.1); border-radius: 10px;">
                    <div style="background: #ed8936; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">
                        2
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: #2d3748;">المرحلة الثانية - قيد التطوير</div>
                        <div style="font-size: 14px; color: #718096;">تطوير نظام الجدولة والتذكيرات</div>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(102, 126, 234, 0.1); border-radius: 10px;">
                    <div style="background: #667eea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">
                        3
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 600; color: #2d3748;">المرحلة الثالثة - مخطط لها</div>
                        <div style="font-size: 14px; color: #718096;">إضافة التقارير المتقدمة والتحليلات</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-top: 40px;">
            <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.create') }}" 
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-2px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-plus"></i>
                إضافة فحص جديد
            </a>
            
            <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.import.form') }}" 
               style="background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 15px 30px; border: 2px solid #667eea; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-2px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-upload"></i>
                استيراد من Excel
            </a>
            
            <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.export') }}" 
               style="background: rgba(102, 126, 234, 0.1); color: #667eea; padding: 15px 30px; border: 2px solid #667eea; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-2px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-download"></i>
                تصدير النتائج
            </a>
        </div>
        
        <!-- Contact Info -->
        <div style="margin-top: 50px; padding-top: 30px; border-top: 2px solid #e2e8f0;">
            <p style="color: #718096; margin: 0; font-size: 16px;">
                <i class="fas fa-info-circle" style="margin-left: 8px; color: #667eea;"></i>
                لمزيد من المعلومات حول موعد إطلاق هذه الميزة، يرجى التواصل مع فريق التطوير
            </p>
        </div>
        
    </div>
</div>

@endsection
