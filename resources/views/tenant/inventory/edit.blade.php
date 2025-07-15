@extends('layouts.modern')

@section('page-title', 'تعديل المخزون')
@section('page-description', 'تعديل بيانات عنصر المخزون')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-edit" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            تعديل المخزون ✏️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $inventory->product->name }} - {{ $inventory->warehouse->name }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-box" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $inventory->product->code }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-warehouse" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $inventory->warehouse->code }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-cubes" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($inventory->quantity, 0) }} وحدة</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.show', $inventory) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-eye"></i>
                    عرض التفاصيل
                </a>
                <a href="{{ route('tenant.inventory.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.inventory.update', $inventory) }}" id="inventoryEditForm">
    @csrf
    @method('PUT')
    
    <!-- Current Information Display -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #f59e0b; margin-left: 10px;"></i>
            المعلومات الحالية
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتج</label>
                <div style="padding: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; color: #6b7280;">
                    {{ $inventory->product->name }} ({{ $inventory->product->code }})
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المستودع</label>
                <div style="padding: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; color: #6b7280;">
                    {{ $inventory->warehouse->name }} ({{ $inventory->warehouse->code }})
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية الإجمالية</label>
                <div style="padding: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; color: #6b7280; font-weight: 600;">
                    {{ number_format($inventory->quantity, 0) }} {{ $inventory->product->unit ?? 'وحدة' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الكمية المتاحة</label>
                <div style="padding: 12px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 8px; color: #059669; font-weight: 600;">
                    {{ number_format($inventory->available_quantity, 0) }} {{ $inventory->product->unit ?? 'وحدة' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Editable Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-edit" style="color: #f59e0b; margin-left: 10px;"></i>
            المعلومات القابلة للتعديل
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر التكلفة</label>
                <input type="number" name="cost_price" value="{{ old('cost_price', $inventory->cost_price) }}" min="0" step="0.01" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="0.00">
                @error('cost_price')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">كود الموقع</label>
                <input type="text" name="location_code" value="{{ old('location_code', $inventory->location_code) }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مثال: A-01-01">
                @error('location_code')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الدفعة</label>
                <input type="text" name="batch_number" value="{{ old('batch_number', $inventory->batch_number) }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="رقم الدفعة">
                @error('batch_number')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ انتهاء الصلاحية</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date', $inventory->expiry_date ? $inventory->expiry_date->format('Y-m-d') : '') }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('expiry_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة *</label>
                <select name="status" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="active" {{ old('status', $inventory->status) === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="quarantine" {{ old('status', $inventory->status) === 'quarantine' ? 'selected' : '' }}>حجر صحي</option>
                    <option value="damaged" {{ old('status', $inventory->status) === 'damaged' ? 'selected' : '' }}>تالف</option>
                    <option value="expired" {{ old('status', $inventory->status) === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                </select>
                @error('status')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; margin-left: 10px;"></i>
            ملاحظات مهمة
        </h3>
        
        <div style="background: #fef3c7; border-radius: 12px; padding: 20px; border-right: 4px solid #f59e0b;">
            <ul style="margin: 0; padding-right: 20px; color: #92400e; line-height: 1.6;">
                <li><strong>الكميات:</strong> لا يمكن تعديل الكميات من هذه الصفحة. استخدم حركات المخزون لتعديل الكميات.</li>
                <li><strong>المنتج والمستودع:</strong> لا يمكن تغيير المنتج أو المستودع بعد الإنشاء.</li>
                <li><strong>تاريخ الصلاحية:</strong> تأكد من صحة التاريخ للمنتجات الطبية.</li>
                <li><strong>الحالة:</strong> تغيير الحالة قد يؤثر على توفر المنتج للبيع.</li>
                <li><strong>كود الموقع:</strong> يساعد في تحديد موقع المنتج داخل المستودع.</li>
            </ul>
        </div>
    </div>

    <!-- Current Values Summary -->
    @if($inventory->cost_price > 0 || $inventory->expiry_date)
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-line" style="color: #f59e0b; margin-left: 10px;"></i>
            معلومات إضافية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            @if($inventory->cost_price > 0)
            <div style="text-align: center; padding: 15px; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <div style="font-size: 20px; font-weight: 700; color: #3b82f6;">{{ number_format($inventory->cost_price * $inventory->quantity, 2) }} د.ع</div>
                <div style="font-size: 12px; color: #1e40af;">إجمالي قيمة المخزون</div>
            </div>
            @endif
            
            @if($inventory->expiry_date)
            @php
                $daysToExpiry = now()->diffInDays($inventory->expiry_date, false);
            @endphp
            <div style="text-align: center; padding: 15px; background: {{ $daysToExpiry < 0 ? '#fee2e2' : ($daysToExpiry <= 30 ? '#fef3c7' : '#f0fdf4') }}; border-radius: 8px; border-left: 4px solid {{ $daysToExpiry < 0 ? '#ef4444' : ($daysToExpiry <= 30 ? '#f59e0b' : '#10b981') }};">
                <div style="font-size: 20px; font-weight: 700; color: {{ $daysToExpiry < 0 ? '#ef4444' : ($daysToExpiry <= 30 ? '#f59e0b' : '#10b981') }};">
                    @if($daysToExpiry < 0)
                        منتهي منذ {{ abs($daysToExpiry) }} يوم
                    @else
                        {{ $daysToExpiry }} يوم متبقي
                    @endif
                </div>
                <div style="font-size: 12px; color: {{ $daysToExpiry < 0 ? '#991b1b' : ($daysToExpiry <= 30 ? '#92400e' : '#065f46') }};">حتى انتهاء الصلاحية</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.inventory.show', $inventory) }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            حفظ التعديلات
        </button>
    </div>
</form>

@push('scripts')
<script>
// Form validation
document.getElementById('inventoryEditForm').addEventListener('submit', function(e) {
    const status = document.querySelector('select[name="status"]').value;
    
    if (!status) {
        e.preventDefault();
        alert('يرجى اختيار حالة المخزون');
        return false;
    }
    
    // Check expiry date if provided
    const expiryDate = document.querySelector('input[name="expiry_date"]').value;
    if (expiryDate) {
        const today = new Date();
        const expiry = new Date(expiryDate);
        
        if (expiry <= today && status === 'active') {
            if (!confirm('تاريخ انتهاء الصلاحية في الماضي أو اليوم والحالة نشطة. هل تريد المتابعة؟')) {
                e.preventDefault();
                return false;
            }
        }
    }
    
    // Confirm status change if changing to damaged or expired
    const originalStatus = '{{ $inventory->status }}';
    if ((status === 'damaged' || status === 'expired') && status !== originalStatus) {
        if (!confirm('هل أنت متأكد من تغيير حالة المخزون إلى "' + document.querySelector('select[name="status"] option:checked').text + '"؟ هذا قد يؤثر على توفر المنتج.')) {
            e.preventDefault();
            return false;
        }
    }
});

// Auto-suggest location codes based on warehouse
const warehouseName = '{{ $inventory->warehouse->name }}';
const locationInput = document.querySelector('input[name="location_code"]');
if (!locationInput.value) {
    locationInput.placeholder = `مثال: ${warehouseName}-A-01-01`;
}

// Highlight changes
document.querySelectorAll('input, select').forEach(element => {
    const originalValue = element.value;
    element.addEventListener('change', function() {
        if (this.value !== originalValue) {
            this.style.borderColor = '#f59e0b';
            this.style.backgroundColor = '#fef3c7';
        } else {
            this.style.borderColor = '#e2e8f0';
            this.style.backgroundColor = 'white';
        }
    });
});
</script>
@endpush
@endsection
