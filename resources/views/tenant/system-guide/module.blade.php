@extends('layouts.modern')

@section('page-title', $module['name'])
@section('page-description', 'دليل شامل لاستخدام ' . $module['name'])

@section('content')
<div class="container-fluid">
    <!-- Module Header -->
    <div style="background: linear-gradient(135deg, {{ $module['color'] }} 0%, {{ $module['color'] }}dd 100%); border-radius: 20px; padding: 40px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 20px; padding: 20px; backdrop-filter: blur(10px);">
                        <i class="{{ $module['icon'] }}" style="font-size: 48px;"></i>
                    </div>
                    <div>
                        <h1 style="margin: 0 0 10px 0; font-size: 36px; font-weight: 800;">{{ $module['name'] }}</h1>
                        <p style="font-size: 18px; margin: 0; opacity: 0.9;">{{ $module['description'] }}</p>
                        <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                            <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 15px; font-size: 14px; font-weight: 600;">
                                <i class="fas fa-signal"></i> {{ $module['difficulty'] }}
                            </span>
                            <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 15px; font-size: 14px; font-weight: 600;">
                                <i class="fas fa-clock"></i> {{ $module['video_duration'] }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="{{ route('tenant.system-guide.videos', $module['slug']) }}" 
                       style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); transition: all 0.3s ease;"
                       onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        <i class="fas fa-play"></i> شاهد الفيديو
                    </a>
                    
                    <button onclick="startModuleTour('{{ $module['slug'] }}')" 
                            style="background: #10b981; color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#059669'"
                            onmouseout="this.style.background='#10b981'">
                        <i class="fas fa-route"></i> جولة تفاعلية
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Module Features -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-star" style="color: {{ $module['color'] }}; margin-left: 10px;"></i>
            المميزات الرئيسية
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            @foreach($module['features'] as $feature)
            <div style="display: flex; align-items: center; gap: 12px; padding: 15px; background: linear-gradient(135deg, {{ $module['color'] }}10 0%, {{ $module['color'] }}05 100%); border-radius: 12px; border-right: 3px solid {{ $module['color'] }};">
                <div style="background: {{ $module['color'] }}; color: white; width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-check" style="font-size: 14px;"></i>
                </div>
                <span style="color: #374151; font-weight: 600; font-size: 14px;">{{ $feature }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Step-by-Step Guide -->
    @if(isset($module['steps']) && count($module['steps']) > 0)
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-list-ol" style="color: {{ $module['color'] }}; margin-left: 10px;"></i>
            دليل الاستخدام خطوة بخطوة
        </h2>
        
        <div style="display: grid; gap: 25px;">
            @foreach($module['steps'] as $index => $step)
            <div style="border: 1px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); position: relative;">
                <!-- Step Number -->
                <div style="position: absolute; top: -15px; right: 25px; background: {{ $module['color'] }}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px;">
                    {{ $index + 1 }}
                </div>
                
                <!-- Step Header -->
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px; margin-top: 10px;">
                    <div style="background: {{ $module['color'] }}20; color: {{ $module['color'] }}; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="{{ $step['icon'] }}"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #1e293b; font-size: 20px; font-weight: 700;">{{ $step['title'] }}</h3>
                        <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;">{{ $step['description'] }}</p>
                        <span style="background: {{ $module['color'] }}20; color: {{ $module['color'] }}; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; margin-top: 5px; display: inline-block;">
                            <i class="fas fa-clock"></i> {{ $step['estimated_time'] }}
                        </span>
                    </div>
                </div>
                
                <!-- Step Instructions -->
                <div style="background: white; border-radius: 12px; padding: 20px; border-right: 4px solid {{ $module['color'] }};">
                    <h4 style="margin: 0 0 15px 0; color: #374151; font-size: 16px; font-weight: 600;">الخطوات:</h4>
                    <ol style="margin: 0; padding-right: 20px; color: #475569; line-height: 1.8;">
                        @foreach($step['steps'] as $stepItem)
                        <li style="margin-bottom: 8px;">{{ $stepItem }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tips and Best Practices -->
    @if(isset($module['tips']) && count($module['tips']) > 0)
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-lightbulb" style="color: #f59e0b; margin-left: 10px;"></i>
            نصائح وأفضل الممارسات
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            @foreach($module['tips'] as $tip)
            <div style="display: flex; align-items: flex-start; gap: 12px; padding: 20px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; border-right: 3px solid #f59e0b;">
                <div style="background: #f59e0b; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                    <i class="fas fa-lightbulb" style="font-size: 12px;"></i>
                </div>
                <p style="margin: 0; color: #92400e; line-height: 1.6; font-weight: 500;">{{ $tip }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Common Issues -->
    @if(isset($module['common_issues']) && count($module['common_issues']) > 0)
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-left: 10px;"></i>
            المشاكل الشائعة وحلولها
        </h2>
        
        <div style="display: grid; gap: 20px;">
            @foreach($module['common_issues'] as $issue)
            <div style="border: 1px solid #fecaca; border-radius: 12px; padding: 20px; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);">
                <div style="display: flex; align-items: flex-start; gap: 15px;">
                    <div style="background: #ef4444; color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-question" style="font-size: 16px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 10px 0; color: #991b1b; font-weight: 700; font-size: 16px;">المشكلة:</h4>
                        <p style="margin: 0 0 15px 0; color: #7f1d1d; line-height: 1.6;">{{ $issue['issue'] }}</p>
                        
                        <div style="background: white; border-radius: 8px; padding: 15px; border-right: 3px solid #10b981;">
                            <h5 style="margin: 0 0 8px 0; color: #065f46; font-weight: 600; font-size: 14px;">
                                <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                                الحل:
                            </h5>
                            <p style="margin: 0; color: #064e3b; line-height: 1.6;">{{ $issue['solution'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Navigation -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <a href="{{ route('tenant.system-guide.index') }}" 
               style="display: flex; align-items: center; gap: 10px; background: #6b7280; color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
               onmouseover="this.style.background='#4b5563'"
               onmouseout="this.style.background='#6b7280'">
                <i class="fas fa-arrow-right"></i>
                العودة للقائمة الرئيسية
            </a>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('tenant.system-guide.faq') }}" 
                   style="background: #f59e0b; color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
                   onmouseover="this.style.background='#d97706'"
                   onmouseout="this.style.background='#f59e0b'">
                    <i class="fas fa-question-circle"></i> الأسئلة الشائعة
                </a>
                
                <a href="{{ route('tenant.system-guide.videos', $module['slug']) }}" 
                   style="background: {{ $module['color'] }}; color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
                   onmouseover="this.style.opacity='0.9'"
                   onmouseout="this.style.opacity='1'">
                    <i class="fas fa-play"></i> الفيديوهات التعليمية
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function startModuleTour(moduleSlug) {
    // سيتم تطبيق هذا لاحقاً مع Intro.js
    alert('سيتم إطلاق الجولة التفاعلية لوحدة ' + moduleSlug + ' قريباً!');
}
</script>
@endsection
