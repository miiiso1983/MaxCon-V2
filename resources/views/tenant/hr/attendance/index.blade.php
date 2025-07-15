@extends('layouts.modern')

@section('title', 'الحضور والانصراف')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">الحضور والانصراف</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تسجيل ومتابعة حضور الموظفين</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="checkIn()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-sign-in-alt"></i>
                    تسجيل دخول
                </button>
                <button onclick="checkOut()" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    تسجيل خروج
                </button>
            </div>
        </div>
    </div>

    <!-- Today's Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-user-check"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">22</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">حاضرون اليوم</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-user-times"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">2</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">غائبون اليوم</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-clock"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">3</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">متأخرون</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-percentage"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">92%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">نسبة الحضور</p>
        </div>
    </div>

    <!-- Quick Check-in/out -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
            <i class="fas fa-stopwatch" style="margin-left: 10px; color: #4299e1;"></i>
            تسجيل سريع
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            
            <!-- Check In -->
            <div style="text-align: center; padding: 30px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; color: white;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h4 style="margin: 0 0 15px 0; font-size: 24px; font-weight: 700;">تسجيل الدخول</h4>
                <p style="margin: 0 0 20px 0; opacity: 0.9;">سجل حضورك لبدء يوم العمل</p>
                <div style="background: rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                    <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;" id="current-time">{{ now()->format('H:i') }}</div>
                    <div style="opacity: 0.9;">{{ now()->format('Y-m-d') }}</div>
                </div>
                <button onclick="checkIn()" style="background: white; color: #48bb78; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; width: 100%;">
                    تسجيل الدخول الآن
                </button>
            </div>

            <!-- Check Out -->
            <div style="text-align: center; padding: 30px; background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 20px; color: white;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h4 style="margin: 0 0 15px 0; font-size: 24px; font-weight: 700;">تسجيل الخروج</h4>
                <p style="margin: 0 0 20px 0; opacity: 0.9;">سجل انصرافك عند انتهاء يوم العمل</p>
                <div style="background: rgba(255,255,255,0.2); border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                    <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">ساعات العمل اليوم</div>
                    <div style="font-size: 32px; font-weight: 700; opacity: 0.9;">8:30</div>
                </div>
                <button onclick="checkOut()" style="background: white; color: #f56565; padding: 15px 30px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; width: 100%;">
                    تسجيل الخروج الآن
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-history" style="margin-left: 10px; color: #4299e1;"></i>
                سجل الحضور الأخير
            </h3>
            <div style="display: flex; gap: 10px;">
                <button onclick="generateAttendanceReport()" style="background: #ed8936; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                    <i class="fas fa-chart-bar"></i> التقارير
                </button>
                <button onclick="exportAttendanceData()" style="background: #48bb78; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                    <i class="fas fa-download"></i> تصدير
                </button>
            </div>
        </div>

        <!-- Sample Attendance Records -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f7fafc;">
                        <th style="padding: 15px; text-align: right; border-bottom: 2px solid #e2e8f0; color: #2d3748; font-weight: 700;">الموظف</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e2e8f0; color: #2d3748; font-weight: 700;">التاريخ</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e2e8f0; color: #2d3748; font-weight: 700;">وقت الدخول</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e2e8f0; color: #2d3748; font-weight: 700;">وقت الخروج</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e2e8f0; color: #2d3748; font-weight: 700;">ساعات العمل</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #e2e8f0; color: #2d3748; font-weight: 700;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; color: #2d3748; font-weight: 600;">أحمد محمد</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">{{ now()->format('Y-m-d') }}</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">08:00</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">17:00</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568; font-weight: 600;">9:00</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">حاضر</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; color: #2d3748; font-weight: 600;">سارة أحمد</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">{{ now()->format('Y-m-d') }}</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">08:15</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">17:00</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568; font-weight: 600;">8:45</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">متأخر</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; color: #2d3748; font-weight: 600;">محمد علي</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">{{ now()->format('Y-m-d') }}</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">07:45</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">16:45</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568; font-weight: 600;">9:00</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">حاضر</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; color: #2d3748; font-weight: 600;">فاطمة حسن</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">{{ now()->subDay()->format('Y-m-d') }}</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">-</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">-</td>
                        <td style="padding: 15px; text-align: center; color: #4a5568; font-weight: 600;">0:00</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">غائب</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                
                <button onclick="addAttendanceRecord()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-plus"></i>
                    إضافة سجل حضور
                </button>

                <button onclick="generateAttendanceReport()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-chart-bar"></i>
                    تقارير الحضور
                </button>

                <button onclick="exportAttendanceData()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </button>

                <a href="{{ route('tenant.hr.shifts.index') }}" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-clock"></i>
                    إدارة المناوبات
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function checkIn() {
    const now = new Date();
    const time = now.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });

    if (confirm(`هل تريد تسجيل الدخول الآن؟\nالوقت: ${time}`)) {
        // Show loading state
        const button = event.target;
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التسجيل...';
        button.disabled = true;

        // Simulate API call
        setTimeout(() => {
            alert(`تم تسجيل الدخول بنجاح!\nالوقت: ${time}\nمرحباً بك في يوم عمل جديد`);

            // Reset button
            button.innerHTML = originalContent;
            button.disabled = false;

            // Show success notification
            showNotification('تم تسجيل الدخول بنجاح!', 'success');
        }, 1500);
    }
}

