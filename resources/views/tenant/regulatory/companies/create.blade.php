@extends('layouts.modern')

@section('title', 'إضافة شركة جديدة')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إضافة شركة جديدة</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تسجيل شركة جديدة في النظام التنظيمي</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.companies.index') }}" style="background: rgba(255,255,255,0.2); color: #4facfe; padding: 15px 25px; border: 2px solid #4facfe; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form id="companyForm" action="{{ route('tenant.inventory.regulatory.companies.store') }}" method="POST" onsubmit="submitForm(event)">
            @csrf
            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4facfe; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="margin-left: 10px; color: #4facfe;"></i>
                    المعلومات الأساسية
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-building" style="margin-left: 8px; color: #4facfe;"></i>
                            اسم الشركة (عربي) *
                        </label>
                        <input type="text" name="company_name" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="أدخل اسم الشركة بالعربية"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-building" style="margin-left: 8px; color: #4facfe;"></i>
                            اسم الشركة (إنجليزي)
                        </label>
                        <input type="text" name="company_name_en" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="Enter company name in English"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-id-card" style="margin-left: 8px; color: #4facfe;"></i>
                            رقم التسجيل *
                        </label>
                        <input type="text" name="registration_number" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="أدخل رقم التسجيل"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-certificate" style="margin-left: 8px; color: #4facfe;"></i>
                            رقم الترخيص *
                        </label>
                        <input type="text" name="license_number" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="أدخل رقم الترخيص"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- License Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4facfe; padding-bottom: 10px;">
                    <i class="fas fa-certificate" style="margin-left: 10px; color: #4facfe;"></i>
                    معلومات الترخيص
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-tags" style="margin-left: 8px; color: #4facfe;"></i>
                            نوع الترخيص *
                        </label>
                        <select name="license_type" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4facfe'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر نوع الترخيص</option>
                            <option value="manufacturing">تصنيع</option>
                            <option value="import">استيراد</option>
                            <option value="export">تصدير</option>
                            <option value="distribution">توزيع</option>
                            <option value="wholesale">جملة</option>
                            <option value="retail">تجزئة</option>
                            <option value="research">بحث وتطوير</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-university" style="margin-left: 8px; color: #4facfe;"></i>
                            الجهة التنظيمية *
                        </label>
                        <input type="text" name="regulatory_authority" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="مثل: وزارة الصحة العراقية"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar" style="margin-left: 8px; color: #4facfe;"></i>
                            تاريخ التسجيل *
                        </label>
                        <input type="date" name="registration_date" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-check" style="margin-left: 8px; color: #4facfe;"></i>
                            تاريخ إصدار الترخيص *
                        </label>
                        <input type="date" name="license_issue_date" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-times" style="margin-left: 8px; color: #f56565;"></i>
                            تاريخ انتهاء الترخيص *
                        </label>
                        <input type="date" name="license_expiry_date" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-shield-alt" style="margin-left: 8px; color: #4facfe;"></i>
                            حالة الامتثال *
                        </label>
                        <select name="compliance_status" required 
                                style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                                onfocus="this.style.borderColor='#4facfe'" 
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">اختر حالة الامتثال</option>
                            <option value="compliant">ملتزم</option>
                            <option value="non_compliant">غير ملتزم</option>
                            <option value="under_investigation">قيد التحقيق</option>
                            <option value="corrective_action">إجراء تصحيحي</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4facfe; padding-bottom: 10px;">
                    <i class="fas fa-address-book" style="margin-left: 10px; color: #4facfe;"></i>
                    معلومات الاتصال
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-map-marker-alt" style="margin-left: 8px; color: #4facfe;"></i>
                            عنوان الشركة *
                        </label>
                        <textarea name="company_address" required rows="3"
                                  style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                                  placeholder="أدخل العنوان الكامل للشركة"
                                  onfocus="this.style.borderColor='#4facfe'" 
                                  onblur="this.style.borderColor='#e2e8f0'"></textarea>
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-user" style="margin-left: 8px; color: #4facfe;"></i>
                            الشخص المسؤول *
                        </label>
                        <input type="text" name="contact_person" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="اسم الشخص المسؤول"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-envelope" style="margin-left: 8px; color: #4facfe;"></i>
                            البريد الإلكتروني *
                        </label>
                        <input type="email" name="contact_email" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="example@company.com"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-phone" style="margin-left: 8px; color: #4facfe;"></i>
                            رقم الهاتف *
                        </label>
                        <input type="tel" name="contact_phone" required 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               placeholder="+964 XXX XXX XXXX"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #4facfe;"></i>
                            تاريخ التفتيش القادم
                        </label>
                        <input type="date" name="next_inspection_date" 
                               style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                               onfocus="this.style.borderColor='#4facfe'" 
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div style="margin-bottom: 30px;">
                <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; border-bottom: 2px solid #4facfe; padding-bottom: 10px;">
                    <i class="fas fa-sticky-note" style="margin-left: 10px; color: #4facfe;"></i>
                    ملاحظات إضافية
                </h3>
                
                <textarea name="notes" rows="4"
                          style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                          placeholder="أدخل أي ملاحظات إضافية حول الشركة..."
                          onfocus="this.style.borderColor='#4facfe'" 
                          onblur="this.style.borderColor='#e2e8f0'"></textarea>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                <button type="submit" 
                        style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i>
                    حفظ الشركة
                </button>
                
                <button type="button" onclick="resetForm()" 
                        style="background: rgba(79, 172, 254, 0.1); color: #4facfe; padding: 15px 40px; border: 2px solid #4facfe; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>
                
                <a href="{{ route('tenant.inventory.regulatory.companies.index') }}" 
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
function submitForm(event) {
    event.preventDefault();
    
    // Get form data
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Validate required fields
    const requiredFields = ['company_name', 'registration_number', 'license_number', 'license_type', 'regulatory_authority', 'registration_date', 'license_issue_date', 'license_expiry_date', 'compliance_status', 'company_address', 'contact_person', 'contact_email', 'contact_phone'];
    
    for (let field of requiredFields) {
        if (!data[field] || data[field].trim() === '') {
            alert(`الرجاء ملء الحقل المطلوب: ${getFieldLabel(field)}`);
            return;
        }
    }
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.contact_email)) {
        alert('الرجاء إدخال بريد إلكتروني صحيح');
        return;
    }
    
    // Validate dates
    const registrationDate = new Date(data.registration_date);
    const issueDate = new Date(data.license_issue_date);
    const expiryDate = new Date(data.license_expiry_date);
    
    if (issueDate < registrationDate) {
        alert('تاريخ إصدار الترخيص يجب أن يكون بعد تاريخ التسجيل');
        return;
    }
    
    if (expiryDate <= issueDate) {
        alert('تاريخ انتهاء الترخيص يجب أن يكون بعد تاريخ الإصدار');
        return;
    }
    
    // Show loading message
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    submitBtn.disabled = true;

    // Send data to server
    fetch(event.target.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else if (response.status === 422) {
            return response.json().then(errorData => {
                throw { response: { json: () => Promise.resolve(errorData) } };
            });
        }
        throw new Error('Network response was not ok');
    })
    .then(data => {
        // Show success message
        alert('✅ تم حفظ بيانات الشركة بنجاح!');

        // Redirect to companies list
        window.location.href = '{{ route("tenant.inventory.regulatory.companies.index") }}';
    })
    .catch(error => {
        console.error('Error:', error);

        // Try to parse error response
        if (error.response) {
            error.response.json().then(errorData => {
                if (errorData.errors) {
                    let errorMessage = 'أخطاء في البيانات:\n';
                    Object.keys(errorData.errors).forEach(field => {
                        errorMessage += `- ${errorData.errors[field][0]}\n`;
                    });
                    alert('❌ ' + errorMessage);
                } else {
                    alert('❌ ' + (errorData.message || 'حدث خطأ أثناء حفظ البيانات'));
                }
            }).catch(() => {
                alert('❌ حدث خطأ أثناء حفظ البيانات. الرجاء المحاولة مرة أخرى.');
            });
        } else {
            alert('❌ حدث خطأ أثناء حفظ البيانات. الرجاء المحاولة مرة أخرى.');
        }

        // Restore button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
        document.getElementById('companyForm').reset();
        alert('تم إعادة تعيين النموذج');
    }
}

function getFieldLabel(field) {
    const labels = {
        'company_name': 'اسم الشركة',
        'registration_number': 'رقم التسجيل',
        'license_number': 'رقم الترخيص',
        'license_type': 'نوع الترخيص',
        'regulatory_authority': 'الجهة التنظيمية',
        'registration_date': 'تاريخ التسجيل',
        'license_issue_date': 'تاريخ إصدار الترخيص',
        'license_expiry_date': 'تاريخ انتهاء الترخيص',
        'compliance_status': 'حالة الامتثال',
        'company_address': 'عنوان الشركة',
        'contact_person': 'الشخص المسؤول',
        'contact_email': 'البريد الإلكتروني',
        'contact_phone': 'رقم الهاتف'
    };
    return labels[field] || field;
}
</script>

@endsection
