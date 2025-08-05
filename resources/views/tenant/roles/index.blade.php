@extends('layouts.modern')

@section('page-title', 'إدارة الأدوار والصلاحيات')
@section('page-description', 'إدارة أدوار وصلاحيات المستخدمين في المؤسسة')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-user-shield" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة الأدوار والصلاحيات 🛡️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تحكم في أدوار وصلاحيات المستخدمين في مؤسستك
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-users" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $tenantUsers->total() ?? 0 }} مستخدم</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-user-tag" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $roles->count() }} دور</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-key" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $permissions->count() }} صلاحية</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <button onclick="showCreateRoleModal()" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease; cursor: pointer;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-plus"></i>
                    إنشاء دور جديد
                </button>

                <a href="{{ route('tenant.seed.permissions') }}"
                   onclick="return confirm('هل أنت متأكد من تحديث جميع الصلاحيات؟ سيتم إضافة الصلاحيات الجديدة للنظام.')"
                   style="background: rgba(102, 126, 234, 0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(102, 126, 234, 0.3); transition: all 0.3s ease; text-decoration: none;"
                   onmouseover="this.style.background='rgba(102, 126, 234, 0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(102, 126, 234, 0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-sync-alt"></i>
                    تحديث الصلاحيات الشاملة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Users and Roles Table -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-table" style="color: #667eea; margin-left: 10px;"></i>
        المستخدمون والأدوار
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المستخدم</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الأدوار</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الصلاحيات المباشرة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                    <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenantUsers as $user)
                <tr style="transition: all 0.3s ease;" onmouseover="this.style.background='#f7fafc';" onmouseout="this.style.background='white';">
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px; font-weight: 700; font-size: 18px;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ $user->name }}</div>
                                <div style="font-size: 14px; color: #718096; margin-bottom: 2px;">{{ $user->email }}</div>
                                <div style="font-size: 12px; color: #667eea;">{{ $user->position ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            @forelse($user->roles as $role)
                                <span style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                    {{ $role->display_name ?? $role->name }}
                                </span>
                            @empty
                                <span style="color: #718096; font-size: 12px;">لا توجد أدوار</span>
                            @endforelse
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            @php
                                $directPermissions = $user->getDirectPermissions();
                            @endphp
                            @forelse($directPermissions->take(3) as $permission)
                                <span style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                <span style="color: #718096; font-size: 12px;">لا توجد صلاحيات مباشرة</span>
                            @endforelse
                            @if($directPermissions->count() > 3)
                                <span style="color: #667eea; font-size: 12px;">+{{ $directPermissions->count() - 3 }} أخرى</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        @if($user->is_active)
                            <span class="status-badge status-active">نشط</span>
                        @else
                            <span class="status-badge status-inactive">غير نشط</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <button onclick="showUserPermissionsModal({{ $user->id }})" 
                                    style="background: none; border: none; color: #4299e1; cursor: pointer; padding: 4px;" 
                                    title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="showAssignRoleModal({{ $user->id }}, '{{ $user->name }}')" 
                                    style="background: none; border: none; color: #48bb78; cursor: pointer; padding: 4px;" 
                                    title="تعيين دور">
                                <i class="fas fa-user-tag"></i>
                            </button>
                            <button onclick="showAssignPermissionModal({{ $user->id }}, '{{ $user->name }}')" 
                                    style="background: none; border: none; color: #9f7aea; cursor: pointer; padding: 4px;" 
                                    title="تعيين صلاحيات">
                                <i class="fas fa-key"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: #718096;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <i class="fas fa-users" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px;"></i>
                            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد مستخدمون</p>
                            <p style="font-size: 14px; margin: 0;">ابدأ بإضافة مستخدمين جدد للمؤسسة</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($tenantUsers->hasPages())
    <div style="margin-top: 20px;">
        {{ $tenantUsers->links() }}
    </div>
    @endif
</div>

<!-- Available Roles Section -->
<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-user-tag" style="color: #48bb78; margin-left: 10px;"></i>
        الأدوار المتاحة
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        @forelse($roles as $role)
        <div style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#48bb78'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)';">
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                    <i class="fas fa-user-tag"></i>
                </div>
                <div>
                    <h4 style="font-size: 16px; font-weight: 700; color: #2d3748; margin: 0;">{{ $role->display_name ?? $role->name }}</h4>
                    <p style="font-size: 12px; color: #718096; margin: 2px 0 0 0;">{{ $role->name }}</p>
                </div>
            </div>
            
            @if($role->description)
            <p style="color: #4a5568; font-size: 14px; margin-bottom: 15px;">{{ $role->description }}</p>
            @endif
            
            <div style="margin-bottom: 15px;">
                <div style="font-size: 12px; color: #718096; margin-bottom: 8px;">الصلاحيات ({{ $role->permissions->count() }}):</div>
                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                    @forelse($role->permissions->take(5) as $permission)
                        <span style="background: #f7fafc; color: #4a5568; padding: 2px 6px; border-radius: 8px; font-size: 10px;">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <span style="color: #718096; font-size: 12px;">لا توجد صلاحيات</span>
                    @endforelse
                    @if($role->permissions->count() > 5)
                        <span style="color: #667eea; font-size: 10px;">+{{ $role->permissions->count() - 5 }} أخرى</span>
                    @endif
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                    {{ $role->users->count() }} مستخدم
                </span>
                <button onclick="showRoleDetailsModal({{ $role->id }})" style="background: none; border: 1px solid #48bb78; color: #48bb78; padding: 6px 12px; border-radius: 8px; font-size: 12px; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#48bb78'; this.style.color='white';" onmouseout="this.style.background='none'; this.style.color='#48bb78';">
                    عرض التفاصيل
                </button>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #718096;">
            <i class="fas fa-user-tag" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px;"></i>
            <p style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">لا توجد أدوار</p>
            <p style="font-size: 14px; margin: 0;">ابدأ بإنشاء أدوار جديدة للمؤسسة</p>
        </div>
        @endforelse
    </div>
</div>

<!-- System Permissions Overview -->
<div class="content-card" style="margin-top: 30px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-shield-alt" style="color: #667eea; margin-left: 10px;"></i>
        صلاحيات النظام حسب الأقسام
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">

        <!-- Sales Management Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%); border-color: #10b981;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #065f46; margin: 0;">إدارة المبيعات</h4>
                    <p style="font-size: 12px; color: #047857; margin: 2px 0 0 0;">Sales Management</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #10b981;">
                    <strong style="color: #065f46;">إدارة العملاء:</strong>
                    <span style="font-size: 12px; color: #047857;">عرض، إضافة، تعديل، حذف العملاء</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #10b981;">
                    <strong style="color: #065f46;">إدارة الطلبات:</strong>
                    <span style="font-size: 12px; color: #047857;">إنشاء وإدارة طلبات المبيعات</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #10b981;">
                    <strong style="color: #065f46;">إدارة الفواتير:</strong>
                    <span style="font-size: 12px; color: #047857;">إصدار وطباعة الفواتير</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #10b981;">
                    <strong style="color: #065f46;">إدارة المرتجعات:</strong>
                    <span style="font-size: 12px; color: #047857;">معالجة مرتجعات العملاء</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #10b981;">
                    <strong style="color: #065f46;">أهداف المبيعات:</strong>
                    <span style="font-size: 12px; color: #047857;">إدارة ومتابعة أهداف البيع</span>
                </div>
            </div>
        </div>

        <!-- Inventory Management Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-color: #3b82f6;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin: 0;">إدارة المخزون</h4>
                    <p style="font-size: 12px; color: #1d4ed8; margin: 2px 0 0 0;">Inventory Management</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #3b82f6;">
                    <strong style="color: #1e3a8a;">كتالوج المنتجات:</strong>
                    <span style="font-size: 12px; color: #1d4ed8;">إدارة المنتجات والفئات</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #3b82f6;">
                    <strong style="color: #1e3a8a;">إدارة المستودعات:</strong>
                    <span style="font-size: 12px; color: #1d4ed8;">تنظيم وإدارة المستودعات</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #3b82f6;">
                    <strong style="color: #1e3a8a;">حركات المخزون:</strong>
                    <span style="font-size: 12px; color: #1d4ed8;">تسجيل دخول وخروج البضائع</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #3b82f6;">
                    <strong style="color: #1e3a8a;">الجرد الدوري:</strong>
                    <span style="font-size: 12px; color: #1d4ed8;">إجراء وإدارة عمليات الجرد</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #3b82f6;">
                    <strong style="color: #1e3a8a;">تقارير المخزون:</strong>
                    <span style="font-size: 12px; color: #1d4ed8;">تقارير مستويات وحركات المخزون</span>
                </div>
            </div>
        </div>

        <!-- Accounting System Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-color: #ef4444;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-calculator"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #7f1d1d; margin: 0;">النظام المحاسبي</h4>
                    <p style="font-size: 12px; color: #dc2626; margin: 2px 0 0 0;">Accounting System</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #ef4444;">
                    <strong style="color: #7f1d1d;">دليل الحسابات:</strong>
                    <span style="font-size: 12px; color: #dc2626;">إدارة شجرة الحسابات</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #ef4444;">
                    <strong style="color: #7f1d1d;">القيود المحاسبية:</strong>
                    <span style="font-size: 12px; color: #dc2626;">إنشاء وإدارة القيود</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #ef4444;">
                    <strong style="color: #7f1d1d;">التقارير المالية:</strong>
                    <span style="font-size: 12px; color: #dc2626;">ميزان المراجعة والقوائم المالية</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #ef4444;">
                    <strong style="color: #7f1d1d;">مراكز التكلفة:</strong>
                    <span style="font-size: 12px; color: #dc2626;">إدارة مراكز التكلفة</span>
                </div>
            </div>
        </div>

        <!-- Human Resources Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border-color: #8b5cf6;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #581c87; margin: 0;">الموارد البشرية</h4>
                    <p style="font-size: 12px; color: #7c3aed; margin: 2px 0 0 0;">Human Resources</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #8b5cf6;">
                    <strong style="color: #581c87;">إدارة الموظفين:</strong>
                    <span style="font-size: 12px; color: #7c3aed;">ملفات وبيانات الموظفين</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #8b5cf6;">
                    <strong style="color: #581c87;">الحضور والانصراف:</strong>
                    <span style="font-size: 12px; color: #7c3aed;">تسجيل ومتابعة الحضور</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #8b5cf6;">
                    <strong style="color: #581c87;">إدارة الإجازات:</strong>
                    <span style="font-size: 12px; color: #7c3aed;">طلبات وموافقات الإجازات</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #8b5cf6;">
                    <strong style="color: #581c87;">كشف الرواتب:</strong>
                    <span style="font-size: 12px; color: #7c3aed;">حساب وإدارة الرواتب</span>
                </div>
            </div>
        </div>

        <!-- Purchasing Management Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%); border-color: #f59e0b;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-truck"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #92400e; margin: 0;">إدارة المشتريات</h4>
                    <p style="font-size: 12px; color: #d97706; margin: 2px 0 0 0;">Purchasing Management</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #f59e0b;">
                    <strong style="color: #92400e;">إدارة الموردين:</strong>
                    <span style="font-size: 12px; color: #d97706;">بيانات وتقييم الموردين</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #f59e0b;">
                    <strong style="color: #92400e;">طلبات الشراء:</strong>
                    <span style="font-size: 12px; color: #d97706;">إنشاء وموافقة طلبات الشراء</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #f59e0b;">
                    <strong style="color: #92400e;">أوامر الشراء:</strong>
                    <span style="font-size: 12px; color: #d97706;">إصدار وإدارة أوامر الشراء</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #f59e0b;">
                    <strong style="color: #92400e;">عروض الأسعار:</strong>
                    <span style="font-size: 12px; color: #d97706;">مقارنة وإدارة عروض الأسعار</span>
                </div>
            </div>
        </div>

        <!-- Regulatory Affairs Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-color: #06b6d4;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #164e63; margin: 0;">الشؤون التنظيمية</h4>
                    <p style="font-size: 12px; color: #0891b2; margin: 2px 0 0 0;">Regulatory Affairs</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #06b6d4;">
                    <strong style="color: #164e63;">تسجيل الشركات:</strong>
                    <span style="font-size: 12px; color: #0891b2;">تراخيص وتسجيل الشركات</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #06b6d4;">
                    <strong style="color: #164e63;">تسجيل المنتجات:</strong>
                    <span style="font-size: 12px; color: #0891b2;">تسجيل المنتجات الدوائية</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #06b6d4;">
                    <strong style="color: #164e63;">شهادات الجودة:</strong>
                    <span style="font-size: 12px; color: #0891b2;">إدارة شهادات الجودة</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #06b6d4;">
                    <strong style="color: #164e63;">التفتيش التنظيمي:</strong>
                    <span style="font-size: 12px; color: #0891b2;">جدولة ومتابعة التفتيش</span>
                </div>
            </div>
        </div>

        <!-- Analytics & AI Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #fdf4ff 0%, #fae8ff 100%); border-color: #d946ef;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #d946ef 0%, #c026d3 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #86198f; margin: 0;">الذكاء الاصطناعي</h4>
                    <p style="font-size: 12px; color: #c026d3; margin: 2px 0 0 0;">AI & Analytics</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #d946ef;">
                    <strong style="color: #86198f;">التنبؤات الذكية:</strong>
                    <span style="font-size: 12px; color: #c026d3;">تنبؤات المبيعات والمخزون</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #d946ef;">
                    <strong style="color: #86198f;">تحليل السوق:</strong>
                    <span style="font-size: 12px; color: #c026d3;">تحليل اتجاهات السوق</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #d946ef;">
                    <strong style="color: #86198f;">تحليل العملاء:</strong>
                    <span style="font-size: 12px; color: #c026d3;">تحليل سلوك العملاء</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #d946ef;">
                    <strong style="color: #86198f;">التقارير التنفيذية:</strong>
                    <span style="font-size: 12px; color: #c026d3;">تقارير الإدارة العليا</span>
                </div>
            </div>
        </div>

        <!-- System Guide Permissions -->
        <div style="border: 2px solid #e2e8f0; border-radius: 15px; padding: 25px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-color: #64748b;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #64748b 0%, #475569 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">دليل النظام</h4>
                    <p style="font-size: 12px; color: #475569; margin: 2px 0 0 0;">System Guide</p>
                </div>
            </div>

            <div style="display: grid; gap: 8px;">
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #64748b;">
                    <strong style="color: #1e293b;">الفيديوهات التعليمية:</strong>
                    <span style="font-size: 12px; color: #475569;">مشاهدة الدروس التعليمية</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #64748b;">
                    <strong style="color: #1e293b;">الأسئلة الشائعة:</strong>
                    <span style="font-size: 12px; color: #475569;">الوصول للأسئلة والأجوبة</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #64748b;">
                    <strong style="color: #1e293b;">دليل المستخدم:</strong>
                    <span style="font-size: 12px; color: #475569;">تحميل دليل الاستخدام</span>
                </div>
                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #64748b;">
                    <strong style="color: #1e293b;">الجولات التفاعلية:</strong>
                    <span style="font-size: 12px; color: #475569;">جولات إرشادية في النظام</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 30px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0 0 20px 0; display: flex; align-items: center;">
            <i class="fas fa-plus-circle" style="color: #48bb78; margin-left: 10px;"></i>
            إنشاء دور جديد
        </h3>
        
        <form id="createRoleForm" method="POST" action="{{ route('tenant.roles.create') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">اسم الدور (بالإنجليزية)</label>
                <input type="text" name="name" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مثال: manager">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الاسم المعروض</label>
                <input type="text" name="display_name" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" placeholder="مثال: مدير">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الوصف</label>
                <textarea name="description" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="وصف مختصر للدور..."></textarea>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #4a5568;">الصلاحيات</label>
                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; background: #f8fafc;">

                    <!-- Select All Controls -->
                    <div style="margin-bottom: 20px; padding: 15px; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h4 style="margin: 0; color: #2d3748; font-size: 16px;">
                                <i class="fas fa-check-double" style="color: #48bb78; margin-left: 8px;"></i>
                                التحكم السريع
                            </h4>
                        </div>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="button" onclick="selectAllPermissions()" style="background: #48bb78; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                <i class="fas fa-check-circle"></i> تحديد الكل
                            </button>
                            <button type="button" onclick="deselectAllPermissions()" style="background: #f56565; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                <i class="fas fa-times-circle"></i> إلغاء تحديد الكل
                            </button>
                        </div>
                    </div>

                    @php
                    $moduleGroups = [
                        'sales' => ['name' => '📊 إدارة المبيعات', 'color' => '#48bb78'],
                        'inventory' => ['name' => '📦 إدارة المخزون', 'color' => '#4299e1'],
                        'accounting' => ['name' => '💰 النظام المحاسبي', 'color' => '#ed8936'],
                        'hr' => ['name' => '👥 الموارد البشرية', 'color' => '#9f7aea'],
                        'purchasing' => ['name' => '🚚 إدارة المشتريات', 'color' => '#38b2ac'],
                        'regulatory' => ['name' => '🛡️ الشؤون التنظيمية', 'color' => '#f56565'],
                        'analytics' => ['name' => '📊 التحليلات والذكاء الاصطناعي', 'color' => '#667eea'],
                        'system-guide' => ['name' => '📚 دليل النظام', 'color' => '#68d391'],
                        'reports' => ['name' => '📊 التقارير الديناميكية', 'color' => '#f6ad55'],
                        'roles' => ['name' => '⚙️ إدارة الأدوار', 'color' => '#fc8181'],
                        'permissions' => ['name' => '🔐 إدارة الصلاحيات', 'color' => '#805ad5'],
                        'users' => ['name' => '👤 إدارة المستخدمين', 'color' => '#e53e3e'],
                        'settings' => ['name' => '⚙️ إعدادات النظام', 'color' => '#718096'],
                        'dashboard' => ['name' => '🏠 لوحات التحكم', 'color' => '#319795'],
                        'sales-targets' => ['name' => '🎯 أهداف المبيعات', 'color' => '#d69e2e'],
                        'localization' => ['name' => '🌍 التوطين العراقي', 'color' => '#4fd1c7']
                    ];
                    @endphp

                    <!-- Debug: Show total permissions count -->
                    <div style="margin-bottom: 15px; padding: 10px; background: #e2e8f0; border-radius: 6px; font-size: 12px;">
                        <strong>Debug Info:</strong> إجمالي الصلاحيات: {{ $permissions->count() }} |
                        الوحدات المتاحة: {{ count($moduleGroups) }}
                    </div>

                    @foreach($moduleGroups as $moduleKey => $moduleInfo)
                        @php
                        $modulePermissions = $permissions->filter(function($permission) use ($moduleKey) {
                            return str_starts_with($permission->name, $moduleKey . '.');
                        });
                        @endphp

                        <!-- Debug: Show module info -->
                        <div style="margin-bottom: 10px; padding: 8px; background: #f7fafc; border-radius: 4px; font-size: 11px; color: #4a5568;">
                            <strong>{{ $moduleKey }}:</strong> {{ $modulePermissions->count() }} صلاحية
                            @if($modulePermissions->count() > 0)
                                ({{ $modulePermissions->pluck('name')->take(3)->implode(', ') }}...)
                            @endif
                        </div>

                        @if($modulePermissions->count() > 0)
                        <div style="margin-bottom: 20px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                            <div style="background: {{ $moduleInfo['color'] }}; color: white; padding: 15px; cursor: pointer;" onclick="toggleModulePermissions('{{ $moduleKey }}')">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h5 style="margin: 0; font-size: 16px; font-weight: 600;">
                                        {{ $moduleInfo['name'] }}
                                        <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 12px; margin-right: 10px;">
                                            {{ $modulePermissions->count() }} صلاحية
                                        </span>
                                    </h5>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <label style="display: flex; align-items: center; cursor: pointer; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 6px;">
                                            <input type="checkbox" onchange="toggleModuleCheckboxes('{{ $moduleKey }}', this.checked)" style="margin-left: 8px;">
                                            <span style="font-size: 12px;">تحديد الكل</span>
                                        </label>
                                        <i class="fas fa-chevron-down" id="chevron-{{ $moduleKey }}" style="transition: transform 0.3s;"></i>
                                    </div>
                                </div>
                            </div>
                            <div id="permissions-{{ $moduleKey }}" style="padding: 20px; display: block;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                                    @foreach($modulePermissions as $permission)
                                    <label style="display: flex; align-items: center; cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='transparent'">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="module-{{ $moduleKey }}" style="margin-left: 10px; transform: scale(1.2);">
                                        <div>
                                            <div style="font-size: 13px; color: #2d3748; font-weight: 500;">{{ $permission->description ?? $permission->name }}</div>
                                            <div style="font-size: 11px; color: #718096; margin-top: 2px;">{{ $permission->name }}</div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach

                    <!-- Other permissions not in modules -->
                    @php
                    $otherPermissions = $permissions->filter(function($permission) use ($moduleGroups) {
                        foreach(array_keys($moduleGroups) as $moduleKey) {
                            if(str_starts_with($permission->name, $moduleKey . '.')) {
                                return false;
                            }
                        }
                        return true;
                    });
                    @endphp

                    @if($otherPermissions->count() > 0)
                    <div style="margin-bottom: 20px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                        <div style="background: #718096; color: white; padding: 15px;">
                            <h5 style="margin: 0; font-size: 16px; font-weight: 600;">
                                🔧 صلاحيات أخرى
                                <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 12px; margin-right: 10px;">
                                    {{ $otherPermissions->count() }} صلاحية
                                </span>
                            </h5>
                        </div>
                        <div style="padding: 20px;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                                @foreach($otherPermissions as $permission)
                                <label style="display: flex; align-items: center; cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='#f7fafc'" onmouseout="this.style.background='transparent'">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" style="margin-left: 10px; transform: scale(1.2);">
                                    <div>
                                        <div style="font-size: 13px; color: #2d3748; font-weight: 500;">{{ $permission->description ?? $permission->name }}</div>
                                        <div style="font-size: 11px; color: #718096; margin-top: 2px;">{{ $permission->name }}</div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button type="button" onclick="closeCreateRoleModal()" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; cursor: pointer;">
                    إلغاء
                </button>
                <button type="submit" class="btn-green" style="padding: 12px 24px;">
                    <i class="fas fa-plus-circle"></i>
                    إنشاء الدور
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showCreateRoleModal() {
    document.getElementById('createRoleModal').style.display = 'flex';
}

