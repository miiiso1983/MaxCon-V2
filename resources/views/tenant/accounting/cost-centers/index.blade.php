@extends('layouts.modern')

@section('page-title', 'مراكز التكلفة')
@section('page-description', 'إدارة مراكز التكلفة والميزانيات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            مراكز التكلفة 🏢
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة مراكز التكلفة ومتابعة الميزانيات
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-list" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $costCenters->total() }} مركز</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $costCenters->where('is_active', true)->count() }} نشط</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.accounting.cost-centers.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    إضافة مركز تكلفة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 30px;">
    <form method="GET" action="{{ route('tenant.accounting.cost-centers.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحالة:</label>
                <select name="is_active" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">جميع الحالات</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">البحث:</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المركز أو الرمز..." 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #8b5cf6; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                بحث
            </button>
            <a href="{{ route('tenant.accounting.cost-centers.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-times" style="margin-left: 8px;"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Cost Centers Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رمز المركز</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">اسم المركز</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">المركز الأب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">المدير المسؤول</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الميزانية</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">المصروف الفعلي</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الحالة</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($costCenters as $costCenter)
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.2s;" 
                        onmouseover="this.style.backgroundColor='#f8fafc'" 
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">{{ $costCenter->code }}</td>
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($costCenter->level > 1)
                                    <span style="color: #9ca3af;">{{ str_repeat('└─ ', $costCenter->level - 1) }}</span>
                                @endif
                                <span style="font-weight: 600; color: #2d3748;">{{ $costCenter->name }}</span>
                            </div>
                        </td>
                        <td style="padding: 15px; color: #6b7280; font-size: 14px;">
                            {{ $costCenter->parentCostCenter ? $costCenter->parentCostCenter->name : '-' }}
                        </td>
                        <td style="padding: 15px; color: #6b7280; font-size: 14px;">
                            {{ $costCenter->manager_name ?: '-' }}
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: #3b82f6;">
                            {{ number_format($costCenter->budget_amount, 2) }} {{ $costCenter->currency_code }}
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: #f59e0b;">
                            {{ number_format($costCenter->actual_amount, 2) }} {{ $costCenter->currency_code }}
                        </td>
                        <td style="padding: 15px;">
                            @if($costCenter->is_active)
                                <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">نشط</span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير نشط</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('tenant.accounting.cost-centers.show', $costCenter) }}" 
                                   style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenant.accounting.cost-centers.edit', $costCenter) }}" 
                                   style="background: #f59e0b; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($costCenter->accounts()->count() == 0 && $costCenter->journalEntries()->count() == 0 && $costCenter->childCostCenters()->count() == 0)
                                    <form method="POST" action="{{ route('tenant.accounting.cost-centers.destroy', $costCenter) }}" 
                                          style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف مركز التكلفة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: #ef4444; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 40px; text-align: center; color: #6b7280;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                                <i class="fas fa-building" style="font-size: 48px; color: #d1d5db;"></i>
                                <div>
                                    <h3 style="margin: 0 0 8px 0; color: #374151;">لا توجد مراكز تكلفة</h3>
                                    <p style="margin: 0; color: #6b7280;">ابدأ بإضافة مراكز تكلفة جديدة</p>
                                </div>
                                <a href="{{ route('tenant.accounting.cost-centers.create') }}" 
                                   style="background: #8b5cf6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                                    إضافة مركز تكلفة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($costCenters->hasPages())
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            {{ $costCenters->links() }}
        </div>
    @endif
</div>

<!-- Budget Summary -->
@if($costCenters->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-chart-pie" style="color: #8b5cf6;"></i>
        ملخص الميزانيات
    </h3>
    
    @php
        $totalBudget = $costCenters->sum('budget_amount');
        $totalActual = $costCenters->sum('actual_amount');
        $totalVariance = $totalBudget - $totalActual;
        $variancePercentage = $totalBudget > 0 ? (($totalActual - $totalBudget) / $totalBudget) * 100 : 0;
    @endphp
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #1e40af; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ number_format($totalBudget, 0) }}</div>
            <div style="color: #1e3a8a; font-weight: 600;">إجمالي الميزانية</div>
            <div style="color: #3b82f6; font-size: 12px;">دينار عراقي</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #d97706; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ number_format($totalActual, 0) }}</div>
            <div style="color: #92400e; font-weight: 600;">إجمالي المصروف</div>
            <div style="color: #f59e0b; font-size: 12px;">دينار عراقي</div>
        </div>
        
        <div style="background: linear-gradient(135deg, {{ $totalVariance >= 0 ? '#dcfce7' : '#fee2e2' }} 0%, {{ $totalVariance >= 0 ? '#bbf7d0' : '#fecaca' }} 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: {{ $totalVariance >= 0 ? '#166534' : '#dc2626' }}; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ number_format(abs($totalVariance), 0) }}</div>
            <div style="color: {{ $totalVariance >= 0 ? '#14532d' : '#991b1b' }}; font-weight: 600;">{{ $totalVariance >= 0 ? 'فائض' : 'عجز' }}</div>
            <div style="color: {{ $totalVariance >= 0 ? '#22c55e' : '#ef4444' }}; font-size: 12px;">دينار عراقي</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #7c3aed; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ number_format(abs($variancePercentage), 1) }}%</div>
            <div style="color: #581c87; font-weight: 600;">نسبة الانحراف</div>
            <div style="color: #8b5cf6; font-size: 12px;">{{ $variancePercentage >= 0 ? 'زيادة' : 'نقص' }}</div>
        </div>
    </div>
</div>
@endif
@endsection
