@extends('layouts.modern')

@section('page-title', 'عرض المستأجر')
@section('page-description', 'تفاصيل المستأجر')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; gap: 25px;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 800; backdrop-filter: blur(10px);">
                T
            </div>
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    تفاصيل المستأجر 🏢
                </h1>
                <p style="font-size: 18px; margin: 5px 0 15px 0; opacity: 0.9;">
                    عرض معلومات المؤسسة التفصيلية
                </p>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(72, 187, 120, 0.3); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px; font-weight: 600;">نشط</span>
                    </div>

                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-layer-group" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">خطة أساسية</span>
                    </div>

                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-users" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">10 مستخدم</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div style="display: flex; gap: 15px; justify-content: flex-end; margin-bottom: 30px;">
    <a href="{{ route('admin.tenants.maxcon') }}"
       style="padding: 12px 24px; border: 1px solid #e2e8f0; border-radius: 8px; color: #4a5568; text-decoration: none; font-weight: 600;">
        العودة للقائمة
    </a>
    <a href="{{ route('admin.tenants.edit', 1) }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-edit"></i>
        تعديل المؤسسة
    </a>
</div>

<!-- Tenant Information Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 30px;">
    <!-- Basic Information Card -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-building" style="color: #667eea;"></i>
            المعلومات الأساسية
        </h3>

        <div>
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">اسم المؤسسة:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $tenant->name ?? 'غير محدد' }}
                </p>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">الرمز المميز:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $tenant->slug ?? 'غير محدد' }}
                </p>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">الحالة:</label>
                @if($tenant->status === 'active')
                    <span style="padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600; background: #c6f6d5; color: #22543d;">
                        🟢 نشط
                    </span>
                @else
                    <span style="padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600; background: #fed7d7; color: #742a2a;">
                        🔴 غير نشط
                    </span>
                @endif
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">الخطة:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ ucfirst($tenant->plan ?? 'basic') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Information Card -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-address-card" style="color: #48bb78;"></i>
            معلومات الاتصال
        </h3>

        <div>
            @if($tenant->contact_info)
                @if(isset($tenant->contact_info['email']))
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">البريد الإلكتروني:</label>
                    <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                        <i class="fas fa-envelope" style="color: #667eea; margin-left: 8px;"></i>
                        {{ $tenant->contact_info['email'] }}
                    </p>
                </div>
                @endif

                @if(isset($tenant->contact_info['phone']))
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">رقم الهاتف:</label>
                    <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                        <i class="fas fa-phone" style="color: #48bb78; margin-left: 8px;"></i>
                        {{ $tenant->contact_info['phone'] }}
                    </p>
                </div>
                @endif

                @if(isset($tenant->contact_info['address']))
                <div style="margin-bottom: 15px;">
                    <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">العنوان:</label>
                    <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                        <i class="fas fa-map-marker-alt" style="color: #ed8936; margin-left: 8px;"></i>
                        {{ $tenant->contact_info['address'] }}
                    </p>
                </div>
                @endif
            @else
                <p style="color: #a0aec0; font-style: italic; text-align: center; padding: 20px;">
                    لم يتم إدخال معلومات الاتصال بعد
                </p>
            @endif
        </div>
    </div>

    <!-- Technical Information Card -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-server" style="color: #ed8936;"></i>
            المعلومات التقنية
        </h3>

        <div>
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">النطاق الفرعي:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $tenant->subdomain ?? 'غير محدد' }}
                </p>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">النطاق المخصص:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $tenant->domain ?? 'غير محدد' }}
                </p>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">قاعدة البيانات:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $tenant->database_name ?? 'افتراضية' }}
                </p>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 5px;">الحد الأقصى للمستخدمين:</label>
                <p style="color: #2d3748; font-size: 16px; margin: 0; padding: 8px 12px; background: #f7fafc; border-radius: 8px;">
                    {{ $tenant->max_users ?? 'غير محدد' }} مستخدم
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics and Usage Information -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- Users Statistics -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-users" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">المستخدمين</h4>
        </div>
        <p style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">
            {{ $tenant->users->count() ?? 0 }}
        </p>
        <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
            من أصل {{ $tenant->max_users ?? 'غير محدد' }}
        </p>
    </div>

    <!-- Storage Usage -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-hdd" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">التخزين</h4>
        </div>
        <p style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">
            {{ number_format(($tenant->storage_limit ?? 1073741824) / 1073741824, 1) }} GB
        </p>
        <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
            الحد المسموح
        </p>
    </div>

    <!-- License Status -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-certificate" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">الترخيص</h4>
        </div>
        @if($tenant->license_expires_at)
            <p style="font-size: 16px; font-weight: 600; color: #2d3748; margin: 0;">
                {{ \Carbon\Carbon::parse($tenant->license_expires_at)->format('Y/m/d') }}
            </p>
            <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
                تاريخ الانتهاء
            </p>
        @else
            <p style="font-size: 16px; font-weight: 600; color: #2d3748; margin: 0;">
                غير محدد
            </p>
        @endif
    </div>

    <!-- Creation Date -->
    <div class="content-card" style="text-align: center;">
        <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
            <i class="fas fa-calendar-plus" style="font-size: 30px; margin-bottom: 10px;"></i>
            <h4 style="margin: 0; font-size: 18px;">تاريخ الإنشاء</h4>
        </div>
        <p style="font-size: 16px; font-weight: 600; color: #2d3748; margin: 0;">
            {{ $tenant->created_at ? $tenant->created_at->format('Y/m/d') : 'غير محدد' }}
        </p>
        <p style="color: #718096; font-size: 14px; margin: 5px 0 0 0;">
            {{ $tenant->created_at ? $tenant->created_at->diffForHumans() : '' }}
        </p>
    </div>
</div>

<!-- Users List -->
@if($tenant->users && $tenant->users->count() > 0)
<div class="content-card">
    <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-users" style="color: #667eea;"></i>
        قائمة المستخدمين ({{ $tenant->users->count() }})
    </h3>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">الاسم</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">البريد الإلكتروني</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">الدور</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">الحالة</th>
                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 600; color: #4a5568;">تاريخ الانضمام</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenant->users as $user)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; color: #2d3748;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 35px; height: 35px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            {{ $user->name }}
                        </div>
                    </td>
                    <td style="padding: 12px; color: #4a5568;">{{ $user->email }}</td>
                    <td style="padding: 12px; text-align: center;">
                        @if($user->roles->count() > 0)
                            <span style="padding: 4px 8px; background: #e6fffa; color: #234e52; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                {{ $user->roles->first()->name }}
                            </span>
                        @else
                            <span style="color: #a0aec0;">لا يوجد دور</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        @if($user->is_active ?? true)
                            <span style="padding: 4px 8px; background: #c6f6d5; color: #22543d; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                نشط
                            </span>
                        @else
                            <span style="padding: 4px 8px; background: #fed7d7; color: #742a2a; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                غير نشط
                            </span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: center; color: #718096; font-size: 14px;">
                        {{ $user->created_at ? $user->created_at->format('Y/m/d') : 'غير محدد' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Action Buttons -->
<div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
    <a href="{{ route('admin.tenants.edit', $tenant) }}"
       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: transform 0.2s;">
        <i class="fas fa-edit"></i>
        تعديل المستأجر
    </a>

    <a href="{{ route('admin.tenants.index') }}"
       style="background: #f7fafc; color: #4a5568; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; border: 2px solid #e2e8f0; transition: all 0.2s;">
        <i class="fas fa-arrow-right"></i>
        العودة للقائمة
    </a>
</div>
@endsection