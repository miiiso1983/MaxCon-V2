<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Employee;
use App\Models\Tenant\HR\Department;
use App\Models\Tenant\HR\Position;
use App\Models\Tenant\HR\Attendance;
use App\Models\Tenant\HR\Leave;
use App\Models\Tenant\HR\Overtime;
use App\Models\Tenant\HR\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HRDashboardController extends Controller
{
    /**
     * Display HR dashboard
     */
    public function index()
    {
        // Sample statistics for demonstration
        $totalEmployees = 26;
        $activeEmployees = 24;
        $newEmployeesThisMonth = 3;

        // Sample statistics
        $totalDepartments = 5;
        $totalPositions = 12;

        // Today's Attendance
        $presentToday = 22;
        $absentToday = 2;
        $lateToday = 3;

        // Leave Statistics
        $pendingLeaves = 4;
        $approvedLeavesToday = 2;

        // Overtime Statistics
        $pendingOvertimes = 2;
        $thisMonthOvertimeHours = 45.5;

        // Payroll Statistics
        $currentPayrollStatus = null;

        // Sample recent activities
        $recentEmployees = collect([
            (object) [
                'id' => 1,
                'first_name' => 'أحمد',
                'last_name' => 'محمد',
                'full_name' => 'أحمد محمد',
                'hire_date' => now()->subDays(5),
                'department' => (object) ['name' => 'الإدارة العامة'],
                'position' => (object) ['title' => 'مدير عام']
            ],
            (object) [
                'id' => 2,
                'first_name' => 'سارة',
                'last_name' => 'أحمد',
                'full_name' => 'سارة أحمد',
                'hire_date' => now()->subDays(10),
                'department' => (object) ['name' => 'الموارد البشرية'],
                'position' => (object) ['title' => 'مدير الموارد البشرية']
            ]
        ]);

        $recentLeaves = collect([
            (object) [
                'id' => 1,
                'status' => 'pending',
                'start_date' => now()->addDays(5),
                'days_requested' => 3,
                'employee' => (object) ['full_name' => 'محمد علي'],
                'leaveType' => (object) ['name' => 'إجازة سنوية']
            ]
        ]);

        $recentOvertimes = collect([
            (object) [
                'id' => 1,
                'date' => now()->subDays(1),
                'hours_requested' => 4,
                'status' => 'pending',
                'employee' => (object) ['full_name' => 'عمر خالد']
            ]
        ]);

        // Sample charts data
        $employeesByDepartment = collect([
            'الإدارة العامة' => 5,
            'الموارد البشرية' => 3,
            'المالية والمحاسبة' => 4,
            'المبيعات والتسويق' => 8,
            'تقنية المعلومات' => 6
        ]);

        $attendanceThisWeek = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $attendanceThisWeek[$date] = rand(18, 24);
        }

        $leavesByType = collect([
            'إجازة سنوية' => 12,
            'إجازة مرضية' => 5,
            'إجازة طارئة' => 3,
            'إجازة أمومة' => 2
        ]);

        // Sample upcoming events
        $upcomingBirthdays = collect([
            (object) [
                'full_name' => 'أحمد محمد',
                'date_of_birth' => now()->addDays(3)
            ],
            (object) [
                'full_name' => 'سارة أحمد',
                'date_of_birth' => now()->addDays(5)
            ]
        ]);

        $contractsExpiringSoon = collect([
            (object) [
                'full_name' => 'محمد علي',
                'contract_end_date' => now()->addDays(15)
            ]
        ]);

        $probationEndingSoon = collect([
            (object) [
                'full_name' => 'فاطمة حسن',
                'probation_end_date' => now()->addDays(10)
            ]
        ]);

        return view('tenant.hr.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'newEmployeesThisMonth',
            'totalDepartments',
            'totalPositions',
            'presentToday',
            'absentToday',
            'lateToday',
            'pendingLeaves',
            'approvedLeavesToday',
            'pendingOvertimes',
            'thisMonthOvertimeHours',
            'currentPayrollStatus',
            'recentEmployees',
            'recentLeaves',
            'recentOvertimes',
            'employeesByDepartment',
            'attendanceThisWeek',
            'leavesByType',
            'upcomingBirthdays',
            'contractsExpiringSoon',
            'probationEndingSoon'
        ));
    }
}
