<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\InvoicePermissions;

class InvoicePermissionHelper
{
    /**
     * Check if current user has specific invoice permission
     */
    public static function can(string $permission): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        $middleware = new InvoicePermissions();
        return $middleware->hasInvoicePermission($user, $permission);
    }

    /**
     * Check if current user can access specific invoice
     */
    public static function canAccess($invoice): bool
    {
        $user = Auth::user();
        
        if (!$user || !$invoice) {
            return false;
        }

        return InvoicePermissions::canAccessInvoice($user, $invoice);
    }

    /**
     * Get current user's invoice permissions
     */
    public static function getUserPermissions(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        return InvoicePermissions::getUserPermissions($user);
    }

    /**
     * Get permission label in Arabic
     */
    public static function getPermissionLabel(string $permission): string
    {
        $labels = [
            'view' => 'عرض الفواتير',
            'create' => 'إنشاء فواتير',
            'edit' => 'تعديل الفواتير',
            'delete' => 'حذف الفواتير',
            'print' => 'طباعة الفواتير',
            'send' => 'إرسال الفواتير',
            'payment' => 'إدارة المدفوعات',
            'reports' => 'تقارير الفواتير',
        ];

        return $labels[$permission] ?? $permission;
    }

    /**
     * Get role label in Arabic
     */
    public static function getRoleLabel(string $role): string
    {
        $labels = [
            'super_admin' => 'مدير النظام',
            'tenant_admin' => 'مدير المؤسسة',
            'sales_manager' => 'مدير المبيعات',
            'sales_rep' => 'مندوب مبيعات',
            'accountant' => 'محاسب',
            'warehouse_manager' => 'مدير المخزن',
        ];

        return $labels[$role] ?? $role;
    }

    /**
     * Get user's role permissions summary
     */
    public static function getRolePermissions(string $role): array
    {
        $rolePermissions = [
            'super_admin' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => true,
                'print' => true,
                'send' => true,
                'payment' => true,
                'reports' => true,
            ],
            'tenant_admin' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => false, // Cannot delete invoices
                'print' => true,
                'send' => true,
                'payment' => true,
                'reports' => true,
            ],
            'sales_manager' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => false,
                'print' => true,
                'send' => true,
                'payment' => true,
                'reports' => true,
            ],
            'sales_rep' => [
                'view' => true,
                'create' => true,
                'edit' => 'own_only', // Can edit only own invoices
                'delete' => false,
                'print' => true,
                'send' => true,
                'payment' => false,
                'reports' => false,
            ],
            'accountant' => [
                'view' => true,
                'create' => false,
                'edit' => false,
                'delete' => false,
                'print' => false,
                'send' => false,
                'payment' => true,
                'reports' => true,
            ],
            'warehouse_manager' => [
                'view' => true,
                'create' => false,
                'edit' => false,
                'delete' => false,
                'print' => false,
                'send' => false,
                'payment' => false,
                'reports' => true,
            ],
        ];

        return $rolePermissions[$role] ?? [];
    }

    /**
     * Check if user can perform action on specific invoice
     */
    public static function canPerformAction(string $action, $invoice = null): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Check basic permission
        if (!self::can($action)) {
            return false;
        }

        // For edit action, sales rep can only edit their own invoices
        if ($action === 'edit' && $user->role === 'sales_rep' && $invoice) {
            return $invoice->sales_rep_id === $user->id;
        }

        // Check invoice access
        if ($invoice && !self::canAccess($invoice)) {
            return false;
        }

        return true;
    }

    /**
     * Get available actions for current user on specific invoice
     */
    public static function getAvailableActions($invoice = null): array
    {
        $actions = ['view', 'create', 'edit', 'delete', 'print', 'send', 'payment'];
        $availableActions = [];

        foreach ($actions as $action) {
            if (self::canPerformAction($action, $invoice)) {
                $availableActions[] = $action;
            }
        }

        return $availableActions;
    }

    /**
     * Generate permission summary for display
     */
    public static function getPermissionSummary(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        $permissions = self::getUserPermissions();
        $summary = [
            'user' => $user->name,
            'role' => self::getRoleLabel($user->role),
            'permissions' => [],
        ];

        foreach ($permissions as $permission => $hasPermission) {
            if ($hasPermission) {
                $summary['permissions'][] = self::getPermissionLabel($permission);
            }
        }

        return $summary;
    }
}
