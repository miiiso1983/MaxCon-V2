@extends('layouts.modern')

@section('page-title', 'الفيديوهات التعليمية')
@section('page-description', 'مكتبة شاملة من الفيديوهات التعليمية لتعلم استخدام نظام MaxCon ERP')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 25px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: -20px;
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .video-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }

    .video-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #667eea;
    }

    .video-thumbnail {
        position: relative;
        aspect-ratio: 16/9;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 48px;
        cursor: pointer;
    }

    .video-duration {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .video-info {
        padding: 20px;
    }

    .video-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .video-description {
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .video-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #94a3b8;
    }

    .difficulty-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .difficulty-beginner { background: #dcfce7; color: #166534; }
    .difficulty-intermediate { background: #fef3c7; color: #92400e; }
    .difficulty-advanced { background: #fecaca; color: #991b1b; }

    .category-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .category-btn {
        padding: 10px 20px;
        border-radius: 25px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-btn:hover,
    .category-btn.active {
        border-color: #667eea;
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .featured-video {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #f59e0b;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #64748b;
        font-size: 14px;
    }

    .videos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .search-box {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .search-input {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .video-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .rating-stars {
        color: #fbbf24;
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .videos-header {
            padding: 25px;
        }
        
        .featured-video {
            padding: 20px;
        }
        
        .videos-grid {
            grid-template-columns: 1fr;
        }
        
        .category-filter {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div style="position: relative; z-index: 2; text-align: center;">
            <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 10px;">
                <i class="fas fa-play-circle" style="margin-left: 10px;"></i>
                الفيديوهات التعليمية
            </h1>
            <p style="font-size: 1rem; opacity: 0.9; margin-bottom: 20px;">
                مكتبة شاملة من الفيديوهات التعليمية لتعلم استخدام نظام MaxCon ERP
            </p>

            <!-- Quick Stats -->
            @if(isset($videoStats))
            <div class="row text-center">
                <div class="col-md-4">
                    <div style="font-size: 1.5rem; font-weight: 700;">{{ $videoStats['total_videos'] }}</div>
                    <div style="font-size: 0.9rem; opacity: 0.8;">فيديو تعليمي</div>
                </div>
                <div class="col-md-4">
                    <div style="font-size: 1.5rem; font-weight: 700;">{{ $videoStats['total_duration'] }}</div>
                    <div style="font-size: 0.9rem; opacity: 0.8;">إجمالي المدة</div>
                </div>
                <div class="col-md-4">
                    <div style="font-size: 1.5rem; font-weight: 700;">{{ number_format($videoStats['total_views']) }}</div>
                    <div style="font-size: 0.9rem; opacity: 0.8;">مشاهدة</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <div style="display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-search" style="color: #64748b; font-size: 18px;"></i>
            <input type="text" class="search-input" placeholder="ابحث عن فيديو تعليمي..." id="videoSearch">
        </div>
    </div>

    <!-- Category Filters -->
    @if(isset($categories))
    <div class="category-filter">
        <a href="{{ route('tenant.system-guide.videos') }}" class="category-btn {{ !$moduleSlug ? 'active' : '' }}">
            <i class="fas fa-th-large"></i>
            جميع الفيديوهات
        </a>
        @foreach($categories as $slug => $category)
        <a href="{{ route('tenant.system-guide.videos', $slug) }}" 
           class="category-btn {{ $moduleSlug == $slug ? 'active' : '' }}"
           @php
               $activeStyle = ($moduleSlug == $slug) ? 'border-color: ' . $category['color'] . '; background: ' . $category['color'] . ';' : '';
           @endphp
           @if(!empty($activeStyle)) style="{{ $activeStyle }}" @endif>
            <i class="{{ $category['icon'] }}"></i>
            {{ $category['name'] }}
        </a>
        @endforeach
    </div>
    @endif

    <!-- Featured Video -->
    @if(isset($featuredVideo) && !$moduleSlug)
    <div class="featured-video">
        <h2 style="color: #1e293b; font-weight: 700; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-star" style="color: #fbbf24; margin-left: 10px;"></i>
            الفيديو المميز
        </h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: center;">
            <div>
                <div class="video-thumbnail" onclick="playVideo('{{ $featuredVideo['id'] }}')">
                    <i class="fas fa-play-circle"></i>
                    <div class="video-duration">{{ $featuredVideo['duration'] }}</div>
                </div>
            </div>
            
            <div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 15px;">
                    {{ $featuredVideo['title'] }}
                </h3>
                <p style="color: #64748b; line-height: 1.6; margin-bottom: 20px;">
                    {{ $featuredVideo['description'] }}
                </p>
                
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                    <span class="difficulty-badge difficulty-beginner">{{ $featuredVideo['difficulty'] }}</span>
                    <span style="color: #64748b; font-size: 14px;">
                        <i class="fas fa-eye" style="margin-left: 5px;"></i>
                        {{ number_format($featuredVideo['views']) }} مشاهدة
                    </span>
                    <span style="color: #64748b; font-size: 14px;">
                        <span class="rating-stars">★★★★★</span>
                        {{ $featuredVideo['rating'] }}
                    </span>
                </div>
                
                <button onclick="playVideo('{{ $featuredVideo['id'] }}')"
                        style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: transform 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'">>
                    <i class="fas fa-play" style="margin-left: 8px;"></i>
                    مشاهدة الفيديو
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Videos Grid -->
    <div style="margin-bottom: 30px;">
        <h2 style="color: #1e293b; font-weight: 700; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-video" style="color: #f59e0b; margin-left: 10px;"></i>
            @if($moduleSlug && isset($categories[$moduleSlug]))
                فيديوهات {{ $categories[$moduleSlug]['name'] }}
            @else
                جميع الفيديوهات التعليمية
            @endif
        </h2>

        <div class="videos-grid" id="videosGrid">
            @if(isset($videos) && count($videos) > 0)
                @foreach($videos as $moduleKey => $moduleVideos)
                    @if(is_array($moduleVideos))
                        @foreach($moduleVideos as $video)
                        <div class="video-card" data-category="{{ $moduleKey }}" data-title="{{ strtolower($video['title']) }}" data-tags="{{ implode(' ', $video['tags']) }}">
                            <div class="video-thumbnail" onclick="playVideo('{{ $video['id'] }}')">
                                <i class="fas fa-play-circle"></i>
                                <div class="video-duration">{{ $video['duration'] }}</div>
                            </div>

                            <div class="video-info">
                                <h3 class="video-title">{{ $video['title'] }}</h3>
                                <p class="video-description">{{ $video['description'] }}</p>

                                <div class="video-meta">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span class="difficulty-badge difficulty-{{ $video['difficulty'] == 'مبتدئ' ? 'beginner' : ($video['difficulty'] == 'متوسط' ? 'intermediate' : 'advanced') }}">
                                            {{ $video['difficulty'] }}
                                        </span>
                                        <span>{{ $video['category'] }}</span>
                                    </div>

                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <span>
                                            <i class="fas fa-eye" style="margin-left: 3px;"></i>
                                            {{ number_format($video['views']) }}
                                        </span>
                                        <span class="rating-stars">★</span>
                                        <span>{{ $video['rating'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                @endforeach
            @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                    <i class="fas fa-video" style="font-size: 64px; color: #e2e8f0; margin-bottom: 20px;"></i>
                    <h3 style="color: #64748b; margin-bottom: 10px;">لا توجد فيديوهات متاحة حالياً</h3>
                    <p style="color: #94a3b8;">سيتم إضافة المزيد من الفيديوهات التعليمية قريباً</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Video Creation Guide -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="color: #1e293b; font-weight: 700; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-camera" style="color: #10b981; margin-left: 10px;"></i>
            دليل إنشاء الفيديوهات التعليمية
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
            <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
                <h3 style="color: #1e293b; font-weight: 600; margin-bottom: 15px;">
                    <i class="fas fa-desktop" style="color: #3b82f6; margin-left: 8px;"></i>
                    أدوات التسجيل المقترحة
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        <strong>Loom:</strong> سهل الاستخدام، مثالي للمبتدئين
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        <strong>OBS Studio:</strong> مجاني ومتقدم
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        <strong>Camtasia:</strong> احترافي مع تحرير متقدم
                    </li>
                    <li style="padding: 8px 0;">
                        <strong>ScreenFlow:</strong> ممتاز لنظام Mac
                    </li>
                </ul>
            </div>

            <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
                <h3 style="color: #1e293b; font-weight: 600; margin-bottom: 15px;">
                    <i class="fas fa-list-check" style="color: #10b981; margin-left: 8px;"></i>
                    نصائح لفيديوهات فعالة
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        ✓ مدة مثالية: 5-15 دقيقة
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        ✓ صوت واضح ومفهوم
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        ✓ خطوات واضحة ومنطقية
                    </li>
                    <li style="padding: 8px 0;">
                        ✓ أمثلة عملية من النظام
                    </li>
                </ul>
            </div>

            <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
                <h3 style="color: #1e293b; font-weight: 600; margin-bottom: 15px;">
                    <i class="fas fa-upload" style="color: #f59e0b; margin-left: 8px;"></i>
                    خطوات الرفع والنشر
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        1. تسجيل الفيديو بجودة عالية
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        2. تحرير وإضافة العناوين
                    </li>
                    <li style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        3. رفع على منصة الاستضافة
                    </li>
                    <li style="padding: 8px 0;">
                        4. إضافة للنظام مع البيانات
                    </li>
                </ul>
            </div>
        </div>

        <div style="text-align: center; margin-top: 25px;">
            <button style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: transform 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.transform='translateY(0)'"
                    onclick="alert('سيتم إضافة نموذج طلب إنشاء فيديو قريباً')">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                طلب إنشاء فيديو جديد
            </button>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div id="videoModal" class="video-modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeVideoModal()">
            <i class="fas fa-times"></i>
        </button>

        <div id="videoContent">
            <!-- Video content will be loaded here -->
        </div>
    </div>
</div>

<script>
// Video search functionality
document.getElementById('videoSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const videoCards = document.querySelectorAll('.video-card');

    videoCards.forEach(card => {
        const title = card.dataset.title;
        const tags = card.dataset.tags;
        const category = card.dataset.category;

        if (title.includes(searchTerm) || tags.includes(searchTerm) || category.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Video player functionality
function playVideo(videoId) {
    const modal = document.getElementById('videoModal');
    const content = document.getElementById('videoContent');

    // Sample video content - في التطبيق الحقيقي ستكون هذه فيديوهات حقيقية
    const videos = {
        'system-overview': {
            title: 'نظرة عامة شاملة على نظام MaxCon ERP',
            description: 'تعرف على جميع وحدات النظام وكيفية استخدامها بفعالية',
            embedCode: '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-play-circle"></i><div style="font-size: 16px; margin-top: 15px; text-align: center;">فيديو تجريبي - نظرة عامة على النظام<br><small style="font-size: 14px; opacity: 0.8;">سيتم إضافة الفيديو الحقيقي قريباً</small></div></div>'
        },
        'sales-overview': {
            title: 'مقدمة في إدارة المبيعات',
            description: 'تعلم كيفية إدارة العملاء وإنشاء الطلبات والفواتير',
            embedCode: '<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-shopping-bag"></i><div style="font-size: 16px; margin-top: 15px; text-align: center;">فيديو إدارة المبيعات<br><small style="font-size: 14px; opacity: 0.8;">شرح شامل لوحدة المبيعات</small></div></div>'
        },
        'customer-management': {
            title: 'إدارة العملاء',
            description: 'كيفية إضافة وإدارة بيانات العملاء بشكل فعال',
            embedCode: '<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-users"></i><div style="font-size: 16px; margin-top: 15px; text-align: center;">إدارة العملاء<br><small style="font-size: 14px; opacity: 0.8;">إضافة وتحديث بيانات العملاء</small></div></div>'
        },
        'inventory-overview': {
            title: 'مقدمة في إدارة المخزون',
            description: 'نظرة شاملة على وحدة إدارة المخزون وإمكانياتها',
            embedCode: '<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-warehouse"></i><div style="font-size: 16px; margin-top: 15px; text-align: center;">إدارة المخزون<br><small style="font-size: 14px; opacity: 0.8;">تتبع المنتجات والمستودعات</small></div></div>'
        },
        'accounting-overview': {
            title: 'مقدمة في النظام المحاسبي',
            description: 'أساسيات النظام المحاسبي ودليل الحسابات',
            embedCode: '<div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-calculator"></i><div style="font-size: 16px; margin-top: 15px; text-align: center;">النظام المحاسبي<br><small style="font-size: 14px; opacity: 0.8;">القيود والتقارير المالية</small></div></div>'
        }
    };

    const video = videos[videoId] || {
        title: 'فيديو تعليمي',
        description: 'وصف الفيديو التعليمي',
        embedCode: '<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 48px; border-radius: 10px;"><i class="fas fa-video"></i><div style="font-size: 16px; margin-top: 15px; text-align: center;">فيديو تجريبي<br><small style="font-size: 14px; opacity: 0.8;">سيتم إضافة المحتوى قريباً</small></div></div>'
    };

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

function closeVideoModal() {
    document.getElementById('videoModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideoModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoModal();
    }
});
</script>
@endsection
