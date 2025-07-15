@extends('layouts.modern')

@section('page-title', 'التقارير المخصصة')
@section('page-description', 'إنشاء تقارير مخصصة حسب احتياجاتك')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-chart-line" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            التقارير المخصصة 📊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء تقارير مخصصة حسب احتياجاتك
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-filter" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">فلترة متقدمة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-download" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تصدير متعدد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-pie" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">رسوم بيانية</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.reports.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    التقارير العادية
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Report Builder -->
<form method="POST" action="{{ route('tenant.inventory.custom-reports.generate') }}" id="reportForm">
    @csrf
    
    <!-- Report Type Selection -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="color: #8b5cf6; margin-left: 10px;"></i>
            نوع التقرير
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <label style="cursor: pointer;">
                <input type="radio" name="report_type" value="inventory_summary" required style="display: none;">
                <div class="report-type-card" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-boxes" style="font-size: 24px; color: #3b82f6; margin-left: 15px;"></i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600;">ملخص المخزون</h4>
                    </div>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">تقرير شامل عن حالة المخزون الحالية</p>
                </div>
            </label>
            
            <label style="cursor: pointer;">
                <input type="radio" name="report_type" value="movement_analysis" required style="display: none;">
                <div class="report-type-card" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-exchange-alt" style="font-size: 24px; color: #10b981; margin-left: 15px;"></i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600;">تحليل الحركات</h4>
                    </div>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">تحليل مفصل لحركات المخزون خلال فترة محددة</p>
                </div>
            </label>
            
            <label style="cursor: pointer;">
                <input type="radio" name="report_type" value="product_performance" required style="display: none;">
                <div class="report-type-card" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-chart-line" style="font-size: 24px; color: #f59e0b; margin-left: 15px;"></i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600;">أداء المنتجات</h4>
                    </div>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">تقييم أداء المنتجات ومعدل الدوران</p>
                </div>
            </label>
            
            <label style="cursor: pointer;">
                <input type="radio" name="report_type" value="warehouse_comparison" required style="display: none;">
                <div class="report-type-card" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-warehouse" style="font-size: 24px; color: #8b5cf6; margin-left: 15px;"></i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600;">مقارنة المستودعات</h4>
                    </div>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">مقارنة الأداء بين المستودعات المختلفة</p>
                </div>
            </label>
            
            <label style="cursor: pointer;">
                <input type="radio" name="report_type" value="cost_analysis" required style="display: none;">
                <div class="report-type-card" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-dollar-sign" style="font-size: 24px; color: #ef4444; margin-left: 15px;"></i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600;">تحليل التكاليف</h4>
                    </div>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">تحليل تفصيلي للتكاليف والقيم المالية</p>
                </div>
            </label>
            
            <label style="cursor: pointer;">
                <input type="radio" name="report_type" value="expiry_tracking" required style="display: none;">
                <div class="report-type-card" style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-clock" style="font-size: 24px; color: #f97316; margin-left: 15px;"></i>
                        <h4 style="margin: 0; font-size: 16px; font-weight: 600;">تتبع انتهاء الصلاحية</h4>
                    </div>
                    <p style="margin: 0; color: #6b7280; font-size: 14px;">مراقبة المنتجات قريبة أو منتهية الصلاحية</p>
                </div>
            </label>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-filter" style="color: #8b5cf6; margin-left: 10px;"></i>
            فلاتر التقرير
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">من تاريخ</label>
                <input type="date" name="date_from" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">إلى تاريخ</label>
                <input type="date" name="date_to" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المستودعات</label>
                <select name="warehouse_ids[]" multiple style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 120px;">
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->code }})</option>
                    @endforeach
                </select>
                <small style="color: #6b7280;">اتركه فارغاً لتشمل جميع المستودعات</small>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتجات</label>
                <select name="product_ids[]" multiple style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; min-height: 120px;">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                    @endforeach
                </select>
                <small style="color: #6b7280;">اتركه فارغاً لتشمل جميع المنتجات</small>
            </div>
        </div>
        
        <!-- Advanced Filters -->
        <div id="advanced-filters" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">أنواع الحركات</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="movement_types[]" value="in">
                            <span>وارد</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="movement_types[]" value="out">
                            <span>صادر</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="movement_types[]" value="transfer_in">
                            <span>تحويل وارد</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="movement_types[]" value="transfer_out">
                            <span>تحويل صادر</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">حالة المخزون</label>
                    <select name="status_filter" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="quarantine">حجر صحي</option>
                        <option value="damaged">تالف</option>
                        <option value="expired">منتهي الصلاحية</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 15px;">
            <button type="button" onclick="toggleAdvancedFilters()" style="background: none; border: 1px solid #8b5cf6; color: #8b5cf6; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-cog"></i> فلاتر متقدمة
            </button>
        </div>
    </div>

    <!-- Generate Button -->
    <div style="text-align: center; margin-bottom: 30px;">
        <button type="submit" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 15px 40px; border: none; border-radius: 12px; font-size: 18px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);">
            <i class="fas fa-chart-bar"></i>
            إنشاء التقرير
        </button>
    </div>
