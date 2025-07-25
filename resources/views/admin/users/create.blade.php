@extends('layouts.modern')

@section('page-title', 'إضافة مستخدم جديد')
@section('page-description', 'إنشاء مستخدم جديد في النظام')

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
                        <i class="fas fa-user-plus" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إضافة مستخدم جديد ➕
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء حساب مستخدم جديد في النظام
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-user-plus" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">إنشاء مستخدم</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تعيين الأدوار</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">حفظ آمن</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <!-- الاسم -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الاسم الكامل *</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="الاسم الكامل" required>
                @error('name')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- البريد الإلكتروني -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">البريد الإلكتروني *</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="user@example.com" required>
                @error('email')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- كلمة المرور -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">كلمة المرور *</label>
                <input type="password" name="password" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="كلمة المرور" required>
                @error('password')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- تأكيد كلمة المرور -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">تأكيد كلمة المرور *</label>
                <input type="password" name="password_confirmation" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="تأكيد كلمة المرور" required>
                @error('password_confirmation')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الدور -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الدور</label>
                <select name="role" data-custom-select data-placeholder="اختر الدور..." data-searchable="true">
                    <option value="">اختر الدور</option>
                    <option value="super-admin" {{ old('role') == 'super-admin' ? 'selected' : '' }}>مدير عام</option>
                    <option value="tenant-admin" {{ old('role') == 'tenant-admin' ? 'selected' : '' }}>مدير مستأجر</option>
                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>مدير</option>
                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>موظف</option>
                    <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>محاسب</option>
                    <option value="pharmacist" {{ old('role') == 'pharmacist' ? 'selected' : '' }}>صيدلاني</option>
                    <option value="sales-rep" {{ old('role') == 'sales-rep' ? 'selected' : '' }}>مندوب مبيعات</option>
                    <option value="warehouse-manager" {{ old('role') == 'warehouse-manager' ? 'selected' : '' }}>مدير مستودع</option>
                    <option value="quality-manager" {{ old('role') == 'quality-manager' ? 'selected' : '' }}>مدير جودة</option>
                    <option value="hr-manager" {{ old('role') == 'hr-manager' ? 'selected' : '' }}>مدير موارد بشرية</option>
                </select>
                @error('role')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الحالة -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الحالة</label>
                <select name="is_active" data-custom-select data-placeholder="اختر الحالة..." data-searchable="false">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>معطل</option>
                </select>
                @error('is_active')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- أزرار الحفظ -->
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('admin.users.index') }}" 
               style="padding: 12px 24px; border: 1px solid #e2e8f0; border-radius: 8px; color: #4a5568; text-decoration: none; font-weight: 600;">
                إلغاء
            </a>
            <button type="submit" class="btn-blue">
                <i class="fas fa-save"></i>
                حفظ المستخدم
            </button>
        </div>
    </form>
</div>
@endsection
