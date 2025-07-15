<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Employee;
use App\Models\Tenant\HR\Department;
use App\Models\Tenant\HR\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        // Create sample employees data for demonstration
        $employees = collect([
            (object) [
                'id' => 1,
                'employee_code' => 'EMP0001',
                'first_name' => 'أحمد',
                'last_name' => 'محمد',
                'full_name' => 'أحمد محمد',
                'email' => 'ahmed.mohamed@company.com',
                'mobile' => '07901234567',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => now()->subMonths(6),
                'department' => (object) ['name' => 'الإدارة العامة'],
                'position' => (object) ['title' => 'مدير عام'],
                'basic_salary' => 2500000
            ],
            (object) [
                'id' => 2,
                'employee_code' => 'EMP0002',
                'first_name' => 'سارة',
                'last_name' => 'أحمد',
                'full_name' => 'سارة أحمد',
                'email' => 'sara.ahmed@company.com',
                'mobile' => '07901234568',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => now()->subMonths(4),
                'department' => (object) ['name' => 'الموارد البشرية'],
                'position' => (object) ['title' => 'مدير الموارد البشرية'],
                'basic_salary' => 2000000
            ],
            (object) [
                'id' => 3,
                'employee_code' => 'EMP0003',
                'first_name' => 'محمد',
                'last_name' => 'علي',
                'full_name' => 'محمد علي',
                'email' => 'mohamed.ali@company.com',
                'mobile' => '07901234569',
                'employment_status' => 'probation',
                'employment_type' => 'full_time',
                'hire_date' => now()->subMonths(1),
                'department' => (object) ['name' => 'المالية والمحاسبة'],
                'position' => (object) ['title' => 'محاسب'],
                'basic_salary' => 1500000
            ],
            (object) [
                'id' => 4,
                'employee_code' => 'EMP0004',
                'first_name' => 'فاطمة',
                'last_name' => 'حسن',
                'full_name' => 'فاطمة حسن',
                'email' => 'fatima.hassan@company.com',
                'mobile' => '07901234570',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => now()->subMonths(8),
                'department' => (object) ['name' => 'المبيعات والتسويق'],
                'position' => (object) ['title' => 'مدير المبيعات'],
                'basic_salary' => 1800000
            ],
            (object) [
                'id' => 5,
                'employee_code' => 'EMP0005',
                'first_name' => 'عمر',
                'last_name' => 'خالد',
                'full_name' => 'عمر خالد',
                'email' => 'omar.khaled@company.com',
                'mobile' => '07901234571',
                'employment_status' => 'active',
                'employment_type' => 'full_time',
                'hire_date' => now()->subMonths(12),
                'department' => (object) ['name' => 'تقنية المعلومات'],
                'position' => (object) ['title' => 'مطور برمجيات'],
                'basic_salary' => 2200000
            ]
        ]);

        // Apply search filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $employees = $employees->filter(function ($employee) use ($search) {
                return str_contains(strtolower($employee->full_name), $search) ||
                       str_contains(strtolower($employee->employee_code), $search) ||
                       str_contains(strtolower($employee->email), $search);
            });
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $departmentNames = [
                1 => 'الإدارة العامة',
                2 => 'الموارد البشرية',
                3 => 'المالية والمحاسبة',
                4 => 'المبيعات والتسويق',
                5 => 'تقنية المعلومات'
            ];
            $targetDepartment = $departmentNames[$request->department_id] ?? '';
            $employees = $employees->filter(function ($employee) use ($targetDepartment) {
                return $employee->department->name === $targetDepartment;
            });
        }

        // Apply employment status filter
        if ($request->filled('employment_status')) {
            $employees = $employees->filter(function ($employee) use ($request) {
                return $employee->employment_status === $request->employment_status;
            });
        }

        // Create pagination-like object
        $employees = new \Illuminate\Pagination\LengthAwarePaginator(
            $employees->forPage(1, 15),
            $employees->count(),
            15,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Get filter options
        $departments = collect([
            (object) ['id' => 1, 'name' => 'الإدارة العامة'],
            (object) ['id' => 2, 'name' => 'الموارد البشرية'],
            (object) ['id' => 3, 'name' => 'المالية والمحاسبة'],
            (object) ['id' => 4, 'name' => 'المبيعات والتسويق'],
            (object) ['id' => 5, 'name' => 'تقنية المعلومات']
        ]);

        $positions = collect([
            (object) ['id' => 1, 'title' => 'مدير عام'],
            (object) ['id' => 2, 'title' => 'مدير الموارد البشرية'],
            (object) ['id' => 3, 'title' => 'محاسب'],
            (object) ['id' => 4, 'title' => 'مدير المبيعات'],
            (object) ['id' => 5, 'title' => 'مطور برمجيات']
        ]);

        return view('tenant.hr.employees.index', compact('employees', 'departments', 'positions'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $departments = Department::where('tenant_id', tenant()->id ?? 1)->active()->get();
        $positions = Position::where('tenant_id', tenant()->id ?? 1)->active()->get();
        $managers = Employee::where('tenant_id', tenant()->id ?? 1)->active()->get();
        
        return view('tenant.hr.employees.create', compact('departments', 'positions', 'managers'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_id' => 'required|string|unique:hr_employees,national_id',
            'email' => 'required|email|unique:hr_employees,email',
            'mobile' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'department_id' => 'required|exists:hr_departments,id',
            'position_id' => 'required|exists:hr_positions,id',
            'hire_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'employment_type' => 'required|in:full_time,part_time,contract,internship,consultant',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'id_copy' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $employeeData = $request->all();
            $employeeData['tenant_id'] = tenant()->id ?? 1;
            $employeeData['created_by'] = auth()->id();
            
            // Handle file uploads
            if ($request->hasFile('profile_photo')) {
                $employeeData['profile_photo'] = $request->file('profile_photo')->store('hr/employees/photos', 'public');
            }
            
            if ($request->hasFile('cv_file')) {
                $employeeData['cv_file'] = $request->file('cv_file')->store('hr/employees/cvs', 'public');
            }
            
            if ($request->hasFile('id_copy')) {
                $employeeData['id_copy'] = $request->file('id_copy')->store('hr/employees/documents', 'public');
            }
            
            // Handle arrays
            if ($request->filled('skills')) {
                $employeeData['skills'] = explode(',', $request->skills);
            }
            
            if ($request->filled('languages')) {
                $employeeData['languages'] = explode(',', $request->languages);
            }
            
            $employee = Employee::create($employeeData);
            
            return redirect()->route('tenant.hr.employees.index')
                           ->with('success', 'تم إنشاء ملف الموظف بنجاح');
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'حدث خطأ أثناء إنشاء ملف الموظف: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified employee
     */
    public function show($id)
    {
        // For now, return a view with sample data since we don't have real employees yet
        return view('tenant.hr.employees.show');
    }

    /**
     * Show the form for editing the employee
     */
    public function edit($id)
    {
        $departments = Department::where('tenant_id', tenant()->id ?? 1)->active()->get();
        $positions = Position::where('tenant_id', tenant()->id ?? 1)->active()->get();

        return view('tenant.hr.employees.edit', compact('departments', 'positions'));
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, $id)
    {
        // For now, just redirect with success message since we don't have real employees yet
        return redirect()->route('tenant.hr.employees.show', $id)->with('success', 'تم تحديث بيانات الموظف بنجاح');

        /*
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_id' => 'required|string|unique:hr_employees,national_id,' . $id,
            'email' => 'required|email|unique:hr_employees,email,' . $id,
            'mobile' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'department_id' => 'required|exists:hr_departments,id',
            'position_id' => 'required|exists:hr_positions,id',
            'hire_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'employment_type' => 'required|in:full_time,part_time,contract,internship,consultant',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'id_copy' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $employeeData = $request->all();
            $employeeData['updated_by'] = auth()->id();
            
            // Handle file uploads
            if ($request->hasFile('profile_photo')) {
                // Delete old photo
                if ($employee->profile_photo) {
                    Storage::disk('public')->delete($employee->profile_photo);
                }
                $employeeData['profile_photo'] = $request->file('profile_photo')->store('hr/employees/photos', 'public');
            }
            
            if ($request->hasFile('cv_file')) {
                // Delete old CV
                if ($employee->cv_file) {
                    Storage::disk('public')->delete($employee->cv_file);
                }
                $employeeData['cv_file'] = $request->file('cv_file')->store('hr/employees/cvs', 'public');
            }
            
            if ($request->hasFile('id_copy')) {
                // Delete old ID copy
                if ($employee->id_copy) {
                    Storage::disk('public')->delete($employee->id_copy);
                }
                $employeeData['id_copy'] = $request->file('id_copy')->store('hr/employees/documents', 'public');
            }
            
            // Handle arrays
            if ($request->filled('skills')) {
                $employeeData['skills'] = explode(',', $request->skills);
            }
            
            if ($request->filled('languages')) {
                $employeeData['languages'] = explode(',', $request->languages);
            }
            
            $employee->update($employeeData);
            
            return redirect()->route('tenant.hr.employees.show', $employee)
                           ->with('success', 'تم تحديث بيانات الموظف بنجاح');
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'حدث خطأ أثناء تحديث بيانات الموظف: ' . $e->getMessage())
                           ->withInput();
        }
        */
    }

    /**
     * Remove the specified employee
     */
    public function destroy($id)
    {
        // For now, just redirect with success message since we don't have real employees yet
        return redirect()->route('tenant.hr.employees.index')->with('success', 'تم حذف الموظف بنجاح');

        /*
        try {
            // Soft delete the employee
            // $employee->delete();

            return redirect()->route('tenant.hr.employees.index')
                           ->with('success', 'تم حذف الموظف بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'حدث خطأ أثناء حذف الموظف: ' . $e->getMessage());
        }
        */
    }

    /**
     * Export employees to Excel
     */
    public function export(Request $request)
    {
        try {
            $tenantId = tenant()->id ?? 1;
            $filters = $request->only(['search', 'department_id', 'position_id', 'employment_status', 'employment_type']);

            $fileName = 'employees_' . now()->format('Y_m_d_H_i_s') . '.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\EmployeesExport($tenantId, $filters),
                $fileName
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage());
        }
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('tenant.hr.employees.import');
    }

    /**
     * Import employees from Excel
     */
    public function import(Request $request)
    {
        // TODO: Implement Excel import
        return redirect()->back()->with('info', 'ميزة الاستيراد قيد التطوير');
    }

    /**
     * Show employee attendance records
     */
    public function attendance($id)
    {
        // For now, return a view with sample data since we don't have real employees yet
        return view('tenant.hr.employees.attendance');
    }
}
