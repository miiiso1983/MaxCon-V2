@extends('layouts.modern')

@section('page-title', 'تعديل الفئة: ' . $category->name)
@section('page-description', 'تعديل بيانات فئة المنتجات')

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
                            تعديل الفئة ✏️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تعديل بيانات فئة: {{ $category->name }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.categories.show', $category) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-eye"></i>
                    عرض الفئة
                </a>
                <a href="{{ route('tenant.inventory.categories.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.inventory.categories.update', $category) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- Basic Information -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-info-circle" style="color: #f59e0b;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        اسم الفئة <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="مثال: الأدوية، المكملات الغذائية..."
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('name')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        رمز الفئة <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $category->code) }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="رمز فريد للفئة"
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('code')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الوصف
                    </label>
                    <textarea name="description" rows="4"
                              style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; resize: vertical; transition: border-color 0.3s;"
                              placeholder="وصف مختصر للفئة..."
                              onfocus="this.style.borderColor='#f59e0b'"
                              onblur="this.style.borderColor='#e2e8f0'">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Category Settings -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-cogs" style="color: #f59e0b;"></i>
                    إعدادات الفئة
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الفئة الأب
                    </label>
                    <select name="parent_id" 
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#f59e0b'"
                            onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">فئة رئيسية</option>
                        @foreach($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->full_path }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        ترتيب العرض
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="0"
                           onfocus="this.style.borderColor='#f59e0b'"
                           onblur="this.style.borderColor='#e2e8f0'">
                    @error('sort_order')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        الحالة <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="status" required
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#f59e0b'"
                            onblur="this.style.borderColor='#e2e8f0'">
                        <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>نشطة</option>
                        <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                    </select>
                    @error('status')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">
                        صورة الفئة
                    </label>
                    
                    @if($category->image)
                        <div style="margin-bottom: 15px;">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                 style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover; border: 2px solid #e2e8f0;">
                            <p style="font-size: 12px; color: #6b7280; margin-top: 5px;">الصورة الحالية</p>
                        </div>
                    @endif
                    
                    <div style="border: 2px dashed #e2e8f0; border-radius: 8px; padding: 20px; text-align: center; transition: border-color 0.3s;"
                         ondragover="event.preventDefault(); this.style.borderColor='#f59e0b';"
                         ondragleave="this.style.borderColor='#e2e8f0';"
                         ondrop="event.preventDefault(); this.style.borderColor='#e2e8f0'; handleFileDrop(event);">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #f59e0b; margin-bottom: 10px;"></i>
                        <p style="margin: 0 0 10px 0; color: #6b7280;">اسحب الصورة هنا أو انقر للاختيار</p>
                        <input type="file" name="image" accept="image/*" 
                               style="display: none;" id="imageInput"
                               onchange="previewImage(this)">
                        <button type="button" onclick="document.getElementById('imageInput').click()"
                                style="background: #f59e0b; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                            اختيار صورة جديدة
                        </button>
                        <div id="imagePreview" style="margin-top: 15px; display: none;">
                            <img id="previewImg" style="max-width: 150px; max-height: 150px; border-radius: 8px; object-fit: cover;">
                        </div>
                    </div>
                    @error('image')
                        <div style="color: #ef4444; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.inventory.categories.show', $category) }}" 
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

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function handleFileDrop(event) {
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('imageInput').files = files;
        previewImage(document.getElementById('imageInput'));
    }
}
</script>
@endsection
