@extends('layouts.modern')

@section('page-title', 'إدارة المستودعات')
@section('page-description', 'إدارة شاملة للمستودعات والمواقع')

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
                        <i class="fas fa-warehouse" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إدارة المستودعات 🏭
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة المستودعات والمواقع مع تتبع المخزون والسعة
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-warehouse" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $stats['total'] }} مستودع</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">{{ $stats['active'] }} نشط</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-building" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $stats['main'] }} رئيسي</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-cube" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">{{ number_format($stats['total_capacity'], 0) }} م³ سعة</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.warehouses.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-plus"></i>
                    مستودع جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-filter" style="color: #667eea; margin-left: 10px;"></i>
        فلترة وبحث
    </h3>
    
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">البحث</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المستودع، الكود، أو الموقع..." style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">النوع</label>
            <select name="type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأنواع</option>
                <option value="main" {{ request('type') === 'main' ? 'selected' : '' }}>مستودع رئيسي</option>
                <option value="branch" {{ request('type') === 'branch' ? 'selected' : '' }}>مستودع فرع</option>
                <option value="storage" {{ request('type') === 'storage' ? 'selected' : '' }}>مخزن</option>
                <option value="pharmacy" {{ request('type') === 'pharmacy' ? 'selected' : '' }}>صيدلية</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.inventory.warehouses.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Warehouses Grid -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #667eea; margin-left: 10px;"></i>
            قائمة المستودعات ({{ $warehouses->total() }})
        </h3>
    </div>
    
    @if($warehouses->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            @foreach($warehouses as $warehouse)
                <div style="background: white; border-radius: 15px; padding: 20px; border: 1px solid #e2e8f0; transition: all 0.3s ease; position: relative; overflow: hidden;"
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    
                    <!-- Status Badge -->
                    <div style="position: absolute; top: 15px; left: 15px;">
                        @if($warehouse->is_active)
                            <span style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-check-circle"></i> نشط
                            </span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-times-circle"></i> غير نشط
                            </span>
                        @endif
                    </div>

                    <!-- Type Badge -->
                    <div style="position: absolute; top: 15px; right: 15px;">
                        @switch($warehouse->type)
                            @case('main')
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-building"></i> رئيسي
                                </span>
                                @break
                            @case('branch')
                                <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-code-branch"></i> فرع
                                </span>
                                @break
                            @case('storage')
                                <span style="background: #f3e8ff; color: #7c3aed; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-boxes"></i> مخزن
                                </span>
                                @break
                            @case('pharmacy')
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-pills"></i> صيدلية
                                </span>
                                @break
                        @endswitch
                    </div>

                    <!-- Warehouse Info -->
                    <div style="margin-top: 40px;">
                        <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 5px 0;">{{ $warehouse->name }}</h4>
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">{{ $warehouse->code }}</div>
                        
                        @if($warehouse->location)
                            <div style="font-size: 14px; color: #4a5568; margin-bottom: 15px; display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-map-marker-alt" style="color: #ef4444;"></i>
                                {{ $warehouse->location }}
                            </div>
                        @endif

                        @if($warehouse->manager)
                            <div style="font-size: 14px; color: #4a5568; margin-bottom: 15px; display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-user-tie" style="color: #3b82f6;"></i>
                                {{ $warehouse->manager->name }}
                            </div>
                        @endif
                    </div>

                    <!-- Capacity Bar -->
                    @if($warehouse->total_capacity > 0)
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <span style="font-size: 12px; color: #6b7280;">السعة المستخدمة</span>
                                <span style="font-size: 12px; font-weight: 600; color: #4a5568;">{{ $warehouse->getCapacityUsagePercentage() }}%</span>
                            </div>
                            <div style="background: #f1f5f9; border-radius: 10px; height: 8px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #10b981, #059669); height: 100%; width: {{ $warehouse->getCapacityUsagePercentage() }}%; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Stats -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                        <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 8px;">
                            <div style="font-size: 18px; font-weight: 700; color: #3b82f6;">{{ $warehouse->getTotalProducts() }}</div>
                            <div style="font-size: 12px; color: #6b7280;">منتج</div>
                        </div>
                        <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 8px;">
                            <div style="font-size: 18px; font-weight: 700; color: #059669;">{{ number_format($warehouse->getTotalQuantity(), 0) }}</div>
                            <div style="font-size: 12px; color: #6b7280;">وحدة</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('tenant.inventory.warehouses.show', $warehouse) }}" style="flex: 1; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; font-size: 14px;">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        <a href="{{ route('tenant.inventory.warehouses.edit', $warehouse) }}" style="flex: 1; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; font-size: 14px;">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 30px;">
            {{ $warehouses->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-warehouse" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد مستودعات</h3>
            <p style="margin: 0;">لم يتم العثور على أي مستودعات تطابق معايير البحث</p>
            <a href="{{ route('tenant.inventory.warehouses.create') }}" style="display: inline-block; margin-top: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus"></i> إنشاء مستودع جديد
            </a>
        </div>
    @endif
</div>
@endsection
