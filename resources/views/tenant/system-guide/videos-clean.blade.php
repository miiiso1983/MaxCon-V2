@extends('layouts.modern')

@section('page-title', 'الفيديوهات التعليمية')
@section('page-description', 'مكتبة شاملة من الفيديوهات التعليمية لتعلم استخدام نظام MaxCon ERP')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        color: white;
        text-align: center;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .stats-row {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .stat-item {
        text-align: center;
        padding: 15px;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .search-filter-section {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .search-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .category-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .category-btn {
        padding: 8px 16px;
        border-radius: 20px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .category-btn:hover,
    .category-btn.active {
        border-color: #667eea;
        background: #667eea;
        color: white;
        text-decoration: none;
    }

    .videos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .video-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
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
        font-size: 2.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .video-thumbnail:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    }

    .video-duration {
        position: absolute;
        bottom: 8px;
        left: 8px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .video-info {
        padding: 15px;
    }

    .video-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .video-description {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.4;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .video-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #9ca3af;
    }

    .difficulty-badge {
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
    }

    .difficulty-beginner { background: #dcfce7; color: #166534; }
    .difficulty-intermediate { background: #fef3c7; color: #92400e; }
    .difficulty-advanced { background: #fecaca; color: #991b1b; }

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
        border-radius: 15px;
        padding: 25px;
        max-width: 800px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }

    .modal-close {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 4rem;
        color: #e5e7eb;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .videos-grid {
            grid-template-columns: 1fr;
        }
        
        .category-filters {
            justify-content: center;
        }
        
        .page-header {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 8px;">
            <i class="fas fa-play-circle" style="margin-left: 8px;"></i>
            الفيديوهات التعليمية
        </h1>
        <p style="font-size: 1rem; opacity: 0.9; margin: 0;">
            مكتبة شاملة من الفيديوهات التعليمية لتعلم استخدام نظام MaxCon ERP
        </p>
    </div>

    <!-- Stats Section -->
    @if(isset($videoStats))
    <div class="stats-row">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-item">
                    <div class="stat-number">{{ $videoStats['total_videos'] }}</div>
                    <div class="stat-label">فيديو تعليمي</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <div class="stat-number">{{ $videoStats['total_duration'] }}</div>
                    <div class="stat-label">إجمالي المدة</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <div class="stat-number">{{ number_format($videoStats['total_views']) }}</div>
                    <div class="stat-label">مشاهدة</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="search-filter-section">
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-search text-muted me-2"></i>
                <input type="text" class="search-input" placeholder="ابحث عن فيديو تعليمي..." id="videoSearch">
            </div>
        </div>
        
        @if(isset($categories))
        <div class="category-filters">
            <a href="{{ route('tenant.system-guide.videos') }}" class="category-btn {{ !$moduleSlug ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                جميع الفيديوهات
            </a>
            @foreach($categories as $slug => $category)
            <a href="{{ route('tenant.system-guide.videos', $slug) }}" 
               class="category-btn {{ $moduleSlug == $slug ? 'active' : '' }}">
                <i class="{{ $category['icon'] }}"></i>
                {{ $category['name'] }}
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Videos Grid -->
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
                                <div class="d-flex align-items-center gap-2">
                                    <span class="difficulty-badge difficulty-{{ $video['difficulty'] == 'مبتدئ' ? 'beginner' : ($video['difficulty'] == 'متوسط' ? 'intermediate' : 'advanced') }}">
                                        {{ $video['difficulty'] }}
                                    </span>
                                    <span>{{ $video['category'] }}</span>
                                </div>
                                
                                <div class="d-flex align-items-center gap-2">
                                    <span>
                                        <i class="fas fa-eye"></i>
                                        {{ number_format($video['views']) }}
                                    </span>
                                    <span>
                                        <i class="fas fa-star text-warning"></i>
                                        {{ $video['rating'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            @endforeach
        @else
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-video"></i>
                <h3>لا توجد فيديوهات متاحة حالياً</h3>
                <p>سيتم إضافة المزيد من الفيديوهات التعليمية قريباً</p>
            </div>
        @endif
    </div>

    <!-- Video Creation Guide Section -->
    <div class="search-filter-section">
        <h3 class="mb-3" style="color: #1f2937; font-weight: 600;">
            <i class="fas fa-camera text-success me-2"></i>
            دليل إنشاء الفيديوهات التعليمية
        </h3>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded">
                    <h5 style="color: #667eea; font-size: 1rem; font-weight: 600;">
                        <i class="fas fa-desktop me-2"></i>
                        أدوات التسجيل المقترحة
                    </h5>
                    <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                        <li class="mb-1"><strong>Loom:</strong> سهل الاستخدام، مثالي للمبتدئين</li>
                        <li class="mb-1"><strong>OBS Studio:</strong> مجاني ومتقدم</li>
                        <li class="mb-1"><strong>Camtasia:</strong> احترافي مع تحرير متقدم</li>
                        <li class="mb-0"><strong>ScreenFlow:</strong> ممتاز لنظام Mac</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded">
                    <h5 style="color: #10b981; font-size: 1rem; font-weight: 600;">
                        <i class="fas fa-list-check me-2"></i>
                        نصائح لفيديوهات فعالة
                    </h5>
                    <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                        <li class="mb-1">✓ مدة مثالية: 5-15 دقيقة</li>
                        <li class="mb-1">✓ صوت واضح ومفهوم</li>
                        <li class="mb-1">✓ خطوات واضحة ومنطقية</li>
                        <li class="mb-0">✓ أمثلة عملية من النظام</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded">
                    <h5 style="color: #f59e0b; font-size: 1rem; font-weight: 600;">
                        <i class="fas fa-upload me-2"></i>
                        خطوات الرفع والنشر
                    </h5>
                    <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                        <li class="mb-1">1. تسجيل الفيديو بجودة عالية</li>
                        <li class="mb-1">2. تحرير وإضافة العناوين</li>
                        <li class="mb-1">3. رفع على منصة الاستضافة</li>
                        <li class="mb-0">4. إضافة للنظام مع البيانات</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <button class="btn btn-success" onclick="alert('سيتم إضافة نموذج طلب إنشاء فيديو قريباً')">
                <i class="fas fa-plus me-2"></i>
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('videoSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const videoCards = document.querySelectorAll('.video-card');

            videoCards.forEach(card => {
                const title = card.dataset.title || '';
                const tags = card.dataset.tags || '';
                const category = card.dataset.category || '';

                if (title.includes(searchTerm) || tags.includes(searchTerm) || category.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
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
            embedCode: '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 3rem; border-radius: 10px;"><i class="fas fa-play-circle"></i><div style="font-size: 1rem; margin-top: 15px; text-align: center;">فيديو تجريبي - نظرة عامة على النظام<br><small style="font-size: 0.9rem; opacity: 0.8;">سيتم إضافة الفيديو الحقيقي قريباً</small></div></div>'
        },
        'sales-overview': {
            title: 'مقدمة في إدارة المبيعات',
            description: 'تعلم كيفية إدارة العملاء وإنشاء الطلبات والفواتير',
            embedCode: '<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 3rem; border-radius: 10px;"><i class="fas fa-shopping-bag"></i><div style="font-size: 1rem; margin-top: 15px; text-align: center;">فيديو إدارة المبيعات<br><small style="font-size: 0.9rem; opacity: 0.8;">شرح شامل لوحدة المبيعات</small></div></div>'
        },
        'customer-management': {
            title: 'إدارة العملاء',
            description: 'كيفية إضافة وإدارة بيانات العملاء بشكل فعال',
            embedCode: '<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 3rem; border-radius: 10px;"><i class="fas fa-users"></i><div style="font-size: 1rem; margin-top: 15px; text-align: center;">إدارة العملاء<br><small style="font-size: 0.9rem; opacity: 0.8;">إضافة وتحديث بيانات العملاء</small></div></div>'
        },
        'inventory-overview': {
            title: 'مقدمة في إدارة المخزون',
            description: 'نظرة شاملة على وحدة إدارة المخزون وإمكانياتها',
            embedCode: '<div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 3rem; border-radius: 10px;"><i class="fas fa-warehouse"></i><div style="font-size: 1rem; margin-top: 15px; text-align: center;">إدارة المخزون<br><small style="font-size: 0.9rem; opacity: 0.8;">تتبع المنتجات والمستودعات</small></div></div>'
        },
        'accounting-overview': {
            title: 'مقدمة في النظام المحاسبي',
            description: 'أساسيات النظام المحاسبي ودليل الحسابات',
            embedCode: '<div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 3rem; border-radius: 10px;"><i class="fas fa-calculator"></i><div style="font-size: 1rem; margin-top: 15px; text-align: center;">النظام المحاسبي<br><small style="font-size: 0.9rem; opacity: 0.8;">القيود والتقارير المالية</small></div></div>'
        }
    };

    const video = videos[videoId] || {
        title: 'فيديو تعليمي',
        description: 'وصف الفيديو التعليمي',
        embedCode: '<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); aspect-ratio: 16/9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; font-size: 3rem; border-radius: 10px;"><i class="fas fa-video"></i><div style="font-size: 1rem; margin-top: 15px; text-align: center;">فيديو تجريبي<br><small style="font-size: 0.9rem; opacity: 0.8;">سيتم إضافة المحتوى قريباً</small></div></div>'
    };

    content.innerHTML = `
        <h2 style="margin: 0 0 15px 0; color: #1f2937; font-size: 1.5rem; font-weight: 700;">${video.title}</h2>
        <p style="color: #6b7280; margin-bottom: 20px; line-height: 1.6;">${video.description}</p>
        ${video.embedCode}
        <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 10px; border-right: 4px solid #667eea;">
            <p style="margin: 0; color: #374151; font-size: 14px;">
                <i class="fas fa-info-circle" style="color: #667eea; margin-left: 5px;"></i>
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
