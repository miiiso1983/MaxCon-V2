@extends('layouts.modern')

@section('title', 'رفع ملفات متعددة')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-upload"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">رفع ملفات متعددة</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">رفع عدة وثائق تنظيمية في مرة واحدة</p>
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

    @if(session('warning'))
        <div style="background: rgba(237, 137, 54, 0.1); border: 2px solid #ed8936; border-radius: 15px; padding: 20px; margin-bottom: 20px; color: #2d3748;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-exclamation-triangle" style="color: #ed8936; font-size: 20px;"></i>
                <strong>{{ session('warning') }}</strong>
            </div>
            @if(session('upload_errors'))
                <div style="margin-top: 15px; padding: 15px; background: rgba(255,255,255,0.5); border-radius: 10px;">
                    <strong>الأخطاء:</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        @foreach(session('upload_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
        
        <!-- Instructions -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px; margin-bottom: 30px;">
            <h3 style="margin: 0 0 15px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-info-circle" style="margin-left: 10px;"></i>
                تعليمات الرفع المتعدد
            </h3>
            <ul style="margin: 0; padding-right: 20px; line-height: 1.8;">
                <li>يمكنك اختيار عدة ملفات في مرة واحدة</li>
                <li>الملفات المدعومة: PDF, DOC, DOCX, JPG, PNG</li>
                <li>حد أقصى لحجم كل ملف: 10 ميجابايت</li>
                <li>سيتم استخدام اسم الملف كعنوان للوثيقة</li>
                <li>يمكنك تحديد نوع الوثيقة والجهة المصدرة لجميع الملفات</li>
            </ul>
        </div>

        <form action="{{ route('tenant.inventory.regulatory.documents.bulk-upload') }}" method="POST" enctype="multipart/form-data" id="bulkUploadForm">
            @csrf
            
            <!-- Common Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-cog" style="margin-left: 10px; color: #4ecdc4;"></i>
                    معلومات مشتركة
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
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

            <!-- File Upload -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4ecdc4; padding-bottom: 10px;">
                    <i class="fas fa-upload" style="margin-left: 10px; color: #4ecdc4;"></i>
                    رفع الملفات
                </h3>
                
                <div id="dropZone" style="border: 3px dashed #4ecdc4; border-radius: 15px; padding: 60px 20px; background: rgba(78, 205, 196, 0.05); transition: all 0.3s; cursor: pointer; text-align: center;"
                     ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);" onclick="document.getElementById('fileInput').click();">
                    
                    <div style="font-size: 64px; color: #4ecdc4; margin-bottom: 20px;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    
                    <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">
                        اسحب الملفات هنا أو انقر للاختيار
                    </h3>
                    
                    <p style="color: #718096; margin: 0 0 20px 0; font-size: 16px;">
                        يمكنك اختيار عدة ملفات في مرة واحدة
                    </p>
                    
                    <input type="file" id="fileInput" name="bulk_files[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple required style="display: none;" onchange="filesSelected(this)">
                    
                    <div id="filesInfo" style="display: none; margin-top: 20px;">
                        <div id="filesList" style="text-align: right;"></div>
                        <button type="button" onclick="removeAllFiles()" style="background: #f56565; color: white; padding: 10px 20px; border: none; border-radius: 8px; margin-top: 15px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                            حذف جميع الملفات
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" id="submitBtn" disabled style="background: #cbd5e0; color: #a0aec0; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: not-allowed; display: inline-flex; align-items: center; gap: 10px;">
                    <i class="fas fa-upload"></i>
                    رفع الملفات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedFiles = [];

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
        handleFiles(files);
    }
    
    dragLeaveHandler(ev);
}

function filesSelected(input) {
    handleFiles(input.files);
}

function handleFiles(files) {
    selectedFiles = [];
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check file type
        if (!allowedTypes.includes(file.type)) {
            alert(`نوع الملف ${file.name} غير مدعوم. يرجى اختيار ملفات PDF, DOC, DOCX, JPG, أو PNG.`);
            continue;
        }
        
        // Check file size
        if (file.size > maxSize) {
            alert(`حجم الملف ${file.name} كبير جداً. يجب أن يكون أقل من 10 ميجابايت.`);
            continue;
        }
        
        selectedFiles.push(file);
    }
    
    displayFiles();
    updateSubmitButton();
}

function displayFiles() {
    const filesList = document.getElementById('filesList');
    const filesInfo = document.getElementById('filesInfo');
    
    if (selectedFiles.length === 0) {
        filesInfo.style.display = 'none';
        return;
    }
    
    filesInfo.style.display = 'block';
    filesList.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const fileDiv = document.createElement('div');
        fileDiv.style.cssText = 'background: rgba(72, 187, 120, 0.1); border: 1px solid #48bb78; border-radius: 8px; padding: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;';
        
        fileDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-file" style="color: #48bb78;"></i>
                <span style="font-weight: 600;">${file.name}</span>
                <span style="color: #718096; font-size: 14px;">(${formatFileSize(file.size)})</span>
            </div>
            <button type="button" onclick="removeFile(${index})" style="background: #f56565; color: white; border: none; border-radius: 4px; padding: 5px 8px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        filesList.appendChild(fileDiv);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFiles();
    updateSubmitButton();
    updateFileInput();
}

function removeAllFiles() {
    selectedFiles = [];
    document.getElementById('fileInput').value = '';
    displayFiles();
    updateSubmitButton();
}

function updateFileInput() {
    // Create new FileList with remaining files
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    document.getElementById('fileInput').files = dt.files;
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    
    if (selectedFiles.length > 0) {
        submitBtn.disabled = false;
        submitBtn.style.background = 'linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%)';
        submitBtn.style.color = 'white';
        submitBtn.style.cursor = 'pointer';
        submitBtn.innerHTML = `<i class="fas fa-upload"></i> رفع ${selectedFiles.length} ملف`;
    } else {
        submitBtn.disabled = true;
        submitBtn.style.background = '#cbd5e0';
        submitBtn.style.color = '#a0aec0';
        submitBtn.style.cursor = 'not-allowed';
        submitBtn.innerHTML = '<i class="fas fa-upload"></i> رفع الملفات';
    }
}

function formatFileSize(bytes) {
    if (bytes >= 1048576) {
        return (bytes / 1048576).toFixed(2) + ' MB';
    } else if (bytes >= 1024) {
        return (bytes / 1024).toFixed(2) + ' KB';
    } else {
        return bytes + ' bytes';
    }
}

// Form submission
document.getElementById('bulkUploadForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الرفع...';
    submitBtn.disabled = true;
});
</script>

@endsection