function closeCreateRoleModal() {
    document.getElementById('createRoleModal').style.display = 'none';
}

// Select/Deselect all permissions
function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);

    // Update module checkboxes
    const moduleCheckboxes = document.querySelectorAll('input[onchange*="toggleModuleCheckboxes"]');
    moduleCheckboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);

    // Update module checkboxes
    const moduleCheckboxes = document.querySelectorAll('input[onchange*="toggleModuleCheckboxes"]');
    moduleCheckboxes.forEach(checkbox => checkbox.checked = false);
}

// Toggle module permissions
function toggleModuleCheckboxes(moduleKey, checked) {
    const moduleCheckboxes = document.querySelectorAll(`.module-${moduleKey}`);
    moduleCheckboxes.forEach(checkbox => checkbox.checked = checked);
}

// Toggle module visibility
function toggleModulePermissions(moduleKey) {
    const permissionsDiv = document.getElementById(`permissions-${moduleKey}`);
    const chevron = document.getElementById(`chevron-${moduleKey}`);

    if (permissionsDiv.style.display === 'none') {
        permissionsDiv.style.display = 'block';
        chevron.style.transform = 'rotate(0deg)';
    } else {
        permissionsDiv.style.display = 'none';
        chevron.style.transform = 'rotate(-90deg)';
    }
}

