@extends('layouts.modern')

@section('page-title', 'كيفية استخدام النظام')
@section('page-description', 'دليل شامل لاستخدام جميع وحدات النظام')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 40px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h1 style="margin: 0; font-size: 36px; font-weight: 800; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                        <i class="fas fa-graduation-cap" style="margin-left: 15px;"></i>
                        كيفية استخدام النظام
                    </h1>
                    <p style="font-size: 18px; margin: 10px 0 0 0; opacity: 0.9;">
                        دليل شامل وتفاعلي لتعلم استخدام جميع وحدات نظام MaxCon ERP
                    </p>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="{{ route('tenant.system-guide.introduction') }}" 
                       style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                       onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        <i class="fas fa-play"></i> ابدأ التعلم
                    </a>
                    
                    <button onclick="startInteractiveTour()" 
                            style="background: #10b981; color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#059669'"
                            onmouseout="this.style.background='#10b981'">
                        <i class="fas fa-route"></i> جولة تفاعلية
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">{{ $quickStats['total_modules'] }}</div>
            <div style="opacity: 0.9;">وحدة نظام</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">{{ $quickStats['video_tutorials'] }}</div>
            <div style="opacity: 0.9;">فيديو تعليمي</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">{{ $quickStats['faq_items'] }}</div>
            <div style="opacity: 0.9;">سؤال شائع</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">{{ $quickStats['user_manual_pages'] }}</div>
            <div style="opacity: 0.9;">صفحة دليل</div>
        </div>
    </div>

    <!-- Quick Access Menu -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-rocket" style="color: #3b82f6; margin-left: 10px;"></i>
            ابدأ من هنا
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <a href="{{ route('tenant.system-guide.introduction') }}" 
               style="display: flex; align-items: center; gap: 15px; padding: 20px; background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%); border-radius: 12px; text-decoration: none; color: #0c4a6e; border: 1px solid #0284c7; transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(2,132,199,0.15)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="background: #0284c7; color: white; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 16px; margin-bottom: 5px;">مقدمة عن النظام</div>
                    <div style="font-size: 14px; opacity: 0.8;">تعرف على النظام وإمكانياته</div>
                </div>
            </a>

            <a href="{{ route('tenant.system-guide.videos') }}" 
               style="display: flex; align-items: center; gap: 15px; padding: 20px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; text-decoration: none; color: #92400e; border: 1px solid #f59e0b; transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(245,158,11,0.15)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="background: #f59e0b; color: white; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-play"></i>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 16px; margin-bottom: 5px;">الفيديوهات التعليمية</div>
                    <div style="font-size: 14px; opacity: 0.8;">شاهد شروحات مرئية تفاعلية</div>
                </div>
            </a>

            <a href="{{ route('tenant.system-guide.faq') }}" 
               style="display: flex; align-items: center; gap: 15px; padding: 20px; background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 12px; text-decoration: none; color: #065f46; border: 1px solid #10b981; transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(16,185,129,0.15)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="background: #10b981; color: white; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 16px; margin-bottom: 5px;">الأسئلة الشائعة</div>
                    <div style="font-size: 14px; opacity: 0.8;">إجابات للأسئلة المتكررة</div>
                </div>
            </a>

            <a href="{{ route('tenant.system-guide.download-manual') }}" 
               style="display: flex; align-items: center; gap: 15px; padding: 20px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border-radius: 12px; text-decoration: none; color: #581c87; border: 1px solid #8b5cf6; transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(139,92,246,0.15)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <div style="background: #8b5cf6; color: white; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-download"></i>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 16px; margin-bottom: 5px;">تحميل دليل المستخدم</div>
                    <div style="font-size: 14px; opacity: 0.8;">دليل PDF شامل للنظام</div>
                </div>
            </a>
        </div>
    </div>

    <!-- System Modules -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-puzzle-piece" style="color: #3b82f6; margin-left: 10px;"></i>
            وحدات النظام
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            @foreach($modules as $slug => $module)
            <div style="border: 1px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); transition: all 0.3s ease; position: relative; overflow: hidden;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'; this.style.borderColor='{{ $module['color'] }}'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e2e8f0'">
                
                <!-- Module Header -->
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="background: {{ $module['color'] }}; color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="{{ $module['icon'] }}"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 700;">{{ $module['name'] }}</h3>
                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                            <span style="background: {{ $module['color'] }}20; color: {{ $module['color'] }}; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                {{ $module['difficulty'] }}
                            </span>
                            <span style="color: #64748b; font-size: 12px;">
                                <i class="fas fa-clock"></i> {{ $module['video_duration'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Module Description -->
                <p style="color: #64748b; margin-bottom: 15px; line-height: 1.6;">{{ $module['description'] }}</p>

                <!-- Module Features -->
                <div style="margin-bottom: 20px;">
                    <h4 style="color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 10px;">المميزات الرئيسية:</h4>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach(array_slice($module['features'], 0, 3) as $feature)
                        <li style="color: #64748b; font-size: 13px; margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-check" style="color: {{ $module['color'] }}; font-size: 10px;"></i>
                            {{ $feature }}
                        </li>
                        @endforeach
                        @if(count($module['features']) > 3)
                        <li style="color: #94a3b8; font-size: 12px; margin-top: 5px;">
                            +{{ count($module['features']) - 3 }} مميزات أخرى
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Module Actions -->
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="{{ route('tenant.system-guide.module', $slug) }}" 
                       style="flex: 1; background: {{ $module['color'] }}; color: white; padding: 10px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; font-size: 14px; transition: all 0.3s ease;"
                       onmouseover="this.style.opacity='0.9'"
                       onmouseout="this.style.opacity='1'">
                        <i class="fas fa-book-open"></i> شرح تفصيلي
                    </a>
                    
                    <a href="{{ route('tenant.system-guide.videos', $slug) }}" 
                       style="background: #f1f5f9; color: {{ $module['color'] }}; padding: 10px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; border: 1px solid {{ $module['color'] }}20; transition: all 0.3s ease;"
                       onmouseover="this.style.background='{{ $module['color'] }}'; this.style.color='white'"
                       onmouseout="this.style.background='#f1f5f9'; this.style.color='{{ $module['color'] }}'">
                        <i class="fas fa-play"></i> فيديو
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Help Section -->
    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; border: 2px solid #f59e0b;">
        <div style="text-align: center;">
            <h2 style="margin: 0 0 15px 0; color: #92400e; font-size: 24px; font-weight: 700;">
                <i class="fas fa-life-ring" style="margin-left: 10px;"></i>
                هل تحتاج مساعدة إضافية؟
            </h2>
            <p style="color: #92400e; margin-bottom: 25px; font-size: 16px;">
                فريق الدعم الفني متاح لمساعدتك في أي وقت
            </p>
            
            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <a href="mailto:support@maxcon.app" 
                   style="background: #f59e0b; color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
                   onmouseover="this.style.background='#d97706'"
                   onmouseout="this.style.background='#f59e0b'">
                    <i class="fas fa-envelope"></i> البريد الإلكتروني
                </a>
                
                <a href="tel:+964-XXX-XXXX" 
                   style="background: #10b981; color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
                   onmouseover="this.style.background='#059669'"
                   onmouseout="this.style.background='#10b981'">
                    <i class="fas fa-phone"></i> الهاتف
                </a>
                
                <a href="#" onclick="openLiveChat()" 
                   style="background: #3b82f6; color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
                   onmouseover="this.style.background='#1d4ed8'"
                   onmouseout="this.style.background='#3b82f6'">
                    <i class="fas fa-comments"></i> الدردشة المباشرة
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function startInteractiveTour() {
    // سيتم تطبيق هذا لاحقاً مع Intro.js
    alert('سيتم إطلاق الجولة التفاعلية قريباً!');
}

function openLiveChat() {
    // سيتم ربطه بنظام الدردشة المباشرة
    alert('سيتم فتح نافذة الدردشة المباشرة قريباً!');
}
</script>
@endsection
