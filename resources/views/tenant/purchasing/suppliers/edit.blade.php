@extends('layouts.tenant')

@section('title', 'تعديل المورد')

@section('content')
<div class="main-content">
    <!-- Green Header Section like in the image -->
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-truck" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 28px; font-weight: 700; margin: 0;">
                            🚚 تعديل المورد
                        </h1>
                        <p style="font-size: 16px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة لمعلومات الموردين والعمليات التجارية
                        </p>
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px;">
                    <i class="fas fa-edit" style="font-size: 24px;"></i>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('tenant.purchasing.suppliers.index') }}"
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-list"></i> قائمة الموردين
                </a>
                <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}"
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-eye"></i> عرض التفاصيل
                </a>
                <button type="button" onclick="document.querySelector('form').submit()"
                        style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.3); font-weight: 600; backdrop-filter: blur(10px); transition: all 0.3s ease; cursor: pointer;"
                        onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </div>
        </div>
    </div>

    <!-- Colored Stats Cards like in the image -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 20px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);">
                <div style="font-size: 24px; margin-bottom: 10px;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                    {{ number_format($supplier->credit_limit ?? 0, 0) }}
                </div>
                <div style="font-size: 14px; opacity: 0.9;">حد الائتمان</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);">
                <div style="font-size: 24px; margin-bottom: 10px;">
                    <i class="fas fa-star"></i>
                </div>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                    {{ $supplier->purchaseOrders ? $supplier->purchaseOrders->count() : 0 }}
                </div>
                <div style="font-size: 14px; opacity: 0.9;">أوامر الشراء</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                <div style="font-size: 24px; margin-bottom: 10px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                    {{ $supplier->status == 'active' ? '1' : '0' }}
                </div>
                <div style="font-size: 14px; opacity: 0.9;">مورد نشط</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                <div style="font-size: 24px; margin-bottom: 10px;">
                    <i class="fas fa-users"></i>
                </div>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                    {{ $supplier->supplierContracts ? $supplier->supplierContracts->count() : 0 }}
                </div>
                <div style="font-size: 14px; opacity: 0.9;">العقود</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px;">
                    <h3 style="margin: 0; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-user-edit" style="margin-left: 12px; color: #fbbf24;"></i>
                        تعديل بيانات المورد
                    </h3>
                    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">قم بتحديث معلومات المورد أدناه</p>
                </div>
                <div style="padding: 30px;">
                    <form action="{{ route('tenant.purchasing.suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">المعلومات الأساسية</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        اسم المورد <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('name') border-red-500 @enderror">
                                    @error('name')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        اسم الشركة
                                    </label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('company_name') border-red-500 @enderror">
                                    @error('company_name')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">معلومات الاتصال</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        البريد الإلكتروني
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('email') border-red-500 @enderror">
                                    @error('email')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        رقم الهاتف
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        رقم الجوال
                                    </label>
                                    <input type="text" name="mobile" value="{{ old('mobile', $supplier->mobile) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('mobile') border-red-500 @enderror">
                                    @error('mobile')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">معلومات العنوان</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        المدينة
                                    </label>
                                    <input type="text" name="city" value="{{ old('city', $supplier->city) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#f59e0b'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('city') border-red-500 @enderror">
                                    @error('city')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        البلد
                                    </label>
                                    <input type="text" name="country" value="{{ old('country', $supplier->country) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#f59e0b'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('country') border-red-500 @enderror">
                                    @error('country')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                    العنوان الكامل
                                </label>
                                <textarea name="address" rows="4"
                                          style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05); resize: vertical;"
                                          onfocus="this.style.borderColor='#f59e0b'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.15)';"
                                          onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                          class="@error('address') border-red-500 @enderror">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">المعلومات التجارية والمالية</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        الرقم الضريبي
                                    </label>
                                    <input type="text" name="tax_number" value="{{ old('tax_number', $supplier->tax_number) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('tax_number') border-red-500 @enderror">
                                    @error('tax_number')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        السجل التجاري
                                    </label>
                                    <input type="text" name="commercial_register" value="{{ old('commercial_register', $supplier->commercial_register) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('commercial_register') border-red-500 @enderror">
                                    @error('commercial_register')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        حد الائتمان (دينار عراقي)
                                    </label>
                                    <input type="number" step="0.01" name="credit_limit" value="{{ old('credit_limit', $supplier->credit_limit) }}"
                                           style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                           onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.15)';"
                                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                           class="@error('credit_limit') border-red-500 @enderror">
                                    @error('credit_limit')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        شروط الدفع
                                    </label>
                                    <select name="payment_terms"
                                            style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                            onfocus="this.style.borderColor='#10b981'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.15)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
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

                        <!-- Status and Notes -->
                        <div style="margin-bottom: 30px;">
                            <div style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 12px;">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <h4 style="margin: 0; color: #1f2937; font-weight: 600;">الحالة والملاحظات</h4>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; align-items: start;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        الحالة
                                    </label>
                                    <select name="status"
                                            style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                            onfocus="this.style.borderColor='#8b5cf6'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(139, 92, 246, 0.15)';"
                                            onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                            class="@error('status') border-red-500 @enderror">
                                        <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    </select>
                                    @error('status')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">
                                        ملاحظات
                                    </label>
                                    <textarea name="notes" rows="4"
                                              style="width: 100%; padding: 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 16px; transition: all 0.3s; background: #f9fafb; box-shadow: 0 2px 4px rgba(0,0,0,0.05); resize: vertical;"
                                              onfocus="this.style.borderColor='#8b5cf6'; this.style.background='white'; this.style.boxShadow='0 4px 12px rgba(139, 92, 246, 0.15)';"
                                              onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';"
                                              class="@error('notes') border-red-500 @enderror">{{ old('notes', $supplier->notes) }}</textarea>
                                    @error('notes')
                                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 2px solid #e2e8f0;">
                            <a href="{{ route('tenant.purchasing.suppliers.index') }}"
                               style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 15px 30px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3); transition: all 0.3s ease;"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(107, 114, 128, 0.4)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(107, 114, 128, 0.3)';">
                                <i class="fas fa-times" style="margin-left: 8px;"></i>
                                إلغاء
                            </a>

                            <button type="submit"
                                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 30px; border-radius: 15px; border: none; font-weight: 600; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease; cursor: pointer;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(16, 185, 129, 0.4)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.3)';">
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
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 20px;">
                    <h4 style="margin: 0; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-info-circle" style="margin-left: 10px; color: #fbbf24;"></i>
                        معلومات المورد
                    </h4>
                </div>
                <div style="padding: 25px;">
                    <div style="margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 12px; border-right: 4px solid #3b82f6;">
                        <div style="color: #6b7280; font-size: 12px; margin-bottom: 5px;">تاريخ الإنشاء</div>
                        <div style="font-weight: 600; color: #1f2937;">{{ $supplier->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div style="margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 12px; border-right: 4px solid #10b981;">
                        <div style="color: #6b7280; font-size: 12px; margin-bottom: 5px;">آخر تحديث</div>
                        <div style="font-weight: 600; color: #1f2937;">{{ $supplier->updated_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div style="padding: 15px; background: #f8fafc; border-radius: 12px; border-right: 4px solid #f59e0b;">
                        <div style="color: #6b7280; font-size: 12px; margin-bottom: 5px;">الحالة الحالية</div>
                        <div>
                            @if($supplier->status == 'active')
                                <span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">نشط</span>
                            @else
                                <span style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير نشط</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e2e8f0;">
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 20px;">
                    <h4 style="margin: 0; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-bolt" style="margin-left: 10px; color: #fbbf24;"></i>
                        إجراءات سريعة
                    </h4>
                </div>
                <div style="padding: 25px;">
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}"
                           style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 15px 20px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2); transition: all 0.3s ease;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(59, 130, 246, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(59, 130, 246, 0.2)';">
                            <i class="fas fa-eye" style="margin-left: 10px;"></i>
                            عرض التفاصيل
                        </a>
                        <a href="#"
                           style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 15px 20px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(139, 92, 246, 0.2); transition: all 0.3s ease;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(139, 92, 246, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(139, 92, 246, 0.2)';">
                            <i class="fas fa-file-contract" style="margin-left: 10px;"></i>
                            العقود
                        </a>
                        <a href="#"
                           style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 20px; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2); transition: all 0.3s ease;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(16, 185, 129, 0.3)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.2)';">
                            <i class="fas fa-shopping-cart" style="margin-left: 10px;"></i>
                            أوامر الشراء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Fade in animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .main-content > * {
        animation: fadeInUp 0.6s ease-out;
    }

    .main-content > *:nth-child(2) {
        animation-delay: 0.1s;
    }

    .main-content > *:nth-child(3) {
        animation-delay: 0.2s;
    }

    .main-content > *:nth-child(4) {
        animation-delay: 0.3s;
    }

    /* Hover effects for cards */
    .col-lg-3:hover > div {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation with enhanced styling
    $('form').on('submit', function(e) {
        let isValid = true;

        // Check required fields
        $('input[required]').each(function() {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).css('border-color', '#ef4444');
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

    // Add loading state to submit button
    $('button[type="submit"]').on('click', function() {
        var $btn = $(this);
        var originalText = $btn.html();

        $btn.html('<i class="fas fa-spinner fa-spin" style="margin-left: 8px;"></i>جاري الحفظ...');
        $btn.prop('disabled', true);

        // Re-enable after form submission
        setTimeout(function() {
            $btn.html(originalText);
            $btn.prop('disabled', false);
        }, 3000);
    });

    // Auto-format phone numbers
    $('input[name="phone"], input[name="mobile"]').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                $(this).val(value);
            } else if (value.length <= 6) {
                $(this).val(value.substring(0, 3) + '-' + value.substring(3));
            } else {
                $(this).val(value.substring(0, 3) + '-' + value.substring(3, 6) + '-' + value.substring(6, 10));
            }
        }
    });

    // Credit limit formatting with thousand separators
    $('input[name="credit_limit"]').on('input', function() {
        var value = $(this).val().replace(/[^\d.]/g, '');
        if (value) {
            // Add thousand separators
            var parts = value.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            $(this).val(parts.join('.'));
        }
    });
});
</script>
@endpush
