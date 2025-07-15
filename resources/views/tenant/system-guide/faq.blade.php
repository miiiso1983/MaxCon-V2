@extends('layouts.modern')

@section('page-title', 'الأسئلة الشائعة')
@section('page-description', 'إجابات للأسئلة الأكثر شيوعاً حول استخدام النظام')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 40px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 2;">
            <h1 style="margin: 0 0 15px 0; font-size: 36px; font-weight: 800;">
                <i class="fas fa-question-circle" style="margin-left: 15px;"></i>
                الأسئلة الشائعة
            </h1>
            <p style="font-size: 18px; margin: 0; opacity: 0.9; line-height: 1.6;">
                إجابات سريعة وواضحة للأسئلة الأكثر شيوعاً حول استخدام نظام MaxCon ERP
            </p>
        </div>
    </div>

    <!-- Search Box -->
    <div style="background: white; border-radius: 20px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="flex: 1; position: relative;">
                <input type="text" 
                       id="faqSearch" 
                       placeholder="ابحث في الأسئلة الشائعة..." 
                       style="width: 100%; padding: 15px 50px 15px 20px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; transition: all 0.3s ease;"
                       onkeyup="searchFAQs()"
                       onfocus="this.style.borderColor='#8b5cf6'"
                       onblur="this.style.borderColor='#e2e8f0'">
                <i class="fas fa-search" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 18px;"></i>
            </div>
            <button onclick="clearSearch()" 
                    style="background: #6b7280; color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                    onmouseover="this.style.background='#4b5563'"
                    onmouseout="this.style.background='#6b7280'">
                <i class="fas fa-times"></i> مسح
            </button>
        </div>
    </div>

    <!-- FAQ Categories -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
            <i class="fas fa-th-large" style="color: #8b5cf6; margin-left: 10px;"></i>
            تصفح حسب الفئة
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            @foreach($categories as $categoryKey => $category)
            <div style="padding: 20px; border-radius: 15px; background: linear-gradient(135deg, {{ $category['color'] }}10 0%, {{ $category['color'] }}05 100%); border: 2px solid {{ $category['color'] }}20; cursor: pointer; transition: all 0.3s ease;"
                 onclick="filterByCategory('{{ $categoryKey }}')"
                 onmouseover="this.style.borderColor='{{ $category['color'] }}'; this.style.transform='translateY(-3px)'"
                 onmouseout="this.style.borderColor='{{ $category['color'] }}20'; this.style.transform='translateY(0)'">
                
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="background: {{ $category['color'] }}; color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="{{ $category['icon'] }}"></i>
                        </div>
                        <h3 style="margin: 0; color: #1e293b; font-size: 16px; font-weight: 700;">{{ $category['name'] }}</h3>
                    </div>
                    <span style="background: {{ $category['color'] }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        {{ $category['count'] }}
                    </span>
                </div>
                
                <p style="margin: 0; color: #64748b; font-size: 14px;">{{ $categoryKey }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- FAQ Items -->
    <div style="background: white; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
            <h2 style="margin: 0; color: #1e293b; font-size: 24px; font-weight: 700;">
                <i class="fas fa-list" style="color: #8b5cf6; margin-left: 10px;"></i>
                جميع الأسئلة
            </h2>
            
            <div style="display: flex; gap: 10px;">
                <button onclick="expandAll()" 
                        style="background: #10b981; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#059669'"
                        onmouseout="this.style.background='#10b981'">
                    <i class="fas fa-expand-alt"></i> توسيع الكل
                </button>
                
                <button onclick="collapseAll()" 
                        style="background: #ef4444; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#dc2626'"
                        onmouseout="this.style.background='#ef4444'">
                    <i class="fas fa-compress-alt"></i> طي الكل
                </button>
            </div>
        </div>
        
        <div id="faqContainer">
            @foreach($faqs as $faq)
            <div class="faq-item" data-category="{{ $faq['category'] }}" style="border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 15px; overflow: hidden; transition: all 0.3s ease;">
                <!-- Question -->
                <div class="faq-question" 
                     style="padding: 20px; background: #f8fafc; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease;"
                     onclick="toggleFAQ({{ $faq['id'] }})"
                     onmouseover="this.style.background='#f1f5f9'"
                     onmouseout="this.style.background='#f8fafc'">
                    
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 8px 0; color: #1e293b; font-size: 18px; font-weight: 700;">{{ $faq['question'] }}</h3>
                        
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="background: {{ $categories[$faq['category']]['color'] }}20; color: {{ $categories[$faq['category']]['color'] }}; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                <i class="{{ $categories[$faq['category']]['icon'] }}"></i> {{ $faq['category'] }}
                            </span>
                            
                            <span style="color: #64748b; font-size: 12px;">
                                <i class="fas fa-thumbs-up"></i> {{ $faq['helpful_count'] }} شخص وجد هذا مفيداً
                            </span>
                            
                            @if(isset($faq['tags']))
                            <div style="display: flex; gap: 5px;">
                                @foreach($faq['tags'] as $tag)
                                <span style="background: #e2e8f0; color: #475569; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 500;">{{ $tag }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div style="margin-right: 15px;">
                        <i id="faq-icon-{{ $faq['id'] }}" class="fas fa-chevron-down" style="color: #64748b; font-size: 16px; transition: transform 0.3s ease;"></i>
                    </div>
                </div>
                
                <!-- Answer -->
                <div id="faq-answer-{{ $faq['id'] }}" class="faq-answer" style="display: none; padding: 25px; background: white; border-top: 1px solid #e2e8f0;">
                    <div style="color: #475569; line-height: 1.8; font-size: 16px; margin-bottom: 20px;">
                        {{ $faq['answer'] }}
                    </div>
                    
                    <!-- Helpful Actions -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 15px; border-top: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="color: #64748b; font-size: 14px; font-weight: 600;">هل كانت هذه الإجابة مفيدة؟</span>
                            
                            <button onclick="markHelpful({{ $faq['id'] }}, true)" 
                                    style="background: #dcfce7; color: #065f46; padding: 6px 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px; transition: all 0.3s ease;"
                                    onmouseover="this.style.background='#bbf7d0'"
                                    onmouseout="this.style.background='#dcfce7'">
                                <i class="fas fa-thumbs-up"></i> نعم
                            </button>
                            
                            <button onclick="markHelpful({{ $faq['id'] }}, false)" 
                                    style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px; transition: all 0.3s ease;"
                                    onmouseover="this.style.background='#fecaca'"
                                    onmouseout="this.style.background='#fee2e2'">
                                <i class="fas fa-thumbs-down"></i> لا
                            </button>
                        </div>
                        
                        <button onclick="contactSupport()" 
                                style="background: #8b5cf6; color: white; padding: 6px 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px; transition: all 0.3s ease;"
                                onmouseover="this.style.background='#7c3aed'"
                                onmouseout="this.style.background='#8b5cf6'">
                            <i class="fas fa-headset"></i> تواصل مع الدعم
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- No Results Message -->
        <div id="noResults" style="display: none; text-align: center; padding: 40px; color: #64748b;">
            <i class="fas fa-search" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600;">لم يتم العثور على نتائج</h3>
            <p style="margin: 0; font-size: 16px;">جرب البحث بكلمات مختلفة أو تصفح الفئات أعلاه</p>
        </div>
    </div>

    <!-- Contact Support -->
    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; border: 2px solid #f59e0b;">
        <div style="text-align: center;">
            <h2 style="margin: 0 0 15px 0; color: #92400e; font-size: 24px; font-weight: 700;">
                <i class="fas fa-life-ring" style="margin-left: 10px;"></i>
                لم تجد إجابة لسؤالك؟
            </h2>
            <p style="color: #92400e; margin-bottom: 25px; font-size: 16px;">
                فريق الدعم الفني جاهز لمساعدتك في أي وقت
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
                
                <button onclick="openLiveChat()" 
                        style="background: #3b82f6; color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#1d4ed8'"
                        onmouseout="this.style.background='#3b82f6'">
                    <i class="fas fa-comments"></i> الدردشة المباشرة
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(id) {
    const answer = document.getElementById(`faq-answer-${id}`);
    const icon = document.getElementById(`faq-icon-${id}`);
    
    if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}

function expandAll() {
    const answers = document.querySelectorAll('.faq-answer');
    const icons = document.querySelectorAll('[id^="faq-icon-"]');
    
    answers.forEach(answer => answer.style.display = 'block');
    icons.forEach(icon => icon.style.transform = 'rotate(180deg)');
}

function collapseAll() {
    const answers = document.querySelectorAll('.faq-answer');
    const icons = document.querySelectorAll('[id^="faq-icon-"]');
    
    answers.forEach(answer => answer.style.display = 'none');
    icons.forEach(icon => icon.style.transform = 'rotate(0deg)');
}

function searchFAQs() {
    const searchTerm = document.getElementById('faqSearch').value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('noResults');
    let hasResults = false;
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
            hasResults = true;
        } else {
            item.style.display = 'none';
        }
    });
    
    noResults.style.display = hasResults ? 'none' : 'block';
}

