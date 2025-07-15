<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.restore',
            'users.force-delete',

            // Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Permission Management
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            // Tenant Management (Super Admin only)
            'tenants.view',
            'tenants.create',
            'tenants.edit',
            'tenants.delete',
            'tenants.suspend',
            'tenants.activate',

            // Dashboard Access
            'dashboard.view',
            'dashboard.admin',
            'dashboard.super-admin',

            // Settings
            'settings.view',
            'settings.edit',
            'settings.system',

            // Reports
            'reports.view',
            'reports.export',

            // Activity Logs
            'activity-logs.view',
            'activity-logs.delete',

            // API Access
            'api.access',
            'api.admin',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin Role (System-wide access)
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Tenant Admin Role (Full access within tenant)
        $tenantAdmin = Role::create(['name' => 'tenant-admin']);
        $tenantAdmin->givePermissionTo([
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'permissions.view',
            'dashboard.view', 'dashboard.admin',
            'settings.view', 'settings.edit',
            'reports.view', 'reports.export',
            'activity-logs.view',
            'api.access', 'api.admin',
        ]);

        // Manager Role (Limited admin access)
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'users.view', 'users.create', 'users.edit',
            'roles.view',
            'permissions.view',
            'dashboard.view',
            'settings.view',
            'reports.view', 'reports.export',
            'activity-logs.view',
            'api.access',
        ]);

        // Employee Role (Basic access)
        $employee = Role::create(['name' => 'employee']);
        $employee->givePermissionTo([
            'users.view',
            'dashboard.view',
            'settings.view',
            'api.access',
        ]);

        // Customer Role (Limited access)
        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo([
            'dashboard.view',
            'api.access',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
