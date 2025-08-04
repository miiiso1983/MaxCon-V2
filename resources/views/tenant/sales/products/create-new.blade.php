@extends('layouts.modern')

@section('page-title', 'إضافة منتج جديد - نسخة محسنة')
@section('page-description', 'إضافة منتج جديد مع ضمان الأمان والدقة')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 30px; margin-bottom: 30px; color: white; text-align: center;">
        <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
            <i class="fas fa-plus-circle" style="margin-left: 10px;"></i>
            إضافة منتج جديد
        </h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 16px;">
            نسخة محسنة مع ضمان الأمان والدقة
        </p>
    </div>

    <!-- Tenant Info Display -->
    <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 12px; padding: 20px; margin-bottom: 25px;">
        <h3 style="color: #0369a1; margin: 0 0 10px 0; font-size: 18px;">
            <i class="fas fa-info-circle" style="margin-left: 8px;"></i>
            معلومات المؤسسة
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 14px;">
            <div>
                <strong>معرف المستخدم:</strong> {{ auth()->id() }}
            </div>
            <div>
                <strong>معرف المؤسسة:</strong> {{ auth()->user()->tenant_id ?? 'غير محدد' }}
            </div>
            <div>
                <strong>اسم المستخدم:</strong> {{ auth()->user()->name }}
            </div>
            <div>
                <strong>التوقيت:</strong> {{ now()->format('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('tenant.products.store.secure') }}" method="POST" style="background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        @csrf
        
        <!-- Hidden tenant_id for extra security -->
        <input type="hidden" name="tenant_id" value="{{ auth()->user()->tenant_id }}">
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        <input type="hidden" name="secure_token" value="{{ md5(auth()->user()->tenant_id . auth()->id() . now()->format('Y-m-d')) }}">

        <div style="display: grid; gap: 25px;">
            
            <!-- Product Name -->
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                    <i class="fas fa-tag" style="margin-left: 8px; color: #6366f1;"></i>
                    اسم المنتج *
                </label>
                <input type="text" name="name" id="product_name_new" required
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                    placeholder="أدخل اسم المنتج"
                    onfocus="this.style.borderColor='#6366f1'"
                    onblur="this.style.borderColor='#e5e7eb'">
            </div>

            <!-- Category -->
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                    <i class="fas fa-list" style="margin-left: 8px; color: #10b981;"></i>
                    الفئة *
                </label>
                <select name="category" id="product_category_new" required
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                    onfocus="this.style.borderColor='#10b981'"
                    onblur="this.style.borderColor='#e5e7eb'">
                    <option value="">اختر الفئة</option>
                    <option value="أدوية القلب والأوعية الدموية">أدوية القلب والأوعية الدموية</option>
                    <option value="المضادات الحيوية">المضادات الحيوية</option>
                    <option value="مسكنات الألم">مسكنات الألم</option>
                    <option value="أدوية الجهاز الهضمي">أدوية الجهاز الهضمي</option>
                    <option value="أدوية الجهاز التنفسي">أدوية الجهاز التنفسي</option>
                    <option value="الفيتامينات والمكملات">الفيتامينات والمكملات</option>
                    <option value="أدوية الأطفال">أدوية الأطفال</option>
                    <option value="أدوية أخرى">أدوية أخرى</option>
                </select>
            </div>

            <!-- Prices Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                        <i class="fas fa-dollar-sign" style="margin-left: 8px; color: #f59e0b;"></i>
                        سعر الشراء *
                    </label>
                    <input type="number" name="cost_price" step="0.01" min="0" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                        placeholder="0.00"
                        onfocus="this.style.borderColor='#f59e0b'"
                        onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                        <i class="fas fa-tag" style="margin-left: 8px; color: #ef4444;"></i>
                        سعر البيع *
                    </label>
                    <input type="number" name="selling_price" step="0.01" min="0" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                        placeholder="0.00"
                        onfocus="this.style.borderColor='#ef4444'"
                        onblur="this.style.borderColor='#e5e7eb'">
                </div>
            </div>

            <!-- Stock Row -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                        <i class="fas fa-boxes" style="margin-left: 8px; color: #8b5cf6;"></i>
                        المخزون الحالي *
                    </label>
                    <input type="number" name="stock_quantity" min="0" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                        placeholder="0"
                        onfocus="this.style.borderColor='#8b5cf6'"
                        onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                        <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #f59e0b;"></i>
                        الحد الأدنى *
                    </label>
                    <input type="number" name="min_stock_level" min="0" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                        placeholder="0"
                        onfocus="this.style.borderColor='#f59e0b'"
                        onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                        <i class="fas fa-balance-scale" style="margin-left: 8px; color: #06b6d4;"></i>
                        الوحدة *
                    </label>
                    <select name="unit_of_measure" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#06b6d4'"
                        onblur="this.style.borderColor='#e5e7eb'">
                        <option value="">اختر الوحدة</option>
                        <option value="قرص">قرص</option>
                        <option value="كبسولة">كبسولة</option>
                        <option value="شراب">شراب</option>
                        <option value="حقنة">حقنة</option>
                        <option value="مرهم">مرهم</option>
                        <option value="قطرة">قطرة</option>
                        <option value="بخاخ">بخاخ</option>
                        <option value="علبة">علبة</option>
                    </select>
                </div>
            </div>

            <!-- Optional Fields -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                        <i class="fas fa-industry" style="margin-left: 8px; color: #64748b;"></i>
                        الشركة المصنعة
                    </label>
                    <input type="text" name="manufacturer"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                        placeholder="اسم الشركة المصنعة"
                        onfocus="this.style.borderColor='#64748b'"
                        onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                        <i class="fas fa-barcode" style="margin-left: 8px; color: #64748b;"></i>
                        الباركود
                    </label>
                    <input type="text" name="barcode"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s;"
                        placeholder="رقم الباركود"
                        onfocus="this.style.borderColor='#64748b'"
                        onblur="this.style.borderColor='#e5e7eb'">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 16px;">
                    <i class="fas fa-align-right" style="margin-left: 8px; color: #64748b;"></i>
                    الوصف
                </label>
                <textarea name="description" rows="3"
                    style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; transition: all 0.3s; resize: vertical;"
                    placeholder="وصف المنتج (اختياري)"
                    onfocus="this.style.borderColor='#64748b'"
                    onblur="this.style.borderColor='#e5e7eb'"></textarea>
            </div>

        </div>

        <!-- Submit Button -->
        <div style="margin-top: 30px; text-align: center;">
            <button type="submit" 
                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 40px; border: none; border-radius: 12px; font-size: 18px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px -3px rgba(0, 0, 0, 0.1)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'">
                <i class="fas fa-save" style="margin-left: 10px;"></i>
                حفظ المنتج الآن
            </button>
        </div>

    </form>

    <!-- Back Link -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('tenant.sales.products.index') }}" 
            style="color: #6b7280; text-decoration: none; font-size: 16px; transition: color 0.3s;"
            onmouseover="this.style.color='#374151'"
            onmouseout="this.style.color='#6b7280'">
            <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
            العودة لقائمة المنتجات
        </a>
    </div>

</div>
@endsection
