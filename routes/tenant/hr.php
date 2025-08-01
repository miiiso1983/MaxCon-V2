<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\HR\EmployeeController;
use App\Http\Controllers\Tenant\HR\DepartmentController;
use App\Http\Controllers\Tenant\HR\PositionController;
use App\Http\Controllers\Tenant\HR\AttendanceController;
use App\Http\Controllers\Tenant\HR\LeaveController;
use App\Http\Controllers\Tenant\HR\LeaveTypeController;
use App\Http\Controllers\Tenant\HR\PayrollController;
use App\Http\Controllers\Tenant\HR\ShiftController;
use App\Http\Controllers\Tenant\HR\OvertimeController;
use App\Http\Controllers\Tenant\HR\HRDashboardController;

// HR Dashboard
Route::get('/', [HRDashboardController::class, 'index'])->name('dashboard');

// Employees Management
Route::prefix('employees')->name('employees.')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('index');
    Route::get('/create', [EmployeeController::class, 'create'])->name('create');
    Route::post('/', [EmployeeController::class, 'store'])->name('store');
    Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
    Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
    Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
    Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    Route::get('/export/excel', [EmployeeController::class, 'export'])->name('export');
    Route::get('/import/form', [EmployeeController::class, 'showImportForm'])->name('import.form');
    Route::post('/import', [EmployeeController::class, 'import'])->name('import');
    Route::get('/{employee}/attendance', [EmployeeController::class, 'attendance'])->name('attendance');
});

// Departments Management
Route::prefix('departments')->name('departments.')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('index');
    Route::get('/create', [DepartmentController::class, 'create'])->name('create');
    Route::post('/', [DepartmentController::class, 'store'])->name('store');
    Route::get('/chart', [DepartmentController::class, 'chart'])->name('chart');
    Route::get('/reports', [DepartmentController::class, 'reports'])->name('reports');
    Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
    Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
    Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
    Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
    Route::get('/hierarchy/chart', [DepartmentController::class, 'hierarchyChart'])->name('hierarchy');
});

// Positions Management
Route::prefix('positions')->name('positions.')->group(function () {
    Route::get('/', [PositionController::class, 'index'])->name('index');
    Route::get('/create', [PositionController::class, 'create'])->name('create');
    Route::post('/', [PositionController::class, 'store'])->name('store');
    Route::get('/reports', [PositionController::class, 'reports'])->name('reports');
    Route::get('/{position}', [PositionController::class, 'show'])->name('show');
    Route::get('/{position}/edit', [PositionController::class, 'edit'])->name('edit');
    Route::put('/{position}', [PositionController::class, 'update'])->name('update');
    Route::delete('/{position}', [PositionController::class, 'destroy'])->name('destroy');
});

// Attendance Management
Route::prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('/create', [AttendanceController::class, 'create'])->name('create');
    Route::post('/', [AttendanceController::class, 'store'])->name('store');
    Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
    Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');
    Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
    Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
    Route::get('/reports', [AttendanceController::class, 'reports'])->name('reports');
    Route::get('/export', [AttendanceController::class, 'export'])->name('export');
});

// Shifts Management
Route::prefix('shifts')->name('shifts.')->group(function () {
    Route::get('/', [ShiftController::class, 'index'])->name('index');
    Route::get('/create', [ShiftController::class, 'create'])->name('create');
    Route::post('/', [ShiftController::class, 'store'])->name('store');
    Route::get('/{shift}', [ShiftController::class, 'show'])->name('show');
    Route::get('/{shift}/edit', [ShiftController::class, 'edit'])->name('edit');
    Route::put('/{shift}', [ShiftController::class, 'update'])->name('update');
    Route::delete('/{shift}', [ShiftController::class, 'destroy'])->name('destroy');
    Route::get('/assignments', [ShiftController::class, 'assignments'])->name('assignments');
    Route::post('/assign', [ShiftController::class, 'assignEmployees'])->name('assign');
    Route::get('/schedule', [ShiftController::class, 'schedule'])->name('schedule');
});

