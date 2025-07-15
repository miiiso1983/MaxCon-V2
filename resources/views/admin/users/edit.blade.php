@extends('layouts.modern')

@section('page-title', 'تعديل المستخدم')
@section('page-description', 'تعديل بيانات المستخدم: ' . $user->name)

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; gap: 25px;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; font-weight: 800; backdrop-filter: blur(10px);">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    تعديل {{ $user->name }} ✏️
                </h1>
                <p style="font-size: 18px; margin: 5px 0 15px 0; opacity: 0.9;">
                    تحديث بيانات المستخدم في النظام
                </p>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-edit" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تعديل البيانات</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تحديث الأدوار</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-save" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">حفظ آمن</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <!-- الاسم -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الاسم الكامل *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="الاسم الكامل" required>
                @error('name')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- البريد الإلكتروني -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">البريد الإلكتروني *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="user@example.com" required>
                @error('email')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- رقم الهاتف -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="+964 750 123 4567">
                @error('phone')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الدور -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الدور</label>
                <select name="role" 
                        style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">اختر الدور</option>
                    <option value="super-admin" {{ old('role', $user->roles->first()->name ?? '') == 'super-admin' ? 'selected' : '' }}>مدير عام</option>
                    <option value="tenant-admin" {{ old('role', $user->roles->first()->name ?? '') == 'tenant-admin' ? 'selected' : '' }}>مدير مستأجر</option>
                    <option value="manager" {{ old('role', $user->roles->first()->name ?? '') == 'manager' ? 'selected' : '' }}>مدير</option>
                    <option value="employee" {{ old('role', $user->roles->first()->name ?? '') == 'employee' ? 'selected' : '' }}>موظف</option>
                </select>
                @error('role')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- الحالة -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">الحالة</label>
                <select name="is_active" 
                        style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="1" {{ old('is_active', $user->is_active ?? 1) == '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ old('is_active', $user->is_active ?? 1) == '0' ? 'selected' : '' }}>معطل</option>
                </select>
                @error('is_active')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- كلمة المرور الجديدة -->
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2d3748;">كلمة المرور الجديدة</label>
                <input type="password" name="password" 
                       style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                       placeholder="اتركها فارغة إذا لم ترد تغييرها">
                <div style="color: #718096; font-size: 12px; margin-top: 4px;">اتركها فارغة إذا لم ترد تغيير كلمة المرور</div>
                @error('password')
                    <div style="color: #e53e3e; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- أزرار الحفظ -->
        <div style="display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('admin.users.show', $user) }}" 
               style="padding: 12px 24px; border: 1px solid #e2e8f0; border-radius: 8px; color: #4a5568; text-decoration: none; font-weight: 600;">
                إلغاء
            </a>
            <button type="submit" class="btn-blue">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>
@endsection
