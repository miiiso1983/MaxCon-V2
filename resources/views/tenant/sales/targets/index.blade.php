@extends('layouts.tenant')

@section('title', 'أهداف البيع')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1 style="margin: 0; font-size: 28px; font-weight: 700;">
                    <i class="fas fa-bullseye" style="margin-left: 10px;"></i>
                    أهداف البيع
                </h1>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 16px;">
                    إدارة ومتابعة أهداف المبيعات للمنتجات والفرق والمندوبين
                </p>
            </div>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('tenant.sales.targets.create') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-plus"></i>
                    هدف جديد
                </a>
                <a href="{{ route('tenant.sales.targets.dashboard') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-chart-line"></i>
                    لوحة التحكم
                </a>
                <a href="{{ route('tenant.sales.targets.reports') }}" 
                   style="background: rgba(255,255,255,0.2); color: white; padding: 12px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; backdrop-filter: blur(10px);">
                    <i class="fas fa-chart-bar"></i>
                    التقارير
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['total'] }}</div>
            <div style="opacity: 0.9;">إجمالي الأهداف</div>
        </div>
        <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['active'] }}</div>
            <div style="opacity: 0.9;">الأهداف النشطة</div>
        </div>
        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['completed'] }}</div>
            <div style="opacity: 0.9;">الأهداف المكتملة</div>
        </div>
        <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
            <div style="font-size: 36px; font-weight: 700; margin-bottom: 5px;">{{ $stats['overdue'] }}</div>
            <div style="opacity: 0.9;">الأهداف المتأخرة</div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">نوع الهدف</label>
                <select name="target_type" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">جميع الأنواع</option>
                    <option value="product" {{ request('target_type') == 'product' ? 'selected' : '' }}>منتج</option>
                    <option value="vendor" {{ request('target_type') == 'vendor' ? 'selected' : '' }}>شركة</option>
                    <option value="sales_team" {{ request('target_type') == 'sales_team' ? 'selected' : '' }}>فريق مبيعات</option>
                    <option value="department" {{ request('target_type') == 'department' ? 'selected' : '' }}>قسم</option>
                    <option value="sales_rep" {{ request('target_type') == 'sales_rep' ? 'selected' : '' }}>مندوب مبيعات</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">نوع الفترة</label>
                <select name="period_type" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">جميع الفترات</option>
                    <option value="monthly" {{ request('period_type') == 'monthly' ? 'selected' : '' }}>شهرية</option>
                    <option value="quarterly" {{ request('period_type') == 'quarterly' ? 'selected' : '' }}>فصلية</option>
                    <option value="yearly" {{ request('period_type') == 'yearly' ? 'selected' : '' }}>سنوية</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">الحالة</label>
                <select name="status" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>متوقف</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">السنة</label>
                <select name="year" data-custom-select data-placeholder="اختر السنة..." data-searchable="false" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">جميع السنوات</option>
                    @for($year = date('Y'); $year >= date('Y') - 3; $year--)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #374151;">البحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث في الأهداف..." 
                       style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #4299e1; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-search"></i> بحث
                </button>
                <a href="{{ route('tenant.sales.targets.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-times"></i> مسح
                </a>
            </div>
        </form>
    </div>

    <!-- Targets Table -->
    <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        @if($targets->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="padding: 15px; text-align: right; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الهدف</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">النوع</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الفترة</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الهدف المطلوب</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">المحقق</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">التقدم</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الحالة</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($targets as $target)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 15px;">
                                    <div>
                                        <div style="font-weight: 600; color: #1f2937; margin-bottom: 5px;">{{ $target->title }}</div>
                                        <div style="font-size: 14px; color: #6b7280;">{{ $target->target_entity_name }}</div>
                                        @if($target->description)
                                            <div style="font-size: 12px; color: #9ca3af; margin-top: 3px;">{{ Str::limit($target->description, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <span style="background: #e0f2fe; color: #0277bd; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        @switch($target->target_type)
                                            @case('product') منتج @break
                                            @case('vendor') شركة @break
                                            @case('sales_team') فريق @break
                                            @case('department') قسم @break
                                            @case('sales_rep') مندوب @break
                                        @endswitch
                                    </span>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <div style="font-size: 14px; color: #374151;">
                                        @switch($target->period_type)
                                            @case('monthly') شهري @break
                                            @case('quarterly') فصلي @break
                                            @case('yearly') سنوي @break
                                        @endswitch
                                    </div>
                                    <div style="font-size: 12px; color: #6b7280;">
                                        {{ $target->start_date->format('Y-m-d') }} - {{ $target->end_date->format('Y-m-d') }}
                                    </div>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    @if($target->measurement_type === 'quantity' || $target->measurement_type === 'both')
                                        <div style="font-size: 14px; color: #374151;">{{ number_format($target->target_quantity) }} {{ $target->unit }}</div>
                                    @endif
                                    @if($target->measurement_type === 'value' || $target->measurement_type === 'both')
                                        <div style="font-size: 14px; color: #374151;">{{ number_format($target->target_value) }} {{ $target->currency }}</div>
                                    @endif
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    @if($target->measurement_type === 'quantity' || $target->measurement_type === 'both')
                                        <div style="font-size: 14px; color: #059669;">{{ number_format($target->achieved_quantity) }} {{ $target->unit }}</div>
                                    @endif
                                    @if($target->measurement_type === 'value' || $target->measurement_type === 'both')
                                        <div style="font-size: 14px; color: #059669;">{{ number_format($target->achieved_value) }} {{ $target->currency }}</div>
                                    @endif
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <div style="margin-bottom: 5px;">
                                        <span style="font-weight: 600; color: #374151;">{{ $target->progress_percentage }}%</span>
                                    </div>
                                    <div style="background: #e5e7eb; border-radius: 10px; height: 8px; overflow: hidden;">
                                        <div style="background: {{ $target->progress_percentage >= 100 ? '#10b981' : ($target->progress_percentage >= 80 ? '#f59e0b' : '#3b82f6') }}; height: 100%; width: {{ min(100, $target->progress_percentage) }}%; transition: width 0.3s ease;"></div>
                                    </div>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <span style="background: {{ $target->status_color === 'success' ? '#dcfce7' : ($target->status_color === 'danger' ? '#fef2f2' : ($target->status_color === 'warning' ? '#fef3c7' : '#f3f4f6')) }}; 
                                                 color: {{ $target->status_color === 'success' ? '#166534' : ($target->status_color === 'danger' ? '#dc2626' : ($target->status_color === 'warning' ? '#d97706' : '#374151')) }}; 
                                                 padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $target->status_text }}
                                    </span>
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                        <a href="{{ route('tenant.sales.targets.show', $target) }}" 
                                           style="background: #3b82f6; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tenant.sales.targets.edit', $target) }}" 
                                           style="background: #f59e0b; color: white; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('tenant.sales.targets.destroy', $target) }}" style="display: inline;" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الهدف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div style="padding: 20px; border-top: 1px solid #e5e7eb;">
                {{ $targets->appends(request()->query())->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
                <i class="fas fa-bullseye" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                <h3 style="margin: 0 0 10px 0; color: #374151;">لا توجد أهداف</h3>
                <p style="margin: 0 0 20px 0;">لم يتم إنشاء أي أهداف بيع بعد</p>
                <a href="{{ route('tenant.sales.targets.create') }}" 
                   style="background: #4299e1; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-plus"></i> إنشاء هدف جديد
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
