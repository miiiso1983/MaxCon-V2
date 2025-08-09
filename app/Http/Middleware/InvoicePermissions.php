<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicePermissions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // Check if user has tenant access
        if (!$user->tenant_id) {
            abort(403, 'ليس لديك صلاحية للوصول لهذا القسم');
        }

        // Define invoice permissions
        $invoicePermissions = [
            'view' => 'عرض الفواتير',
            'create' => 'إنشاء فواتير',
            'edit' => 'تعديل الفواتير',
            'delete' => 'حذف الفواتير',
            'print' => 'طباعة الفواتير',
            'send' => 'إرسال الفواتير',
            'payment' => 'إدارة المدفوعات',
            'reports' => 'تقارير الفواتير',
        ];

        // If no specific permission required, allow access
        if (!$permission) {
            return $next($request);
        }

        // Check user role and permissions
        if ($this->hasInvoicePermission($user, $permission)) {
            return $next($request);
        }

        // Log unauthorized access attempt
        \Log::warning('Unauthorized invoice access attempt', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'permission' => $permission,
            'route' => $request->route()->getName(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        abort(403, 'ليس لديك صلاحية لتنفيذ هذا الإجراء: ' . ($invoicePermissions[$permission] ?? $permission));
    }

    /**
     * Check if user has specific invoice permission
     */
    public function hasInvoicePermission($user, string $permission): bool
    {
        // Super admin has all permissions
        if ($user->role === 'super_admin') {
            return true;
        }

        // Tenant admin has most permissions except some sensitive ones
        if ($user->role === 'tenant_admin') {
            $restrictedPermissions = ['delete']; // Tenant admin cannot delete invoices
            return !in_array($permission, $restrictedPermissions);
        }

        // Sales manager permissions
        if ($user->role === 'sales_manager') {
            $allowedPermissions = ['view', 'create', 'edit', 'print', 'send', 'payment', 'reports'];
            return in_array($permission, $allowedPermissions);
        }

        // Sales representative permissions
        if ($user->role === 'sales_rep') {
            $allowedPermissions = ['view', 'create', 'print', 'send'];
            
            // Sales rep can only edit their own invoices
            if ($permission === 'edit') {
                $invoice = $this->getInvoiceFromRequest();
                return $invoice && $invoice->sales_rep_id === $user->id;
            }
            
            return in_array($permission, $allowedPermissions);
        }

        // Accountant permissions
        if ($user->role === 'accountant') {
            $allowedPermissions = ['view', 'payment', 'reports'];
            return in_array($permission, $allowedPermissions);
        }

        // Warehouse manager permissions
        if ($user->role === 'warehouse_manager') {
            $allowedPermissions = ['view', 'reports'];
            return in_array($permission, $allowedPermissions);
        }

        // Default: no permission
        return false;
    }

    /**
     * Get invoice from current request
     */
    private function getInvoiceFromRequest()
    {
        $request = request();
        
        if ($request->route('invoice')) {
            return $request->route('invoice');
        }

        if ($request->has('invoice_id')) {
            return \App\Models\Invoice::find($request->input('invoice_id'));
        }

        return null;
    }

    /**
     * Check if user can access specific invoice
     */
    public static function canAccessInvoice($user, $invoice): bool
    {
        // Super admin and tenant admin can access all invoices in their tenant
        if (in_array($user->role, ['super_admin', 'tenant_admin'])) {
            return $invoice->tenant_id === $user->tenant_id;
        }

        // Sales rep can only access their own invoices
        if ($user->role === 'sales_rep') {
            return $invoice->tenant_id === $user->tenant_id && 
                   $invoice->sales_rep_id === $user->id;
        }

        // Other roles can access invoices in their tenant
        return $invoice->tenant_id === $user->tenant_id;
    }

    /**
     * Get user permissions summary
     */
    public static function getUserPermissions($user): array
    {
        $permissions = [
            'view' => false,
            'create' => false,
            'edit' => false,
            'delete' => false,
            'print' => false,
            'send' => false,
            'payment' => false,
            'reports' => false,
        ];

        foreach ($permissions as $permission => $value) {
            $middleware = new self();
            $permissions[$permission] = $middleware->hasInvoicePermission($user, $permission);
        }

        return $permissions;
    }
}
