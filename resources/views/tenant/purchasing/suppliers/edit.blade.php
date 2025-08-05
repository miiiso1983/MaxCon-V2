@extends('layouts.modern')

@section('page-title', 'تعديل المورد: ' . $supplier->name)
@section('page-description', 'تحديث معلومات المورد وبياناته التجارية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-edit" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            تعديل المورد ✏️
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $supplier->name }} - {{ $supplier->code }}
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    @php
                        $statusColors = [
                            'active' => ['bg' => 'rgba(16, 185, 129, 0.2)', 'text' => 'white'],
                            'inactive' => ['bg' => 'rgba(107, 114, 128, 0.2)', 'text' => 'white'],
                            'pending' => ['bg' => 'rgba(245, 158, 11, 0.2)', 'text' => 'white'],
                            'suspended' => ['bg' => 'rgba(239, 68, 68, 0.2)', 'text' => 'white'],
                        ];
                        $status = $statusColors[$supplier->status] ?? ['bg' => 'rgba(107, 114, 128, 0.2)', 'text' => 'white'];
                    @endphp
                    <div style="background: {{ $status['bg'] }}; border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <span style="font-size: 14px; font-weight: 600; color: {{ $status['text'] }};">{{ $supplier->status == 'active' ? 'نشط' : ($supplier->status == 'inactive' ? 'غير نشط' : 'معلق') }}</span>
                    </div>

                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $supplier->created_at->format('Y-m-d') }}</span>
                    </div>

                    @if($supplier->rating)
                        <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                            <i class="fas fa-star" style="margin-left: 8px;"></i>
                            <span style="font-size: 14px;">{{ $supplier->rating }}/5</span>
                        </div>
                    @endif
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}" style="background: rgba(16, 185, 129, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-eye"></i>
                    عرض التفاصيل
                </a>
                <a href="{{ route('tenant.purchasing.suppliers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: #f8fafc; padding: 20px; border-radius: 15px; border-right: 4px solid #8b5cf6; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">حد الائتمان</div>
                <div style="font-size: 28px; font-weight: 700; color: #2d3748;">{{ number_format($supplier->credit_limit ?? 0, 0) }}</div>
            </div>
            <div style="background: #8b5cf6; color: white; padding: 12px; border-radius: 12px;">
                <i class="fas fa-dollar-sign" style="font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <div style="background: #f8fafc; padding: 20px; border-radius: 15px; border-right: 4px solid #3b82f6; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">أوامر الشراء</div>
                <div style="font-size: 28px; font-weight: 700; color: #2d3748;">{{ $supplier->purchase_orders_count ?? 0 }}</div>
            </div>
            <div style="background: #3b82f6; color: white; padding: 12px; border-radius: 12px;">
                <i class="fas fa-shopping-cart" style="font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <div style="background: #f8fafc; padding: 20px; border-radius: 15px; border-right: 4px solid #10b981; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">مدة السداد (يوم)</div>
                <div style="font-size: 28px; font-weight: 700; color: #2d3748;">{{ $supplier->payment_terms ?? 30 }}</div>
            </div>
            <div style="background: #10b981; color: white; padding: 12px; border-radius: 12px;">
                <i class="fas fa-calendar" style="font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <div style="background: #f8fafc; padding: 20px; border-radius: 15px; border-right: 4px solid #f59e0b; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">التقييم</div>
                <div style="font-size: 28px; font-weight: 700; color: #2d3748;">{{ number_format($supplier->rating ?? 0, 1) }}/5</div>
            </div>
            <div style="background: #f59e0b; color: white; padding: 12px; border-radius: 12px;">
                <i class="fas fa-star" style="font-size: 20px;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Main Form -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-user-edit" style="color: #f59e0b; margin-left: 10px;"></i>
            تعديل بيانات المورد
        </h3>

        <form action="{{ route('tenant.purchasing.suppliers.update', $supplier) }}" method="POST" id="supplierForm">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div style="margin-bottom: 30px;">
                <h4 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                    <i class="fas fa-info-circle" style="color: #10b981; margin-left: 10px;"></i>
                    المعلومات الأساسية
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">اسم المورد *</label>
                        <input type="text"
                               id="name"
                               name="name"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                               value="{{ old('name', $supplier->name) }}"
                               required
                               onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                        @error('name')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">رمز المورد *</label>
                        <input type="text"
                               id="code"
                               name="code"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                               value="{{ old('code', $supplier->code) }}"
                               required
                               onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                        @error('code')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">نوع المورد *</label>
                        <select id="type"
                                name="type"
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                                required
                                onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                            <option value="">اختر نوع المورد</option>
                            <option value="manufacturer" {{ old('type', $supplier->type) == 'manufacturer' ? 'selected' : '' }}>مصنع</option>
                            <option value="distributor" {{ old('type', $supplier->type) == 'distributor' ? 'selected' : '' }}>موزع</option>
                            <option value="wholesaler" {{ old('type', $supplier->type) == 'wholesaler' ? 'selected' : '' }}>تاجر جملة</option>
                            <option value="retailer" {{ old('type', $supplier->type) == 'retailer' ? 'selected' : '' }}>تاجر تجزئة</option>
                            <option value="service_provider" {{ old('type', $supplier->type) == 'service_provider' ? 'selected' : '' }}>مقدم خدمة</option>
                        </select>
                        @error('type')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">الشخص المسؤول</label>
                        <input type="text"
                               id="contact_person"
                               name="contact_person"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                               value="{{ old('contact_person', $supplier->contact_person) }}"
                               onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                        @error('contact_person')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">البريد الإلكتروني</label>
                        <input type="email"
                               id="email"
                               name="email"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                               value="{{ old('email', $supplier->email) }}"
                               onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                        @error('email')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">رقم الهاتف</label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                               value="{{ old('phone', $supplier->phone) }}"
                               onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                        @error('phone')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">العنوان الكامل</label>
                    <textarea id="address"
                              name="address"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb; min-height: 100px; resize: vertical;"
                              rows="3"
                              onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                              onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">{{ old('address', $supplier->address) }}</textarea>
                    @error('address')
                        <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Financial Information -->
            <div style="margin-bottom: 30px;">
                <h4 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                    <i class="fas fa-dollar-sign" style="color: #3b82f6; margin-left: 10px;"></i>
                    المعلومات المالية
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">حد الائتمان</label>
                        <input type="number"
                               id="credit_limit"
                               name="credit_limit"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                               value="{{ old('credit_limit', $supplier->credit_limit) }}"
                               step="0.01"
                               min="0"
                               onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                        @error('credit_limit')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">شروط الدفع *</label>
                        <select id="payment_terms"
                                name="payment_terms"
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                                required
                                onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                            <option value="cash" {{ old('payment_terms', $supplier->payment_terms) == 'cash' ? 'selected' : '' }}>نقداً</option>
                            <option value="credit_7" {{ old('payment_terms', $supplier->payment_terms) == 'credit_7' ? 'selected' : '' }}>آجل 7 أيام</option>
                            <option value="credit_15" {{ old('payment_terms', $supplier->payment_terms) == 'credit_15' ? 'selected' : '' }}>آجل 15 يوم</option>
                            <option value="credit_30" {{ old('payment_terms', $supplier->payment_terms) == 'credit_30' ? 'selected' : '' }}>آجل 30 يوم</option>
                            <option value="credit_45" {{ old('payment_terms', $supplier->payment_terms) == 'credit_45' ? 'selected' : '' }}>آجل 45 يوم</option>
                            <option value="credit_60" {{ old('payment_terms', $supplier->payment_terms) == 'credit_60' ? 'selected' : '' }}>آجل 60 يوم</option>
                            <option value="credit_90" {{ old('payment_terms', $supplier->payment_terms) == 'credit_90' ? 'selected' : '' }}>آجل 90 يوم</option>
                            <option value="custom" {{ old('payment_terms', $supplier->payment_terms) == 'custom' ? 'selected' : '' }}>مخصص</option>
                        </select>
                        @error('payment_terms')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">الرقم الضريبي</label>
                        <input type="text"
                               id="tax_number"
                               name="tax_number"
                               style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                               value="{{ old('tax_number', $supplier->tax_number) }}"
                               onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                        @error('tax_number')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">العملة</label>
                        <select id="currency"
                                name="currency"
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                                onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                            <option value="IQD" {{ old('currency', $supplier->currency) == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                            <option value="USD" {{ old('currency', $supplier->currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="EUR" {{ old('currency', $supplier->currency) == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                        </select>
                        @error('currency')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div style="margin-bottom: 30px;">
                <h4 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
                    <i class="fas fa-cogs" style="color: #8b5cf6; margin-left: 10px;"></i>
                    معلومات إضافية
                </h4>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">فئة المورد</label>
                        <select id="category"
                                name="category"
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                                onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                            <option value="">اختر الفئة</option>
                            <option value="pharmaceutical" {{ old('category', $supplier->category) == 'pharmaceutical' ? 'selected' : '' }}>أدوية</option>
                            <option value="medical_equipment" {{ old('category', $supplier->category) == 'medical_equipment' ? 'selected' : '' }}>معدات طبية</option>
                            <option value="cosmetics" {{ old('category', $supplier->category) == 'cosmetics' ? 'selected' : '' }}>مستحضرات تجميل</option>
                            <option value="supplements" {{ old('category', $supplier->category) == 'supplements' ? 'selected' : '' }}>مكملات غذائية</option>
                            <option value="other" {{ old('category', $supplier->category) == 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('category')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">الحالة</label>
                        <select id="status"
                                name="status"
                                style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb;"
                                onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                            <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="suspended" {{ old('status', $supplier->status) == 'suspended' ? 'selected' : '' }}>معلق</option>
                        </select>
                        @error('status')
                            <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 8px; display: block;">ملاحظات</label>
                    <textarea id="notes"
                              name="notes"
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: #f9fafb; min-height: 120px; resize: vertical;"
                              rows="4"
                              onfocus="this.style.borderColor='#f59e0b'; this.style.background='white';"
                              onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">{{ old('notes', $supplier->notes) }}</textarea>
                    @error('notes')
                        <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 2px solid #f3f4f6;">
                <a href="{{ route('tenant.purchasing.suppliers.index') }}"
                   style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;"
                   onmouseover="this.style.background='#4b5563'"
                   onmouseout="this.style.background='#6b7280'">
                    <i class="fas fa-times"></i>
                    إلغاء
                </a>

                <button type="submit"
                        style="background: #f59e0b; color: white; padding: 12px 24px; border-radius: 10px; border: none; font-weight: 600; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#d97706'"
                        onmouseout="this.style.background='#f59e0b'">
                    <i class="fas fa-save"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="content-card">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-chart-bar" style="color: #f59e0b; margin-left: 10px;"></i>
            معلومات المورد
        </h3>

        <div style="display: grid; gap: 15px;">
            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #10b981;">
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">تاريخ الإنشاء</div>
                <div style="font-size: 16px; font-weight: 600; color: #2d3748;">{{ $supplier->created_at->format('Y-m-d') }}</div>
            </div>

            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #3b82f6;">
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">آخر تحديث</div>
                <div style="font-size: 16px; font-weight: 600; color: #2d3748;">{{ $supplier->updated_at->format('Y-m-d') }}</div>
            </div>

            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #f59e0b;">
                <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">الحالة الحالية</div>
                <div style="font-size: 16px; font-weight: 600; color: #2d3748;">
                    {{ $supplier->status == 'active' ? 'نشط' : ($supplier->status == 'inactive' ? 'غير نشط' : 'معلق') }}
                </div>
            </div>

            @if($supplier->rating)
                <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border-right: 4px solid #8b5cf6;">
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">التقييم</div>
                    <div style="font-size: 16px; font-weight: 600; color: #2d3748;">{{ $supplier->rating }}/5</div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 25px;">
            <h4 style="font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 15px; display: flex; align-items: center;">
                <i class="fas fa-bolt" style="color: #8b5cf6; margin-left: 8px;"></i>
                إجراءات سريعة
            </h4>

            <div style="display: grid; gap: 10px;">
                <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}"
                   style="background: #f8fafc; color: #2d3748; padding: 12px 16px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
                   onmouseover="this.style.background='#e5e7eb'"
                   onmouseout="this.style.background='#f8fafc'">
                    <i class="fas fa-eye" style="color: #10b981;"></i>
                    عرض التفاصيل
                </a>
                <a href="#"
                   style="background: #f8fafc; color: #2d3748; padding: 12px 16px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
                   onmouseover="this.style.background='#e5e7eb'"
                   onmouseout="this.style.background='#f8fafc'">
                    <i class="fas fa-file-invoice" style="color: #3b82f6;"></i>
                    إنشاء طلب شراء
                </a>
                <a href="#"
                   style="background: #f8fafc; color: #2d3748; padding: 12px 16px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease; border: 1px solid #e5e7eb;"
                   onmouseover="this.style.background='#e5e7eb'"
                   onmouseout="this.style.background='#f8fafc'">
                    <i class="fas fa-history" style="color: #f59e0b;"></i>
                    سجل المعاملات
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('supplierForm');
    const requiredFields = form.querySelectorAll('[required]');

    form.addEventListener('submit', function(e) {
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
            } else {
                field.classList.remove('error');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('يرجى ملء جميع الحقول المطلوبة');
        }
    });

    // Remove error styling on input
    requiredFields.forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('error');
        });
    });
});
</script>
@endpush