function clearSearch() {
    document.getElementById('faqSearch').value = '';
    searchFAQs();
}

function filterByCategory(category) {
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('noResults');
    let hasResults = false;
    
    faqItems.forEach(item => {
        const itemCategory = item.getAttribute('data-category');
        
        if (itemCategory === category) {
            item.style.display = 'block';
            hasResults = true;
        } else {
            item.style.display = 'none';
        }
    });
    
    noResults.style.display = hasResults ? 'none' : 'block';
    
    // Clear search
    document.getElementById('faqSearch').value = '';
}

function markHelpful(id, helpful) {
    // هنا يمكن إرسال طلب AJAX لحفظ التقييم
    const message = helpful ? 'شكراً لك على تقييمك الإيجابي!' : 'شكراً لك، سنعمل على تحسين هذه الإجابة';
    
    // عرض رسالة مؤقتة
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; top: 20px; right: 20px; z-index: 10000;
        background: ${helpful ? '#10b981' : '#f59e0b'}; color: white;
        padding: 15px 20px; border-radius: 10px; font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%); transition: transform 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.style.transform = 'translateX(0)', 100);
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

function contactSupport() {
    alert('سيتم توجيهك إلى صفحة الدعم الفني قريباً!');
}

function openLiveChat() {
    alert('سيتم فتح نافذة الدردشة المباشرة قريباً!');
}
</script>
@endsection
