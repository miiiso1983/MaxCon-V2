@extends('layouts.modern')

@section('page-title', 'مقدمة عن النظام')
@section('page-description', 'تعرف على نظام MaxCon ERP وإمكانياته المتقدمة')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 40px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 2;">
            <h1 style="margin: 0 0 15px 0; font-size: 36px; font-weight: 800;">
                <i class="fas fa-rocket" style="margin-left: 15px;"></i>
                مرحباً بك في MaxCon ERP
            </h1>
            <p style="font-size: 18px; margin: 0; opacity: 0.9; line-height: 1.6;">
                نظام إدارة موارد المؤسسات الأكثر تطوراً وشمولية في المنطقة، مصمم خصيصاً للسوق العراقي والعربي
            </p>
        </div>
    </div>

    <!-- What is MaxCon ERP -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 28px; font-weight: 700; text-align: center;">
            <i class="fas fa-question-circle" style="color: #3b82f6; margin-left: 10px;"></i>
            ما هو نظام MaxCon ERP؟
        </h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div>
                <p style="font-size: 16px; line-height: 1.8; color: #475569; margin-bottom: 20px;">
                    <strong>MaxCon ERP</strong> هو نظام إدارة موارد المؤسسات (Enterprise Resource Planning) متكامل وشامل، 
                    يهدف إلى توحيد وأتمتة جميع العمليات التجارية في مؤسستك من خلال منصة واحدة موحدة.
                </p>
                
                <p style="font-size: 16px; line-height: 1.8; color: #475569; margin-bottom: 20px;">
                    يغطي النظام جميع جوانب إدارة الأعمال بدءاً من إدارة المبيعات والمخزون، مروراً بالمحاسبة والموارد البشرية، 
                    وصولاً إلى التحليلات المتقدمة والذكاء الاصطناعي.
                </p>

                <div style="background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%); padding: 20px; border-radius: 12px; border-right: 4px solid #0284c7;">
                    <h4 style="margin: 0 0 10px 0; color: #0c4a6e; font-weight: 700;">
                        <i class="fas fa-lightbulb" style="margin-left: 8px;"></i>
                        لماذا MaxCon ERP؟
                    </h4>
                    <p style="margin: 0; color: #0c4a6e; line-height: 1.6;">
                        مصمم خصيصاً للسوق العراقي مع دعم كامل للغة العربية، الدينار العراقي، 
                        والقوانين المحلية، مما يجعله الخيار الأمثل للشركات العراقية.
                    </p>
                </div>
            </div>
            
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; color: white;">
                    <i class="fas fa-chart-line" style="font-size: 64px; margin-bottom: 20px; opacity: 0.9;"></i>
                    <h3 style="margin: 0 0 15px 0; font-size: 24px; font-weight: 700;">نظام متكامل</h3>
                    <p style="margin: 0; opacity: 0.9; line-height: 1.6;">
                        جميع احتياجات مؤسستك في مكان واحد
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Features -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700; text-align: center;">
            <i class="fas fa-star" style="color: #f59e0b; margin-left: 10px;"></i>
            المميزات الرئيسية للنظام
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
            @foreach($systemFeatures as $feature)
            <div style="padding: 25px; border-radius: 15px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; transition: all 0.3s ease;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto; font-size: 24px;">
                        <i class="{{ $feature['icon'] }}"></i>
                    </div>
                    <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 700;">{{ $feature['title'] }}</h3>
                </div>
                
                <p style="color: #64748b; line-height: 1.6; text-align: center; margin: 0;">{{ $feature['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Target Users -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700; text-align: center;">
            <i class="fas fa-users" style="color: #8b5cf6; margin-left: 10px;"></i>
            من هم المستخدمون المستهدفون؟
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            @foreach($userTypes as $userType)
            <div style="padding: 25px; border-radius: 15px; background: linear-gradient(135deg, {{ $userType['color'] }}10 0%, {{ $userType['color'] }}05 100%); border: 2px solid {{ $userType['color'] }}20; transition: all 0.3s ease;"
                 onmouseover="this.style.borderColor='{{ $userType['color'] }}'; this.style.transform='translateY(-3px)'"
                 onmouseout="this.style.borderColor='{{ $userType['color'] }}20'; this.style.transform='translateY(0)'">
                
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="background: {{ $userType['color'] }}; color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 700;">{{ $userType['title'] }}</h3>
                        <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;">{{ $userType['description'] }}</p>
                    </div>
                </div>
                
                <div>
                    <h4 style="margin: 0 0 10px 0; color: #374151; font-size: 14px; font-weight: 600;">الصلاحيات الرئيسية:</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($userType['permissions'] as $permission)
                        <li style="color: #64748b; font-size: 13px; margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-check" style="color: {{ $userType['color'] }}; font-size: 10px;"></i>
                            {{ $permission }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Key Tasks -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700; text-align: center;">
            <i class="fas fa-tasks" style="color: #10b981; margin-left: 10px;"></i>
            المهام الأساسية التي يمكنك إنجازها
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="padding: 20px; border-radius: 12px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-right: 4px solid #10b981;">
                <h4 style="margin: 0 0 10px 0; color: #065f46; font-weight: 700;">
                    <i class="fas fa-shopping-cart" style="margin-left: 8px;"></i>
                    إدارة المبيعات
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; color: #065f46; font-size: 14px;">
                    <li style="margin-bottom: 5px;">• إنشاء وإدارة طلبات البيع</li>
                    <li style="margin-bottom: 5px;">• إصدار الفواتير الإلكترونية</li>
                    <li style="margin-bottom: 5px;">• متابعة المدفوعات والمستحقات</li>
                    <li>• إدارة المرتجعات والاستبدالات</li>
                </ul>
            </div>

            <div style="padding: 20px; border-radius: 12px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-right: 4px solid #3b82f6;">
                <h4 style="margin: 0 0 10px 0; color: #1e3a8a; font-weight: 700;">
                    <i class="fas fa-warehouse" style="margin-left: 8px;"></i>
                    إدارة المخزون
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; color: #1e3a8a; font-size: 14px;">
                    <li style="margin-bottom: 5px;">• تتبع المنتجات والكميات</li>
                    <li style="margin-bottom: 5px;">• إدارة المستودعات المتعددة</li>
                    <li style="margin-bottom: 5px;">• مراقبة حركات المخزون</li>
                    <li>• تنبيهات نفاد المخزون</li>
                </ul>
            </div>

            <div style="padding: 20px; border-radius: 12px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-right: 4px solid #f59e0b;">
                <h4 style="margin: 0 0 10px 0; color: #92400e; font-weight: 700;">
                    <i class="fas fa-calculator" style="margin-left: 8px;"></i>
                    المحاسبة والمالية
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; color: #92400e; font-size: 14px;">
                    <li style="margin-bottom: 5px;">• إدارة الحسابات والقيود</li>
                    <li style="margin-bottom: 5px;">• إنشاء التقارير المالية</li>
                    <li style="margin-bottom: 5px;">• متابعة الميزانيات</li>
                    <li>• حساب الضرائب والرسوم</li>
                </ul>
            </div>

            <div style="padding: 20px; border-radius: 12px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border-right: 4px solid #8b5cf6;">
                <h4 style="margin: 0 0 10px 0; color: #581c87; font-weight: 700;">
                    <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                    التقارير والتحليلات
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; color: #581c87; font-size: 14px;">
                    <li style="margin-bottom: 5px;">• تقارير مبيعات تفصيلية</li>
                    <li style="margin-bottom: 5px;">• تحليل الأداء والربحية</li>
                    <li style="margin-bottom: 5px;">• مؤشرات الأداء الرئيسية</li>
                    <li>• التنبؤات الذكية</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Getting Started -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 40px; margin-bottom: 30px; color: white; text-align: center;">
        <h2 style="margin: 0 0 20px 0; font-size: 28px; font-weight: 700;">
            <i class="fas fa-play-circle" style="margin-left: 10px;"></i>
            جاهز للبدء؟
        </h2>
        <p style="font-size: 18px; margin: 0 0 30px 0; opacity: 0.9; line-height: 1.6;">
            ابدأ رحلتك مع MaxCon ERP واكتشف كيف يمكن للنظام تحويل طريقة إدارة أعمالك
        </p>
        
        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            <a href="{{ route('tenant.system-guide.videos') }}" 
               style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 12px; text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
               onmouseover="this.style.background='rgba(255,255,255,0.3)'"
               onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-play"></i> شاهد الفيديوهات التعليمية
            </a>
            
            <a href="{{ route('tenant.system-guide.index') }}" 
               style="background: #10b981; color: white; padding: 15px 25px; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
               onmouseover="this.style.background='#059669'"
               onmouseout="this.style.background='#10b981'">
                <i class="fas fa-arrow-right"></i> استكشف وحدات النظام
            </a>
        </div>
    </div>
</div>
@endsection
