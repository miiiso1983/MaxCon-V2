<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(): View
    {
        $users = User::with('roles')->paginate(15);

        // Get statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'super_admins' => User::whereHas('roles', function($q) {
                $q->where('name', 'super-admin');
            })->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user (للـ Tenant Admin فقط)
     */
    public function create(): View
    {
        // التحقق من أن المستخدم هو tenant-admin
        if (!auth()->user()->hasRole('tenant-admin')) {
            abort(403, 'غير مسموح لك بإضافة مستخدمين. هذه الصلاحية متاحة لمدير المؤسسة فقط.');
        }

        return view('tenant.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:super-admin,tenant-admin,manager,employee',
            'is_active' => 'required|boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'is_active' => $validated['is_active'],
            'email_verified_at' => now(),
        ]);

        // Assign role if provided
        if (!empty($validated['role'])) {
            $user->assignRole($validated['role']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'role' => 'nullable|string|in:super-admin,tenant-admin,manager,employee',
            'is_active' => 'required|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        // Update user data
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'],
        ];

        // Update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

        // Update role if provided
        if (!empty($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Activate user
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return back()->with('success', 'User activated successfully.');
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);

        return back()->with('success', 'User deactivated successfully.');
    }

    /**
     * Export users data
     */
    public function export()
    {
        $users = User::with('roles')->get();

        $csvData = [];
        $csvData[] = ['الاسم', 'البريد الإلكتروني', 'الدور', 'الحالة', 'تاريخ التسجيل'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->name,
                $user->email,
                $user->roles->first()->name ?? 'غير محدد',
                ($user->is_active ?? true) ? 'نشط' : 'معطل',
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display users for tenant admin (only users in their tenant)
     */
    public function tenantUsers(): View
    {
        $tenantId = auth()->user()->tenant_id;

        $users = User::with('roles')
            ->where('tenant_id', $tenantId)
            ->paginate(15);

        // Get statistics for this tenant only
        $stats = [
            'total' => User::where('tenant_id', $tenantId)->count(),
            'active' => User::where('tenant_id', $tenantId)->where('is_active', true)->count(),
            'inactive' => User::where('tenant_id', $tenantId)->where('is_active', false)->count(),
            'admins' => User::where('tenant_id', $tenantId)->whereHas('roles', function($q) {
                $q->whereIn('name', ['tenant-admin', 'manager']);
            })->count(),
        ];

        return view('tenant.users.index', compact('users', 'stats'));
    }

    /**
     * Store user for tenant admin (only in their tenant)
     */
    public function tenantStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:manager,employee', // فقط أدوار أقل من tenant-admin
            'is_active' => 'required|boolean',
        ]);

        $tenantId = auth()->user()->tenant_id;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'is_active' => $validated['is_active'],
            'email_verified_at' => now(),
            'tenant_id' => $tenantId, // ربط المستخدم بنفس مؤسسة المدير
        ]);

        // تعيين الدور
        if (!empty($validated['role'])) {
            $user->assignRole($validated['role']);
        }

        return redirect()->route('tenant.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * Export users for tenant admin (only their tenant users)
     */
    public function tenantExport()
    {
        $tenantId = auth()->user()->tenant_id;
        $users = User::with('roles')->where('tenant_id', $tenantId)->get();

        $csvData = [];
        $csvData[] = ['الاسم', 'البريد الإلكتروني', 'الدور', 'الحالة', 'تاريخ التسجيل'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->name,
                $user->email,
                $user->roles->first()->name ?? 'غير محدد',
                ($user->is_active ?? true) ? 'نشط' : 'معطل',
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'tenant_users_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
