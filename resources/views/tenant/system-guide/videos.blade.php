@extends('layouts.modern')

@section('page-title', 'الفيديوهات التعليمية')
@section('page-description', 'مكتبة شاملة من الفيديوهات التعليمية لتعلم استخدام النظام')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 40px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

        <div style="position: relative; z-index: 2;">
            <h1 style="margin: 0 0 15px 0; font-size: 36px; font-weight: 800;">
                <i class="fas fa-play-circle" style="margin-left: 15px;"></i>
                الفيديوهات التعليمية
            </h1>
            <p style="font-size: 18px; margin: 0; opacity: 0.9; line-height: 1.6;">
                تعلم استخدام النظام من خلال فيديوهات تفاعلية وشروحات مرئية مفصلة
            </p>
        </div>
    </div>

    <!-- Module Filter -->
    <div style="background: white; border-radius: 20px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 20px 0; color: #1e293b; font-size: 18px; font-weight: 700;">
            <i class="fas fa-filter" style="color: #3b82f6; margin-left: 8px;"></i>
            تصفية حسب الوحدة
        </h3>

        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('tenant.system-guide.videos') }}"
               style="padding: 10px 20px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; {{ !$moduleSlug ? 'background: #3b82f6; color: white;' : 'background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;' }}"
               @if(!$moduleSlug)
               onmouseover="this.style.background='#1d4ed8'"
               onmouseout="this.style.background='#3b82f6'"
               @else
               onmouseover="this.style.background='#e2e8f0'"
               onmouseout="this.style.background='#f1f5f9'"
               @endif>
                <i class="fas fa-th-large"></i> جميع الوحدات
            </a>

            @foreach($modules as $slug => $module)
            <a href="{{ route('tenant.system-guide.videos', $slug) }}"
               style="padding: 10px 20px; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; {{ $moduleSlug === $slug ? 'background: ' . $module['color'] . '; color: white;' : 'background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;' }}"
               @if($moduleSlug === $slug)
               onmouseover="this.style.opacity='0.9'"
               onmouseout="this.style.opacity='1'"
               @else
               onmouseover="this.style.background='#e2e8f0'"
               onmouseout="this.style.background='#f1f5f9'"
               @endif>
                <i class="{{ $module['icon'] }}"></i> {{ $module['name'] }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Video Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">24</div>
            <div style="opacity: 0.9;">إجمالي الفيديوهات</div>
        </div>

        <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">3:45:20</div>
            <div style="opacity: 0.9;">إجمالي المدة</div>
        </div>

        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">8</div>
            <div style="opacity: 0.9;">وحدات مغطاة</div>
        </div>

        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">HD</div>
            <div style="opacity: 0.9;">جودة عالية</div>
        </div>
    </div>

    <!-- Featured Video -->
    @if(!$moduleSlug)
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-star" style="color: #f59e0b; margin-left: 10px;"></i>
            الفيديو المميز
        </h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: center;">
            <div>
                <div style="position: relative; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.15);">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; cursor: pointer;"
                         onclick="playFeaturedVideo()">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        12:30
                    </div>
                </div>
            </div>

            <div>
                <h3 style="margin: 0 0 15px 0; color: #1e293b; font-size: 24px; font-weight: 700;">مقدمة شاملة عن النظام</h3>
                <p style="color: #64748b; line-height: 1.6; margin-bottom: 20px;">
                    تعرف على جميع وحدات النظام وكيفية التنقل بينها، وأهم المميزات التي ستساعدك في إدارة أعمالك بكفاءة أكبر.
                </p>

                <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                    <span style="background: #e0f2fe; color: #0c4a6e; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-clock"></i> 12:30
                    </span>
                    <span style="background: #dcfce7; color: #065f46; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-signal"></i> مبتدئ
                    </span>
                    <span style="background: #fef3c7; color: #92400e; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-eye"></i> 1,234 مشاهدة
                    </span>
                </div>

                <button onclick="playFeaturedVideo()"
                        style="background: #f59e0b; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 16px;"
                        onmouseover="this.style.background='#d97706'"
                        onmouseout="this.style.background='#f59e0b'">
                    <i class="fas fa-play"></i> شاهد الآن
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Video Library -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-video" style="color: #3b82f6; margin-left: 10px;"></i>
            @if($moduleSlug)
                فيديوهات {{ $modules[$moduleSlug]['name'] ?? 'الوحدة المحددة' }}
            @else
                مكتبة الفيديوهات
            @endif
        </h2>

        <!-- Sample Videos Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <!-- Video 1: Sales Management -->
            <div style="border: 1px solid #e2e8f0; border-radius: 15px; overflow: hidden; background: #f8fafc; transition: all 0.3s ease;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                <div style="position: relative; cursor: pointer;" onclick="playVideo('sales-intro')">
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        8:30
                    </div>
                    <div style="position: absolute; top: 10px; right: 10px; background: #10b981; color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                        جديد
                    </div>
                </div>

                <div style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 18px; font-weight: 700;">مقدمة في إدارة المبيعات</h3>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                        تعلم كيفية إدارة العملاء وإنشاء الطلبات وإصدار الفواتير بطريقة احترافية.
                    </p>

                    <div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span style="background: #dcfce7; color: #065f46; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-signal"></i> مبتدئ
                        </span>
                        <span style="background: #e0f2fe; color: #0c4a6e; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-shopping-bag"></i> المبيعات
                        </span>
                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-eye"></i> 856 مشاهدة
                        </span>
                    </div>

                    <button onclick="playVideo('sales-intro')"
                            style="width: 100%; background: #10b981; color: white; padding: 10px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#059669'"
                            onmouseout="this.style.background='#10b981'">
                        <i class="fas fa-play"></i> شاهد الفيديو
                    </button>
                </div>
            </div>

            <!-- Video 2: Inventory Management -->
            <div style="border: 1px solid #e2e8f0; border-radius: 15px; overflow: hidden; background: #f8fafc; transition: all 0.3s ease;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                <div style="position: relative; cursor: pointer;" onclick="playVideo('inventory-intro')">
                    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        12:15
                    </div>
                </div>

                <div style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 18px; font-weight: 700;">إدارة المخزون والمنتجات</h3>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                        دليل شامل لإدارة المنتجات والمستودعات وتتبع حركات المخزون.
                    </p>

                    <div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-signal"></i> متوسط
                        </span>
                        <span style="background: #dbeafe; color: #1e3a8a; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-warehouse"></i> المخزون
                        </span>
                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-eye"></i> 642 مشاهدة
                        </span>
                    </div>

                    <button onclick="playVideo('inventory-intro')"
                            style="width: 100%; background: #3b82f6; color: white; padding: 10px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#1d4ed8'"
                            onmouseout="this.style.background='#3b82f6'">
                        <i class="fas fa-play"></i> شاهد الفيديو
                    </button>
                </div>
            </div>

            <!-- Video 3: Sales Targets -->
            <div style="border: 1px solid #e2e8f0; border-radius: 15px; overflow: hidden; background: #f8fafc; transition: all 0.3s ease;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                <div style="position: relative; cursor: pointer;" onclick="playVideo('targets-intro')">
                    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        6:20
                    </div>
                </div>

                <div style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 18px; font-weight: 700;">إدارة أهداف البيع</h3>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                        كيفية تحديد الأهداف ومتابعة التقدم وتحليل الأداء.
                    </p>

                    <div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span style="background: #dcfce7; color: #065f46; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-signal"></i> مبتدئ
                        </span>
                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-bullseye"></i> الأهداف
                        </span>
                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-eye"></i> 423 مشاهدة
                        </span>
                    </div>

                    <button onclick="playVideo('targets-intro')"
                            style="width: 100%; background: #f59e0b; color: white; padding: 10px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#d97706'"
                            onmouseout="this.style.background='#f59e0b'">
                        <i class="fas fa-play"></i> شاهد الفيديو
                    </button>
                </div>
            </div>

            <!-- Video 4: Accounting -->
            <div style="border: 1px solid #e2e8f0; border-radius: 15px; overflow: hidden; background: #f8fafc; transition: all 0.3s ease;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                <div style="position: relative; cursor: pointer;" onclick="playVideo('accounting-intro')">
                    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                        15:30
                    </div>
                </div>

                <div style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 18px; font-weight: 700;">النظام المحاسبي</h3>
                    <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                        شرح شامل للنظام المحاسبي والقيود والتقارير المالية.
                    </p>

                    <div style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-signal"></i> متقدم
                        </span>
                        <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-calculator"></i> المحاسبة
                        </span>
                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            <i class="fas fa-eye"></i> 789 مشاهدة
                        </span>
                    </div>

                    <button onclick="playVideo('accounting-intro')"
                            style="width: 100%; background: #ef4444; color: white; padding: 10px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#dc2626'"
                            onmouseout="this.style.background='#ef4444'">
                        <i class="fas fa-play"></i> شاهد الفيديو
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Player Modal -->
    <div id="videoModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 800px; width: 90%; max-height: 90%; overflow-y: auto; position: relative;">
            <button onclick="closeVideoModal()"
                    style="position: absolute; top: 15px; left: 15px; background: #ef4444; color: white; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-times"></i>
            </button>

            <div id="videoContent">
                <!-- Video content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function playFeaturedVideo() {
    playVideo('featured');
}

function playVideo(videoId) {
    const modal = document.getElementById('videoModal');
    const content = document.getElementById('videoContent');

    // Sample video content - في التطبيق الحقيقي ستكون هذه فيديوهات حقيقية
    const videos = {
        'featured': {
            title: 'مقدمة شاملة عن النظام',
            description: 'تعرف على جميع وحدات النظام وكيفية استخدامها',
            embedCode: '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-play-circle"></i><br><small style="font-size: 16px; margin-top: 10px;">فيديو تجريبي - سيتم إضافة الفيديو الحقيقي قريباً</small></div>'
        },
        'sales-intro': {
            title: 'مقدمة في إدارة المبيعات',
            description: 'تعلم كيفية إدارة العملاء وإنشاء الطلبات',
            embedCode: '<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-shopping-bag"></i><br><small style="font-size: 16px; margin-top: 10px;">فيديو إدارة المبيعات</small></div>'
        },
        'inventory-intro': {
            title: 'إدارة المخزون والمنتجات',
            description: 'دليل شامل لإدارة المنتجات والمستودعات',
            embedCode: '<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-warehouse"></i><br><small style="font-size: 16px; margin-top: 10px;">فيديو إدارة المخزون</small></div>'
        },
        'targets-intro': {
            title: 'إدارة أهداف البيع',
            description: 'كيفية تحديد الأهداف ومتابعة التقدم',
            embedCode: '<div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-bullseye"></i><br><small style="font-size: 16px; margin-top: 10px;">فيديو أهداف البيع</small></div>'
        },
        'accounting-intro': {
            title: 'النظام المحاسبي',
            description: 'شرح شامل للنظام المحاسبي والقيود المالية',
            embedCode: '<div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-calculator"></i><br><small style="font-size: 16px; margin-top: 10px;">فيديو النظام المحاسبي</small></div>'
        }
    };

    const video = videos[videoId];
    if (video) {
        content.innerHTML = `
            <h2 style="margin: 0 0 15px 0; color: #1e293b; font-size: 24px; font-weight: 700;">${video.title}</h2>
            <p style="color: #64748b; margin-bottom: 20px; line-height: 1.6;">${video.description}</p>
            ${video.embedCode}
            <div style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 10px; border-right: 4px solid #3b82f6;">
                <p style="margin: 0; color: #475569; font-size: 14px;">
                    <i class="fas fa-info-circle" style="color: #3b82f6; margin-left: 5px;"></i>
                    هذا فيديو تجريبي. في النسخة النهائية سيتم إضافة فيديوهات حقيقية عالية الجودة مع شرح صوتي باللغة العربية.
                </p>
            </div>
        `;
        modal.style.display = 'flex';
    }
}

function closeVideoModal() {
    document.getElementById('videoModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideoModal();
    }
});
</script>
@endsection