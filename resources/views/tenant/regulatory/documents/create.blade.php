@extends('layouts.modern')

@section('title', 'إضافة وثيقة جديدة')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة وثيقة جديدة</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">رفع وثيقة تنظيمية جديدة إلى النظام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.documents.index') }}" style="background: rgba(255,255,255,0.2); color: #4ecdc4; padding: 15px 25px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div style="background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="color: #48bb78; font-size: 20px;"></i>
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-times-circle" style="color: #f56565; font-size: 20px;"></i>
                <strong>{{ session('error') }}</strong>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-times-circle" style="color: #f56565; font-size: 20px;"></i>
                <strong>يرجى تصحيح الأخطاء التالية:</strong>
            </div>
            <ul style="margin: 0 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form action="{{ route('tenant.inventory.regulatory.documents.store') }}" method="POST" enctype="multipart/form-data" id="documentForm">
            @csrf
            
            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات الوثيقة
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-file-alt" style="margin-left: 8px; color: #4ecdc4;"></i>
                            عنوان الوثيقة *
                        </label>
                        <input type="text" name="document_title" value="{{ old('document_title') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="أدخل عنوان الوثيقة"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #4ecdc4;"></i>
                            نوع الوثيقة *
                        </label>
                        <select name="document_type" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4ecdc4'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع الوثيقة</option>
                            <option value="license" {{ old('document_type') == 'license' ? 'selected' : '' }}>ترخيص</option>
                            <option value="certificate" {{ old('document_type') == 'certificate' ? 'selected' : '' }}>شهادة</option>
                            <option value="policy" {{ old('document_type') == 'policy' ? 'selected' : '' }}>سياسة</option>
                            <option value="procedure" {{ old('document_type') == 'procedure' ? 'selected' : '' }}>إجراء</option>
                            <option value="legal" {{ old('document_type') == 'legal' ? 'selected' : '' }}>قانوني</option>
                            <option value="compliance" {{ old('document_type') == 'compliance' ? 'selected' : '' }}>امتثال</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-hashtag" style="margin-left: 8px; color: #4ecdc4;"></i>
                            رقم الوثيقة
                        </label>
                        <input type="text" name="document_number" value="{{ old('document_number') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="رقم الوثيقة (اختياري)"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-university" style="margin-left: 8px; color: #4ecdc4;"></i>
                            الجهة المصدرة *
                        </label>
                        <input type="text" name="issuing_authority" value="{{ old('issuing_authority') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: وزارة الصحة العراقية"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-calendar" style="margin-left: 10px; color: #4ecdc4;"></i>
                    التواريخ
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #4ecdc4;"></i>
                            تاريخ الإصدار *
                        </label>
                        <input type="date" name="issue_date" value="{{ old('issue_date') }}" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-times" style="margin-left: 8px; color: #f56565;"></i>
                            تاريخ الانتهاء
                        </label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="تاريخ الانتهاء (اختياري)"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- File Upload -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-upload" style="margin-left: 10px; color: #4ecdc4;"></i>
                    رفع الملف
                </h3>
                
                <div id="dropZone" style="border: 3px dashed #4ecdc4; border-radius: 15px; padding: 40px 20px; background: rgba(78, 205, 196, 0.05); transition: all 0.3s; cursor: pointer; text-align: center;"
                     ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);" onclick="document.getElementById('fileInput').click();">
                    
                    <div style="font-size: 48px; color: #4ecdc4; margin-bottom: 15px;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 600;">
                        اسحب الملف هنا أو انقر للاختيار
                    </h4>
                    
                    <p style="color: #718096; margin: 0; font-size: 14px;">
                        الملفات المدعومة: PDF, DOC, DOCX, JPG, PNG (حد أقصى 10 ميجابايت)
                    </p>
                    
                    <input type="file" id="fileInput" name="document_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required style="display: none;" onchange="fileSelected(this)">
                    
                    <div id="fileInfo" style="display: none; background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 10px; padding: 15px; margin-top: 15px;">
                        <i class="fas fa-file" style="color: #48bb78; margin-left: 10px;"></i>
                        <span id="fileName" style="color: #2d3748; font-weight: 600;"></span>
                        <button type="button" onclick="removeFile()" style="background: none; border: none; color: #f56565; margin-right: 10px; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-info" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات إضافية
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-align-left" style="margin-left: 8px; color: #4ecdc4;"></i>
                            وصف الوثيقة
                        </label>
                        <textarea name="description" rows="4"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="وصف مختصر للوثيقة ومحتواها..."
                                  onfocus="this.style.borderColor='#4ecdc4'" 
                                  onblur="this.style.borderColor='#e2e8f0'">{{ old('description') }}</textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #4ecdc4;"></i>
                            العلامات (مفصولة بفواصل)
                        </label>
                        <input type="text" name="tags" value="{{ old('tags') }}" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: ترخيص, صحة, دواء"
                               onfocus="this.style.borderColor='#4ecdc4'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                <button type="submit" 
                        style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ الوثيقة
                </button>
                
                <button type="button" onclick="resetForm()" 
                        style="background: rgba(78, 205, 196, 0.1); color: #4ecdc4; padding: 15px 40px; border: 2px solid #4ecdc4; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>
                
                <a href="{{ route('tenant.inventory.regulatory.documents.index') }}" 
                   style="background: rgba(113, 128, 150, 0.1); color: #718096; padding: 15px 40px; border: 2px solid #718096; border-radius: 15px; font-weight: 600; font-size: 16px; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                   onmouseover="this.style.transform='translateY(-2px)'" 
                   onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function dragOverHandler(ev) {
    ev.preventDefault();
    document.getElementById('dropZone').style.borderColor = '#48bb78';
    document.getElementById('dropZone').style.background = 'rgba(72, 187, 120, 0.1)';
}

function dragLeaveHandler(ev) {
    document.getElementById('dropZone').style.borderColor = '#4ecdc4';
    document.getElementById('dropZone').style.background = 'rgba(78, 205, 196, 0.05)';
}

function dropHandler(ev) {
    ev.preventDefault();
    
    const files = ev.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('fileInput').files = files;
        fileSelected(document.getElementById('fileInput'));
    }
    
    dragLeaveHandler(ev);
}

function fileSelected(input) {
    const file = input.files[0];
    if (file) {
        // Check file type
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('نوع الملف غير مدعوم. يرجى اختيار ملف PDF, DOC, DOCX, JPG, أو PNG.');
            removeFile();
            return;
        }
        
        // Check file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('حجم الملف كبير جداً. يجب أن يكون أقل من 10 ميجابايت.');
            removeFile();
            return;
        }
        
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileInfo').style.display = 'block';
    }
}

function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').style.display = 'none';
}

function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
        document.getElementById('documentForm').reset();
        removeFile();
        alert('تم إعادة تعيين النموذج');
    }
}

// Form submission
document.getElementById('documentForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('يرجى اختيار ملف للرفع');
        return;
    }
    
    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;
});
</script>

@endsection
