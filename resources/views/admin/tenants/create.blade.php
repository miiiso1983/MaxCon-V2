@extends('layouts.modern')

@section('page-title', 'إضافة مستأجر جديد')
@section('page-description', 'إنشاء مستأجر جديد في النظام')

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
                        <i class="fas fa-building" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إضافة مستأجر جديد 🏢
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء مؤسسة جديدة في النظام
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-building" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">إنشاء مؤسسة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-cog" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">إعداد النظام</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">تفعيل فوري</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.tenants.store') }}">
        @csrf

        <!-- معلومات المؤسسة -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; padding: 20px; margin-bottom: 30px; color: white;">
            <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; display: flex; align-items: center;">
                <i class="fas fa-building" style="margin-left: 10px;"></i>
                معلومات المؤسسة
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- اسم المؤسسة -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">اسم المؤسسة *</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);"
                           placeholder="اسم المؤسسة" required>
                    @error('name')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- الرمز المختصر -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">الرمز المختصر</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);"
                           placeholder="pharmacy-name">
                    @error('slug')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- النطاق الفرعي -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">النطاق الفرعي</label>
                    <input type="text" name="subdomain" value="{{ old('subdomain') }}"
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);"
                           placeholder="pharmacy">
                    @error('subdomain')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- معلومات مدير المؤسسة -->
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 15px; padding: 20px; margin-bottom: 30px; color: white;">
            <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; display: flex; align-items: center;">
                <i class="fas fa-user-tie" style="margin-left: 10px;"></i>
                معلومات مدير المؤسسة
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- اسم المدير -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">اسم المدير *</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);"
                           placeholder="الاسم الكامل للمدير" required>
                    @error('admin_name')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- بريد المدير -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">البريد الإلكتروني *</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);"
                           placeholder="admin@company.com" required>
                    @error('admin_email')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- رقم الموبايل -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">رقم الموبايل *</label>
                    <input type="text" name="admin_phone" value="{{ old('admin_phone') }}"
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);"
                           placeholder="+964 750 123 4567" required>
                    @error('admin_phone')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- كلمة المرور -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">كلمة المرور *</label>
                    <input type="password" name="admin_password"
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);"
                           placeholder="كلمة مرور قوية" required>
                    @error('admin_password')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- إعدادات النظام -->
        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 15px; padding: 20px; margin-bottom: 30px; color: white;">
            <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; display: flex; align-items: center;">
                <i class="fas fa-cogs" style="margin-left: 10px;"></i>
                إعدادات النظام
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

                <!-- الخطة -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">الخطة *</label>
                    <select name="plan" required
                            style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);">
                        <option value="" style="color: #333;">اختر الخطة</option>
                        <option value="basic" {{ old('plan') == 'basic' ? 'selected' : '' }} style="color: #333;">أساسي (10 مستخدمين - 5GB)</option>
                        <option value="premium" {{ old('plan') == 'premium' ? 'selected' : '' }} style="color: #333;">متقدم (50 مستخدم - 20GB)</option>
                        <option value="enterprise" {{ old('plan') == 'enterprise' ? 'selected' : '' }} style="color: #333;">مؤسسي (100 مستخدم - 100GB)</option>
                    </select>
                    @error('plan')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- عدد المستخدمين الأقصى -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">عدد المستخدمين الأقصى *</label>
                    <input type="number" name="max_users" value="{{ old('max_users', 10) }}" min="1" required
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);">
                    @error('max_users')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- حد التخزين -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">حد التخزين (GB) *</label>
                    <input type="number" name="storage_limit" value="{{ old('storage_limit', 5) }}" min="1" required
                           style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);">
                    @error('storage_limit')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- الحالة -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; opacity: 0.9;">الحالة</label>
                    <select name="is_active"
                            style="width: 100%; padding: 12px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 14px; background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(10px);">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }} style="color: #333;">نشط</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }} style="color: #333;">معطل</option>
                    </select>
                    @error('is_active')
                        <div style="color: #fbbf24; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        </div>

        <!-- أزرار الحفظ -->
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('admin.tenants.index') }}"
               style="padding: 12px 24px; border: 1px solid #e2e8f0; border-radius: 8px; color: #4a5568; text-decoration: none; font-weight: 600;">
                إلغاء
            </a>
            <button type="submit" class="btn-blue">
                <i class="fas fa-save"></i>
                حفظ المستأجر
            </button>
    </form>
</div>
@endsection
