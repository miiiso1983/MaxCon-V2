@extends('layouts.modern')

@section('page-title', 'تعديل المنتج: ' . $product->name)
@section('page-description', 'تعديل بيانات المنتج')

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
                            تعديل المنتج ✏️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تعديل بيانات: {{ $product->name }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.inventory-products.show', $product) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-eye"></i>
                    عرض المنتج
                </a>
                <a href="{{ route('tenant.inventory.inventory-products.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.inventory.inventory-products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Section -->
        <div style="margin-bottom: 40px;">
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                <i class="fas fa-info-circle" style="color: #f59e0b;"></i>
                المعلومات الأساسية
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            اسم المنتج <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: باراسيتامول 500 مجم"
                               onfocus="this.style.borderColor='#f59e0b'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('name')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            رمز المنتج <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="code" value="{{ old('code', $product->code ?? $product->product_code) }}" required
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="رمز فريد للمنتج"
                               onfocus="this.style.borderColor='#f59e0b'"
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
                                onfocus="this.style.borderColor='#f59e0b'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}"
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثال: فايزر، نوفارتيس..."
                               onfocus="this.style.borderColor='#f59e0b'"
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
                        <input type="text" name="manufacturer" value="{{ old('manufacturer', $product->manufacturer) }}"
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم الشركة المصنعة"
                               onfocus="this.style.borderColor='#f59e0b'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('manufacturer')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            الحالة <span style="color: #ef4444;">*</span>
                        </label>
                        @php
                            $currentStatus = old('status', $product->status ?? ($product->is_active ? 'active' : 'inactive'));
                        @endphp
                        <select name="status" required
                                style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#f59e0b'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="discontinued" {{ $currentStatus === 'discontinued' ? 'selected' : '' }}>متوقف</option>
                        </select>
                        @error('status')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            وحدة القياس <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="base_unit" required
                                style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#f59e0b'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            @php
                                $currentUnit = old('base_unit', $product->base_unit ?? $product->unit_of_measure ?? 'piece');
                            @endphp
                            <option value="piece" {{ $currentUnit === 'piece' ? 'selected' : '' }}>قطعة</option>
                            <option value="box" {{ $currentUnit === 'box' ? 'selected' : '' }}>علبة</option>
                            <option value="bottle" {{ $currentUnit === 'bottle' ? 'selected' : '' }}>زجاجة</option>
                            <option value="tube" {{ $currentUnit === 'tube' ? 'selected' : '' }}>أنبوب</option>
                            <option value="vial" {{ $currentUnit === 'vial' ? 'selected' : '' }}>قارورة</option>
                            <option value="pack" {{ $currentUnit === 'pack' ? 'selected' : '' }}>عبوة</option>
                            <option value="kg" {{ $currentUnit === 'kg' ? 'selected' : '' }}>كيلوجرام</option>
                            <option value="liter" {{ $currentUnit === 'liter' ? 'selected' : '' }}>لتر</option>
                        </select>
                        @error('base_unit')
                            <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                            الباركود
                        </label>
                        <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                               style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="رمز الباركود"
                               onfocus="this.style.borderColor='#f59e0b'"
                               onblur="this.style.borderColor='#e2e8f0'">
                        @error('barcode')
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
                          onfocus="this.style.borderColor='#f59e0b'"
                          onblur="this.style.borderColor='#e2e8f0'">{{ old('description', $product->description) }}</textarea>
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
                    <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('cost_price')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        سعر البيع <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('selling_price')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        العملة <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="currency" required
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#f59e0b'"
                            onblur="this.style.borderColor='#e2e8f0'">
                        @php
                            $currentCurrency = old('currency', $product->currency ?? 'IQD');
                        @endphp
                        <option value="IQD" {{ $currentCurrency === 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ $currentCurrency === 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ $currentCurrency === 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
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
                    <input type="number" name="current_stock" value="{{ old('current_stock', $product->current_stock ?? $product->stock_quantity ?? 0) }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('current_stock')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الحد الأدنى للمخزون <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock ?? $product->min_stock_level ?? 0) }}" step="0.01" min="0" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('minimum_stock')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الحد الأقصى للمخزون
                    </label>
                    <input type="number" name="maximum_stock" value="{{ old('maximum_stock', $product->maximum_stock ?? $product->max_stock_level) }}" step="0.01" min="0"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0.00"
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('maximum_stock')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.inventory.inventory-products.show', $product) }}"
               style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" 
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>
@endsection
