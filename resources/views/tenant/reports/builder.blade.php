@extends('layouts.modern')

@section('title', 'منشئ التقارير المخصص')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 15px 0; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-cogs"></i>
            منشئ التقارير المخصص
        </h1>
        <p style="font-size: 18px; opacity: 0.9; margin: 0;">
            أنشئ تقاريرك المخصصة بسهولة مع أدوات التصميم المتقدمة
        </p>
    </div>
    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"rgba(255,255,255,0.1)\"/></svg>') repeat; animation: float 20s infinite linear;"></div>
</div>

<!-- Report Builder Form -->
<div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <form id="reportBuilderForm">
        @csrf
        
        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-info-circle" style="color: #667eea;"></i>
                المعلومات الأساسية
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">اسم التقرير:</label>
                    <input type="text" name="name" id="reportName" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div>
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">نوع التقرير:</label>
                    <select name="type" id="reportType" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        @foreach($types as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">الفئة:</label>
                    <select name="category" id="reportCategory" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        @foreach($categories as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">الجدول الأساسي:</label>
                    <select name="base_table" id="baseTable" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="invoices">الفواتير (invoices)</option>
                        <option value="customers">العملاء (customers)</option>
                        <option value="products">المنتجات (products)</option>
                        <option value="inventory_movements">حركات المخزون (inventory_movements)</option>
                        <option value="transactions">المعاملات المالية (transactions)</option>
                        <option value="users">المستخدمين (users)</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">وصف التقرير:</label>
                <textarea name="description" id="reportDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; resize: vertical;" placeholder="وصف مختصر لما يحتويه التقرير..."></textarea>
            </div>
        </div>

        <!-- Columns Selection -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-columns" style="color: #10b981;"></i>
                اختيار الأعمدة
            </h3>
            
            <div id="columnsContainer" style="display: grid; gap: 15px;">
                <!-- Columns will be added dynamically -->
            </div>
            
            <button type="button" onclick="addColumn()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; margin-top: 15px;">
                <i class="fas fa-plus"></i> إضافة عمود
            </button>
        </div>

        <!-- Filters -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-filter" style="color: #3b82f6;"></i>
                المرشحات والفلاتر
            </h3>
            
            <div id="filtersContainer" style="display: grid; gap: 15px;">
                <!-- Filters will be added dynamically -->
            </div>
            
            <button type="button" onclick="addFilter()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; margin-top: 15px;">
                <i class="fas fa-plus"></i> إضافة مرشح
            </button>
        </div>

        <!-- Sorting and Grouping -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-sort" style="color: #8b5cf6;"></i>
                الترتيب والتجميع
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">ترتيب حسب:</label>
                    <select name="order_by" id="orderBy" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="">بدون ترتيب</option>
                        <option value="created_at">تاريخ الإنشاء</option>
                        <option value="updated_at">تاريخ التحديث</option>
                        <option value="name">الاسم</option>
                        <option value="amount">المبلغ</option>
                    </select>
                </div>
                
                <div>
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">اتجاه الترتيب:</label>
                    <select name="order_direction" id="orderDirection" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="asc">تصاعدي</option>
                        <option value="desc">تنازلي</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <button type="button" onclick="previewReport()" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                معاينة التقرير
            </button>
            
            <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ التقرير
            </button>
            
            <button type="button" onclick="resetForm()" style="background: #e2e8f0; color: #4a5568; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-undo"></i>
                إعادة تعيين
            </button>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div id="previewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 30px; max-width: 90%; width: 1000px; max-height: 90vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
            <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">معاينة التقرير</h3>
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
let columnCount = 0;
let filterCount = 0;

// Add initial column and filter
document.addEventListener('DOMContentLoaded', function() {
    addColumn();
    addFilter();
});

function addColumn() {
    columnCount++;
    const container = document.getElementById('columnsContainer');
    const columnDiv = document.createElement('div');
    columnDiv.id = `column_${columnCount}`;
    columnDiv.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; align-items: center; padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;';
    
    columnDiv.innerHTML = `
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">اسم الحقل:</label>
            <input type="text" name="columns[${columnCount}][field]" placeholder="مثال: name, total_amount" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">التسمية:</label>
            <input type="text" name="columns[${columnCount}][label]" placeholder="مثال: اسم العميل" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">النوع:</label>
            <select name="columns[${columnCount}][type]" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px;">
                <option value="text">نص</option>
                <option value="number">رقم</option>
                <option value="currency">عملة</option>
                <option value="date">تاريخ</option>
                <option value="datetime">تاريخ ووقت</option>
                <option value="percentage">نسبة مئوية</option>
            </select>
        </div>
        <div>
            <button type="button" onclick="removeColumn(${columnCount})" style="background: #ef4444; color: white; padding: 8px; border: none; border-radius: 6px; cursor: pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(columnDiv);
}

function removeColumn(id) {
    const element = document.getElementById(`column_${id}`);
    if (element) {
        element.remove();
    }
}

function addFilter() {
    filterCount++;
    const container = document.getElementById('filtersContainer');
    const filterDiv = document.createElement('div');
    filterDiv.id = `filter_${filterCount}`;
    filterDiv.style.cssText = 'display: grid; grid-template-columns: 2fr 1fr 2fr auto; gap: 10px; align-items: center; padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;';
    
    filterDiv.innerHTML = `
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">الحقل:</label>
            <input type="text" name="filters[${filterCount}][field]" placeholder="مثال: created_at, customer_id" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">المشغل:</label>
            <select name="filters[${filterCount}][operator]" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px;">
                <option value="=">=</option>
                <option value="!=">!=</option>
                <option value=">">></option>
                <option value=">=">>=</option>
                <option value="<"><</option>
                <option value="<="><=</option>
                <option value="like">يحتوي على</option>
                <option value="in">ضمن القائمة</option>
                <option value="between">بين</option>
                <option value="date_range">نطاق تاريخ</option>
            </select>
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">القيمة:</label>
            <input type="text" name="filters[${filterCount}][value]" placeholder="مثال: :date_from, :customer_id" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px;">
        </div>
        <div>
            <button type="button" onclick="removeFilter(${filterCount})" style="background: #ef4444; color: white; padding: 8px; border: none; border-radius: 6px; cursor: pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(filterDiv);
}

function removeFilter(id) {
    const element = document.getElementById(`filter_${id}`);
    if (element) {
        element.remove();
    }
}

function previewReport() {
    const formData = new FormData(document.getElementById('reportBuilderForm'));
    
    // Show preview modal
    document.getElementById('previewModal').style.display = 'flex';
    document.getElementById('previewContent').innerHTML = `
        <div style="text-align: center; padding: 40px;">
            <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #667eea; margin-bottom: 20px;"></i>
            <h3>جاري تحضير المعاينة...</h3>
        </div>
    `;
    
    // Simulate preview generation
    setTimeout(() => {
        document.getElementById('previewContent').innerHTML = `
            <div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4 style="color: #2d3748; margin-bottom: 10px;">معلومات التقرير:</h4>
                <p><strong>الاسم:</strong> ${formData.get('name') || 'غير محدد'}</p>
                <p><strong>النوع:</strong> ${document.getElementById('reportType').selectedOptions[0].text}</p>
                <p><strong>الفئة:</strong> ${document.getElementById('reportCategory').selectedOptions[0].text}</p>
                <p><strong>الجدول الأساسي:</strong> ${document.getElementById('baseTable').selectedOptions[0].text}</p>
            </div>
            
            <div style="background: #f0fff4; padding: 20px; border-radius: 10px; border: 1px solid #10b981;">
                <h4 style="color: #065f46; margin-bottom: 15px;">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    المعاينة جاهزة!
                </h4>
                <p style="color: #047857;">تم إنشاء التقرير بنجاح. يمكنك الآن حفظه أو تعديل الإعدادات.</p>
                
                <div style="margin-top: 15px;">
                    <button onclick="closePreview()" style="background: #10b981; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; margin-left: 10px;">
                        إغلاق المعاينة
                    </button>
                    <button onclick="saveReport()" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">
                        حفظ التقرير
                    </button>
                </div>
            </div>
        `;
    }, 2000);
}

function closePreview() {
    document.getElementById('previewModal').style.display = 'none';
}

function saveReport() {
    const form = document.getElementById('reportBuilderForm');
    const formData = new FormData(form);
    
    fetch('{{ route("tenant.reports.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ تم حفظ التقرير بنجاح!\n\nيمكنك الآن العثور عليه في قائمة التقارير المخصصة.');
            closePreview();
            resetForm();
        } else {
            alert('❌ حدث خطأ في حفظ التقرير: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ خطأ في الاتصال: ' + error.message);
    });
}

function resetForm() {
    document.getElementById('reportBuilderForm').reset();
    document.getElementById('columnsContainer').innerHTML = '';
    document.getElementById('filtersContainer').innerHTML = '';
    columnCount = 0;
    filterCount = 0;
    addColumn();
    addFilter();
}

// Form submission
document.getElementById('reportBuilderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveReport();
});
</script>

@endsection
