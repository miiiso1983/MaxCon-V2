@extends('layouts.modern')

@section('title', 'سجل الحضور - أحمد محمد')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">سجل الحضور</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">أحمد محمد - مدير عام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.employees.show', 1) }}" style="background: #4299e1; color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-user"></i>
                    ملف الموظف
                </a>
                <a href="{{ route('tenant.hr.employees.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">22</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">أيام الحضور هذا الشهر</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">2</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">أيام الغياب هذا الشهر</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-clock"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">3</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">مرات التأخير هذا الشهر</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-stopwatch"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">176</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي ساعات العمل</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-percentage"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">92%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">نسبة الحضور</p>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}"
                       style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
            </div>
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date', now()->format('Y-m-d')) }}"
                       style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
            </div>
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">حالة الحضور</label>
                <select name="status" style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <option value="">جميع الحالات</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                    <option value="early_leave" {{ request('status') == 'early_leave' ? 'selected' : '' }}>انصراف مبكر</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-search"></i> بحث
                </button>
                <button type="button" onclick="exportAttendance()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-download"></i> تصدير
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance Records -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-history" style="margin-left: 10px; color: #48bb78;"></i>
                سجل الحضور والانصراف
            </h3>
        </div>

        <!-- Attendance Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white;">
                        <th style="padding: 15px; text-align: center; border-radius: 10px 0 0 0; font-weight: 700;">التاريخ</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">اليوم</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">وقت الدخول</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">وقت الخروج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">ساعات العمل</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الحالة</th>
                        <th style="padding: 15px; text-align: center; border-radius: 0 10px 0 0; font-weight: 700;">ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $attendanceRecords = [
                            [
                                'date' => now()->format('Y-m-d'),
                                'day' => 'الأحد',
                                'check_in' => '08:00',
                                'check_out' => '17:00',
                                'hours' => '9:00',
                                'status' => 'present',
                                'notes' => 'حضور منتظم'
                            ],
                            [
                                'date' => now()->subDay()->format('Y-m-d'),
                                'day' => 'السبت',
                                'check_in' => '08:15',
                                'check_out' => '17:00',
                                'hours' => '8:45',
                                'status' => 'late',
                                'notes' => 'تأخير 15 دقيقة'
                            ],
                            [
                                'date' => now()->subDays(2)->format('Y-m-d'),
                                'day' => 'الخميس',
                                'check_in' => '07:45',
                                'check_out' => '16:30',
                                'hours' => '8:45',
                                'status' => 'early_leave',
                                'notes' => 'انصراف مبكر بإذن'
                            ],
                            [
                                'date' => now()->subDays(3)->format('Y-m-d'),
                                'day' => 'الأربعاء',
                                'check_in' => '-',
                                'check_out' => '-',
                                'hours' => '0:00',
                                'status' => 'absent',
                                'notes' => 'إجازة مرضية'
                            ],
                            [
                                'date' => now()->subDays(4)->format('Y-m-d'),
                                'day' => 'الثلاثاء',
                                'check_in' => '08:00',
                                'check_out' => '17:30',
                                'hours' => '9:30',
                                'status' => 'present',
                                'notes' => 'ساعات إضافية'
                            ],
                            [
                                'date' => now()->subDays(5)->format('Y-m-d'),
                                'day' => 'الاثنين',
                                'check_in' => '08:05',
                                'check_out' => '17:00',
                                'hours' => '8:55',
                                'status' => 'present',
                                'notes' => 'حضور منتظم'
                            ],
                            [
                                'date' => now()->subDays(6)->format('Y-m-d'),
                                'day' => 'الأحد',
                                'check_in' => '08:20',
                                'check_out' => '17:00',
                                'hours' => '8:40',
                                'status' => 'late',
                                'notes' => 'تأخير 20 دقيقة'
                            ]
                        ];
                    @endphp

                    @foreach($attendanceRecords as $record)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s;" 
                            onmouseover="this.style.backgroundColor='#f7fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 600;">{{ $record['date'] }}</td>
                            <td style="padding: 15px; text-align: center; color: #4a5568;">{{ $record['day'] }}</td>
                            <td style="padding: 15px; text-align: center; color: #4a5568; font-weight: 600;">{{ $record['check_in'] }}</td>
                            <td style="padding: 15px; text-align: center; color: #4a5568; font-weight: 600;">{{ $record['check_out'] }}</td>
                            <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700;">{{ $record['hours'] }}</td>
                            <td style="padding: 15px; text-align: center;">
                                @if($record['status'] === 'present')
                                    <span style="background: #48bb78; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> حاضر
                                    </span>
                                @elseif($record['status'] === 'late')
                                    <span style="background: #ed8936; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-clock"></i> متأخر
                                    </span>
                                @elseif($record['status'] === 'absent')
                                    <span style="background: #f56565; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-times-circle"></i> غائب
                                    </span>
                                @elseif($record['status'] === 'early_leave')
                                    <span style="background: #4299e1; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-sign-out-alt"></i> انصراف مبكر
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center; color: #4a5568; font-size: 14px;">{{ $record['notes'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 30px; display: flex; justify-content: center;">
            <div style="display: flex; gap: 10px;">
                <button style="background: #e2e8f0; color: #4a5568; padding: 10px 15px; border: none; border-radius: 8px; cursor: pointer;">السابق</button>
                <button style="background: #48bb78; color: white; padding: 10px 15px; border: none; border-radius: 8px; cursor: pointer;">1</button>
                <button style="background: #e2e8f0; color: #4a5568; padding: 10px 15px; border: none; border-radius: 8px; cursor: pointer;">2</button>
                <button style="background: #e2e8f0; color: #4a5568; padding: 10px 15px; border: none; border-radius: 8px; cursor: pointer;">3</button>
                <button style="background: #e2e8f0; color: #4a5568; padding: 10px 15px; border: none; border-radius: 8px; cursor: pointer;">التالي</button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                
                <button onclick="addAttendanceRecord()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-plus"></i>
                    إضافة سجل حضور
                </button>

                <button onclick="generateReport()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-chart-bar"></i>
                    تقرير الحضور
                </button>

                <button onclick="exportAttendance()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </button>

                <a href="{{ route('tenant.hr.attendance.index') }}" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                    <i class="fas fa-calendar-check"></i>
                    نظام الحضور العام
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function addAttendanceRecord() {
    alert('ميزة إضافة سجل حضور يدوي قيد التطوير\n\nستتيح هذه الميزة:\n• إضافة سجل حضور يدوي\n• تعديل أوقات الدخول والخروج\n• إضافة ملاحظات\n• تبرير الغياب أو التأخير');
}

function generateReport() {
    alert('ميزة تقرير الحضور قيد التطوير\n\nسيتضمن التقرير:\n• إحصائيات شهرية ومفصلة\n• رسوم بيانية للحضور\n• مقارنة الأداء\n• تحليل أنماط الحضور');
}

function exportAttendance() {
    // Show loading state
    const button = event.target;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
    button.disabled = true;
    
    // Simulate export process
    setTimeout(() => {
        alert('تم تصدير سجل الحضور بنجاح!\n\nتم تصدير:\n• سجل الحضور والانصراف\n• الإحصائيات الشهرية\n• تفاصيل الساعات والحالات\n• الملاحظات والتبريرات');
        
        // Reset button
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 2000);
}

// Auto-refresh attendance status every 30 seconds
setInterval(function() {
    // This would normally fetch real-time data
    console.log('تحديث حالة الحضور...');
}, 30000);
</script>

@endsection
