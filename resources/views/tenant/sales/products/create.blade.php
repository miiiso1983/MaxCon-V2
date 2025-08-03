@extends('layouts.modern')

@section('page-title', 'إضافة منتج جديد')
@section('page-description', 'إضافة منتج دوائي جديد إلى الكتالوج')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            إضافة منتج جديد 💊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة منتج دوائي جديد إلى الكتالوج
                        </p>
                    </div>
                </div>
            </div>
            
            <div>
                <a href="{{ route('tenant.sales.products.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Product Form -->
<form method="POST" action="{{ route('tenant.sales.products.store') }}" onsubmit="
    console.log('=== FORM SUBMISSION ===');
    console.log('Form submitted to:', this.action);
    console.log('Form method:', this.method);
    console.log('CSRF token:', this.querySelector('[name=_token]').value);
    return true;
">
    @csrf

    @if(config('app.debug'))
    <div style="background: #e0f2fe; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 12px;">
        <strong>تشخيص Form:</strong><br>
        Action: {{ route('tenant.sales.products.store') }}<br>
        Method: POST<br>
        CSRF: {{ csrf_token() }}
    </div>
    @endif

    <div style="background: #fef2f2; border: 2px solid #f56565; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h4 style="color: #dc2626; margin: 0 0 10px 0;">⚠️ تحذير مهم</h4>
        <p style="margin: 0; color: #7f1d1d;">
            <strong>يجب ملء حقل "اسم المنتج" و "الفئة" بشكل صحيح وإلا لن يتم حفظ المنتج!</strong><br>
            تأكد من اختيار فئة من القائمة المنسدلة.
        </p>
    </div>
    
    <!-- Basic Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #9f7aea; margin-left: 10px;"></i>
            المعلومات الأساسية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Name -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم المنتج *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f0fff4;"
                       placeholder="اسم المنتج التجاري"
                       onchange="console.log('Name changed to:', this.value)">
                @error('name')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
                @if(config('app.debug'))
                <div style="font-size: 10px; color: #666; margin-top: 2px;">تشخيص: حقل الاسم</div>
                @endif
            </div>
            
            <!-- Generic Name -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الاسم العلمي</label>
                <input type="text" name="generic_name" value="{{ old('generic_name') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="الاسم العلمي للمادة الفعالة">
                @error('generic_name')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Category -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الفئة *</label>
                <select name="category" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f0fff4;"
                        onchange="console.log('Category changed to:', this.value)">
                    <option value="">اختر الفئة</option>
                    <option value="أدوية القلب والأوعية الدموية" {{ old('category') === 'أدوية القلب والأوعية الدموية' ? 'selected' : '' }}>أدوية القلب والأوعية الدموية</option>
                    <option value="المضادات الحيوية" {{ old('category') === 'المضادات الحيوية' ? 'selected' : '' }}>المضادات الحيوية</option>
                    <option value="أدوية الجهاز التنفسي" {{ old('category') === 'أدوية الجهاز التنفسي' ? 'selected' : '' }}>أدوية الجهاز التنفسي</option>
                    <option value="أدوية الجهاز الهضمي" {{ old('category') === 'أدوية الجهاز الهضمي' ? 'selected' : '' }}>أدوية الجهاز الهضمي</option>
                    <option value="أدوية الجهاز العصبي" {{ old('category') === 'أدوية الجهاز العصبي' ? 'selected' : '' }}>أدوية الجهاز العصبي</option>
                    <option value="أدوية السكري" {{ old('category') === 'أدوية السكري' ? 'selected' : '' }}>أدوية السكري</option>
                    <option value="مسكنات الألم" {{ old('category') === 'مسكنات الألم' ? 'selected' : '' }}>مسكنات الألم</option>
                    <option value="الفيتامينات والمكملات" {{ old('category') === 'الفيتامينات والمكملات' ? 'selected' : '' }}>الفيتامينات والمكملات</option>
                    <option value="أدوية الأطفال" {{ old('category') === 'أدوية الأطفال' ? 'selected' : '' }}>أدوية الأطفال</option>
                    <option value="أخرى" {{ old('category') === 'أخرى' ? 'selected' : '' }}>أخرى</option>
                </select>
                @error('category')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Manufacturer -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الشركة المصنعة</label>
                <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="اسم الشركة المصنعة">
                @error('manufacturer')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Barcode -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الباركود</label>
                <input type="text" name="barcode" value="{{ old('barcode') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="رقم الباركود">
                @error('barcode')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Unit -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الوحدة *</label>
                <select name="unit" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر الوحدة</option>
                    <option value="قرص" {{ old('unit') === 'قرص' ? 'selected' : '' }}>قرص</option>
                    <option value="كبسولة" {{ old('unit') === 'كبسولة' ? 'selected' : '' }}>كبسولة</option>
                    <option value="شراب" {{ old('unit') === 'شراب' ? 'selected' : '' }}>شراب</option>
                    <option value="حقنة" {{ old('unit') === 'حقنة' ? 'selected' : '' }}>حقنة</option>
                    <option value="مرهم" {{ old('unit') === 'مرهم' ? 'selected' : '' }}>مرهم</option>
                    <option value="قطرة" {{ old('unit') === 'قطرة' ? 'selected' : '' }}>قطرة</option>
                    <option value="بخاخ" {{ old('unit') === 'بخاخ' ? 'selected' : '' }}>بخاخ</option>
                    <option value="علبة" {{ old('unit') === 'علبة' ? 'selected' : '' }}>علبة</option>
                    <option value="زجاجة" {{ old('unit') === 'زجاجة' ? 'selected' : '' }}>زجاجة</option>
                </select>
                @error('unit')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Pricing Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-dollar-sign" style="color: #059669; margin-left: 10px;"></i>
            معلومات التسعير
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Purchase Price -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر الشراء *</label>
                <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" min="0" step="0.01" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="0.00">
                @error('purchase_price')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Selling Price -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر البيع *</label>
                <input type="number" name="selling_price" value="{{ old('selling_price') }}" min="0" step="0.01" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="0.00">
                @error('selling_price')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Stock Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-boxes" style="color: #f59e0b; margin-left: 10px;"></i>
            معلومات المخزون
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Current Stock -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">المخزون الحالي *</label>
                <input type="number" name="current_stock" value="{{ old('current_stock', '0') }}" min="0" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="0">
                @error('current_stock')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Min Stock Level -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحد الأدنى للمخزون *</label>
                <input type="number" name="min_stock_level" value="{{ old('min_stock_level', '10') }}" min="0" required 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="10">
                @error('min_stock_level')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Batch and Expiry Information -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-calendar-alt" style="color: #dc2626; margin-left: 10px;"></i>
            معلومات الدفعة والصلاحية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Batch Number -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الدفعة</label>
                <input type="text" name="batch_number" value="{{ old('batch_number') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" 
                       placeholder="رقم الدفعة">
                @error('batch_number')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Manufacturing Date -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ التصنيع</label>
                <input type="date" name="manufacturing_date" value="{{ old('manufacturing_date') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('manufacturing_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Expiry Date -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ انتهاء الصلاحية</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" 
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                @error('expiry_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Storage Conditions -->
            <div style="grid-column: 1 / -1;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">شروط التخزين</label>
                <textarea name="storage_conditions" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="شروط التخزين المطلوبة (درجة الحرارة، الرطوبة، إلخ)...">{{ old('storage_conditions') }}</textarea>
                @error('storage_conditions')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Description and Notes -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-sticky-note" style="color: #6366f1; margin-left: 10px;"></i>
            الوصف والملاحظات
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <!-- Description -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">وصف المنتج</label>
                <textarea name="description" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 100px;" placeholder="وصف تفصيلي للمنتج، الاستخدامات، الجرعات...">{{ old('description') }}</textarea>
                @error('description')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Notes -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات إضافية</label>
                <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="ملاحظات إضافية، تحذيرات، تعليمات خاصة...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.sales.products.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" class="btn-purple" style="padding: 12px 24px;" onclick="
            console.log('=== SAVE BUTTON CLICKED ===');
            console.log('Form element:', this.form);
            console.log('Form action:', this.form.action);
            console.log('Form method:', this.form.method);

            // Check specific required fields
            const nameField = this.form.querySelector('[name=name]');
            const categoryField = this.form.querySelector('[name=category]');
            console.log('Name field value:', nameField ? nameField.value : 'NOT FOUND');
            console.log('Category field value:', categoryField ? categoryField.value : 'NOT FOUND');

            const formData = new FormData(this.form);
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            console.log('=== END FORM DATA ===');

            // Check if required fields are empty
            if (!nameField || !nameField.value.trim()) {
                console.error('❌ NAME FIELD IS EMPTY!');
                alert('اسم المنتج مطلوب!');
                return false;
            }
            if (!categoryField || !categoryField.value.trim()) {
                console.error('❌ CATEGORY FIELD IS EMPTY!');
                alert('الفئة مطلوبة!');
                return false;
            }
        ">
            <i class="fas fa-save"></i>
            حفظ المنتج
        </button>
    </div>
</form>
@endsection
