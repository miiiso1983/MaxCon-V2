@extends('layouts.modern')

@section('page-title', 'إدارة المستخدمين')
@section('page-description', 'إدارة مستخدمي المؤسسة')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-users" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة المستخدمين 👥
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة مستخدمي مؤسستك
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-user-tie" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">مدير المؤسسة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-users" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $stats['total'] ?? 0 }} مستخدم</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ $stats['active'] ?? 0 }} نشط</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div style="display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
    <a href="{{ route('tenant.users.export') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3); transition: all 0.3s ease;">
        <i class="fas fa-download"></i>
        تصدير البيانات
    </a>
    <a href="{{ route('tenant.users.create') }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
        <i class="fas fa-plus"></i>
        إضافة مستخدم جديد
    </a>
</div>

<div class="content-card">
    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-top: 30px;">
        <!-- إجمالي المستخدمين -->
        <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(66, 153, 225, 0.3);">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                        <i class="fas fa-users" style="font-size: 24px;"></i>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['total'] ?? 0 }}</div>
                        <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">مستخدم</div>
                    </div>
                </div>
                <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">إجمالي المستخدمين</div>
                <div style="font-size: 14px; opacity: 0.9;">في مؤسستك</div>
            </div>
        </div>

        <!-- المستخدمين النشطين -->
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(72, 187, 120, 0.3);">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                        <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['active'] ?? 0 }}</div>
                        <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">نشط</div>
                    </div>
                </div>
                <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المستخدمين النشطين</div>
                <div style="font-size: 14px; opacity: 0.9;">يستخدمون النظام</div>
            </div>
        </div>

        <!-- المستخدمين المعطلين -->
        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(237, 137, 54, 0.3);">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                        <i class="fas fa-ban" style="font-size: 24px;"></i>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['inactive'] ?? 0 }}</div>
                        <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">معطل</div>
                    </div>
                </div>
                <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المستخدمين المعطلين</div>
                <div style="font-size: 14px; opacity: 0.9;">غير نشطين</div>
            </div>
        </div>

        <!-- المديرين -->
        <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(159, 122, 234, 0.3);">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                        <i class="fas fa-user-tie" style="font-size: 24px;"></i>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['admins'] ?? 0 }}</div>
                        <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">مدير</div>
                    </div>
                </div>
                <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المديرين</div>
                <div style="font-size: 14px; opacity: 0.9;">صلاحيات إدارية</div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div style="margin-top: 30px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-table" style="color: #48bb78; margin-left: 10px;"></i>
            قائمة المستخدمين
        </h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f7fafc;">
                        <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">المستخدم</th>
                        <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الدور</th>
                        <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الحالة</th>
                        <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">تاريخ التسجيل</th>
                        <th style="padding: 12px; text-align: right; font-weight: 600; color: #4a5568; border-bottom: 1px solid #e2e8f0;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #48bb78; color: white; display: flex; align-items: center; justify-content: center; margin-left: 10px; font-weight: 600;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $user->name }}</div>
                                    <div style="font-size: 14px; color: #718096;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            @if($user->roles->isNotEmpty())
                                <span style="background: #e6fffa; color: #234e52; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    {{ $user->roles->first()->name }}
                                </span>
                            @else
                                <span style="color: #718096;">غير محدد</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            @if($user->is_active ?? true)
                                <span class="status-badge status-active">نشط</span>
                            @else
                                <span class="status-badge status-inactive">معطل</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0; color: #718096;">
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                        <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <a href="{{ route('tenant.users.show', $user) }}" 
                                   style="color: #4299e1; text-decoration: none; padding: 4px;" 
                                   title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenant.users.edit', $user) }}" 
                                   style="color: #4299e1; text-decoration: none; padding: 4px;" 
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <button onclick="deleteUser({{ $user->id }})" 
                                        style="background: none; border: none; color: #e53e3e; cursor: pointer; padding: 4px;" 
                                        title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #718096;">
                            لا توجد مستخدمين في مؤسستك حالياً
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function deleteUser(userId) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/tenant/users/${userId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
