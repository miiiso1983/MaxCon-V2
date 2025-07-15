@extends('layouts.modern')

@section('title', 'جدولة التفتيشات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">جدولة التفتيشات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">جدولة وتنظيم التفتيشات التنظيمية القادمة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}" style="background: rgba(255,255,255,0.2); color: #f093fb; padding: 15px 25px; border: 2px solid #f093fb; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للتفتيشات
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- Schedule New Inspection -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="showScheduleModal()"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-plus"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">جدولة تفتيش جديد</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">إضافة تفتيش جديد للجدولة</p>
        </div>

        <!-- Calendar View -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="showCalendarView()"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">عرض التقويم</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">عرض التفتيشات في التقويم</p>
        </div>

        <!-- Upcoming Inspections -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="showUpcomingInspections()"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-clock"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">التفتيشات القادمة</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">عرض التفتيشات المجدولة</p>
        </div>

        <!-- Overdue Inspections -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;"
             onclick="showOverdueInspections()"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">التفتيشات المتأخرة</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">التفتيشات المتجاوزة للموعد</p>
        </div>
    </div>

    <!-- Schedule Form -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
            <i class="fas fa-calendar-plus" style="margin-left: 10px; color: #f093fb;"></i>
            جدولة تفتيش جديد
        </h3>

        <form id="scheduleForm" onsubmit="scheduleInspection(event)">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                
                <!-- Inspection Title -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-search" style="margin-left: 8px; color: #f093fb;"></i>
                        عنوان التفتيش *
                    </label>
                    <input type="text" name="inspection_title" required 
                           style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="مثل: تفتيش دوري للجودة"
                           onfocus="this.style.borderColor='#f093fb'" 
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <!-- Inspection Type -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-tags" style="margin-left: 8px; color: #f093fb;"></i>
                        نوع التفتيش *
                    </label>
                    <select name="inspection_type" required 
                            style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#f093fb'" 
                            onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">اختر نوع التفتيش</option>
                        <option value="routine">روتيني</option>
                        <option value="complaint">شكوى</option>
                        <option value="follow_up">متابعة</option>
                        <option value="pre_approval">ما قبل الموافقة</option>
                        <option value="post_market">ما بعد التسويق</option>
                    </select>
                </div>

                <!-- Inspector Name -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-user-tie" style="margin-left: 8px; color: #f093fb;"></i>
                        اسم المفتش *
                    </label>
                    <input type="text" name="inspector_name" required 
                           style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="مثل: د. أحمد محمد"
                           onfocus="this.style.borderColor='#f093fb'" 
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <!-- Inspection Authority -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-university" style="margin-left: 8px; color: #f093fb;"></i>
                        الجهة المفتشة *
                    </label>
                    <input type="text" name="inspection_authority" required 
                           style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="مثل: وزارة الصحة العراقية"
                           onfocus="this.style.borderColor='#f093fb'" 
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <!-- Scheduled Date -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-calendar-plus" style="margin-left: 8px; color: #f093fb;"></i>
                        التاريخ المجدول *
                    </label>
                    <input type="date" name="scheduled_date" required 
                           style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                           onfocus="this.style.borderColor='#f093fb'" 
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <!-- Scheduled Time -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-clock" style="margin-left: 8px; color: #f093fb;"></i>
                        الوقت المجدول
                    </label>
                    <input type="time" name="scheduled_time" 
                           style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                           onfocus="this.style.borderColor='#f093fb'" 
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <!-- Facility Name -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-industry" style="margin-left: 8px; color: #f093fb;"></i>
                        اسم المنشأة *
                    </label>
                    <input type="text" name="facility_name" required 
                           style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                           placeholder="مثل: مصنع الأدوية المتقدمة"
                           onfocus="this.style.borderColor='#f093fb'" 
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <!-- Priority -->
                <div>
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                        <i class="fas fa-star" style="margin-left: 8px; color: #f093fb;"></i>
                        الأولوية
                    </label>
                    <select name="priority" 
                            style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#f093fb'" 
                            onblur="this.style.borderColor='#e2e8f0'">
                        <option value="normal">عادي</option>
                        <option value="high">عالي</option>
                        <option value="urgent">عاجل</option>
                    </select>
                </div>
            </div>

            <!-- Facility Address -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-map-marker-alt" style="margin-left: 8px; color: #f093fb;"></i>
                    عنوان المنشأة *
                </label>
                <textarea name="facility_address" rows="3" required
                          style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                          placeholder="العنوان الكامل للمنشأة..."
                          onfocus="this.style.borderColor='#f093fb'" 
                          onblur="this.style.borderColor='#e2e8f0'"></textarea>
            </div>

            <!-- Scope of Inspection -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-list-alt" style="margin-left: 8px; color: #f093fb;"></i>
                    نطاق التفتيش
                </label>
                <textarea name="scope_of_inspection" rows="3"
                          style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                          placeholder="وصف نطاق ومجال التفتيش..."
                          onfocus="this.style.borderColor='#f093fb'" 
                          onblur="this.style.borderColor='#e2e8f0'"></textarea>
            </div>

            <!-- Notes -->
            <div style="margin-bottom: 30px;">
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-sticky-note" style="margin-left: 8px; color: #f093fb;"></i>
                    ملاحظات إضافية
                </label>
                <textarea name="notes" rows="3"
                          style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; transition: border-color 0.3s; resize: vertical;"
                          placeholder="أي ملاحظات إضافية..."
                          onfocus="this.style.borderColor='#f093fb'" 
                          onblur="this.style.borderColor='#e2e8f0'"></textarea>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; gap: 15px; justify-content: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                <button type="submit" 
                        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 40px; border: none; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-calendar-plus"></i>
                    جدولة التفتيش
                </button>
                
                <button type="button" onclick="resetScheduleForm()" 
                        style="background: rgba(240, 147, 251, 0.1); color: #f093fb; padding: 15px 40px; border: 2px solid #f093fb; border-radius: 15px; font-weight: 600; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s;"
                        onmouseover="this.style.transform='translateY(-2px)'" 
                        onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showScheduleModal() {
    // Scroll to the form
    document.getElementById('scheduleForm').scrollIntoView({ behavior: 'smooth' });
}

function showCalendarView() {
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.calendar") }}';
}

function showUpcomingInspections() {
    // Redirect to inspections page with filter
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.index") }}?filter=upcoming';
}

function showOverdueInspections() {
    // Redirect to inspections page with filter
    window.location.href = '{{ route("tenant.inventory.regulatory.inspections.index") }}?filter=overdue';
}

function scheduleInspection(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Validate required fields
    if (!data.inspection_title || !data.inspection_type || !data.inspector_name || 
        !data.inspection_authority || !data.scheduled_date || !data.facility_name || !data.facility_address) {
        alert('يرجى ملء جميع الحقول المطلوبة');
        return;
    }
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الجدولة...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Redirect to create page with pre-filled data
        const params = new URLSearchParams();
        Object.keys(data).forEach(key => {
            if (data[key]) {
                params.append(key, data[key]);
            }
        });
        
        // Set status to scheduled
        params.append('inspection_status', 'scheduled');
        
        window.location.href = '{{ route("tenant.inventory.regulatory.inspections.create") }}?' + params.toString();
    }, 1000);
}

function resetScheduleForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
        document.getElementById('scheduleForm').reset();
        alert('تم إعادة تعيين النموذج');
    }
}

// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.querySelector('input[name="scheduled_date"]');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
    }
});
</script>

@endsection
