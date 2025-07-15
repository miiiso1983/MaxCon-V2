@extends('layouts.modern')

@section('page-title', 'عرض المستخدم')
@section('page-description', 'تفاصيل المستخدم: ' . $user->name)

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; gap: 25px;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 800; backdrop-filter: blur(10px);">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    {{ $user->name }} 👤
                </h1>
                <p style="font-size: 18px; margin: 5px 0 15px 0; opacity: 0.9;">
                    {{ $user->email }}
                </p>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    @if($user->is_active ?? true)
                        <div style="background: rgba(72, 187, 120, 0.3); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                            <span style="font-size: 14px; font-weight: 600;">نشط</span>
                        </div>
                    @else
                        <div style="background: rgba(237, 137, 54, 0.3); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-ban" style="margin-left: 8px; color: #fbbf24;"></i>
                            <span style="font-size: 14px; font-weight: 600;">معطل</span>
                        </div>
                    @endif

                    @if($user->roles->isNotEmpty())
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-crown" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px; font-weight: 600;">{{ $user->roles->first()->name }}</span>
                        </div>
                    @endif

                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">عضو منذ {{ $user->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">

    <!-- User Details -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Basic Information -->
        <div>
            <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #2d3748;">المعلومات الأساسية</h3>
            
            <div style="space-y: 15px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">الاسم الكامل</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px; color: #2d3748;">{{ $user->name }}</div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">البريد الإلكتروني</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px; color: #2d3748;">{{ $user->email }}</div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">رقم الهاتف</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px; color: #2d3748;">{{ $user->phone ?? 'غير محدد' }}</div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">تاريخ التسجيل</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px; color: #2d3748;">{{ $user->created_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div>
            <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #2d3748;">معلومات الحساب</h3>
            
            <div style="space-y: 15px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">الحالة</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px;">
                        @if($user->is_active ?? true)
                            <span class="status-badge status-active">نشط</span>
                        @else
                            <span class="status-badge status-inactive">معطل</span>
                        @endif
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">الأدوار</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px;">
                        @if($user->roles->isNotEmpty())
                            @foreach($user->roles as $role)
                                <span style="background: #e6fffa; color: #234e52; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-left: 5px;">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        @else
                            <span style="color: #718096;">لا توجد أدوار مُعينة</span>
                        @endif
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">تأكيد البريد الإلكتروني</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px;">
                        @if($user->email_verified_at)
                            <span style="color: #48bb78;">✓ مؤكد في {{ $user->email_verified_at->format('Y-m-d') }}</span>
                        @else
                            <span style="color: #f56565;">✗ غير مؤكد</span>
                        @endif
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">آخر تسجيل دخول</label>
                    <div style="padding: 10px; background: #f7fafc; border-radius: 8px; color: #2d3748;">
                        {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'لم يسجل دخول بعد' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
        <a href="{{ route('admin.users.index') }}" 
           style="padding: 12px 24px; border: 1px solid #e2e8f0; border-radius: 8px; color: #4a5568; text-decoration: none; font-weight: 600;">
            العودة للقائمة
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-blue">
            <i class="fas fa-edit"></i>
            تعديل المستخدم
        </a>
    </div>
</div>
@endsection
