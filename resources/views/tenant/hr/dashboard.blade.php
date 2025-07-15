@extends('layouts.modern')

@section('title', 'لوحة تحكم الموارد البشرية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة الموارد البشرية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">نظام شامل لإدارة الموظفين والحضور والرواتب</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.hr.employees.create') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-user-plus"></i>
                    إضافة موظف جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- Total Employees -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-users"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $totalEmployees }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي الموظفين</p>
        </div>

        <!-- Active Employees -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-user-check"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $activeEmployees }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">الموظفون النشطون</p>
        </div>

        <!-- Present Today -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $presentToday }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">حاضرون اليوم</p>
        </div>

        <!-- Absent Today -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-user-times"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $absentToday }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">غائبون اليوم</p>
        </div>

        <!-- Pending Leaves -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-clock"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $pendingLeaves }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجازات في الانتظار</p>
        </div>

        <!-- Departments -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-building"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $totalDepartments }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">الأقسام</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- Employees Management -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.employees.index') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-users"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">إدارة الموظفين</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">ملفات الموظفين والبيانات الشخصية</p>
        </div>

        <!-- Attendance -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.attendance.index') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">الحضور والانصراف</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">تسجيل ومتابعة الحضور</p>
        </div>

        <!-- Leaves -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.leaves.index') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">إدارة الإجازات</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">طلبات الإجازات والموافقات</p>
        </div>

        <!-- Payroll -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.payroll.index') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">كشف الرواتب</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">حساب ودفع الرواتب</p>
        </div>

        <!-- Departments -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.departments.index') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-building"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">الأقسام والمناصب</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">الهيكل التنظيمي للشركة</p>
        </div>

        <!-- Shifts -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center; cursor: pointer; transition: transform 0.3s;" 
             onclick="window.location.href='{{ route('tenant.hr.shifts.index') }}'"
             onmouseover="this.style.transform='translateY(-5px)'" 
             onmouseout="this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-clock"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 18px; font-weight: 700;">إدارة المناوبات</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">جدولة المناوبات والساعات</p>
        </div>
    </div>

    <!-- Recent Activities -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-bottom: 30px;">
        
        <!-- Recent Employees -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-user-plus" style="margin-left: 10px; color: #667eea;"></i>
                الموظفون الجدد
            </h3>
            
            @if($recentEmployees->isNotEmpty())
                <div style="space-y: 15px;">
                    @foreach($recentEmployees as $employee)
                        <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f7fafc; border-radius: 10px; margin-bottom: 10px;">
                            <div style="background: #667eea; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $employee->full_name }}</div>
                                <div style="font-size: 12px; color: #718096;">{{ $employee->department->name ?? 'غير محدد' }} - {{ $employee->position->title ?? 'غير محدد' }}</div>
                            </div>
                            <div style="font-size: 12px; color: #4a5568;">{{ $employee->hire_date->format('Y-m-d') }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #718096;">
                    <i class="fas fa-users" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p style="margin: 0; font-size: 16px;">لا يوجد موظفون جدد</p>
                </div>
            @endif
        </div>

        <!-- Pending Leaves -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-clock" style="margin-left: 10px; color: #ed8936;"></i>
                طلبات الإجازات المعلقة
            </h3>
            
            @if($recentLeaves->where('status', 'pending')->isNotEmpty())
                <div style="space-y: 15px;">
                    @foreach($recentLeaves->where('status', 'pending') as $leave)
                        <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f7fafc; border-radius: 10px; margin-bottom: 10px;">
                            <div style="background: #ed8936; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $leave->employee->full_name }}</div>
                                <div style="font-size: 12px; color: #718096;">{{ $leave->leaveType->name ?? 'غير محدد' }} - {{ $leave->days_requested }} أيام</div>
                            </div>
                            <div style="font-size: 12px; color: #4a5568;">{{ $leave->start_date->format('Y-m-d') }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #718096;">
                    <i class="fas fa-calendar-check" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p style="margin: 0; font-size: 16px;">لا توجد طلبات إجازات معلقة</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Alerts and Notifications -->
    @if($contractsExpiringSoon->isNotEmpty() || $probationEndingSoon->isNotEmpty() || $upcomingBirthdays->isNotEmpty())
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-bell" style="margin-left: 10px; color: #f56565;"></i>
            التنبيهات والإشعارات
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            
            @if($contractsExpiringSoon->isNotEmpty())
            <div style="background: rgba(245, 101, 101, 0.1); border: 2px solid #f56565; border-radius: 15px; padding: 20px;">
                <h4 style="color: #f56565; margin: 0 0 15px 0; font-weight: 700;">
                    <i class="fas fa-file-contract" style="margin-left: 8px;"></i>
                    عقود تنتهي قريباً ({{ $contractsExpiringSoon->count() }})
                </h4>
                @foreach($contractsExpiringSoon->take(3) as $employee)
                    <div style="margin-bottom: 10px; padding: 10px; background: rgba(255,255,255,0.7); border-radius: 8px;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $employee->full_name }}</div>
                        <div style="font-size: 12px; color: #718096;">ينتهي في: {{ $employee->contract_end_date->format('Y-m-d') }}</div>
                    </div>
                @endforeach
            </div>
            @endif
            
            @if($probationEndingSoon->isNotEmpty())
            <div style="background: rgba(237, 137, 54, 0.1); border: 2px solid #ed8936; border-radius: 15px; padding: 20px;">
                <h4 style="color: #ed8936; margin: 0 0 15px 0; font-weight: 700;">
                    <i class="fas fa-user-clock" style="margin-left: 8px;"></i>
                    فترة تجربة تنتهي قريباً ({{ $probationEndingSoon->count() }})
                </h4>
                @foreach($probationEndingSoon->take(3) as $employee)
                    <div style="margin-bottom: 10px; padding: 10px; background: rgba(255,255,255,0.7); border-radius: 8px;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $employee->full_name }}</div>
                        <div style="font-size: 12px; color: #718096;">تنتهي في: {{ $employee->probation_end_date->format('Y-m-d') }}</div>
                    </div>
                @endforeach
            </div>
            @endif
            
            @if($upcomingBirthdays->isNotEmpty())
            <div style="background: rgba(72, 187, 120, 0.1); border: 2px solid #48bb78; border-radius: 15px; padding: 20px;">
                <h4 style="color: #48bb78; margin: 0 0 15px 0; font-weight: 700;">
                    <i class="fas fa-birthday-cake" style="margin-left: 8px;"></i>
                    أعياد ميلاد قريبة ({{ $upcomingBirthdays->count() }})
                </h4>
                @foreach($upcomingBirthdays->take(3) as $employee)
                    <div style="margin-bottom: 10px; padding: 10px; background: rgba(255,255,255,0.7); border-radius: 8px;">
                        <div style="font-weight: 600; color: #2d3748;">{{ $employee->full_name }}</div>
                        <div style="font-size: 12px; color: #718096;">{{ $employee->date_of_birth->format('m-d') }}</div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection
