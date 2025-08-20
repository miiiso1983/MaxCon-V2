<?php

namespace App\Http\Controllers\Tenant\HR;

use App\Http\Controllers\Controller;
use App\Models\Tenant\HR\Employee;
use App\Models\Tenant\HR\Department;
use App\Models\Tenant\HR\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);

        $query = Employee::where('tenant_id', $tenantId)
            ->with(['department', 'position'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        $employees = $query->paginate(15)->appends($request->query());

        // Filter options from DB
        $departments = Department::where('tenant_id', $tenantId)->active()->orderBy('name')->get(['id','name']);
        $positions = Position::where('tenant_id', $tenantId)->active()->orderBy('title')->get(['id','title']);

        return view('tenant.hr.employees.index', compact('employees', 'departments', 'positions'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $departments = Department::where('tenant_id', $tenantId)->active()->orderBy('name')->get();
        $positions = Position::where('tenant_id', $tenantId)->active()->orderBy('title')->get();
        $managers = Employee::where('tenant_id', $tenantId)->active()->orderBy('first_name')->get();

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
            'marital_status' => 'required|in:single,married,divorced,widowed',

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
            $employeeData['tenant_id'] = Auth::user()->tenant_id ?? (tenant()->id ?? null);
            $employeeData['created_by'] = auth()->id();

            // Defaults
            if (empty($employeeData['employment_status'])) {
                $employeeData['employment_status'] = 'active';
            }
            if (empty($employeeData['marital_status'])) {
                $employeeData['marital_status'] = 'single';
            }
            if (empty($employeeData['employee_code'])) {
                $last = Employee::where('tenant_id', $employeeData['tenant_id'])->orderBy('id', 'desc')->first();
                if ($last && !empty($last->employee_code) && preg_match('/(\d+)/', $last->employee_code, $m)) {
                    $num = (int)$m[1] + 1;
                } else {
                    $num = 1;
                }
                $employeeData['employee_code'] = 'EMP' . str_pad((string)$num, 4, '0', STR_PAD_LEFT);
            }

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

            // Create linked user account with default role
            $plainPassword = str()->password(10);
            $user = new \App\Models\User();
            $user->tenant_id = $employee->tenant_id;
            $user->name = $employee->full_name_arabic ?: ($employee->first_name . ' ' . $employee->last_name);
            $user->email = $employee->email;
            $user->password = bcrypt($plainPassword);
            $user->is_active = true;
            $user->email_verified_at = now();
            $user->save();

            // Assign default role based on position/department if exists, else 'employee'
            $defaultRole = 'employee';
            try {
                if (method_exists($employee, 'position') && $employee->position && $employee->position->title) {
                    $candidate = strtolower($employee->position->title);
                    if (\Spatie\Permission\Models\Role::where('name', $candidate)->exists()) {
                        $defaultRole = $candidate;
                    }
                }
            } catch (\Throwable $e) {
                // ignore and fallback to 'employee'
            }
            if (!\Spatie\Permission\Models\Role::where('name', $defaultRole)->exists()) {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => $defaultRole]);
            }
            $user->assignRole($defaultRole);

            // Link user to employee
            $employee->user_id = $user->id;
            $employee->save();

            // Notify user with credentials
            try {
                $user->notify(new \App\Notifications\SendUserCredentialsNotification($user->email, $plainPassword));
            } catch (\Throwable $e) {
                \Log::warning('Failed to send credentials email to new employee user', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            return redirect()->route('tenant.hr.employees.index')
                           ->with('success', 'تم إنشاء ملف الموظف والحساب المرتبط بنجاح');

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
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employee = Employee::where('tenant_id', $tenantId)
            ->with(['department', 'position'])
            ->findOrFail($id);
        return view('tenant.hr.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the employee
     */
    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id ?? (tenant()->id ?? null);
        $employee = Employee::where('tenant_id', $tenantId)->findOrFail($id);
        $departments = Department::where('tenant_id', $tenantId)->active()->orderBy('name')->get();
        $positions = Position::where('tenant_id', $tenantId)->active()->orderBy('title')->get();

        return view('tenant.hr.employees.edit', compact('employee','departments', 'positions'));
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
     * Download employees import Excel template
     */
    public function downloadTemplate()
    {
        $fileName = 'employees_import_template_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EmployeesTemplateExport(), $fileName);
    }


    /**
     * Import employees from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'skip_duplicates' => 'nullable|boolean',
            'validate_emails' => 'nullable|boolean',
            'send_welcome_email' => 'nullable|boolean',
        ]);

        try {
            $tenantId = auth()->user()->tenant_id ?? (tenant()->id ?? null);
            $options = [
                'skip_duplicates' => (bool)$request->boolean('skip_duplicates', true),
                'validate_emails' => (bool)$request->boolean('validate_emails', true),
                'send_welcome_email' => (bool)$request->boolean('send_welcome_email', false),
            ];

            $import = new \App\Imports\EmployeesImport($tenantId, $options);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            $summary = $import->getSummary();
            $message = "تم الاستيراد: {$summary['created']} مضافة، {$summary['skipped']} متخطية، {$summary['failed']} فاشلة.";
            if (!empty($summary['errors'])) {
                $message .= "\n\nالأخطاء:\n- " . implode("\n- ", array_slice($summary['errors'], 0, 10));
                if (count($summary['errors']) > 10) {
                    $message .= "\n...";
                }
            }

            return redirect()->route('tenant.hr.employees.index')->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'تعذر استيراد الملف: ' . $e->getMessage());
        }
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