</form>

<!-- Quick Reports -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-bolt" style="color: #8b5cf6; margin-left: 10px;"></i>
        تقارير سريعة
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
        <button onclick="generateQuickReport('inventory_summary')" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; text-align: right;">
            <i class="fas fa-boxes" style="float: left; font-size: 20px; margin-top: 2px;"></i>
            <div style="font-weight: 600; margin-bottom: 5px;">ملخص المخزون الحالي</div>
            <div style="font-size: 12px; opacity: 0.9;">جميع المنتجات والمستودعات</div>
        </button>
        
        <button onclick="generateQuickReport('movement_analysis')" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; text-align: right;">
            <i class="fas fa-exchange-alt" style="float: left; font-size: 20px; margin-top: 2px;"></i>
            <div style="font-weight: 600; margin-bottom: 5px;">حركات الشهر الماضي</div>
            <div style="font-size: 12px; opacity: 0.9;">تحليل حركات آخر 30 يوم</div>
        </button>
        
        <button onclick="generateQuickReport('expiry_tracking')" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; text-align: right;">
            <i class="fas fa-clock" style="float: left; font-size: 20px; margin-top: 2px;"></i>
            <div style="font-weight: 600; margin-bottom: 5px;">انتهاء الصلاحية</div>
            <div style="font-size: 12px; opacity: 0.9;">المنتجات قريبة الانتهاء</div>
        </button>
        
        <button onclick="generateQuickReport('cost_analysis')" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; text-align: right;">
            <i class="fas fa-dollar-sign" style="float: left; font-size: 20px; margin-top: 2px;"></i>
            <div style="font-weight: 600; margin-bottom: 5px;">تحليل التكاليف</div>
            <div style="font-size: 12px; opacity: 0.9;">تكاليف آخر 3 أشهر</div>
        </button>
    </div>
</div>

@push('scripts')
<script>
// Report type selection
document.querySelectorAll('input[name="report_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.report-type-card').forEach(card => {
            card.style.borderColor = '#e2e8f0';
            card.style.backgroundColor = 'white';
        });
        
        const selectedCard = this.parentElement.querySelector('.report-type-card');
        selectedCard.style.borderColor = '#8b5cf6';
        selectedCard.style.backgroundColor = '#faf5ff';
    });
});

// Toggle advanced filters
function toggleAdvancedFilters() {
    const filters = document.getElementById('advanced-filters');
    if (filters.style.display === 'none') {
        filters.style.display = 'block';
    } else {
        filters.style.display = 'none';
    }
}

// Quick report generation
function generateQuickReport(type) {
    const form = document.getElementById('reportForm');
    
    // Set report type
    document.querySelector(`input[value="${type}"]`).checked = true;
    
    // Set default date range based on type
    const today = new Date();
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');
    
    switch(type) {
        case 'movement_analysis':
            const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
            dateFrom.value = lastMonth.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
        case 'cost_analysis':
            const threeMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 3, today.getDate());
            dateFrom.value = threeMonthsAgo.toISOString().split('T')[0];
            dateTo.value = today.toISOString().split('T')[0];
            break;
        default:
            // For inventory_summary and expiry_tracking, no date range needed
            dateFrom.value = '';
            dateTo.value = '';
    }
    
    // Submit form
    form.submit();
}

// Form validation
document.getElementById('reportForm').addEventListener('submit', function(e) {
    const reportType = document.querySelector('input[name="report_type"]:checked');
    
    if (!reportType) {
        e.preventDefault();
        alert('يرجى اختيار نوع التقرير');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري إنشاء التقرير...';
    submitBtn.disabled = true;
});
</script>
@endpush
@endsection
