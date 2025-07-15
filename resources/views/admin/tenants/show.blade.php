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

<div class="content-card">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px;">
        تفاصيل المؤسسة
    </h3>

    <p>صفحة عرض تفاصيل المستأجر - قيد التطوير</p>
</div>
@endsection