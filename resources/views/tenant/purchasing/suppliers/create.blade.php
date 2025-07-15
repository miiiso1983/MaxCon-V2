@extends('layouts.modern')

@section('page-title', 'مورد جديد')
@section('page-description', 'إضافة مورد جديد للنظام')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-plus" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            مورد جديد 🚚
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة مورد جديد للنظام
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.suppliers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.purchasing.suppliers.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-info-circle" style="color: #10b981; margin-left: 10px;"></i>
                المعلومات الأساسية
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم المورد *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="أدخل اسم المورد">
                    @error('name')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رمز المورد</label>
                    <input type="text" name="code" value="{{ old('code') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="سيتم إنشاؤه تلقائياً إذا ترك فارغاً">
                    @error('code')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع المورد *</label>
                    <select name="type" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                        <option value="">اختر نوع المورد</option>
                        <option value="manufacturer" {{ old('type') === 'manufacturer' ? 'selected' : '' }}>مصنع</option>
                        <option value="distributor" {{ old('type') === 'distributor' ? 'selected' : '' }}>موزع</option>
                        <option value="wholesaler" {{ old('type') === 'wholesaler' ? 'selected' : '' }}>تاجر جملة</option>
                        <option value="retailer" {{ old('type') === 'retailer' ? 'selected' : '' }}>تاجر تجزئة</option>
                        <option value="service_provider" {{ old('type') === 'service_provider' ? 'selected' : '' }}>مقدم خدمة</option>
                        <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                    @error('type')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة *</label>
                    <select name="status" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>معلق</option>
                    </select>
                    @error('status')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">وصف المورد</label>
                <textarea name="description" rows="3" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل وصف تفصيلي للمورد">{{ old('description') }}</textarea>
                @error('description')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Contact Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-address-book" style="color: #10b981; margin-left: 10px;"></i>
                معلومات الاتصال
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="supplier@example.com">
                    @error('email')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="+964 XXX XXX XXXX">
                    @error('phone')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الفاكس</label>
                    <input type="text" name="fax" value="{{ old('fax') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="+964 XXX XXX XXXX">
                    @error('fax')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الموقع الإلكتروني</label>
                    <input type="url" name="website" value="{{ old('website') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="https://www.example.com">
                    @error('website')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العنوان</label>
                <textarea name="address" rows="3" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل العنوان الكامل للمورد">{{ old('address') }}</textarea>
                @error('address')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المدينة</label>
                    <input type="text" name="city" value="{{ old('city') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="بغداد">
                    @error('city')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المحافظة</label>
                    <input type="text" name="state" value="{{ old('state') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="بغداد">
                    @error('state')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البلد</label>
                    <input type="text" name="country" value="{{ old('country', 'العراق') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="العراق">
                    @error('country')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Contact Person -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-user" style="color: #10b981; margin-left: 10px;"></i>
                شخص الاتصال
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم شخص الاتصال</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="أدخل اسم شخص الاتصال">
                    @error('contact_person')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنصب</label>
                    <input type="text" name="contact_title" value="{{ old('contact_title') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="مدير المبيعات">
                    @error('contact_title')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">بريد شخص الاتصال</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="contact@supplier.com">
                    @error('contact_email')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">هاتف شخص الاتصال</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="+964 XXX XXX XXXX">
                    @error('contact_phone')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Business Information -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                <i class="fas fa-building" style="color: #10b981; margin-left: 10px;"></i>
                المعلومات التجارية
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم السجل التجاري</label>
                    <input type="text" name="tax_number" value="{{ old('tax_number') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="أدخل رقم السجل التجاري">
                    @error('tax_number')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الترخيص</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}" 
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;"
                           placeholder="أدخل رقم الترخيص">
                    @error('license_number')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">شروط الدفع</label>
                    <select name="payment_terms" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                        <option value="">اختر شروط الدفع</option>
                        <option value="cash" {{ old('payment_terms') === 'cash' ? 'selected' : '' }}>نقداً</option>
                        <option value="net_30" {{ old('payment_terms') === 'net_30' ? 'selected' : '' }}>30 يوم</option>
                        <option value="net_60" {{ old('payment_terms') === 'net_60' ? 'selected' : '' }}>60 يوم</option>
                        <option value="net_90" {{ old('payment_terms') === 'net_90' ? 'selected' : '' }}>90 يوم</option>
                        <option value="custom" {{ old('payment_terms') === 'custom' ? 'selected' : '' }}>مخصص</option>
                    </select>
                    @error('payment_terms')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة المفضلة</label>
                    <select name="currency" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                        <option value="IQD" {{ old('currency', 'IQD') === 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                    @error('currency')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المنتجات/الخدمات المقدمة</label>
                <textarea name="products_services" rows="3" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل وصف للمنتجات أو الخدمات التي يقدمها المورد">{{ old('products_services') }}</textarea>
                @error('products_services')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
                <textarea name="notes" rows="3" 
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical;"
                          placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('tenant.purchasing.suppliers.index') }}" style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ المورد
            </button>
        </div>
    </form>
</div>
@endsection
