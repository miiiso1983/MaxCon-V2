@extends('layouts.modern')

@section('page-title', 'الجرد الدوري')
@section('page-description', 'إدارة عمليات الجرد والتدقيق الدوري للمخزون')

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
                        <i class="fas fa-clipboard-list" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            الجرد الدوري 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة عمليات الجرد والتدقيق الدوري للمخزون
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-list" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">{{ $stats['total_audits'] }} عملية جرد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-calendar" style="margin-left: 8px; color: #60a5fa;"></i>
                        <span style="font-size: 14px;">{{ $stats['scheduled'] }} مجدولة</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-play" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px;">{{ $stats['in_progress'] }} قيد التنفيذ</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check" style="margin-left: 8px; color: #34d399;"></i>
                        <span style="font-size: 14px;">{{ $stats['completed'] }} مكتملة</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.audits.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    جرد جديد
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
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">المستودع</label>
            <select name="warehouse_id" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع المستودعات</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">نوع الجرد</label>
            <select name="audit_type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الأنواع</option>
                <option value="full" {{ request('audit_type') === 'full' ? 'selected' : '' }}>جرد شامل</option>
                <option value="partial" {{ request('audit_type') === 'partial' ? 'selected' : '' }}>جرد جزئي</option>
                <option value="cycle" {{ request('audit_type') === 'cycle' ? 'selected' : '' }}>جرد دوري</option>
                <option value="spot" {{ request('audit_type') === 'spot' ? 'selected' : '' }}>جرد فوري</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الحالة</label>
            <select name="status" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">جميع الحالات</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتملة</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغية</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">من تاريخ</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-search"></i>
                بحث
            </button>
            <a href="{{ route('tenant.inventory.audits.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Audits Grid -->
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #667eea; margin-left: 10px;"></i>
            عمليات الجرد ({{ $audits->total() }})
        </h3>
    </div>
    
    @if($audits->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
            @foreach($audits as $audit)
                <div style="background: white; border-radius: 15px; padding: 20px; border: 1px solid #e2e8f0; transition: all 0.3s ease; position: relative; overflow: hidden;"
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    
                    <!-- Status Badge -->
                    <div style="position: absolute; top: 15px; left: 15px;">
                        <span style="background: {{ $audit->getStatusColor() === 'success' ? '#d1fae5' : ($audit->getStatusColor() === 'warning' ? '#fef3c7' : ($audit->getStatusColor() === 'info' ? '#dbeafe' : '#fee2e2')) }}; 
                                     color: {{ $audit->getStatusColor() === 'success' ? '#065f46' : ($audit->getStatusColor() === 'warning' ? '#92400e' : ($audit->getStatusColor() === 'info' ? '#1e40af' : '#991b1b')) }}; 
                                     padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $audit->getStatusLabel() }}
                        </span>
                    </div>

                    <!-- Type Badge -->
                    <div style="position: absolute; top: 15px; right: 15px;">
                        <span style="background: #f3e8ff; color: #7c3aed; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                            {{ $audit->getAuditTypeLabel() }}
                        </span>
                    </div>

                    <!-- Audit Info -->
                    <div style="margin-top: 40px;">
                        <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 5px 0;">{{ $audit->audit_number }}</h4>
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">{{ $audit->warehouse->name }}</div>
                        
                        <div style="font-size: 14px; color: #4a5568; margin-bottom: 15px; display: flex; align-items: center; gap: 5px;">
                            <i class="fas fa-calendar" style="color: #3b82f6;"></i>
                            {{ $audit->scheduled_date->format('Y-m-d') }}
                        </div>

                        @if($audit->createdBy)
                            <div style="font-size: 14px; color: #4a5568; margin-bottom: 15px; display: flex; align-items: center; gap: 5px;">
                                <i class="fas fa-user" style="color: #10b981;"></i>
                                {{ $audit->createdBy->name }}
                            </div>
                        @endif
                    </div>

                    <!-- Progress Bar (for in-progress audits) -->
                    @if($audit->status === 'in_progress')
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <span style="font-size: 12px; color: #6b7280;">التقدم</span>
                                <span style="font-size: 12px; font-weight: 600; color: #4a5568;">{{ $audit->getProgressPercentage() }}%</span>
                            </div>
                            <div style="background: #f1f5f9; border-radius: 10px; height: 8px; overflow: hidden;">
                                <div style="background: linear-gradient(90deg, #10b981, #059669); height: 100%; width: {{ $audit->getProgressPercentage() }}%; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Stats -->
                    @if($audit->status !== 'scheduled')
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                            <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 8px;">
                                <div style="font-size: 18px; font-weight: 700; color: #3b82f6;">{{ $audit->getTotalItems() }}</div>
                                <div style="font-size: 12px; color: #6b7280;">إجمالي العناصر</div>
                            </div>
                            <div style="text-align: center; padding: 10px; background: #f8fafc; border-radius: 8px;">
                                <div style="font-size: 18px; font-weight: 700; color: #ef4444;">{{ $audit->getDiscrepancies() }}</div>
                                <div style="font-size: 12px; color: #6b7280;">فروقات</div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('tenant.inventory.audits.show', $audit) }}" style="flex: 1; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; font-size: 14px;">
                            <i class="fas fa-eye"></i> عرض
                        </a>
                        @if($audit->status === 'scheduled')
                            <a href="#" style="flex: 1; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; font-size: 14px;">
                                <i class="fas fa-play"></i> بدء
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 30px;">
            {{ $audits->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0;">لا توجد عمليات جرد</h3>
            <p style="margin: 0;">لم يتم العثور على أي عمليات جرد تطابق معايير البحث</p>
            <a href="{{ route('tenant.inventory.audits.create') }}" style="display: inline-block; margin-top: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-plus"></i> إنشاء جرد جديد
            </a>
        </div>
    @endif
</div>
@endsection
