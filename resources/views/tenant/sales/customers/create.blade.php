@extends('layouts.modern')

@section('page-title', 'إضافة عميل جديد')
@section('page-description', 'إضافة عميل جديد إلى قاعدة البيانات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-user-plus" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إضافة عميل جديد ✨
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة عميل جديد إلى قاعدة البيانات
                        </p>
                    </div>
                </div>
            </div>
            
            <div>
                <a href="{{ route('tenant.sales.customers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Customer Form -->
<form method="POST" action="{{ route('tenant.sales.customers.store') }}">
    @csrf
    
    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #48bb78; margin-left: 10px;"></i>
            المعلومات الأساسية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Name -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم العميل *</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="اسم العميل الكامل">
                @error('name')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Customer Type -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع العميل *</label>
                <select name="customer_type" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر نوع العميل</option>
                    <option value="individual" {{ old('customer_type') === 'individual' ? 'selected' : '' }}>فرد</option>
                    <option value="company" {{ old('customer_type') === 'company' ? 'selected' : '' }}>شركة</option>
                </select>
                @error('customer_type')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Tax Number -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الرقم الضريبي</label>
                <input type="text" name="tax_number" value="{{ old('tax_number') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="الرقم الضريبي للعميل">
                @error('tax_number')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Commercial Register -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">السجل التجاري</label>
                <input type="text" name="commercial_register" value="{{ old('commercial_register') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="رقم السجل التجاري">
                @error('commercial_register')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-phone" style="color: #4299e1; margin-left: 10px;"></i>
            معلومات الاتصال
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Email -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="example@domain.com">
                @error('email')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Mobile -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الجوال</label>
                <input type="text" name="mobile" value="{{ old('mobile') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="05xxxxxxxx">
                @error('mobile')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Phone -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="011xxxxxxx">
                @error('phone')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-map-marker-alt" style="color: #9f7aea; margin-left: 10px;"></i>
            معلومات العنوان
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Address -->
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العنوان</label>
                <textarea name="address" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="العنوان الكامل...">{{ old('address') }}</textarea>
                @error('address')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- City -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المدينة</label>
                <input type="text" name="city" value="{{ old('city') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="المدينة">
                @error('city')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- State -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنطقة</label>
                <input type="text" name="state" value="{{ old('state') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="المنطقة">
                @error('state')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Country -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الدولة *</label>
                <select name="country" required data-custom-select data-placeholder="اختر الدولة..." data-search-placeholder="ابحث عن الدولة..." data-searchable="true">
                    <option value="">اختر الدولة</option>
                    <option value="IQ" {{ old('country', 'IQ') === 'IQ' ? 'selected' : '' }}>العراق</option>
                    <option value="SA" {{ old('country') === 'SA' ? 'selected' : '' }}>السعودية</option>
                    <option value="AE" {{ old('country') === 'AE' ? 'selected' : '' }}>الإمارات</option>
                    <option value="KW" {{ old('country') === 'KW' ? 'selected' : '' }}>الكويت</option>
                    <option value="QA" {{ old('country') === 'QA' ? 'selected' : '' }}>قطر</option>
                    <option value="BH" {{ old('country') === 'BH' ? 'selected' : '' }}>البحرين</option>
                    <option value="OM" {{ old('country') === 'OM' ? 'selected' : '' }}>عمان</option>
                    <option value="JO" {{ old('country') === 'JO' ? 'selected' : '' }}>الأردن</option>
                    <option value="LB" {{ old('country') === 'LB' ? 'selected' : '' }}>لبنان</option>
                    <option value="SY" {{ old('country') === 'SY' ? 'selected' : '' }}>سوريا</option>
                    <option value="EG" {{ old('country') === 'EG' ? 'selected' : '' }}>مصر</option>
                    <option value="LY" {{ old('country') === 'LY' ? 'selected' : '' }}>ليبيا</option>
                    <option value="TN" {{ old('country') === 'TN' ? 'selected' : '' }}>تونس</option>
                    <option value="DZ" {{ old('country') === 'DZ' ? 'selected' : '' }}>الجزائر</option>
                    <option value="MA" {{ old('country') === 'MA' ? 'selected' : '' }}>المغرب</option>
                    <option value="SD" {{ old('country') === 'SD' ? 'selected' : '' }}>السودان</option>
                    <option value="YE" {{ old('country') === 'YE' ? 'selected' : '' }}>اليمن</option>
                    <option value="PS" {{ old('country') === 'PS' ? 'selected' : '' }}>فلسطين</option>
                    <option value="BH" {{ old('country') === 'BH' ? 'selected' : '' }}>البحرين</option>
                    <option value="OM" {{ old('country') === 'OM' ? 'selected' : '' }}>عمان</option>
                    <option value="JO" {{ old('country') === 'JO' ? 'selected' : '' }}>الأردن</option>
                    <option value="LB" {{ old('country') === 'LB' ? 'selected' : '' }}>لبنان</option>
                    <option value="SY" {{ old('country') === 'SY' ? 'selected' : '' }}>سوريا</option>
                    <option value="EG" {{ old('country') === 'EG' ? 'selected' : '' }}>مصر</option>
                    <option value="PS" {{ old('country') === 'PS' ? 'selected' : '' }}>فلسطين</option>
                </select>
                @error('country')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Postal Code -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الرمز البريدي</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="12345">
                @error('postal_code')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Financial Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-dollar-sign" style="color: #ed8936; margin-left: 10px;"></i>
            المعلومات المالية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Payment Terms -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">شروط الدفع *</label>
                <select name="payment_terms" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="cash" {{ old('payment_terms', 'cash') === 'cash' ? 'selected' : '' }}>نقداً</option>
                    <option value="credit_7" {{ old('payment_terms') === 'credit_7' ? 'selected' : '' }}>آجل 7 أيام</option>
                    <option value="credit_15" {{ old('payment_terms') === 'credit_15' ? 'selected' : '' }}>آجل 15 يوم</option>
                    <option value="credit_30" {{ old('payment_terms') === 'credit_30' ? 'selected' : '' }}>آجل 30 يوم</option>
                    <option value="credit_60" {{ old('payment_terms') === 'credit_60' ? 'selected' : '' }}>آجل 60 يوم</option>
                    <option value="credit_90" {{ old('payment_terms') === 'credit_90' ? 'selected' : '' }}>آجل 90 يوم</option>
                </select>
                @error('payment_terms')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Credit Limit -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحد الائتماني *</label>
                <input type="number" name="credit_limit" value="{{ old('credit_limit', '0') }}" min="0" step="0.01" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="0.00">
                @error('credit_limit')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Currency -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة *</label>
                <select name="currency" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="SAR" {{ old('currency', 'SAR') === 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                    <option value="AED" {{ old('currency') === 'AED' ? 'selected' : '' }}>درهم إماراتي (AED)</option>
                    <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                    <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                </select>
                @error('currency')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-sticky-note" style="color: #fbbf24; margin-left: 10px;"></i>
            ملاحظات
        </h3>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات إضافية</label>
            <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="ملاحظات إضافية عن العميل...">{{ old('notes') }}</textarea>
            @error('notes')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.sales.customers.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" class="btn-green" style="padding: 12px 24px;">
            <i class="fas fa-save"></i>
            حفظ العميل
        </button>
    </div>
</form>
@endsection
