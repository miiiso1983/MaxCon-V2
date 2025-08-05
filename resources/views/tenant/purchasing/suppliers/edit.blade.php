@extends('layouts.modern')

@section('page-title', 'تعديل المورد')
@section('page-description', 'تحديث معلومات المورد وبياناته التجارية')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-header-main">
            <div class="page-header-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div class="page-header-text">
                <h1>تعديل المورد</h1>
                <p>تحديث معلومات المورد: {{ $supplier->name }}</p>
            </div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('tenant.purchasing.suppliers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                العودة للقائمة
            </a>
            <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}" class="btn btn-info">
                <i class="fas fa-eye"></i>
                عرض التفاصيل
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card stat-card-purple">
        <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($supplier->credit_limit ?? 0, 0) }}</div>
            <div class="stat-label">حد الائتمان</div>
        </div>
    </div>
    
    <div class="stat-card stat-card-blue">
        <div class="stat-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $supplier->purchase_orders_count ?? 0 }}</div>
            <div class="stat-label">أوامر الشراء</div>
        </div>
    </div>
    
    <div class="stat-card stat-card-green">
        <div class="stat-icon">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ $supplier->payment_terms ?? 30 }}</div>
            <div class="stat-label">مدة السداد (يوم)</div>
        </div>
    </div>
    
    <div class="stat-card stat-card-orange">
        <div class="stat-icon">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value">{{ number_format($supplier->rating ?? 0, 1) }}</div>
            <div class="stat-label">التقييم</div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="content-grid">
    <!-- Main Form -->
    <div class="content-main">
        <div class="content-card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-user-edit"></i>
                    تعديل بيانات المورد
                </h3>
                <p>قم بتحديث معلومات المورد أدناه</p>
            </div>
            
            <div class="card-body">
                <form action="{{ route('tenant.purchasing.suppliers.update', $supplier) }}" method="POST" id="supplierForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h4 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            المعلومات الأساسية
                        </h4>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label required">اسم المورد</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-input @error('name') error @enderror" 
                                       value="{{ old('name', $supplier->name) }}" 
                                       required>
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="company_name" class="form-label">اسم الشركة</label>
                                <input type="text" 
                                       id="company_name" 
                                       name="company_name" 
                                       class="form-input @error('company_name') error @enderror" 
                                       value="{{ old('company_name', $supplier->company_name) }}">
                                @error('company_name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-input @error('email') error @enderror" 
                                       value="{{ old('email', $supplier->email) }}">
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-input @error('phone') error @enderror" 
                                       value="{{ old('phone', $supplier->phone) }}">
                                @error('phone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">العنوان الكامل</label>
                            <textarea id="address" 
                                      name="address" 
                                      class="form-textarea @error('address') error @enderror" 
                                      rows="3">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Financial Information -->
                    <div class="form-section">
                        <h4 class="section-title">
                            <i class="fas fa-dollar-sign"></i>
                            المعلومات المالية
                        </h4>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="credit_limit" class="form-label">حد الائتمان</label>
                                <input type="number" 
                                       id="credit_limit" 
                                       name="credit_limit" 
                                       class="form-input @error('credit_limit') error @enderror" 
                                       value="{{ old('credit_limit', $supplier->credit_limit) }}" 
                                       step="0.01" 
                                       min="0">
                                @error('credit_limit')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_terms" class="form-label">مدة السداد (بالأيام)</label>
                                <input type="number" 
                                       id="payment_terms" 
                                       name="payment_terms" 
                                       class="form-input @error('payment_terms') error @enderror" 
                                       value="{{ old('payment_terms', $supplier->payment_terms) }}" 
                                       min="0">
                                @error('payment_terms')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="tax_number" class="form-label">الرقم الضريبي</label>
                                <input type="text" 
                                       id="tax_number" 
                                       name="tax_number" 
                                       class="form-input @error('tax_number') error @enderror" 
                                       value="{{ old('tax_number', $supplier->tax_number) }}">
                                @error('tax_number')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="currency" class="form-label">العملة</label>
                                <select id="currency" 
                                        name="currency" 
                                        class="form-select @error('currency') error @enderror">
                                    <option value="IQD" {{ old('currency', $supplier->currency) == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                                    <option value="USD" {{ old('currency', $supplier->currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                                    <option value="EUR" {{ old('currency', $supplier->currency) == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                                </select>
                                @error('currency')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="form-section">
                        <h4 class="section-title">
                            <i class="fas fa-cogs"></i>
                            معلومات إضافية
                        </h4>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="category" class="form-label">فئة المورد</label>
                                <select id="category" 
                                        name="category" 
                                        class="form-select @error('category') error @enderror">
                                    <option value="">اختر الفئة</option>
                                    <option value="pharmaceutical" {{ old('category', $supplier->category) == 'pharmaceutical' ? 'selected' : '' }}>أدوية</option>
                                    <option value="medical_equipment" {{ old('category', $supplier->category) == 'medical_equipment' ? 'selected' : '' }}>معدات طبية</option>
                                    <option value="cosmetics" {{ old('category', $supplier->category) == 'cosmetics' ? 'selected' : '' }}>مستحضرات تجميل</option>
                                    <option value="supplements" {{ old('category', $supplier->category) == 'supplements' ? 'selected' : '' }}>مكملات غذائية</option>
                                    <option value="other" {{ old('category', $supplier->category) == 'other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                @error('category')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="status" class="form-label">الحالة</label>
                                <select id="status" 
                                        name="status" 
                                        class="form-select @error('status') error @enderror">
                                    <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    <option value="suspended" {{ old('status', $supplier->status) == 'suspended' ? 'selected' : '' }}>معلق</option>
                                </select>
                                @error('status')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      class="form-textarea @error('notes') error @enderror" 
                                      rows="4">{{ old('notes', $supplier->notes) }}</textarea>
                            @error('notes')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="{{ route('tenant.purchasing.suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="content-sidebar">
        <!-- Supplier Info -->
        <div class="content-card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-info-circle"></i>
                    معلومات المورد
                </h4>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <span class="info-label">تاريخ الإنشاء</span>
                    <span class="info-value">{{ $supplier->created_at->format('Y-m-d') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">آخر تحديث</span>
                    <span class="info-value">{{ $supplier->updated_at->format('Y-m-d') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الحالة الحالية</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $supplier->status }}">
                            {{ $supplier->status == 'active' ? 'نشط' : ($supplier->status == 'inactive' ? 'غير نشط' : 'معلق') }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card">
            <div class="card-header">
                <h4>
                    <i class="fas fa-bolt"></i>
                    إجراءات سريعة
                </h4>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}" class="quick-action">
                        <i class="fas fa-eye"></i>
                        عرض التفاصيل
                    </a>
                    <a href="#" class="quick-action">
                        <i class="fas fa-file-invoice"></i>
                        إنشاء طلب شراء
                    </a>
                    <a href="#" class="quick-action">
                        <i class="fas fa-history"></i>
                        سجل المعاملات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('supplierForm');
    const requiredFields = form.querySelectorAll('[required]');

    form.addEventListener('submit', function(e) {
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
            } else {
                field.classList.remove('error');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
        }
    });

    // Remove error styling on input
    requiredFields.forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('error');
        });
    });
});
</script>
@endpush
