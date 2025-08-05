@extends('layouts.tenant')

@section('title', 'تعديل المورد')

@section('content')
<!-- Modern Header with Gradient -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem 0; margin-bottom: 2rem; border-radius: 0 0 20px 20px;">
    <div class="container-fluid">
        <div style="display: flex; justify-content: space-between; align-items: center; color: white;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 700; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-edit" style="margin-left: 15px; color: #fbbf24;"></i>
                    تعديل المورد
                </h1>
                <div style="margin-top: 10px; opacity: 0.9;">
                    <span style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 14px;">
                        <i class="fas fa-home" style="margin-left: 5px;"></i>الرئيسية
                    </span>
                    <i class="fas fa-chevron-left" style="margin: 0 10px; font-size: 12px;"></i>
                    <span style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 14px;">
                        <i class="fas fa-truck" style="margin-left: 5px;"></i>الموردين
                    </span>
                    <i class="fas fa-chevron-left" style="margin: 0 10px; font-size: 12px;"></i>
                    <span style="background: rgba(255,255,255,0.3); padding: 5px 15px; border-radius: 20px; font-size: 14px;">
                        تعديل المورد
                    </span>
                </div>
            </div>
            <div>
                <a href="{{ route('tenant.purchasing.suppliers.index') }}"
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border: none; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; font-weight: 600; transition: all 0.3s; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row" style="gap: 2rem;">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0;">
                <!-- Card Header -->
                <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 1.5rem;">
                    <h3 style="margin: 0; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-user-edit" style="margin-left: 12px; color: #fbbf24;"></i>
                        تعديل بيانات المورد
                    </h3>
                    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">قم بتحديث معلومات المورد أدناه</p>
                </div>

                <!-- Card Body -->
                <div style="padding: 2rem;">
                    <form action="{{ route('tenant.purchasing.suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section -->
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">المعلومات الأساسية</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        اسم المورد <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('name') border-red-500 @enderror">
                                    @error('name')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        اسم الشركة
                                    </label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('company_name') border-red-500 @enderror">
                                    @error('company_name')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">معلومات الاتصال</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        البريد الإلكتروني
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('email') border-red-500 @enderror">
                                    @error('email')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        رقم الهاتف
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        رقم الجوال
                                    </label>
                                    <input type="text" name="mobile" value="{{ old('mobile', $supplier->mobile) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('mobile') border-red-500 @enderror">
                                    @error('mobile')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">معلومات العنوان</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        المدينة
                                    </label>
                                    <input type="text" name="city" value="{{ old('city', $supplier->city) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('city') border-red-500 @enderror">
                                    @error('city')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                    العنوان الكامل
                                </label>
                                <textarea name="address" rows="3"
                                          style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb; resize: vertical;"
                                          onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                          onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                          class="@error('address') border-red-500 @enderror">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Information Section -->
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">المعلومات التجارية والمالية</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        الرقم الضريبي
                                    </label>
                                    <input type="text" name="tax_number" value="{{ old('tax_number', $supplier->tax_number) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('tax_number') border-red-500 @enderror">
                                    @error('tax_number')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        السجل التجاري
                                    </label>
                                    <input type="text" name="commercial_register" value="{{ old('commercial_register', $supplier->commercial_register) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('commercial_register') border-red-500 @enderror">
                                    @error('commercial_register')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        حد الائتمان (دينار عراقي)
                                    </label>
                                    <input type="number" step="0.01" name="credit_limit" value="{{ old('credit_limit', $supplier->credit_limit) }}"
                                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                           class="@error('credit_limit') border-red-500 @enderror">
                                    @error('credit_limit')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        شروط الدفع
                                    </label>
                                    <select name="payment_terms"
                                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                            class="@error('payment_terms') border-red-500 @enderror">
                                        <option value="">اختر شروط الدفع</option>
                                        <option value="cash" {{ old('payment_terms', $supplier->payment_terms) == 'cash' ? 'selected' : '' }}>نقداً</option>
                                        <option value="30_days" {{ old('payment_terms', $supplier->payment_terms) == '30_days' ? 'selected' : '' }}>30 يوم</option>
                                        <option value="60_days" {{ old('payment_terms', $supplier->payment_terms) == '60_days' ? 'selected' : '' }}>60 يوم</option>
                                        <option value="90_days" {{ old('payment_terms', $supplier->payment_terms) == '90_days' ? 'selected' : '' }}>90 يوم</option>
                                    </select>
                                    @error('payment_terms')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status and Notes Section -->
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">الحالة والملاحظات</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                        الحالة
                                    </label>
                                    <select name="status"
                                            style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb;"
                                            onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                            class="@error('status') border-red-500 @enderror">
                                        <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                    @error('status')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                                    ملاحظات
                                </label>
                                <textarea name="notes" rows="4"
                                          style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; transition: all 0.3s; background: #f9fafb; resize: vertical;"
                                          onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';"
                                          onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';"
                                          class="@error('notes') border-red-500 @enderror">{{ old('notes', $supplier->notes) }}</textarea>
                                @error('notes')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #e2e8f0;">
                            <a href="{{ route('tenant.purchasing.suppliers.index') }}"
                               style="background: #6b7280; color: white; padding: 12px 24px; border: none; border-radius: 12px; text-decoration: none; display: inline-flex; align-items: center; font-weight: 600; transition: all 0.3s;"
                               onmouseover="this.style.background='#4b5563'; this.style.transform='translateY(-2px)';"
                               onmouseout="this.style.background='#6b7280'; this.style.transform='translateY(0)';">
                                <i class="fas fa-times" style="margin-left: 8px;"></i>
                                إلغاء
                            </a>
                            <button type="submit"
                                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border: none; border-radius: 12px; display: inline-flex; align-items: center; font-weight: 600; transition: all 0.3s; cursor: pointer;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(16, 185, 129, 0.3)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                <i class="fas fa-save" style="margin-left: 8px;"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Supplier Info Card -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
                <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; padding: 1.5rem;">
                    <h3 style="margin: 0; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-info-circle" style="margin-left: 12px; color: #fbbf24;"></i>
                        معلومات المورد
                    </h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        <div style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">تاريخ الإنشاء</div>
                        <div style="font-weight: 600; color: #1f2937;">{{ $supplier->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">آخر تحديث</div>
                        <div style="font-weight: 600; color: #1f2937;">{{ $supplier->updated_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">الحالة الحالية</div>
                        <div>
                            @if($supplier->status == 'active')
                                <span style="background: #10b981; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">نشط</span>
                            @else
                                <span style="background: #ef4444; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير نشط</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0;">
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 1.5rem;">
                    <h3 style="margin: 0; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-bolt" style="margin-left: 12px; color: #fbbf24;"></i>
                        إجراءات سريعة
                    </h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}"
                           style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; padding: 12px 16px; border-radius: 12px; text-decoration: none; display: flex; align-items: center; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(6, 182, 212, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="fas fa-eye" style="margin-left: 8px;"></i>
                            عرض التفاصيل
                        </a>
                        <a href="#"
                           style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 16px; border-radius: 12px; text-decoration: none; display: flex; align-items: center; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(139, 92, 246, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="fas fa-file-contract" style="margin-left: 8px;"></i>
                            العقود
                        </a>
                        <a href="#"
                           style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 16px; border-radius: 12px; text-decoration: none; display: flex; align-items: center; font-weight: 600; transition: all 0.3s;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(16, 185, 129, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <i class="fas fa-shopping-cart" style="margin-left: 8px;"></i>
                            أوامر الشراء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $('input[required]').each(function() {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
        }
    });
    
    // Remove validation errors on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush
