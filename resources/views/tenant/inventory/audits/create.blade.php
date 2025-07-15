@extends('layouts.modern')

@section('page-title', 'إنشاء جرد جديد')
@section('page-description', 'إنشاء عملية جرد جديدة للمخزون')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-plus-circle" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            جرد جديد 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء عملية جرد جديدة للمخزون
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.audits.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.inventory.audits.store') }}" id="auditForm">
    @csrf
    
    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #667eea; margin-left: 10px;"></i>
            معلومات الجرد
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع الجرد *</label>
                <select name="audit_type" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر نوع الجرد</option>
                    <option value="full" {{ old('audit_type') === 'full' ? 'selected' : '' }}>جرد شامل</option>
                    <option value="partial" {{ old('audit_type') === 'partial' ? 'selected' : '' }}>جرد جزئي</option>
                    <option value="cycle" {{ old('audit_type') === 'cycle' ? 'selected' : '' }}>جرد دوري</option>
                    <option value="spot" {{ old('audit_type') === 'spot' ? 'selected' : '' }}>جرد فوري</option>
                </select>
                @error('audit_type')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المستودع *</label>
                <select name="warehouse_id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر المستودع</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }} ({{ $warehouse->code }})
                        </option>
                    @endforeach
                </select>
                @error('warehouse_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الجدولة *</label>
                <input type="datetime-local" name="scheduled_date" value="{{ old('scheduled_date', now()->addDay()->format('Y-m-d\T09:00')) }}" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('scheduled_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
                <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                </select>
                @error('status')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
            <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="ملاحظات حول عملية الجرد...">{{ old('notes') }}</textarea>
            @error('notes')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Audit Scope -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-target" style="color: #667eea; margin-left: 10px;"></i>
            نطاق الجرد
        </h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نطاق الجرد</label>
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="radio" name="audit_scope" value="all" checked style="width: 18px; height: 18px;">
                    <span>جميع المنتجات في المستودع</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="radio" name="audit_scope" value="category" style="width: 18px; height: 18px;">
                    <span>فئة محددة</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="radio" name="audit_scope" value="location" style="width: 18px; height: 18px;">
                    <span>موقع محدد</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="radio" name="audit_scope" value="products" style="width: 18px; height: 18px;">
                    <span>منتجات محددة</span>
                </label>
            </div>
        </div>
        
        <!-- Category Selection -->
        <div id="categorySelection" style="display: none; margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الفئة</label>
            <select name="category" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">اختر الفئة</option>
                <option value="medicines">أدوية</option>
                <option value="medical_devices">أجهزة طبية</option>
                <option value="supplements">مكملات غذائية</option>
                <option value="cosmetics">مستحضرات تجميل</option>
                <option value="other">أخرى</option>
            </select>
        </div>
        
        <!-- Location Selection -->
        <div id="locationSelection" style="display: none; margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الموقع</label>
            <input type="text" name="location" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مثال: A-01-01">
        </div>
        
        <!-- Products Selection -->
        <div id="productsSelection" style="display: none;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتجات المحددة</label>
            <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; max-height: 200px; overflow-y: auto;">
                <div style="margin-bottom: 10px;">
                    <input type="text" id="productSearch" placeholder="البحث عن منتج..." style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                </div>
                <div id="productsList">
                    <!-- Products will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.inventory.audits.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            إنشاء الجرد
        </button>
    </div>
</form>

@push('scripts')
<script>
// Handle audit scope changes
document.querySelectorAll('input[name="audit_scope"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Hide all selection divs
        document.getElementById('categorySelection').style.display = 'none';
        document.getElementById('locationSelection').style.display = 'none';
        document.getElementById('productsSelection').style.display = 'none';
        
        // Show relevant selection div
        switch(this.value) {
            case 'category':
                document.getElementById('categorySelection').style.display = 'block';
                break;
            case 'location':
                document.getElementById('locationSelection').style.display = 'block';
                break;
            case 'products':
                document.getElementById('productsSelection').style.display = 'block';
                loadProducts();
                break;
        }
    });
});

// Load products for selection
function loadProducts() {
    const warehouseId = document.querySelector('select[name="warehouse_id"]').value;
    if (!warehouseId) {
        document.getElementById('productsList').innerHTML = '<p style="color: #6b7280; text-align: center;">يرجى اختيار المستودع أولاً</p>';
        return;
    }
    
    // Simulate loading products (in real implementation, this would be an AJAX call)
    const products = [
        {id: 1, name: 'باراسيتامول 500mg', code: 'PAR500'},
        {id: 2, name: 'أموكسيسيلين 250mg', code: 'AMX250'},
        {id: 3, name: 'فيتامين د 1000 وحدة', code: 'VITD1000'},
        {id: 4, name: 'كريم مرطب للبشرة', code: 'MOIST001'},
    ];
    
    let html = '';
    products.forEach(product => {
        html += `
            <label style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 6px; cursor: pointer; margin-bottom: 5px;" 
                   onmouseover="this.style.backgroundColor='#e5e7eb'" 
                   onmouseout="this.style.backgroundColor='transparent'">
                <input type="checkbox" name="selected_products[]" value="${product.id}" style="width: 16px; height: 16px;">
                <div>
                    <div style="font-weight: 600; color: #374151;">${product.name}</div>
                    <div style="font-size: 12px; color: #6b7280;">${product.code}</div>
                </div>
            </label>
        `;
    });
    
    document.getElementById('productsList').innerHTML = html;
}

// Product search functionality
document.getElementById('productSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const productLabels = document.querySelectorAll('#productsList label');
    
    productLabels.forEach(label => {
        const productName = label.textContent.toLowerCase();
        if (productName.includes(searchTerm)) {
            label.style.display = 'flex';
        } else {
            label.style.display = 'none';
        }
    });
});

// Form validation
document.getElementById('auditForm').addEventListener('submit', function(e) {
    const auditType = document.querySelector('select[name="audit_type"]').value;
    const warehouseId = document.querySelector('select[name="warehouse_id"]').value;
    const scheduledDate = document.querySelector('input[name="scheduled_date"]').value;
    
    if (!auditType || !warehouseId || !scheduledDate) {
        e.preventDefault();
        alert('يرجى ملء جميع الحقول المطلوبة');
        return false;
    }
    
    // Check if scheduled date is in the future
    const selectedDate = new Date(scheduledDate);
    const now = new Date();
    
    if (selectedDate < now) {
        e.preventDefault();
        alert('يجب أن يكون تاريخ الجدولة في المستقبل');
        return false;
    }
});
</script>
@endpush
@endsection