function showUserPermissionsModal(userId) {
    // Find user data
    const users = @json($tenantUsers->items());
    const user = users.find(u => u.id === userId);

    if (!user) {
        alert('لم يتم العثور على بيانات المستخدم');
        return;
    }

    // Create modal content
    const modalContent = `
        <div id="userPermissionsModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 800px; width: 90%; max-height: 90vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                        <i class="fas fa-user-shield" style="color: #667eea; margin-left: 10px;"></i>
                        تفاصيل المستخدم والصلاحيات
                    </h3>
                    <button onclick="closeUserPermissionsModal()" style="background: none; border: none; font-size: 24px; color: #a0aec0; cursor: pointer; padding: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- User Info Section -->
                <div style="background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); padding: 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
                    <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0; display: flex; align-items: center;">
                        <i class="fas fa-user" style="color: #4299e1; margin-left: 8px;"></i>
                        معلومات المستخدم
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 12px; color: #718096; margin-bottom: 5px;">الاسم الكامل</div>
                            <div style="font-size: 16px; font-weight: 600; color: #2d3748;">${user.name}</div>
                        </div>

                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 12px; color: #718096; margin-bottom: 5px;">البريد الإلكتروني</div>
                            <div style="font-size: 16px; font-weight: 600; color: #2d3748;">${user.email}</div>
                        </div>

                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 12px; color: #718096; margin-bottom: 5px;">الحالة</div>
                            <div style="font-size: 14px; font-weight: 600; color: ${user.is_active ? '#48bb78' : '#f56565'};">
                                <i class="fas fa-circle" style="font-size: 8px; margin-left: 5px;"></i>
                                ${user.is_active ? 'نشط' : 'غير نشط'}
                            </div>
                        </div>

                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 12px; color: #718096; margin-bottom: 5px;">تاريخ الانضمام</div>
                            <div style="font-size: 14px; font-weight: 600; color: #2d3748;">${new Date(user.created_at).toLocaleDateString('ar-SA')}</div>
                        </div>
                    </div>
                </div>

                <!-- Roles Section -->
                <div style="background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%); padding: 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #10b981;">
                    <h4 style="font-size: 18px; font-weight: 700; color: #065f46; margin: 0 0 15px 0; display: flex; align-items: center;">
                        <i class="fas fa-user-tag" style="color: #10b981; margin-left: 8px;"></i>
                        الأدوار المعينة
                    </h4>

                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        ${user.roles && user.roles.length > 0 ?
                            user.roles.map(role => `
                                <div style="background: white; padding: 10px 15px; border-radius: 20px; border: 2px solid #10b981; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-shield-alt" style="color: #10b981; font-size: 12px;"></i>
                                    <span style="font-weight: 600; color: #065f46;">${role.name}</span>
                                </div>
                            `).join('') :
                            '<div style="color: #718096; font-style: italic; padding: 10px;">لم يتم تعيين أي أدوار لهذا المستخدم</div>'
                        }
                    </div>
                </div>

                <!-- Permissions Section -->
                <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); padding: 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #3b82f6;">
                    <h4 style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin: 0 0 15px 0; display: flex; align-items: center;">
                        <i class="fas fa-key" style="color: #3b82f6; margin-left: 8px;"></i>
                        الصلاحيات المباشرة
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                        ${user.permissions && user.permissions.length > 0 ?
                            user.permissions.map(permission => `
                                <div style="background: white; padding: 12px; border-radius: 10px; border: 1px solid #3b82f6; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-check-circle" style="color: #3b82f6; font-size: 12px;"></i>
                                    <span style="font-size: 14px; color: #1e3a8a;">${permission.name}</span>
                                </div>
                            `).join('') :
                            '<div style="color: #718096; font-style: italic; padding: 10px;">لم يتم تعيين صلاحيات مباشرة لهذا المستخدم</div>'
                        }
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
                    <button onclick="showAssignRoleModal(${user.id}, '${user.name}')" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-user-tag"></i>
                        تعيين دور
                    </button>

                    <button onclick="showAssignPermissionModal(${user.id}, '${user.name}')" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-key"></i>
                        تعيين صلاحيات
                    </button>

                    <button onclick="closeUserPermissionsModal()" style="background: #e2e8f0; color: #4a5568; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#cbd5e0'" onmouseout="this.style.background='#e2e8f0'">
                        <i class="fas fa-times"></i>
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('userPermissionsModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
}

function closeUserPermissionsModal() {
    const modal = document.getElementById('userPermissionsModal');
    if (modal) {
        modal.remove();
    }
}

function showAssignRoleModal(userId, userName) {
    // Get available roles
    const roles = @json($roles);

    // Create modal content
    const modalContent = `
        <div id="assignRoleModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                        <i class="fas fa-user-tag" style="color: #48bb78; margin-left: 10px;"></i>
                        تعيين دور للمستخدم
                    </h3>
                    <button onclick="closeAssignRoleModal()" style="background: none; border: none; font-size: 24px; color: #a0aec0; cursor: pointer; padding: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- User Info -->
                <div style="background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%); padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #10b981;">
                    <h4 style="font-size: 16px; font-weight: 700; color: #065f46; margin: 0 0 10px 0; display: flex; align-items: center;">
                        <i class="fas fa-user" style="color: #10b981; margin-left: 8px;"></i>
                        المستخدم المحدد
                    </h4>
                    <div style="font-size: 18px; font-weight: 600; color: #065f46;">${userName}</div>
                    <div style="font-size: 12px; color: #047857; margin-top: 5px;">معرف المستخدم: ${userId}</div>
                </div>

                <!-- Available Roles -->
                <div style="margin-bottom: 25px;">
                    <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0; display: flex; align-items: center;">
                        <i class="fas fa-list" style="color: #4299e1; margin-left: 8px;"></i>
                        الأدوار المتاحة
                    </h4>

                    <div style="display: grid; gap: 10px; max-height: 300px; overflow-y: auto; padding: 10px; border: 1px solid #e2e8f0; border-radius: 10px; background: #f7fafc;">
                        ${roles.length > 0 ?
                            roles.map(role => `
                                <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.3s ease;"
                                     onclick="selectRole(${role.id}, '${role.name}')"
                                     onmouseover="this.style.borderColor='#48bb78'; this.style.transform='translateX(-3px)'"
                                     onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateX(0)'">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 5px;">${role.name}</div>
                                            <div style="font-size: 13px; color: #718096;">${role.description || 'لا يوجد وصف'}</div>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <span style="background: #e2e8f0; color: #4a5568; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                                                ${role.users ? role.users.length : 0} مستخدم
                                            </span>
                                            <i class="fas fa-chevron-left" style="color: #a0aec0; font-size: 12px;"></i>
                                        </div>
                                    </div>
                                </div>
                            `).join('') :
                            '<div style="color: #718096; font-style: italic; padding: 20px; text-align: center;">لا توجد أدوار متاحة</div>'
                        }
                    </div>
                </div>

                <!-- Selected Role Display -->
                <div id="selectedRoleDisplay" style="display: none; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #3b82f6;">
                    <h4 style="font-size: 16px; font-weight: 700; color: #1e3a8a; margin: 0 0 10px 0; display: flex; align-items: center;">
                        <i class="fas fa-check-circle" style="color: #3b82f6; margin-left: 8px;"></i>
                        الدور المحدد
                    </h4>
                    <div id="selectedRoleName" style="font-size: 18px; font-weight: 600; color: #1e3a8a;"></div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
                    <button id="assignRoleBtn" onclick="confirmAssignRole(${userId}, '${userName}')"
                            style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; opacity: 0.5;"
                            disabled
                            onmouseover="if(!this.disabled) this.style.transform='translateY(-2px)'"
                            onmouseout="if(!this.disabled) this.style.transform='translateY(0)'">
                        <i class="fas fa-save"></i>
                        تعيين الدور
                    </button>

                    <button onclick="closeAssignRoleModal()" style="background: #e2e8f0; color: #4a5568; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#cbd5e0'" onmouseout="this.style.background='#e2e8f0'">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('assignRoleModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
}

let selectedRoleId = null;

function selectRole(roleId, roleName) {
    selectedRoleId = roleId;

    // Update selected role display
    const selectedDisplay = document.getElementById('selectedRoleDisplay');
    const selectedName = document.getElementById('selectedRoleName');
    const assignBtn = document.getElementById('assignRoleBtn');

    selectedDisplay.style.display = 'block';
    selectedName.textContent = roleName;

    // Enable assign button
    assignBtn.disabled = false;
    assignBtn.style.opacity = '1';

    // Highlight selected role
    document.querySelectorAll('#assignRoleModal [onclick^="selectRole"]').forEach(el => {
        el.style.background = 'white';
        el.style.borderColor = '#e2e8f0';
    });

    event.target.closest('[onclick^="selectRole"]').style.background = '#f0fff4';
    event.target.closest('[onclick^="selectRole"]').style.borderColor = '#48bb78';
}

function confirmAssignRole(userId, userName) {
    if (!selectedRoleId) {
        alert('يرجى اختيار دور أولاً');
        return;
    }

    // Here you would normally send an AJAX request to assign the role
    alert(`تم تعيين الدور بنجاح!

المستخدم: ${userName}
معرف الدور: ${selectedRoleId}

سيتم إرسال طلب إلى الخادم لحفظ التغييرات.`);

    closeAssignRoleModal();

    // Optionally refresh the page or update the UI
    // location.reload();
}

function closeAssignRoleModal() {
    const modal = document.getElementById('assignRoleModal');
    if (modal) {
        modal.remove();
    }
    selectedRoleId = null;
}

function showAssignPermissionModal(userId, userName) {
    // Get available permissions and group them by module
    const allPermissions = @json($permissions);

    // Group permissions by module
    const permissionGroups = {
        'إدارة النظام': allPermissions.filter(p =>
            p.name.includes('dashboard') ||
            p.name.includes('system') ||
            p.name.includes('backup') ||
            p.name.includes('restore')
        ),
        'المستخدمين والأدوار': allPermissions.filter(p =>
            p.name.includes('user') ||
            p.name.includes('role') ||
            p.name.includes('permission')
        ),
        'إدارة المبيعات': allPermissions.filter(p =>
            p.name.includes('sales') ||
            p.name.includes('customer') ||
            p.name.includes('invoice') ||
            p.name.includes('return') ||
            p.name.includes('target')
        ),
        'إدارة المخزون': allPermissions.filter(p =>
            p.name.includes('inventory') ||
            p.name.includes('product') ||
            p.name.includes('category') ||
            p.name.includes('warehouse') ||
            p.name.includes('stock')
        ),
        'النظام المحاسبي': allPermissions.filter(p =>
            p.name.includes('accounting') ||
            p.name.includes('account') ||
            p.name.includes('journal') ||
            p.name.includes('trial') ||
            p.name.includes('balance') ||
            p.name.includes('income') ||
            p.name.includes('cash_flow') ||
            p.name.includes('cost_center') ||
            p.name.includes('financial')
        ),
        'الموارد البشرية': allPermissions.filter(p =>
            p.name.includes('hr') ||
            p.name.includes('employee') ||
            p.name.includes('department') ||
            p.name.includes('position') ||
            p.name.includes('attendance') ||
            p.name.includes('leave') ||
            p.name.includes('payroll')
        ),
        'إدارة المشتريات': allPermissions.filter(p =>
            p.name.includes('purchasing') ||
            p.name.includes('supplier') ||
            p.name.includes('purchase') ||
            p.name.includes('quotation') ||
            p.name.includes('goods_receipt')
        ),
        'الشؤون التنظيمية': allPermissions.filter(p =>
            p.name.includes('regulatory') ||
            p.name.includes('registration') ||
            p.name.includes('certificate') ||
            p.name.includes('inspection') ||
            p.name.includes('recall')
        ),
        'الذكاء الاصطناعي': allPermissions.filter(p =>
            p.name.includes('analytics') ||
            p.name.includes('ai') ||
            p.name.includes('predictive') ||
            p.name.includes('kpi') ||
            p.name.includes('trend')
        ),
        'دليل النظام': allPermissions.filter(p =>
            p.name.includes('guide') ||
            p.name.includes('tutorial') ||
            p.name.includes('faq') ||
            p.name.includes('manual') ||
            p.name.includes('tour') ||
            p.name.includes('support')
        ),
        'التقارير العامة': allPermissions.filter(p =>
            p.name.includes('report') && !p.name.includes('sales_report') && !p.name.includes('inventory_report')
        )
    };

    // Create modal content
    const modalContent = `
        <div id="assignPermissionModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 700px; width: 90%; max-height: 90vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                        <i class="fas fa-key" style="color: #9f7aea; margin-left: 10px;"></i>
                        تعيين صلاحيات للمستخدم
                    </h3>
                    <button onclick="closeAssignPermissionModal()" style="background: none; border: none; font-size: 24px; color: #a0aec0; cursor: pointer; padding: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- User Info -->
                <div style="background: linear-gradient(135deg, #faf5ff 0%, #e9d5ff 100%); padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #9f7aea;">
                    <h4 style="font-size: 16px; font-weight: 700; color: #581c87; margin: 0 0 10px 0; display: flex; align-items: center;">
                        <i class="fas fa-user" style="color: #9f7aea; margin-left: 8px;"></i>
                        المستخدم المحدد
                    </h4>
                    <div style="font-size: 18px; font-weight: 600; color: #581c87;">${userName}</div>
                    <div style="font-size: 12px; color: #7c3aed; margin-top: 5px;">معرف المستخدم: ${userId}</div>
                </div>

                <!-- Search Box -->
                <div style="margin-bottom: 20px;">
                    <input type="text" id="permissionSearch" placeholder="البحث في الصلاحيات..."
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 14px; transition: border-color 0.3s ease;"
                           onkeyup="filterPermissions()"
                           onfocus="this.style.borderColor='#9f7aea'"
                           onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <!-- Available Permissions by Module -->
                <div style="margin-bottom: 25px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                            <i class="fas fa-list-check" style="color: #4299e1; margin-left: 8px;"></i>
                            الصلاحيات حسب الأقسام
                        </h4>
                        <div style="display: flex; gap: 10px;">
                            <button onclick="selectAllPermissions()" style="background: #48bb78; color: white; padding: 6px 12px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                تحديد الكل
                            </button>
                            <button onclick="deselectAllPermissions()" style="background: #f56565; color: white; padding: 6px 12px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                إلغاء التحديد
                            </button>
                        </div>
                    </div>

                    <div id="permissionsContainer" style="max-height: 400px; overflow-y: auto; padding: 15px; border: 1px solid #e2e8f0; border-radius: 10px; background: #f7fafc;">
                        ${Object.keys(permissionGroups).map(groupName => {
                            const groupPermissions = permissionGroups[groupName];
                            if (groupPermissions.length === 0) return '';

                            const moduleColors = {
                                'إدارة النظام': '#667eea',
                                'المستخدمين والأدوار': '#4299e1',
                                'إدارة المبيعات': '#10b981',
                                'إدارة المخزون': '#3b82f6',
                                'النظام المحاسبي': '#ef4444',
                                'الموارد البشرية': '#8b5cf6',
                                'إدارة المشتريات': '#f59e0b',
                                'الشؤون التنظيمية': '#06b6d4',
                                'الذكاء الاصطناعي': '#d946ef',
                                'دليل النظام': '#64748b',
                                'التقارير العامة': '#84cc16'
                            };

                            const moduleColor = moduleColors[groupName] || '#6b7280';

                            return `
                                <div style="margin-bottom: 20px; border: 2px solid ${moduleColor}; border-radius: 12px; overflow: hidden;">
                                    <div style="background: ${moduleColor}; color: white; padding: 12px 15px; font-weight: 600; font-size: 16px; display: flex; justify-content: space-between; align-items: center;">
                                        <span>${groupName}</span>
                                        <div style="display: flex; gap: 8px;">
                                            <button onclick="selectModulePermissions('${groupName}')" style="background: rgba(255,255,255,0.2); color: white; padding: 4px 8px; border: none; border-radius: 4px; font-size: 11px; cursor: pointer;">
                                                تحديد القسم
                                            </button>
                                            <span style="background: rgba(255,255,255,0.2); padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                                ${groupPermissions.length} صلاحية
                                            </span>
                                        </div>
                                    </div>
                                    <div style="padding: 15px; background: white;">
                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 8px;">
                                            ${groupPermissions.map(permission => `
                                                <div class="permission-item module-${groupName.replace(/\s+/g, '-')}" style="background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;"
                                                     onclick="togglePermission(${permission.id}, '${permission.name}')"
                                                     onmouseover="this.style.borderColor='${moduleColor}'; this.style.transform='translateY(-1px)'"
                                                     onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'">
                                                    <input type="checkbox" id="perm_${permission.id}" style="transform: scale(1.1); accent-color: ${moduleColor};">
                                                    <label for="perm_${permission.id}" style="font-size: 13px; color: #2d3748; cursor: pointer; flex: 1; line-height: 1.3;">
                                                        <div style="font-weight: 600;">${permission.description || permission.name}</div>
                                                        <div style="font-size: 11px; color: #718096; margin-top: 2px;">${permission.name}</div>
                                                    </label>
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>

                <!-- Selected Permissions Count -->
                <div id="selectedPermissionsCount" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); padding: 15px; border-radius: 10px; margin-bottom: 25px; border: 1px solid #3b82f6; text-align: center;">
                    <span style="font-size: 16px; font-weight: 600; color: #1e3a8a;">تم تحديد <span id="countNumber">0</span> صلاحية</span>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
                    <button id="assignPermissionsBtn" onclick="confirmAssignPermissions(${userId}, '${userName}')"
                            style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; opacity: 0.5;"
                            disabled
                            onmouseover="if(!this.disabled) this.style.transform='translateY(-2px)'"
                            onmouseout="if(!this.disabled) this.style.transform='translateY(0)'">
                        <i class="fas fa-save"></i>
                        تعيين الصلاحيات
                    </button>

                    <button onclick="closeAssignPermissionModal()" style="background: #e2e8f0; color: #4a5568; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#cbd5e0'" onmouseout="this.style.background='#e2e8f0'">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('assignPermissionModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
}

let selectedPermissions = [];

function togglePermission(permissionId, permissionName) {
    const checkbox = document.getElementById(`perm_${permissionId}`);
    const index = selectedPermissions.findIndex(p => p.id === permissionId);

    if (checkbox.checked) {
        if (index === -1) {
            selectedPermissions.push({id: permissionId, name: permissionName});
        }
    } else {
        if (index > -1) {
            selectedPermissions.splice(index, 1);
        }
    }

    updatePermissionsCount();
}

function updatePermissionsCount() {
    const countElement = document.getElementById('countNumber');
    const assignBtn = document.getElementById('assignPermissionsBtn');

    countElement.textContent = selectedPermissions.length;

    if (selectedPermissions.length > 0) {
        assignBtn.disabled = false;
        assignBtn.style.opacity = '1';
    } else {
        assignBtn.disabled = true;
        assignBtn.style.opacity = '0.5';
    }
}

function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('#assignPermissionModal input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
        const permissionId = parseInt(checkbox.id.replace('perm_', ''));
        const permissionName = checkbox.nextElementSibling.textContent;

        if (!selectedPermissions.find(p => p.id === permissionId)) {
            selectedPermissions.push({id: permissionId, name: permissionName});
        }
    });
    updatePermissionsCount();
}

function deselectAllPermissions() {
    const checkboxes = document.querySelectorAll('#assignPermissionModal input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    selectedPermissions = [];
    updatePermissionsCount();
}

function selectModulePermissions(moduleName) {
    const moduleClass = 'module-' + moduleName.replace(/\s+/g, '-');
    const moduleCheckboxes = document.querySelectorAll(`.${moduleClass} input[type="checkbox"]`);

    moduleCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
        const permissionId = parseInt(checkbox.id.replace('perm_', ''));
        const permissionName = checkbox.nextElementSibling.querySelector('div').textContent;

        if (!selectedPermissions.find(p => p.id === permissionId)) {
            selectedPermissions.push({id: permissionId, name: permissionName});
        }
    });

    updatePermissionsCount();
}

function filterPermissions() {
    const searchTerm = document.getElementById('permissionSearch').value.toLowerCase();
    const permissionItems = document.querySelectorAll('.permission-item');

    permissionItems.forEach(item => {
        const permissionName = item.querySelector('label').textContent.toLowerCase();
        if (permissionName.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function confirmAssignPermissions(userId, userName) {
    if (selectedPermissions.length === 0) {
        alert('يرجى اختيار صلاحية واحدة على الأقل');
        return;
    }

    const permissionNames = selectedPermissions.map(p => p.name).join('\\n• ');

    // Here you would normally send an AJAX request to assign the permissions
    alert(`تم تعيين الصلاحيات بنجاح!

المستخدم: ${userName}
عدد الصلاحيات: ${selectedPermissions.length}

الصلاحيات المعينة:
• ${permissionNames}

سيتم إرسال طلب إلى الخادم لحفظ التغييرات.`);

    closeAssignPermissionModal();

    // Optionally refresh the page or update the UI
    // location.reload();
}

function closeAssignPermissionModal() {
    const modal = document.getElementById('assignPermissionModal');
    if (modal) {
        modal.remove();
    }
    selectedPermissions = [];
}

function showRoleDetailsModal(roleId) {
    // Find role data
    const roles = @json($roles);
    const role = roles.find(r => r.id === roleId);

    if (!role) {
        alert('لم يتم العثور على بيانات الدور');
        return;
    }

    // Create modal content
    const modalContent = `
        <div id="roleDetailsModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 700px; width: 90%; max-height: 90vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                        <i class="fas fa-user-tag" style="color: #48bb78; margin-left: 10px;"></i>
                        تفاصيل الدور
                    </h3>
                    <button onclick="closeRoleDetailsModal()" style="background: none; border: none; font-size: 24px; color: #a0aec0; cursor: pointer; padding: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Role Info Section -->
                <div style="background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%); padding: 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #10b981;">
                    <h4 style="font-size: 18px; font-weight: 700; color: #065f46; margin: 0 0 15px 0; display: flex; align-items: center;">
                        <i class="fas fa-info-circle" style="color: #10b981; margin-left: 8px;"></i>
                        معلومات الدور
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #10b981;">
                            <div style="font-size: 12px; color: #047857; margin-bottom: 5px;">اسم الدور</div>
                            <div style="font-size: 16px; font-weight: 600; color: #065f46;">${role.name}</div>
                        </div>

                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #10b981;">
                            <div style="font-size: 12px; color: #047857; margin-bottom: 5px;">الوصف</div>
                            <div style="font-size: 14px; color: #065f46;">${role.description || 'لا يوجد وصف'}</div>
                        </div>

                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #10b981;">
                            <div style="font-size: 12px; color: #047857; margin-bottom: 5px;">عدد المستخدمين</div>
                            <div style="font-size: 16px; font-weight: 600; color: #065f46; display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-users" style="font-size: 12px;"></i>
                                ${role.users ? role.users.length : 0} مستخدم
                            </div>
                        </div>

                        <div style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #10b981;">
                            <div style="font-size: 12px; color: #047857; margin-bottom: 5px;">تاريخ الإنشاء</div>
                            <div style="font-size: 14px; color: #065f46;">${new Date(role.created_at).toLocaleDateString('ar-SA')}</div>
                        </div>
                    </div>
                </div>

                <!-- Users Section -->
                <div style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); padding: 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #3b82f6;">
                    <h4 style="font-size: 18px; font-weight: 700; color: #1e3a8a; margin: 0 0 15px 0; display: flex; align-items: center;">
                        <i class="fas fa-users" style="color: #3b82f6; margin-left: 8px;"></i>
                        المستخدمون المعينون
                    </h4>

                    <div style="display: grid; gap: 10px; max-height: 200px; overflow-y: auto;">
                        ${role.users && role.users.length > 0 ?
                            role.users.map(user => `
                                <div style="background: white; padding: 12px; border-radius: 10px; border: 1px solid #3b82f6; display: flex; align-items: center; justify-content: space-between;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-user" style="color: #3b82f6; font-size: 12px;"></i>
                                        <div>
                                            <div style="font-weight: 600; color: #1e3a8a;">${user.name}</div>
                                            <div style="font-size: 12px; color: #4299e1;">${user.email}</div>
                                        </div>
                                    </div>
                                    <div style="font-size: 12px; color: ${user.is_active ? '#48bb78' : '#f56565'};">
                                        <i class="fas fa-circle" style="font-size: 6px; margin-left: 3px;"></i>
                                        ${user.is_active ? 'نشط' : 'غير نشط'}
                                    </div>
                                </div>
                            `).join('') :
                            '<div style="color: #718096; font-style: italic; padding: 20px; text-align: center;">لا يوجد مستخدمون معينون لهذا الدور</div>'
                        }
                    </div>
                </div>

                <!-- Permissions Section -->
                <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); padding: 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #ef4444;">
                    <h4 style="font-size: 18px; font-weight: 700; color: #7f1d1d; margin: 0 0 15px 0; display: flex; align-items: center;">
                        <i class="fas fa-key" style="color: #ef4444; margin-left: 8px;"></i>
                        صلاحيات الدور
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; max-height: 200px; overflow-y: auto;">
                        ${role.permissions && role.permissions.length > 0 ?
                            role.permissions.map(permission => `
                                <div style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #ef4444; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-check-circle" style="color: #ef4444; font-size: 12px;"></i>
                                    <span style="font-size: 13px; color: #7f1d1d;">${permission.name}</span>
                                </div>
                            `).join('') :
                            '<div style="color: #718096; font-style: italic; padding: 20px; text-align: center;">لا توجد صلاحيات محددة لهذا الدور</div>'
                        }
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
                    <button onclick="editRole(${role.id})" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-edit"></i>
                        تعديل الدور
                    </button>

                    <button onclick="closeRoleDetailsModal()" style="background: #e2e8f0; color: #4a5568; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='#cbd5e0'" onmouseout="this.style.background='#e2e8f0'">
                        <i class="fas fa-times"></i>
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('roleDetailsModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalContent);
}

function closeRoleDetailsModal() {
    const modal = document.getElementById('roleDetailsModal');
    if (modal) {
        modal.remove();
    }
}

function editRole(roleId) {
    alert('سيتم توجيهك إلى صفحة تعديل الدور رقم: ' + roleId);
    closeRoleDetailsModal();
}

// Close modal when clicking outside
document.getElementById('createRoleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateRoleModal();
    }
});
</script>
@endpush
@endsection
