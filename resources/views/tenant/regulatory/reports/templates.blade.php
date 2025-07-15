@extends('layouts.modern')

@section('title', 'قوالب التقارير التنظيمية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">قوالب التقارير التنظيمية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">قوالب جاهزة لإنشاء التقارير التنظيمية المختلفة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.reports.create') }}" style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    إنشاء تقرير مخصص
                </a>
                <a href="{{ route('tenant.inventory.regulatory.reports.index') }}" style="background: rgba(255,255,255,0.2); color: #667eea; padding: 15px 25px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للتقارير
                </a>
            </div>
        </div>
    </div>

    <!-- Template Categories -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- All Templates -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; border: 2px solid #667eea;" onclick="filterTemplates('all')">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-th-large"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">جميع القوالب</h4>
        </div>

        <!-- Compliance -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; border: 2px solid transparent;" onclick="filterTemplates('compliance')">
            <div style="background: #4ecdc4; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">الامتثال</h4>
        </div>

        <!-- Incidents -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; border: 2px solid transparent;" onclick="filterTemplates('incident')">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">الحوادث</h4>
        </div>

        <!-- Audit -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; border: 2px solid transparent;" onclick="filterTemplates('audit')">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-search"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">التدقيق</h4>
        </div>

        <!-- Safety -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; cursor: pointer; border: 2px solid transparent;" onclick="filterTemplates('safety')">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">السلامة</h4>
        </div>
    </div>

    <!-- Templates Grid -->
    <div id="templatesGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">
        
        @foreach($templates as $template)
        <div class="template-card" data-category="{{ $template['category'] }}" style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); transition: transform 0.3s, box-shadow 0.3s;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 25px 50px rgba(0,0,0,0.15)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)'">
            
            <!-- Template Header -->
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                <div style="background: {{ $template['color'] }}; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="{{ $template['icon'] }}"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="color: #2d3748; margin: 0 0 5px 0; font-size: 20px; font-weight: 700;">{{ $template['title'] }}</h3>
                    <p style="color: #718096; margin: 0; font-size: 14px;">{{ $template['description'] }}</p>
                </div>
            </div>

            <!-- Template Details -->
            <div style="margin-bottom: 20px;">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <span style="background: rgba({{ $template['color'] === '#4ecdc4' ? '78, 205, 196' : ($template['color'] === '#f56565' ? '245, 101, 101' : ($template['color'] === '#9f7aea' ? '159, 122, 234' : ($template['color'] === '#ed8936' ? '237, 137, 54' : ($template['color'] === '#f093fb' ? '240, 147, 251' : '72, 187, 120')))) }}, 0.1); color: {{ $template['color'] }}; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                        {{ $template['category'] === 'compliance' ? 'امتثال' : ($template['category'] === 'incident' ? 'حادث' : ($template['category'] === 'audit' ? 'تدقيق' : ($template['category'] === 'inspection' ? 'تفتيش' : 'سلامة'))) }}
                    </span>
                    <span style="background: rgba(113, 128, 150, 0.1); color: #718096; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                        {{ $template['frequency'] === 'quarterly' ? 'ربعي' : ($template['frequency'] === 'annual' ? 'سنوي' : ($template['frequency'] === 'semi_annual' ? 'نصف سنوي' : 'حسب الحاجة')) }}
                    </span>
                </div>
                
                <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">أقسام التقرير:</h4>
                <ul style="margin: 0; padding-right: 20px; color: #4a5568; font-size: 14px; line-height: 1.6;">
                    @foreach($template['sections'] as $section)
                        <li>{{ $section }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- Template Actions -->
            <div style="display: flex; gap: 10px; justify-content: center;">
                <a href="/tenant/inventory/regulatory/reports/templates/{{ $template['id'] }}/create"
                   style="background: {{ $template['color'] }}; color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: opacity 0.3s;"
                   onmouseover="this.style.opacity='0.9'" 
                   onmouseout="this.style.opacity='1'">
                    <i class="fas fa-plus"></i>
                    استخدام القالب
                </a>
                
                <button onclick="previewTemplate('{{ $template['id'] }}')" 
                        style="background: rgba({{ $template['color'] === '#4ecdc4' ? '78, 205, 196' : ($template['color'] === '#f56565' ? '245, 101, 101' : ($template['color'] === '#9f7aea' ? '159, 122, 234' : ($template['color'] === '#ed8936' ? '237, 137, 54' : ($template['color'] === '#f093fb' ? '240, 147, 251' : '72, 187, 120')))) }}, 0.1); color: {{ $template['color'] }}; padding: 12px 20px; border: 2px solid {{ $template['color'] }}; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background-color 0.3s;">
                    <i class="fas fa-eye"></i>
                    معاينة
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div id="emptyState" style="display: none; text-align: center; padding: 60px 20px; color: #718096;">
        <i class="fas fa-search" style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;"></i>
        <h3 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">لا توجد قوالب</h3>
        <p style="margin: 0; font-size: 16px;">لم يتم العثور على قوالب في هذه الفئة</p>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 30px; max-width: 600px; max-height: 80vh; overflow-y: auto; margin: 20px;">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
            <h3 id="previewTitle" style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;"></h3>
            <button onclick="closePreview()" style="background: none; border: none; font-size: 24px; color: #718096; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="previewContent" style="color: #4a5568; line-height: 1.6;"></div>
        <div style="text-align: center; margin-top: 20px;">
            <button id="useTemplateBtn" onclick="useTemplate('')" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-plus"></i>
                استخدام هذا القالب
            </button>
        </div>
    </div>
</div>

<script>
// Template data for preview
const templateData = @json($templates);

function filterTemplates(category) {
    const cards = document.querySelectorAll('.template-card');
    const emptyState = document.getElementById('emptyState');
    let visibleCount = 0;

    // Update category buttons
    document.querySelectorAll('[onclick^="filterTemplates"]').forEach(btn => {
        btn.style.border = '2px solid transparent';
    });
    event.target.closest('div').style.border = '2px solid #667eea';

    cards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
}

function previewTemplate(templateId) {
    const template = templateData.find(t => t.id === templateId);
    if (!template) return;

    document.getElementById('previewTitle').textContent = template.title;
    document.getElementById('previewContent').innerHTML = `
        <p><strong>الوصف:</strong> ${template.description}</p>
        <p><strong>الفئة:</strong> ${template.category === 'compliance' ? 'امتثال' : (template.category === 'incident' ? 'حادث' : (template.category === 'audit' ? 'تدقيق' : (template.category === 'inspection' ? 'تفتيش' : 'سلامة')))}</p>
        <p><strong>التكرار:</strong> ${template.frequency === 'quarterly' ? 'ربعي' : (template.frequency === 'annual' ? 'سنوي' : (template.frequency === 'semi_annual' ? 'نصف سنوي' : 'حسب الحاجة'))}</p>
        <p><strong>أقسام التقرير:</strong></p>
        <ul>${template.sections.map(section => `<li>${section}</li>`).join('')}</ul>
    `;
    
    document.getElementById('useTemplateBtn').setAttribute('onclick', `useTemplate('${templateId}')`);
    document.getElementById('previewModal').style.display = 'flex';
}

function closePreview() {
    document.getElementById('previewModal').style.display = 'none';
}

function useTemplate(templateId) {
    window.location.href = `/tenant/inventory/regulatory/reports/templates/${templateId}/create`;
}

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>

@endsection
