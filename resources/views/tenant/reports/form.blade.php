@extends('layouts.modern')

@section('title', $reportConfig['name'])

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 15px 0; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-chart-bar"></i>
            {{ $reportConfig['name'] }}
        </h1>
        <p style="font-size: 18px; opacity: 0.9; margin: 0;">
            {{ $reportConfig['description'] }}
        </p>
    </div>
    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"rgba(255,255,255,0.1)\"/></svg>') repeat; animation: float 20s infinite linear;"></div>
</div>

<!-- Report Parameters Form -->
<div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <form id="reportForm" method="POST" action="{{ route('tenant.reports.generate', $reportType) }}">
        @csrf
        
        <!-- Report Information -->
        <div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
            <h3 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-info-circle" style="color: #667eea;"></i>
                معلومات التقرير
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <strong>النوع:</strong> {{ $reportConfig['type'] }}
                </div>
                <div>
                    <strong>الفئة:</strong> {{ $reportConfig['category'] }}
                </div>
                <div>
                    <strong>عدد الأعمدة:</strong> {{ count($reportConfig['columns']) }}
                </div>
            </div>
        </div>

        <!-- Parameters Section -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-sliders-h" style="color: #10b981;"></i>
                معايير التقرير
            </h3>
            
            <!-- Date Range -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">الفترة الزمنية:</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; font-size: 14px; color: #718096; margin-bottom: 5px;">من تاريخ:</label>
                        <input type="date" name="parameters[date_from]" id="dateFrom" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 14px; color: #718096; margin-bottom: 5px;">إلى تاريخ:</label>
                        <input type="date" name="parameters[date_to]" id="dateTo" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Additional Filters based on report type -->
            @if($reportConfig['category'] === 'sales')
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">فلاتر إضافية:</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; font-size: 14px; color: #718096; margin-bottom: 5px;">العميل:</label>
                            <select name="parameters[customer_id]" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                <option value="">جميع العملاء</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; color: #718096; margin-bottom: 5px;">المندوب:</label>
                            <select name="parameters[sales_rep_id]" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                <option value="">جميع المندوبين</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            @if($reportConfig['category'] === 'inventory')
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">فلاتر المخزون:</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; font-size: 14px; color: #718096; margin-bottom: 5px;">الفئة:</label>
                            <select name="parameters[category_id]" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                <option value="">جميع الفئات</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; color: #718096; margin-bottom: 5px;">المنتج:</label>
                            <select name="parameters[product_id]" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                                <option value="">جميع المنتجات</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Output Format -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">تنسيق الإخراج:</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onclick="selectFormat(this, 'html')">
                        <input type="radio" name="format" value="html" checked style="margin: 0;">
                        <i class="fas fa-globe" style="color: #3b82f6;"></i>
                        <span>عرض في المتصفح</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onclick="selectFormat(this, 'pdf')">
                        <input type="radio" name="format" value="pdf" style="margin: 0;">
                        <i class="fas fa-file-pdf" style="color: #ef4444;"></i>
                        <span>PDF</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onclick="selectFormat(this, 'excel')">
                        <input type="radio" name="format" value="excel" style="margin: 0;">
                        <i class="fas fa-file-excel" style="color: #10b981;"></i>
                        <span>Excel</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onclick="selectFormat(this, 'csv')">
                        <input type="radio" name="format" value="csv" style="margin: 0;">
                        <i class="fas fa-file-csv" style="color: #8b5cf6;"></i>
                        <span>CSV</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-play"></i>
                تشغيل التقرير
            </button>
            
            <button type="button" onclick="previewReport()" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                معاينة سريعة
            </button>
            
            <a href="{{ route('tenant.reports.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="fas fa-arrow-right"></i>
                العودة للتقارير
            </a>
        </div>
    </form>
</div>

<!-- Quick Preview Modal -->
<div id="previewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 30px; max-width: 90%; width: 800px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
            <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">معاينة سريعة</h3>
            <button onclick="closePreview()" style="background: none; border: none; font-size: 24px; color: #a0aec0; cursor: pointer; padding: 5px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="previewContent">
            <!-- Preview content will be loaded here -->
        </div>
    </div>
</div>

<script>
// Set default dates
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('dateFrom').value = firstDay.toISOString().split('T')[0];
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
    
    // Load dropdown options
    loadDropdownOptions();
});

function selectFormat(element, format) {
    // Remove active class from all format options
    document.querySelectorAll('label[onclick*="selectFormat"]').forEach(label => {
        label.style.borderColor = '#e2e8f0';
        label.style.backgroundColor = 'white';
    });
    
    // Add active class to selected option
    element.style.borderColor = '#3b82f6';
    element.style.backgroundColor = '#eff6ff';
}

function loadDropdownOptions() {
    // This would typically load from API endpoints
    // For demo purposes, we'll show static options
    console.log('Loading dropdown options...');
}

function previewReport() {
    document.getElementById('previewModal').style.display = 'flex';
    document.getElementById('previewContent').innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #667eea; margin-bottom: 20px;"></i>
            <h3>جاري تحضير المعاينة...</h3>
        </div>
    `;
    
    // Simulate preview generation
    setTimeout(() => {
        const reportName = '{{ $reportConfig["name"] }}';
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        document.getElementById('previewContent').innerHTML = `
            <div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4 style="color: #2d3748; margin-bottom: 15px;">معاينة التقرير:</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div><strong>اسم التقرير:</strong> ${reportName}</div>
                    <div><strong>من تاريخ:</strong> ${dateFrom}</div>
                    <div><strong>إلى تاريخ:</strong> ${dateTo}</div>
                    <div><strong>عدد الأعمدة:</strong> {{ count($reportConfig['columns']) }}</div>
                </div>
            </div>
            
            <div style="background: #f0fff4; padding: 20px; border-radius: 10px; border: 1px solid #10b981; text-align: center;">
                <h4 style="color: #065f46; margin-bottom: 15px;">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    المعاينة جاهزة!
                </h4>
                <p style="color: #047857; margin-bottom: 20px;">التقرير سيحتوي على البيانات للفترة المحددة مع جميع المعايير المطبقة.</p>
                
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button onclick="closePreview()" style="background: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
                        إغلاق المعاينة
                    </button>
                    <button onclick="runFullReport()" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
                        تشغيل التقرير الكامل
                    </button>
                </div>
            </div>
        `;
    }, 2000);
}

function closePreview() {
    document.getElementById('previewModal').style.display = 'none';
}

function runFullReport() {
    closePreview();
    document.getElementById('reportForm').submit();
}

// Form submission with loading state
document.getElementById('reportForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التشغيل...';
    submitBtn.disabled = true;
});
</script>

@endsection
