<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display roles and permissions management
     */
    public function index(): View
    {
        // Get current tenant users
        $tenantUsers = User::where('tenant_id', auth()->user()->tenant_id)
            ->with('roles', 'permissions')
            ->paginate(15);

        // Get available roles for this tenant
        $roles = Role::where('guard_name', 'web')->get();

        // Get available permissions
        $permissions = Permission::where('guard_name', 'web')->get();

        // Debug: Log permission count
        \Log::info('Permissions loaded in RoleController', [
            'total_permissions' => $permissions->count(),
            'database_connection' => config('database.default'),
            'first_few_permissions' => $permissions->take(5)->pluck('name')->toArray()
        ]);

        // Group permissions by category
        $permissionGroups = $permissions->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('tenant.roles.index', compact('tenantUsers', 'roles', 'permissions', 'permissionGroups'));
    }

    /**
     * Create a new role
     */
    public function createRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('tenant.roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح');
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        // Check if user belongs to current tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'غير مصرح لك بتعديل هذا المستخدم');
        }

        $role = Role::findById($validated['role_id']);
        $user->syncRoles([$role]);

        return redirect()->route('tenant.roles.index')
            ->with('success', 'تم تعيين الدور بنجاح للمستخدم: ' . $user->name);
    }

    /**
     * Assign permission to user
     */
    public function assignPermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Check if user belongs to current tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'غير مصرح لك بتعديل هذا المستخدم');
        }

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $user->syncPermissions($permissions);
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('tenant.roles.index')
            ->with('success', 'تم تحديث صلاحيات المستخدم: ' . $user->name);
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, Role $role)
    {
        // Check if user belongs to current tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'غير مصرح لك بتعديل هذا المستخدم');
        }

        $user->removeRole($role);

        return redirect()->route('tenant.roles.index')
            ->with('success', 'تم إزالة الدور من المستخدم: ' . $user->name);
    }

    /**
     * Get user details for modal
     */
    public function getUserDetails(User $user)
    {
        // Check if user belongs to current tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'غير مصرح لك بعرض هذا المستخدم');
        }

        return response()->json([
            'user' => $user->load('roles', 'permissions'),
            'available_roles' => Role::where('guard_name', 'web')->get(),
            'available_permissions' => Permission::where('guard_name', 'web')->get()
        ]);
    }
}
