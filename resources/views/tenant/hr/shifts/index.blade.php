@extends('layouts.modern')

@section('title', 'إدارة المناوبات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة المناوبات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تنظيم وإدارة مناوبات العمل للموظفين</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="addNewShift()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    إضافة مناوبة جديدة
                </button>
                <a href="{{ route('tenant.hr.attendance.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للحضور
                </a>
            </div>
        </div>
    </div>

    <!-- Shifts Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-sun"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">3</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المناوبات النهارية</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-moon"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">2</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المناوبات الليلية</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">45</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">الموظفين المعينين</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-calendar-week"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">7</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">أيام الأسبوع</p>
        </div>
    </div>

    <!-- Shifts List -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-list" style="margin-left: 10px; color: #9f7aea;"></i>
            قائمة المناوبات
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            
            <!-- Morning Shift -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; border-right: 4px solid #48bb78;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 20px; font-weight: 700;">المناوبة الصباحية</h4>
                    <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">نشطة</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">وقت البداية</span>
                        <span style="color: #2d3748; font-weight: 600;">08:00 ص</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">وقت النهاية</span>
                        <span style="color: #2d3748; font-weight: 600;">04:00 م</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الساعات</span>
                        <span style="color: #2d3748; font-weight: 600;">8 ساعات</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الموظفين</span>
                        <span style="color: #2d3748; font-weight: 600;">20 موظف</span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button onclick="editShift(1)" style="background: #ed8936; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; flex: 1;">
                        <i class="fas fa-edit"></i> تعديل
                    </button>
                    <button onclick="viewShiftEmployees(1)" style="background: #4299e1; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; flex: 1;">
                        <i class="fas fa-users"></i> الموظفين
                    </button>
                </div>
            </div>

            <!-- Evening Shift -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; border-right: 4px solid #4299e1;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 20px; font-weight: 700;">المناوبة المسائية</h4>
                    <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">نشطة</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">وقت البداية</span>
                        <span style="color: #2d3748; font-weight: 600;">04:00 م</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">وقت النهاية</span>
                        <span style="color: #2d3748; font-weight: 600;">12:00 ص</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الساعات</span>
                        <span style="color: #2d3748; font-weight: 600;">8 ساعات</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الموظفين</span>
                        <span style="color: #2d3748; font-weight: 600;">15 موظف</span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button onclick="editShift(2)" style="background: #ed8936; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; flex: 1;">
                        <i class="fas fa-edit"></i> تعديل
                    </button>
                    <button onclick="viewShiftEmployees(2)" style="background: #4299e1; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; flex: 1;">
                        <i class="fas fa-users"></i> الموظفين
                    </button>
                </div>
            </div>

            <!-- Night Shift -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; border-right: 4px solid #9f7aea;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 20px; font-weight: 700;">المناوبة الليلية</h4>
                    <span style="background: #9f7aea; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">نشطة</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">وقت البداية</span>
                        <span style="color: #2d3748; font-weight: 600;">12:00 ص</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">وقت النهاية</span>
                        <span style="color: #2d3748; font-weight: 600;">08:00 ص</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الساعات</span>
                        <span style="color: #2d3748; font-weight: 600;">8 ساعات</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الموظفين</span>
                        <span style="color: #2d3748; font-weight: 600;">10 موظف</span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button onclick="editShift(3)" style="background: #ed8936; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; flex: 1;">
                        <i class="fas fa-edit"></i> تعديل
                    </button>
                    <button onclick="viewShiftEmployees(3)" style="background: #4299e1; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; flex: 1;">
                        <i class="fas fa-users"></i> الموظفين
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                
                <button onclick="addNewShift()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-plus"></i>
                    إضافة مناوبة جديدة
                </button>

                <button onclick="generateShiftReport()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-chart-bar"></i>
                    تقرير المناوبات
                </button>

                <button onclick="exportShiftData()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </button>

                <button onclick="manageShiftSchedule()" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-calendar-alt"></i>
                    جدولة المناوبات
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function addNewShift() {
    // Redirect to create page instead of modal
    window.location.href = '{{ route("tenant.hr.shifts.create") }}';
}

function editShift(shiftId) {
    // Redirect to edit page
    window.location.href = `/tenant/hr/shifts/${shiftId}/edit`;
}

function viewShiftEmployees(shiftId) {
    const shiftNames = {1: 'المناوبة الصباحية', 2: 'المناوبة المسائية', 3: 'المناوبة الليلية'};
    const employees = {
        1: [
            {name: 'أحمد محمد', position: 'مشرف', status: 'نشط', attendance: '95%'},
            {name: 'سارة أحمد', position: 'موظف', status: 'نشط', attendance: '98%'},
            {name: 'محمد علي', position: 'موظف', status: 'نشط', attendance: '92%'},
            {name: 'فاطمة حسن', position: 'موظف', status: 'إجازة', attendance: '88%'},
            {name: 'عمر خالد', position: 'موظف', status: 'نشط', attendance: '96%'}
        ],
        2: [
            {name: 'ليلى حسن', position: 'مشرف', status: 'نشط', attendance: '97%'},
            {name: 'كريم محمد', position: 'موظف', status: 'نشط', attendance: '94%'},
            {name: 'نور أحمد', position: 'موظف', status: 'نشط', attendance: '91%'}
        ],
        3: [
            {name: 'يوسف علي', position: 'مشرف', status: 'نشط', attendance: '93%'},
            {name: 'حسام محمد', position: 'موظف', status: 'نشط', attendance: '89%'}
        ]
    };

    // Create modal for viewing shift employees
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

    const employeeList = employees[shiftId].map(emp => `
        <div style="background: #f7fafc; border-radius: 10px; padding: 15px; margin-bottom: 10px; display: flex; justify-content: between; align-items: center;">
            <div style="flex: 1;">
                <div style="font-weight: 700; color: #2d3748; margin-bottom: 5px;">${emp.name}</div>
                <div style="font-size: 14px; color: #4a5568;">${emp.position}</div>
            </div>
            <div style="text-align: center; margin: 0 15px;">
                <div style="font-size: 12px; color: #4a5568; margin-bottom: 3px;">نسبة الحضور</div>
                <div style="font-weight: 700; color: #48bb78;">${emp.attendance}</div>
            </div>
            <div>
                <span style="background: ${emp.status === 'نشط' ? '#48bb78' : emp.status === 'إجازة' ? '#ed8936' : '#f56565'}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    ${emp.status}
                </span>
            </div>
        </div>
    `).join('');

    modal.innerHTML = `
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 700px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-users" style="margin-left: 10px; color: #4299e1;"></i>
                موظفو ${shiftNames[shiftId]}
            </h3>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 25px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px; text-align: center;">
                    <div>
                        <div style="color: #4299e1; font-size: 24px; font-weight: 700;">${employees[shiftId].length}</div>
                        <div style="color: #4a5568; font-size: 12px;">إجمالي الموظفين</div>
                    </div>
                    <div>
                        <div style="color: #48bb78; font-size: 24px; font-weight: 700;">${employees[shiftId].filter(e => e.status === 'نشط').length}</div>
                        <div style="color: #4a5568; font-size: 12px;">نشط</div>
                    </div>
                    <div>
                        <div style="color: #ed8936; font-size: 24px; font-weight: 700;">${employees[shiftId].filter(e => e.status === 'إجازة').length}</div>
                        <div style="color: #4a5568; font-size: 12px;">في إجازة</div>
                    </div>
                    <div>
                        <div style="color: #48bb78; font-size: 24px; font-weight: 700;">${Math.round(employees[shiftId].reduce((sum, e) => sum + parseInt(e.attendance), 0) / employees[shiftId].length)}%</div>
                        <div style="color: #4a5568; font-size: 12px;">متوسط الحضور</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">قائمة الموظفين</h4>
                ${employeeList}
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="addEmployeeToShift(${shiftId})" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-user-plus"></i> إضافة موظف
                </button>
                <button onclick="exportEmployeeList(${shiftId})" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-download"></i> تصدير القائمة
                </button>
                <button onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-times"></i> إغلاق
                </button>
            </div>
        </div>
    `;

    modal.className = 'modal';
    document.body.appendChild(modal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function generateShiftReport() {
    // Show loading state
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري إنشاء التقرير...';
    button.disabled = true;

    // Simulate report generation
    setTimeout(() => {
        // Create report modal
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
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 900px; width: 90%; max-height: 80vh; overflow-y: auto;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                    <i class="fas fa-chart-bar" style="margin-left: 10px; color: #ed8936;"></i>
                    تقرير المناوبات الشامل
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                    <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #48bb78;">
                        <div style="color: #48bb78; font-size: 28px; font-weight: 700; margin-bottom: 5px;">3</div>
                        <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي المناوبات</div>
                        <div style="color: #48bb78; font-size: 12px; margin-top: 5px;">+0% من الشهر الماضي</div>
                    </div>

                    <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #4299e1;">
                        <div style="color: #4299e1; font-size: 28px; font-weight: 700; margin-bottom: 5px;">45</div>
                        <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي الموظفين</div>
                        <div style="color: #4299e1; font-size: 12px; margin-top: 5px;">+8% من الشهر الماضي</div>
                    </div>

                    <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #ed8936;">
                        <div style="color: #ed8936; font-size: 28px; font-weight: 700; margin-bottom: 5px;">94%</div>
                        <div style="color: #4a5568; font-size: 14px; font-weight: 600;">متوسط الحضور</div>
                        <div style="color: #ed8936; font-size: 12px; margin-top: 5px;">+3% من الشهر الماضي</div>
                    </div>

                    <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #9f7aea;">
                        <div style="color: #9f7aea; font-size: 28px; font-weight: 700; margin-bottom: 5px;">1,080</div>
                        <div style="color: #4a5568; font-size: 14px; font-weight: 600;">ساعات العمل الشهرية</div>
                        <div style="color: #9f7aea; font-size: 12px; margin-top: 5px;">+5% من الشهر الماضي</div>
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
                    <h4 style="color: #2d3748; margin: 0 0 20px 0; font-size: 18px; font-weight: 700;">أداء المناوبات</h4>

                    <div style="display: grid; gap: 15px;">
                        <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                            <div>
                                <div style="font-weight: 700; color: #2d3748;">المناوبة الصباحية</div>
                                <div style="font-size: 14px; color: #4a5568;">08:00 - 16:00 | 20 موظف</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="color: #48bb78; font-size: 18px; font-weight: 700;">95%</div>
                                <div style="font-size: 12px; color: #4a5568;">نسبة الحضور</div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                            <div>
                                <div style="font-weight: 700; color: #2d3748;">المناوبة المسائية</div>
                                <div style="font-size: 14px; color: #4a5568;">16:00 - 00:00 | 15 موظف</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="color: #4299e1; font-size: 18px; font-weight: 700;">92%</div>
                                <div style="font-size: 12px; color: #4a5568;">نسبة الحضور</div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                            <div>
                                <div style="font-weight: 700; color: #2d3748;">المناوبة الليلية</div>
                                <div style="font-size: 14px; color: #4a5568;">00:00 - 08:00 | 10 موظف</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="color: #9f7aea; font-size: 18px; font-weight: 700;">91%</div>
                                <div style="font-size: 12px; color: #4a5568;">نسبة الحضور</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button onclick="alert('تم تصدير التقرير بصيغة PDF بنجاح!')" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-file-pdf"></i> تصدير PDF
                    </button>
                    <button onclick="alert('تم تصدير التقرير بصيغة Excel بنجاح!')" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-file-excel"></i> تصدير Excel
                    </button>
                    <button onclick="alert('تم إرسال التقرير بالبريد الإلكتروني!')" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-envelope"></i> إرسال بالبريد
                    </button>
                    <button onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-times"></i> إغلاق
                    </button>
                </div>
            </div>
        `;

        modal.className = 'modal';
        document.body.appendChild(modal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });

        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;

        showNotification('تم إنشاء تقرير المناوبات بنجاح!', 'success');
    }, 2000);
}

function exportShiftData() {
    // Show loading state
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
    button.disabled = true;

    // Simulate export process
    setTimeout(() => {
        alert('تم تصدير بيانات المناوبات بنجاح!\n\nتم تصدير:\n• جداول المناوبات الكاملة\n• قوائم الموظفين لكل مناوبة\n• إحصائيات الحضور والأداء\n• التقارير التفصيلية');

        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;

        showNotification('تم تصدير البيانات بنجاح!', 'success');
    }, 2000);
}

function manageShiftSchedule() {
    // Create modal for shift scheduling
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
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 800px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-calendar-alt" style="margin-left: 10px; color: #9f7aea;"></i>
                جدولة المناوبات
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 25px;">
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center;">
                    <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">الجدولة الأسبوعية</h4>
                    <button onclick="createWeeklySchedule()" style="background: #48bb78; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; width: 100%;">
                        إنشاء جدول أسبوعي
                    </button>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center;">
                    <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">الجدولة الشهرية</h4>
                    <button onclick="createMonthlySchedule()" style="background: #4299e1; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; width: 100%;">
                        إنشاء جدول شهري
                    </button>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center;">
                    <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">الجدولة التلقائية</h4>
                    <button onclick="autoSchedule()" style="background: #ed8936; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; width: 100%;">
                        جدولة تلقائية
                    </button>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 25px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">الجدول الحالي - الأسبوع الحالي</h4>

                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; text-align: center;">
                    <div style="font-weight: 700; color: #2d3748; padding: 10px; background: white; border-radius: 8px;">الأحد</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px; background: white; border-radius: 8px;">الاثنين</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px; background: white; border-radius: 8px;">الثلاثاء</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px; background: white; border-radius: 8px;">الأربعاء</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px; background: white; border-radius: 8px;">الخميس</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px; background: white; border-radius: 8px;">الجمعة</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px; background: white; border-radius: 8px;">السبت</div>

                    <div style="padding: 8px; background: white; border-radius: 8px; font-size: 12px;">
                        <div style="color: #48bb78; font-weight: 600;">صباحية</div>
                        <div style="color: #4a5568;">20 موظف</div>
                    </div>
                    <div style="padding: 8px; background: white; border-radius: 8px; font-size: 12px;">
                        <div style="color: #48bb78; font-weight: 600;">صباحية</div>
                        <div style="color: #4a5568;">20 موظف</div>
                    </div>
                    <div style="padding: 8px; background: white; border-radius: 8px; font-size: 12px;">
                        <div style="color: #4299e1; font-weight: 600;">مسائية</div>
                        <div style="color: #4a5568;">15 موظف</div>
                    </div>
                    <div style="padding: 8px; background: white; border-radius: 8px; font-size: 12px;">
                        <div style="color: #9f7aea; font-weight: 600;">ليلية</div>
                        <div style="color: #4a5568;">10 موظف</div>
                    </div>
                    <div style="padding: 8px; background: white; border-radius: 8px; font-size: 12px;">
                        <div style="color: #48bb78; font-weight: 600;">صباحية</div>
                        <div style="color: #4a5568;">20 موظف</div>
                    </div>
                    <div style="padding: 8px; background: white; border-radius: 8px; font-size: 12px;">
                        <div style="color: #4a5568; font-weight: 600;">عطلة</div>
                        <div style="color: #4a5568;">-</div>
                    </div>
                    <div style="padding: 8px; background: white; border-radius: 8px; font-size: 12px;">
                        <div style="color: #4a5568; font-weight: 600;">عطلة</div>
                        <div style="color: #4a5568;">-</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="saveSchedule()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-save"></i> حفظ الجدول
                </button>
                <button onclick="exportSchedule()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-download"></i> تصدير الجدول
                </button>
                <button onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-times"></i> إغلاق
                </button>
            </div>
        </div>
    `;

    modal.className = 'modal';
    document.body.appendChild(modal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function addEmployeeToShift(shiftId) {
    alert(`ميزة إضافة موظف للمناوبة رقم ${shiftId} قيد التطوير\n\nستتيح:\n• اختيار موظف من القائمة\n• تحديد تاريخ البداية\n• إعداد القواعد الخاصة\n• إرسال إشعار للموظف`);
}

function exportEmployeeList(shiftId) {
    alert(`تم تصدير قائمة موظفي المناوبة رقم ${shiftId} بنجاح!\n\nتم تصدير:\n• أسماء ومعلومات الموظفين\n• إحصائيات الحضور\n• تفاصيل المناصب\n• معلومات الاتصال`);
}

function createWeeklySchedule() {
    alert('ميزة إنشاء الجدول الأسبوعي قيد التطوير\n\nستتيح:\n• توزيع المناوبات على الأسبوع\n• تعيين الموظفين تلقائياً\n• مراعاة الإجازات والعطل\n• حفظ وتطبيق الجدول');
}

function createMonthlySchedule() {
    alert('ميزة إنشاء الجدول الشهري قيد التطوير\n\nستتيح:\n• تخطيط المناوبات للشهر كاملاً\n• توزيع عادل للموظفين\n• إدارة الإجازات والعطل\n• تصدير وطباعة الجدول');
}

function autoSchedule() {
    alert('ميزة الجدولة التلقائية قيد التطوير\n\nستقوم بـ:\n• توزيع المناوبات تلقائياً\n• مراعاة تفضيلات الموظفين\n• تحقيق التوازن في التوزيع\n• تجنب التعارضات');
}

function saveSchedule() {
    alert('تم حفظ الجدول بنجاح!\n\nتم حفظ:\n• جدول المناوبات الحالي\n• تعيينات الموظفين\n• الإعدادات والقواعد\n• التواريخ والأوقات');
}

function exportSchedule() {
    alert('تم تصدير الجدول بنجاح!\n\nتم تصدير:\n• جدول المناوبات الكامل\n• قوائم الموظفين\n• التواريخ والأوقات\n• الملاحظات والتعليقات');
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
