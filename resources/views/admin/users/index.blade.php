@extends('layouts.modern')

@section('page-title', 'إدارة المستخدمين')
@section('page-description', 'إدارة شاملة لجميع المستخدمين في النظام')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            إدارة شاملة لجميع المستخدمين في النظام
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-user-shield" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">إدارة المستخدمين</span>
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
    <a href="{{ route('admin.users.export') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3); transition: all 0.3s ease;">
        <i class="fas fa-download"></i>
        تصدير البيانات
    </a>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 25px; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); opacity: 0.6;">
        <i class="fas fa-info-circle"></i>
        إضافة المستخدمين من صلاحيات مدير المؤسسة
    </div>
</div>

<div class="content-card">

    <!-- Search and Filters -->
    <div class="search-filters">
        <input type="text" class="search-box" placeholder="بحث">
        <select class="filter-select">
            <option>جميع الأدوار</option>
            <option>مدير عام</option>
            <option>مدير مستأجر</option>
            <option>موظف</option>
        </select>
        <select class="filter-select">
            <option>الحالة</option>
            <option>نشط</option>
            <option>معطل</option>
        </select>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th>الإجراءات</th>
                <th>الحالة</th>
                <th>الدور</th>
                <th>تاريخ التسجيل</th>
                <th>البريد الإلكتروني</th>
                <th>الاسم</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <a href="{{ route('admin.users.show', $user) }}"
                               style="color: #4299e1; text-decoration: none; padding: 4px;"
                               title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               style="color: #4299e1; text-decoration: none; padding: 4px;"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteUser({{ $user->id }})"
                                    style="background: none; border: none; color: #e53e3e; cursor: pointer; padding: 4px;"
                                    title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                            @if($user->is_active ?? true)
                                <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit"
                                            style="background: none; border: none; color: #ed8936; cursor: pointer; padding: 4px;"
                                            title="تعطيل"
                                            onclick="return confirm('هل أنت متأكد من تعطيل هذا المستخدم؟')">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.activate', $user) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit"
                                            style="background: none; border: none; color: #48bb78; cursor: pointer; padding: 4px;"
                                            title="تفعيل"
                                            onclick="return confirm('هل أنت متأكد من تفعيل هذا المستخدم؟')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($user->is_active ?? true)
                            <span class="status-badge status-active">نشط</span>
                        @else
                            <span class="status-badge status-inactive">معطل</span>
                        @endif
                    </td>
                    <td>
                        @if($user->roles->isNotEmpty())
                            {{ $user->roles->first()->name }}
                        @else
                            غير محدد
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div>
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #718096;">
                        لا توجد مستخدمين
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    @endif
</div>

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
            <div style="font-size: 14px; opacity: 0.9;">في جميع المؤسسات</div>
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

    <!-- المديرين العامين -->
    <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(159, 122, 234, 0.3);">
        <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-crown" style="font-size: 24px;"></i>
                </div>
                <div style="text-align: left;">
                    <div style="font-size: 36px; font-weight: 800; line-height: 1;">{{ $stats['super_admins'] ?? 0 }}</div>
                    <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">مدير</div>
                </div>
            </div>
            <div style="font-size: 16px; font-weight: 600; margin-bottom: 5px;">المديرين العامين</div>
            <div style="font-size: 14px; opacity: 0.9;">صلاحيات كاملة</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteUser(userId) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}`;

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}

// Add hover effects to action buttons
document.addEventListener('DOMContentLoaded', function() {
    const actionButtons = document.querySelectorAll('a[style*="background: linear-gradient"]');

    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = this.style.boxShadow.replace('0.3)', '0.5)');
        });

        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = this.style.boxShadow.replace('0.5)', '0.3)');
        });
    });

    // Add fadeInUp animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .content-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .content-card:nth-child(2) { animation-delay: 0.1s; }
        .content-card:nth-child(3) { animation-delay: 0.2s; }
        .content-card:nth-child(4) { animation-delay: 0.3s; }
    `;
    document.head.appendChild(style);
});
</script>
@endpush

@endsection
