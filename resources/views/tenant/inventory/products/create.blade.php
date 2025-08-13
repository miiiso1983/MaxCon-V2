@extends('layouts.modern')

@section('page-title', 'إضافة منتج جديد')
@section('page-description', 'إضافة منتج جديد إلى كتالوج المنتجات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            إضافة منتج جديد 📦
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة منتج جديد إلى كتالوج المنتجات مع جميع التفاصيل
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.inventory-products.import') }}" style="background: rgba(16, 185, 129, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-file-excel"></i>
                    استيراد من Excel
                </a>
                <a href="{{ route('tenant.inventory.inventory-products.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Import Options -->
<div class="content-card" style="margin-bottom: 30px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
        <h3 style="color: #2d3748; margin: 0; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-upload" style="color: #10b981;"></i>
            خيارات الإضافة
        </h3>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <!-- Manual Entry Option -->
        <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; transition: all 0.3s ease; cursor: pointer;" onclick="showManualForm()">
            <div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-edit"></i>
            </div>
            <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 16px; font-weight: 600;">إدخال يدوي</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">إضافة منتج واحد بالتفصيل</p>
        </div>

        <!-- Excel Import Option -->
        <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; transition: all 0.3s ease; cursor: pointer;" onclick="showExcelForm()">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-file-excel"></i>
            </div>
            <h4 style="margin: 0 0 10px 0; color: #2d3748; font-size: 16px; font-weight: 600;">استيراد من Excel</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">رفع ملف Excel لإضافة منتجات متعددة</p>
        </div>
    </div>
</div>

<!-- Excel Import Form (Hidden by default) -->
<div id="excelForm" class="content-card" style="display: none; margin-bottom: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-file-excel" style="color: #10b981;"></i>
        استيراد المنتجات من Excel
    </h3>

    <form method="POST" action="{{ route('tenant.inventory.inventory-products.process-import') }}" enctype="multipart/form-data">
        @csrf

        <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
            <h4 style="color: #0369a1; margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">
                <i class="fas fa-info-circle" style="margin-left: 8px;"></i>
                تعليمات مهمة
            </h4>
            <ul style="color: #0369a1; margin: 0; padding-right: 20px;">
                <li>تأكد من أن ملف Excel يحتوي على الأعمدة المطلوبة</li>
                <li>استخدم النموذج المتوفر لضمان التوافق</li>
                <li>الحد الأقصى لحجم الملف: 10 ميجابايت</li>
                <li>الصيغ المدعومة: .xlsx, .xls, .csv</li>
            </ul>
        </div>

        <div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                    <i class="fas fa-file-upload" style="margin-left: 5px; color: #10b981;"></i>
                    ملف Excel
                </label>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                       style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: border-color 0.3s ease;"
                       onchange="validateFile(this)">
                <small style="color: #6b7280; font-size: 12px;">اختر ملف Excel يحتوي على بيانات المنتجات</small>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="{{ route('tenant.inventory.inventory-products.template') }}"
                   style="background: #f59e0b; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-download"></i>
                    تحميل النموذج
                </a>
                <button type="submit"
                        style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-upload"></i>
                    رفع واستيراد
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Manual Create Form -->
<div id="manualForm" class="content-card">
    <form method="POST" action="{{ route('tenant.inventory.inventory-products.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 40px;">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
                المعلومات الأساسية
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            اسم المنتج <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: باراسيتامول 500 مجم"
                               onfocus="this.style.borderColor='#3b82f6'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('name')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            رمز المنتج
                        </label>
                        <input type="text" name="code" value="{{ old('code') }}"
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="سيتم إنشاؤه تلقائياً إذا ترك فارغاً"
                               onfocus="this.style.borderColor='#3b82f6'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('code')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            الفئة
                        </label>
                        <select name="category_id" 
                                style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#3b82f6'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            العلامة التجارية
                        </label>
                        <input type="text" name="brand" value="{{ old('brand') }}"
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: فايزر، نوفارتيس..."
                               onfocus="this.style.borderColor='#3b82f6'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('brand')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            الشركة المصنعة
                        </label>
                        <input type="text" name="manufacturer" value="{{ old('manufacturer') }}"
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم الشركة المصنعة"
                               onfocus="this.style.borderColor='#3b82f6'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('manufacturer')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            بلد المنشأ
                        </label>
                        <input type="text" name="country_of_origin" value="{{ old('country_of_origin') }}"
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: ألمانيا، الولايات المتحدة..."
                               onfocus="this.style.borderColor='#3b82f6'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('country_of_origin')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            الحالة <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="status" required
                                style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#3b82f6'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="discontinued" {{ old('status') === 'discontinued' ? 'selected' : '' }}>متوقف</option>
                        </select>
                        @error('status')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            وحدة القياس <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="base_unit" required data-custom-select data-placeholder="اختر وحدة القياس..." data-searchable="true"
                                style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#3b82f6'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر وحدة القياس</option>
                            <option value="piece" {{ old('base_unit', 'piece') === 'piece' ? 'selected' : '' }}>قطعة</option>
                            <option value="box" {{ old('base_unit') === 'box' ? 'selected' : '' }}>علبة</option>
                            <option value="bottle" {{ old('base_unit') === 'bottle' ? 'selected' : '' }}>زجاجة</option>
                            <option value="pack" {{ old('base_unit') === 'pack' ? 'selected' : '' }}>حزمة</option>
                            <option value="tube" {{ old('base_unit') === 'tube' ? 'selected' : '' }}>أنبوب</option>
                            <option value="vial" {{ old('base_unit') === 'vial' ? 'selected' : '' }}>قارورة</option>
                            <option value="ampoule" {{ old('base_unit') === 'ampoule' ? 'selected' : '' }}>أمبولة</option>
                            <option value="capsule" {{ old('base_unit') === 'capsule' ? 'selected' : '' }}>كبسولة</option>
                            <option value="tablet" {{ old('base_unit') === 'tablet' ? 'selected' : '' }}>قرص</option>
                            <option value="sachet" {{ old('base_unit') === 'sachet' ? 'selected' : '' }}>كيس</option>
                            <option value="strip" {{ old('base_unit') === 'strip' ? 'selected' : '' }}>شريط</option>
                            <option value="blister" {{ old('base_unit') === 'blister' ? 'selected' : '' }}>بليستر</option>
                            <option value="ml" {{ old('base_unit') === 'ml' ? 'selected' : '' }}>مليلتر</option>
                            <option value="liter" {{ old('base_unit') === 'liter' ? 'selected' : '' }}>لتر</option>
                            <option value="gram" {{ old('base_unit') === 'gram' ? 'selected' : '' }}>جرام</option>
                            <option value="kg" {{ old('base_unit') === 'kg' ? 'selected' : '' }}>كيلوجرام</option>
                            <option value="tube" {{ old('base_unit') === 'tube' ? 'selected' : '' }}>أنبوب</option>
                            <option value="vial" {{ old('base_unit') === 'vial' ? 'selected' : '' }}>قارورة</option>
                            <option value="pack" {{ old('base_unit') === 'pack' ? 'selected' : '' }}>عبوة</option>
                            <option value="kg" {{ old('base_unit') === 'kg' ? 'selected' : '' }}>كيلوجرام</option>
                            <option value="liter" {{ old('base_unit') === 'liter' ? 'selected' : '' }}>لتر</option>
                        </select>
                        @error('base_unit')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                    الوصف
                </label>
                <textarea name="description" rows="3"
                          style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical; transition: border-color 0.3s;"
                          placeholder="وصف تفصيلي للمنتج..."
                          onfocus="this.style.borderColor='#3b82f6'"
                          onblur="this.style.borderColor='#e2e8f0'">{{ old('description') }}</textarea>
                @error('description')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Pricing Section -->
        <div style="margin-bottom: 40px;">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                <i class="fas fa-dollar-sign" style="color: #059669;"></i>
                الأسعار والتكلفة
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        سعر التكلفة <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="cost_price" value="{{ old('cost_price') }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#3b82f6'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('cost_price')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        سعر البيع <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="selling_price" value="{{ old('selling_price') }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#3b82f6'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('selling_price')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        العملة <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="currency" required data-custom-select data-placeholder="اختر العملة..." data-searchable="false">
                        <option value="">اختر العملة</option>
                        <option value="IQD" {{ old('currency', 'IQD') === 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                    @error('currency')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Stock Management Section -->
        <div style="margin-bottom: 40px;">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                <i class="fas fa-boxes" style="color: #8b5cf6;"></i>
                إدارة المخزون
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الكمية الحالية <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#3b82f6'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('current_stock')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الحد الأدنى للمخزون <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#3b82f6'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('minimum_stock')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الحد الأقصى للمخزون
                    </label>
                    <input type="number" name="maximum_stock" value="{{ old('maximum_stock') }}" step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#3b82f6'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('maximum_stock')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.inventory.inventory-products.index') }}"
               style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" 
                    style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ المنتج
            </button>
        </div>
    </form>
</div>

<script>
// Show manual form by default
document.addEventListener('DOMContentLoaded', function() {
    showManualForm();
});

function showManualForm() {
    document.getElementById('manualForm').style.display = 'block';
    document.getElementById('excelForm').style.display = 'none';

    // Update option styles
    updateOptionStyles('manual');
}

function showExcelForm() {
    document.getElementById('manualForm').style.display = 'none';
    document.getElementById('excelForm').style.display = 'block';

    // Update option styles
    updateOptionStyles('excel');
}

function updateOptionStyles(activeOption) {
    const options = document.querySelectorAll('[onclick*="show"]');
    options.forEach(option => {
        option.style.borderColor = '#e2e8f0';
        option.style.backgroundColor = 'white';
    });

    if (activeOption === 'manual') {
        options[0].style.borderColor = '#3b82f6';
        options[0].style.backgroundColor = '#eff6ff';
    } else {
        options[1].style.borderColor = '#10b981';
        options[1].style.backgroundColor = '#f0fdf4';
    }
}

function validateFile(input) {
    const file = input.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                             'application/vnd.ms-excel',
                             'text/csv'];

        if (fileSize > 10) {
            alert('حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت.');
            input.value = '';
            return false;
        }

        if (!allowedTypes.includes(file.type)) {
            alert('نوع الملف غير مدعوم. يرجى اختيار ملف Excel أو CSV.');
            input.value = '';
            return false;
        }

        // Show file info
        const fileName = file.name;
        const fileInfo = document.createElement('div');
        fileInfo.style.cssText = 'margin-top: 10px; padding: 10px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; color: #166534; font-size: 14px;';
        fileInfo.innerHTML = `<i class="fas fa-check-circle" style="margin-left: 5px;"></i>تم اختيار الملف: ${fileName}`;

        // Remove existing file info
        const existingInfo = input.parentNode.querySelector('[style*="f0fdf4"]');
        if (existingInfo) {
            existingInfo.remove();
        }

        input.parentNode.appendChild(fileInfo);
    }
}

// Auto-select category if passed in URL
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.querySelector('select[name="category_id"]');
    const categoryId = '{{ request("category_id") }}';
    if (categorySelect && categoryId) {
        categorySelect.value = categoryId;
    }
});
</script>
@endsection