function checkOut() {
    const now = new Date();
    const time = now.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });

    if (confirm(`هل تريد تسجيل الخروج الآن؟\nالوقت: ${time}`)) {
        // Show loading state
        const button = event.target;
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التسجيل...';
        button.disabled = true;

        // Simulate API call
        setTimeout(() => {
            alert(`تم تسجيل الخروج بنجاح!\nالوقت: ${time}\nشكراً لك على عملك اليوم`);

            // Reset button
            button.innerHTML = originalContent;
            button.disabled = false;

            // Show success notification
            showNotification('تم تسجيل الخروج بنجاح!', 'success');
        }, 1500);
    }
}

// Update current time every second
setInterval(function() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('ar-EG', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: false 
    });
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}, 1000);

function addAttendanceRecord() {
    // Create modal for adding attendance record
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    `;

    modal.innerHTML = `
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%;">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-plus" style="margin-left: 10px; color: #48bb78;"></i>
                إضافة سجل حضور
            </h3>

            <form id="attendanceForm">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الموظف</label>
                    <select required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                        <option value="">اختر الموظف</option>
                        <option value="1">أحمد محمد</option>
                        <option value="2">سارة أحمد</option>
                        <option value="3">محمد علي</option>
                        <option value="4">فاطمة حسن</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">التاريخ</label>
                    <input type="date" required value="${new Date().toISOString().split('T')[0]}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وقت الدخول</label>
                        <input type="time" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وقت الخروج</label>
                        <input type="time" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">ملاحظات</label>
                    <textarea rows="3" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; resize: vertical;" placeholder="أدخل أي ملاحظات إضافية"></textarea>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-save"></i> حفظ السجل
                    </button>
                    <button type="button" onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </form>
        </div>
    `;

    modal.className = 'modal';
    document.body.appendChild(modal);

    // Handle form submission
    modal.querySelector('#attendanceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;

        setTimeout(() => {
            alert('تم إضافة سجل الحضور بنجاح!');
            modal.remove();
            showNotification('تم إضافة سجل الحضور بنجاح!', 'success');
        }, 1500);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function generateAttendanceReport() {
    // Show loading state
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري إنشاء التقرير...';
    button.disabled = true;

    // Simulate report generation
    setTimeout(() => {
        alert('تم إنشاء تقرير الحضور بنجاح!\n\nيتضمن التقرير:\n• إحصائيات شهرية ومفصلة\n• رسوم بيانية للحضور\n• مقارنة الأداء\n• تحليل أنماط الحضور');

        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;

        showNotification('تم إنشاء التقرير بنجاح!', 'success');
    }, 2000);
}

function exportAttendanceData() {
    // Show loading state
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
    button.disabled = true;

    // Simulate export process
    setTimeout(() => {
        alert('تم تصدير بيانات الحضور بنجاح!\n\nتم تصدير:\n• سجل الحضور والانصراف\n• الإحصائيات الشهرية\n• تفاصيل الساعات والحالات\n• الملاحظات والتبريرات');

        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;

        showNotification('تم تصدير البيانات بنجاح!', 'success');
    }, 2000);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#48bb78' : type === 'error' ? '#f56565' : '#4299e1'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 10000;
        font-weight: 600;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;

    // Add animation keyframes
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>

@endsection