// Overtime Management
Route::prefix('overtime')->name('overtime.')->group(function () {
    Route::get('/', [OvertimeController::class, 'index'])->name('index');
    Route::get('/create', [OvertimeController::class, 'create'])->name('create');
    Route::post('/', [OvertimeController::class, 'store'])->name('store');
    Route::get('/{overtime}', [OvertimeController::class, 'show'])->name('show');
    Route::get('/{overtime}/edit', [OvertimeController::class, 'edit'])->name('edit');
    Route::put('/{overtime}', [OvertimeController::class, 'update'])->name('update');
    Route::delete('/{overtime}', [OvertimeController::class, 'destroy'])->name('destroy');
    Route::post('/{overtime}/approve', [OvertimeController::class, 'approve'])->name('approve');
    Route::post('/{overtime}/reject', [OvertimeController::class, 'reject'])->name('reject');
    Route::get('/reports', [OvertimeController::class, 'reports'])->name('reports');
});

// Payroll Management
Route::prefix('payroll')->name('payroll.')->group(function () {
    Route::get('/', [PayrollController::class, 'index'])->name('index');
    Route::get('/create', [PayrollController::class, 'create'])->name('create');
    Route::post('/', [PayrollController::class, 'store'])->name('store');
    Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');
    Route::get('/{payroll}/edit', [PayrollController::class, 'edit'])->name('edit');
    Route::put('/{payroll}', [PayrollController::class, 'update'])->name('update');
    Route::delete('/{payroll}', [PayrollController::class, 'destroy'])->name('destroy');
    Route::post('/process', [PayrollController::class, 'process'])->name('process');
    Route::post('/generate', [PayrollController::class, 'generatePayroll'])->name('generate');
    Route::post('/{payroll}/approve', [PayrollController::class, 'approve'])->name('approve');
    Route::post('/{payroll}/pay', [PayrollController::class, 'markAsPaid'])->name('pay');
    Route::get('/{payroll}/slip', [PayrollController::class, 'payslip'])->name('payslip');
    Route::get('/{employee}/payslip/print', [PayrollController::class, 'printPayslip'])->name('print-payslip');
    Route::post('/{employee}/payslip/send', [PayrollController::class, 'sendPayslip'])->name('send-payslip');
    Route::get('/reports', [PayrollController::class, 'reports'])->name('reports');
    Route::get('/export', [PayrollController::class, 'export'])->name('export');
    Route::get('/export/{period}', [PayrollController::class, 'exportPeriod'])->name('export-period');
    Route::get('/report', [PayrollController::class, 'report'])->name('report');
});

// Leave Types Management
Route::prefix('leave-types')->name('leave-types.')->group(function () {
    Route::get('/', [LeaveTypeController::class, 'index'])->name('index');
    Route::get('/create', [LeaveTypeController::class, 'create'])->name('create');
    Route::post('/', [LeaveTypeController::class, 'store'])->name('store');
    Route::get('/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('edit');
    Route::put('/{leaveType}', [LeaveTypeController::class, 'update'])->name('update');
    Route::delete('/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('destroy');
});

// Leaves Management
Route::prefix('leaves')->name('leaves.')->group(function () {
    Route::get('/', [LeaveController::class, 'index'])->name('index');
    Route::get('/create', [LeaveController::class, 'create'])->name('create');
    Route::post('/', [LeaveController::class, 'store'])->name('store');
    Route::get('/{leave}', [LeaveController::class, 'show'])->name('show');
    Route::get('/{leave}/edit', [LeaveController::class, 'edit'])->name('edit');
    Route::put('/{leave}', [LeaveController::class, 'update'])->name('update');
    Route::delete('/{leave}', [LeaveController::class, 'destroy'])->name('destroy');
    Route::post('/{leave}/approve', [LeaveController::class, 'approve'])->name('approve');
    Route::post('/{leave}/reject', [LeaveController::class, 'reject'])->name('reject');
    Route::get('/calendar/view', [LeaveController::class, 'calendar'])->name('calendar');
    Route::get('/balance/{employee}', [LeaveController::class, 'balance'])->name('balance');
});



// Reports
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'attendanceReport'])->name('attendance');
    Route::get('/payroll', [PayrollController::class, 'payrollReport'])->name('payroll');
    Route::get('/leaves', [LeaveController::class, 'leaveReport'])->name('leaves');
    Route::get('/overtime', [OvertimeController::class, 'overtimeReport'])->name('overtime');
    Route::get('/employee-summary', [EmployeeController::class, 'employeeSummaryReport'])->name('employee-summary');
});
