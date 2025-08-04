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

    <!-- تشخيص الجلسة -->
    <div style="background: #e0f2fe; border: 2px solid #0ea5e9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <h4 style="color: #0369a1; margin: 0 0 10px 0;">🔍 تشخيص الجلسة</h4>
        <button type="button" onclick="
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    alert('✅ الجلسة نشطة!\\nCSRF Token: ' + data.csrf_token.substring(0, 20) + '...');
                })
                .catch(error => {
                    alert('❌ مشكلة في الجلسة!\\nالخطأ: ' + error.message);
                });
        " style="background: #0ea5e9; color: white; padding: 8px 16px; border: none; border-radius: 6px; margin-left: 10px;">
            فحص الجلسة
        </button>

        <button type="button" onclick="
            fetch('{{ route('tenant.sales.products.index') }}')
                .then(response => {
                    if (response.ok) {
                        alert('✅ الوصول للصفحات محمي!\\nالحالة: ' + response.status);
                    } else {
                        alert('❌ مشكلة في الوصول!\\nالحالة: ' + response.status);
                    }
                })
                .catch(error => {
                    alert('❌ خطأ في الاتصال!\\nالخطأ: ' + error.message);
                });
        " style="background: #059669; color: white; padding: 8px 16px; border: none; border-radius: 6px;">
            فحص الوصول
        </button>
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
                <input type="text" name="name" id="product_name" value="{{ old('name') }}" required
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f0fff4;"
                       placeholder="اسم المنتج التجاري">
                @error('name')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
                @if(config('app.debug'))
                <div style="font-size: 10px; color: #666; margin-top: 2px;">تشخيص: حقل الاسم - ID: product_name</div>
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
                <select name="category" id="product_category" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f0fff4;">
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
        <button type="button" onclick="
            const nameField = document.getElementById('product_name');
            const categoryField = document.getElementById('product_category');
            let message = 'تشخيص الحقول:\\n\\n';
            message += 'اسم المنتج: ' + (nameField ? nameField.value || '[فارغ]' : '[غير موجود]') + '\\n';
            message += 'الفئة: ' + (categoryField ? categoryField.value || '[فارغ]' : '[غير موجود]') + '\\n\\n';
            message += 'هل تريد المتابعة؟';
            if (confirm(message)) {
                // تحديث CSRF token قبل الإرسال
                fetch('/csrf-token')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('input[name=_token]').value = data.csrf_token;
                        document.querySelector('form').submit();
                    })
                    .catch(() => {
                        // إذا فشل تحديث التوكن، جرب الإرسال العادي
                        document.querySelector('form').submit();
                    });
            }
        " style="background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-bug"></i>
            تشخيص وحفظ
        </button>

        <button type="button" onclick="
            // التأكد من ملء الحقول المطلوبة
            const nameField2 = document.getElementById('product_name');
            const categoryField2 = document.getElementById('product_category');

            if (!nameField2.value.trim()) {
                alert('❌ يجب ملء حقل اسم المنتج أولاً!');
                nameField2.focus();
                return;
            }

            if (!categoryField2.value.trim()) {
                alert('❌ يجب اختيار فئة المنتج أولاً!');
                categoryField2.focus();
                return;
            }

            // إنشاء FormData مع جميع بيانات الـ form
            const form = document.querySelector('form');
            const formData = new FormData(form);
            formData.append('debug_mode', '1');

            // عرض البيانات المرسلة
            let dataPreview = 'البيانات المرسلة:\\n\\n';
            for (let [key, value] of formData.entries()) {
                if (key !== '_token' && key !== 'debug_mode') {
                    dataPreview += key + ': ' + value + '\\n';
                }
            }

            if (confirm(dataPreview + '\\nهل تريد إرسال هذه البيانات للخادم؟')) {
                fetch('{{ route('tenant.sales.products.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('✅ استجابة الخادم:\\n' + JSON.stringify(data, null, 2));
                })
                .catch(error => {
                    alert('❌ خطأ في الاتصال:\\n' + error.message);
                });
            }
        " style="background: #f59e0b; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-search"></i>
            تشخيص الخادم
        </button>

        <button type="button" onclick="testButton()" style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-redo"></i>
            حفظ مع إعادة تحميل
        </button>

        <button type="button" onclick="
            alert('اختبار الزر - هل يعمل؟');
            console.log('Button clicked!');
        " style="background: #f59e0b; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-test"></i>
            اختبار الزر
        </button>

        <button type="submit" style="background: #9f7aea; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-save"></i>
            حفظ المنتج العادي
        </button>

        <button type="button" onclick="
            const formDirect = document.querySelector('form');
            const name = document.getElementById('product_name').value;
            const category = document.getElementById('product_category').value;

            if (!name.trim()) {
                alert('❌ اسم المنتج مطلوب!');
                return;
            }

            if (!category.trim()) {
                alert('❌ الفئة مطلوبة!');
                return;
            }

            alert('✅ سيتم حفظ المنتج: ' + name);
            formDirect.submit();
        " style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-check"></i>
            حفظ مباشر
        </button>

        <button type="button" onclick="
            // إنشاء منتج مباشرة عبر route خاص
            const productName = document.getElementById('product_name').value;
            const productCategory = document.getElementById('product_category').value;

            if (!productName.trim()) {
                alert('❌ اسم المنتج مطلوب!');
                return;
            }

            fetch('/create-product-direct', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: productName,
                    category: productCategory,
                    purchase_price: 100,
                    selling_price: 150,
                    current_stock: 50,
                    min_stock_level: 10,
                    unit: 'قرص'
                })
            })
            .then(response => response.json())
            .then(data => {
                alert('نتيجة الإنشاء المباشر: ' + JSON.stringify(data, null, 2));
                if (data.success) {
                    window.location.href = '/tenant/sales/products';
                }
            })
            .catch(error => {
                alert('خطأ: ' + error.message);
            });
        " style="background: #dc2626; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-bolt"></i>
            إنشاء مباشر
        </button>

        <button type="button" onclick="
            fetch('/tenant/sales/products', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({
                    test_mode: true,
                    name: 'اختبار الوصول للـ Controller',
                    category: 'أدوية',
                    purchase_price: 100,
                    selling_price: 150,
                    current_stock: 50,
                    min_stock_level: 10,
                    unit: 'قرص'
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                alert('استجابة الخادم: ' + JSON.stringify(data, null, 2));
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطأ: ' + error.message);
            });
        " style="background: #dc2626; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-bug"></i>
            اختبار الوصول للـ Controller
        </button>

        <button type="button" onclick="
            // حل بسيط: استخدام الـ form العادي
            const formSimple = document.querySelector('form');
            const nameFieldSimple = document.getElementById('product_name');
            const categoryFieldSimple = document.getElementById('product_category');

            if (!nameFieldSimple.value.trim()) {
                alert('❌ اسم المنتج مطلوب!');
                return;
            }

            if (!categoryFieldSimple.value.trim()) {
                alert('❌ الفئة مطلوبة!');
                return;
            }

            // تشخيص البيانات قبل الإرسال
            console.log('=== FORM VALUES BEFORE SUBMIT ===');
            console.log('Name:', nameFieldSimple.value);
            console.log('Category:', categoryFieldSimple.value);

            const purchasePriceField = formSimple.querySelector('[name=purchase_price]');
            const sellingPriceField = formSimple.querySelector('[name=selling_price]');
            const currentStockField = formSimple.querySelector('[name=current_stock]');
            const unitField = formSimple.querySelector('[name=unit]');
            const csrfField = formSimple.querySelector('[name=_token]');

            console.log('Purchase Price:', purchasePriceField ? purchasePriceField.value : 'FIELD NOT FOUND');
            console.log('Selling Price:', sellingPriceField ? sellingPriceField.value : 'FIELD NOT FOUND');
            console.log('Current Stock:', currentStockField ? currentStockField.value : 'FIELD NOT FOUND');
            console.log('Unit:', unitField ? unitField.value : 'FIELD NOT FOUND');
            console.log('CSRF Token:', csrfField ? csrfField.value : 'FIELD NOT FOUND');

            // إرسال الـ form العادي
            console.log('✅ البيانات الأساسية موجودة، سيتم إرسال الـ form...');
            alert('✅ البيانات جاهزة! سيتم حفظ المنتج الآن...');
            formSimple.submit();
        " style="background: #16a34a; color: white; padding: 12px 24px; border: none; border-radius: 8px; margin-left: 10px;">
            <i class="fas fa-save"></i>
            حفظ بـ Form عادي
        </button>
    </div>
</form>

<script>
// Function for save with reload button
function testButton() {
    alert('الزر يعمل! سيتم حفظ البيانات وإعادة التحميل...');

    const form = document.querySelector('form');
    const nameField = document.getElementById('product_name');
    const categoryField = document.getElementById('product_category');

    if (!nameField.value.trim()) {
        alert('❌ اسم المنتج مطلوب!');
        return;
    }

    if (!categoryField.value.trim()) {
        alert('❌ الفئة مطلوبة!');
        return;
    }

    // حفظ البيانات في localStorage
    const formData = {
        name: nameField.value,
        category: categoryField.value,
        purchase_price: form.querySelector('[name=purchase_price]').value,
        selling_price: form.querySelector('[name=selling_price]').value,
        current_stock: form.querySelector('[name=current_stock]').value,
        unit: form.querySelector('[name=unit]').value
    };

    localStorage.setItem('productFormData', JSON.stringify(formData));
    console.log('Data saved to localStorage:', formData);

    // إعادة تحميل الصفحة
    setTimeout(function() {
        window.location.reload();
    }, 1000);
}

// استعادة البيانات بعد إعادة التحميل
document.addEventListener('DOMContentLoaded', function() {
    const savedData = localStorage.getItem('productFormData');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            console.log('Restoring data:', data);

            // ملء الحقول
            document.getElementById('product_name').value = data.name || '';
            document.getElementById('product_category').value = data.category || '';
            document.querySelector('[name=purchase_price]').value = data.purchase_price || '';
            document.querySelector('[name=selling_price]').value = data.selling_price || '';
            document.querySelector('[name=current_stock]').value = data.current_stock || '';
            document.querySelector('[name=unit]').value = data.unit || '';

            // حذف البيانات المحفوظة
            localStorage.removeItem('productFormData');

            // إظهار رسالة
            alert('✅ تم استعادة البيانات! يمكنك الآن الضغط على حفظ المنتج العادي.');
        } catch (e) {
            console.error('Error restoring data:', e);
            localStorage.removeItem('productFormData');
        }
    }
});
</script>

@endsection
