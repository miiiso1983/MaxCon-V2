@extends('layouts.modern')

@section('page-title', 'إدارة الموردين')
@section('page-description', 'إدارة شاملة لملفات الموردين والعلاقات التجارية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-truck" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة الموردين 🚚
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة شاملة لملفات الموردين والعلاقات التجارية
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-star" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">تقييم الأداء</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-file-contract" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">إدارة العقود</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">تحليل الأداء</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button onclick="showImportModal()" style="background: rgba(59, 130, 246, 0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-file-excel"></i>
                    استيراد Excel
                </button>
                <a href="{{ route('tenant.purchasing.suppliers.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    إضافة مورد جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-users" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي الموردين</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">مورد مسجل</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">الموردين النشطين</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['active']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">مورد نشط</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-star" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">الموردين المفضلين</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['preferred']) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">مورد مفضل</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-dollar-sign" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">إجمالي القيمة</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($stats['total_value'], 0) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">دينار عراقي</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('tenant.purchasing.suppliers.index') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث بالاسم، الكود، البريد الإلكتروني..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>معلق</option>
                <option value="blacklisted" {{ request('status') === 'blacklisted' ? 'selected' : '' }}>قائمة سوداء</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">النوع</label>
            <select name="type" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأنواع</option>
                <option value="manufacturer" {{ request('type') === 'manufacturer' ? 'selected' : '' }}>مصنع</option>
                <option value="distributor" {{ request('type') === 'distributor' ? 'selected' : '' }}>موزع</option>
                <option value="wholesaler" {{ request('type') === 'wholesaler' ? 'selected' : '' }}>تاجر جملة</option>
                <option value="retailer" {{ request('type') === 'retailer' ? 'selected' : '' }}>تاجر تجزئة</option>
                <option value="service_provider" {{ request('type') === 'service_provider' ? 'selected' : '' }}>مقدم خدمة</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مفضل</label>
            <select name="preferred" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">الكل</option>
                <option value="1" {{ request('preferred') === '1' ? 'selected' : '' }}>مفضل</option>
                <option value="0" {{ request('preferred') === '0' ? 'selected' : '' }}>غير مفضل</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.3)'">
                <i class="fas fa-search"></i>
                <span>بحث</span>
            </button>
            <a href="{{ route('tenant.purchasing.suppliers.index') }}" style="background: #6b7280; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(107, 114, 128, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(107, 114, 128, 0.3)'">
                <i class="fas fa-times"></i>
                <span>إلغاء</span>
            </a>
        </div>
    </form>
</div>

<!-- Suppliers Table -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">قائمة الموردين</h3>
        <div style="display: flex; gap: 10px;">
            <button onclick="exportSuppliers()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'">
                <i class="fas fa-file-excel"></i>
                <span>تصدير Excel</span>
            </button>
        </div>
    </div>
    
    <!-- Success message - Import functionality is now working perfectly! -->
    @if(request()->get('debug') == 'true')
    <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
        <h4 style="margin: 0 0 10px 0; color: #0369a1;">معلومات التشخيص (للمطورين)</h4>
        <p style="margin: 5px 0; font-size: 14px;">عدد الموردين في الصفحة: {{ $suppliers->count() }}</p>
        <p style="margin: 5px 0; font-size: 14px;">إجمالي الموردين: {{ $suppliers->total() }}</p>
        <p style="margin: 5px 0; font-size: 14px;">Tenant ID: {{ auth()->user()->tenant_id ?? 'غير محدد' }}</p>

        <!-- Test buttons for developers -->
        <div style="margin-top: 15px;">
            <a href="{{ route('tenant.purchasing.suppliers.test-create') }}"
               style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-right: 10px; font-size: 14px;">
                إنشاء مورد اختبار
            </a>
            <a href="{{ route('tenant.purchasing.suppliers.test-import') }}"
               style="background: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                اختبار الاستيراد
            </a>
        </div>
    </div>
    @endif

    @if($suppliers->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المورد</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">النوع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">التقييم</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">إجمالي الطلبات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة الإجمالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                        {{ substr($supplier->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #2d3748; display: flex; align-items: center; gap: 8px;">
                                            {{ $supplier->name }}
                                            @if($supplier->is_preferred)
                                                <i class="fas fa-star" style="color: #f59e0b;" title="مورد مفضل"></i>
                                            @endif
                                        </div>
                                        <div style="font-size: 12px; color: #6b7280;">{{ $supplier->code }}</div>
                                        @if($supplier->contact_person)
                                            <div style="font-size: 12px; color: #6b7280;">{{ $supplier->contact_person }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $supplier->type_label }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $statusColors = [
                                        'active' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                                        'inactive' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                        'suspended' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                        'blacklisted' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $status = $statusColors[$supplier->status] ?? ['bg' => '#f3f4f6', 'text' => '#374151'];
                                @endphp
                                @php
                                    $statusLabels = [
                                        'active' => 'نشط',
                                        'inactive' => 'غير نشط',
                                        'suspended' => 'معلق'
                                    ];
                                @endphp
                                <span class="status-badge status-{{ $supplier->status }}" style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $statusLabels[$supplier->status] ?? $supplier->status }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i <= $supplier->rating ? '#f59e0b' : '#e5e7eb' }}; font-size: 12px;"></i>
                                    @endfor
                                    <span style="font-size: 12px; color: #6b7280; margin-right: 5px;">({{ number_format($supplier->rating, 1) }})</span>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ number_format($supplier->total_orders) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">طلب</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af;">{{ number_format($supplier->total_amount, 0) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <a href="{{ route('tenant.purchasing.suppliers.show', $supplier) }}"
                                       style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'"
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                        <span>عرض</span>
                                    </a>
                                    <a href="{{ route('tenant.purchasing.suppliers.edit', $supplier) }}"
                                       style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);"
                                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(245, 158, 11, 0.4)'"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.3)'"
                                       title="تعديل المورد">
                                        <i class="fas fa-edit"></i>
                                        <span>تعديل</span>
                                    </a>
                                    @if($supplier->total_orders == 0)
                                        <form method="POST" action="{{ route('tenant.purchasing.suppliers.destroy', $supplier) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المورد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 10px 14px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(239, 68, 68, 0.4)'"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(239, 68, 68, 0.3)'"
                                                    title="حذف المورد">
                                                <i class="fas fa-trash"></i>
                                                <span>حذف</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 20px;">
            {{ $suppliers->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-truck" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد موردين</h3>
            <p style="margin: 0 0 20px 0;">ابدأ بإضافة أول مورد لك</p>
            <a href="{{ route('tenant.purchasing.suppliers.create') }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus" style="margin-left: 8px;"></i>
                إضافة مورد جديد
            </a>
        </div>
    @endif
</div>

<!-- Import Modal -->
<div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 15px; padding: 30px; max-width: 500px; width: 90%;">
        <h3 style="margin: 0 0 20px 0; color: #2d3748; font-size: 20px; font-weight: 700;">استيراد الموردين من Excel</h3>

        <form method="POST" action="{{ route('tenant.purchasing.suppliers.import') }}" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملف Excel/CSV *</label>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                       style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px;">
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">
                    الصيغ المدعومة: Excel (.xlsx, .xls) أو CSV (.csv)
                </div>
            </div>

            <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-right: 4px solid #3b82f6;">
                <h4 style="margin: 0 0 10px 0; color: #1e40af; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-info-circle" style="margin-left: 5px;"></i>
                    تعليمات الاستيراد:
                </h4>
                <ul style="margin: 0; padding-right: 20px; color: #1e40af; font-size: 12px;">
                    <li>الصف الأول يجب أن يحتوي على عناوين الأعمدة</li>
                    <li>العمود الأول (اسم المورد) مطلوب</li>
                    <li>إذا لم يتم تحديد رمز المورد، سيتم إنشاؤه تلقائياً</li>
                    <li>يمكن تحميل قالب Excel لمعرفة التنسيق المطلوب</li>
                </ul>
            </div>

            <div style="display: flex; gap: 15px; justify-content: space-between; margin-bottom: 20px;">
                <a href="{{ route('tenant.purchasing.suppliers.export-template') }}" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.3)'">
                    <i class="fas fa-download"></i>
                    <span>تحميل القالب</span>
                </a>
            </div>

            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button type="button" onclick="hideImportModal()" style="background: #6b7280; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(107, 114, 128, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(107, 114, 128, 0.3)'">
                    <i class="fas fa-times"></i>
                    <span>إلغاء</span>
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(59, 130, 246, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.3)'">
                    <i class="fas fa-upload"></i>
                    <span>استيراد الملف</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function exportSuppliers() {
    // Create CSV content with proper headers
    let csv = 'الكود,الاسم,النوع,الحالة,الهاتف,البريد الإلكتروني,التقييم,إجمالي الطلبات,القيمة الإجمالية\n';

    // Get data from table rows
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 8) {
                const code = cells[0].querySelector('div').textContent.trim();
                const name = cells[1].querySelector('div').textContent.trim();
                const type = cells[2].querySelector('div').textContent.trim();
                const status = cells[3].querySelector('span').textContent.trim();
                const phone = cells[4].querySelector('div').textContent.trim();
                const email = cells[5].querySelector('div').textContent.trim();
                const rating = cells[6].querySelector('div').textContent.trim();
                const totalOrders = cells[7].querySelector('div').textContent.trim();

                csv += `"${code}","${name}","${type}","${status}","${phone}","${email}","${rating}","${totalOrders}"\n`;
            }
        }
    });

    // Download CSV
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'suppliers_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function showImportModal() {
    document.getElementById('importModal').style.display = 'flex';
}

function hideImportModal() {
    document.getElementById('importModal').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'importModal') {
        hideImportModal();
    }
});
</script>

<style>
/* Status badges for suppliers */
.status-badge.status-active {
    background: #dcfce7;
    color: #166534;
}
.status-badge.status-inactive {
    background: #f3f4f6;
    color: #6b7280;
}
.status-badge.status-suspended {
    background: #fee2e2;
    color: #991b1b;
}
</style>
@endpush
@endsection
