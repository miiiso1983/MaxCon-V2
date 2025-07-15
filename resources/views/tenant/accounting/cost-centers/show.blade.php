@extends('layouts.modern')

@section('page-title', 'تفاصيل مركز التكلفة: ' . $costCenter->name)
@section('page-description', 'عرض تفاصيل مركز التكلفة والميزانية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            {{ $costCenter->name }} 🏢
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $costCenter->code }} - {{ $costCenter->name_en }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-coins" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($costCenter->budget_amount, 0) }} {{ $costCenter->currency_code }}</span>
                    </div>
                    @php
                        $budgetUtilization = $costCenter->budget_amount > 0 ? ($actualAmount / $costCenter->budget_amount) * 100 : 0;
                    @endphp
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($budgetUtilization, 1) }}% مستخدم</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.accounting.cost-centers.edit', $costCenter) }}" style="background: rgba(245, 158, 11, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل المركز
                </a>
                <a href="{{ route('tenant.inventory.accounting.cost-centers.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- Cost Center Details -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #6366f1;"></i>
            تفاصيل المركز
        </h3>
        
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">رمز المركز:</span>
                <span style="color: #2d3748;">{{ $costCenter->code }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">اسم المركز:</span>
                <span style="color: #2d3748;">{{ $costCenter->name }}</span>
            </div>
            
            @if($costCenter->name_en)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">الاسم الإنجليزي:</span>
                <span style="color: #2d3748;">{{ $costCenter->name_en }}</span>
            </div>
            @endif
            
            @if($costCenter->parentCostCenter)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">المركز الأب:</span>
                <a href="{{ route('tenant.inventory.accounting.cost-centers.show', $costCenter->parentCostCenter) }}" style="color: #6366f1; text-decoration: none;">
                    {{ $costCenter->parentCostCenter->name }}
                </a>
            </div>
            @endif
            
            @if($costCenter->manager_name)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">المدير المسؤول:</span>
                <span style="color: #2d3748;">{{ $costCenter->manager_name }}</span>
            </div>
            @endif
            
            @if($costCenter->manager_email)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">بريد المدير:</span>
                <a href="mailto:{{ $costCenter->manager_email }}" style="color: #6366f1; text-decoration: none;">
                    {{ $costCenter->manager_email }}
                </a>
            </div>
            @endif
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">العملة:</span>
                <span style="color: #2d3748;">{{ $costCenter->currency_code }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">الحالة:</span>
                @if($costCenter->is_active)
                    <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">نشط</span>
                @else
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير نشط</span>
                @endif
            </div>
            
            @if($costCenter->description)
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 8px;">الوصف:</span>
                <span style="color: #2d3748;">{{ $costCenter->description }}</span>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Budget Information -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-chart-pie" style="color: #10b981;"></i>
            معلومات الميزانية
        </h3>
        
        <div style="display: grid; gap: 20px;">
            <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #3b82f6;">
                <div style="color: #1e40af; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الميزانية المخصصة</div>
                <div style="color: #1e40af; font-size: 28px; font-weight: 800;">{{ number_format($costCenter->budget_amount, 2) }}</div>
                <div style="color: #1e3a8a; font-size: 12px;">{{ $costCenter->currency_code }}</div>
            </div>
            
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #f59e0b;">
                <div style="color: #d97706; font-size: 14px; font-weight: 600; margin-bottom: 8px;">المبلغ المصروف</div>
                <div style="color: #d97706; font-size: 28px; font-weight: 800;">{{ number_format($costCenter->actual_amount, 2) }}</div>
                <div style="color: #92400e; font-size: 12px;">{{ $costCenter->currency_code }}</div>
            </div>
            
            @php
                $variance = $costCenter->budget_amount - $costCenter->actual_amount;
                $isPositive = $variance >= 0;
            @endphp
            
            <div style="background: linear-gradient(135deg, {{ $isPositive ? '#dcfce7' : '#fee2e2' }} 0%, {{ $isPositive ? '#bbf7d0' : '#fecaca' }} 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid {{ $isPositive ? '#22c55e' : '#ef4444' }};">
                <div style="color: {{ $isPositive ? '#166534' : '#991b1b' }}; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ $isPositive ? 'الفائض' : 'العجز' }}</div>
                <div style="color: {{ $isPositive ? '#166534' : '#991b1b' }}; font-size: 28px; font-weight: 800;">{{ number_format(abs($variance), 2) }}</div>
                <div style="color: {{ $isPositive ? '#14532d' : '#7f1d1d' }}; font-size: 12px;">{{ $costCenter->currency_code }}</div>
            </div>
            
            <div style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #8b5cf6;">
                <div style="color: #7c3aed; font-size: 14px; font-weight: 600; margin-bottom: 8px;">نسبة الاستخدام</div>
                <div style="color: #7c3aed; font-size: 28px; font-weight: 800;">{{ number_format($budgetUtilization, 1) }}%</div>
                <div style="color: #6b21a8; font-size: 12px;">من الميزانية</div>
            </div>
        </div>
        
        <!-- Budget Progress Bar -->
        <div style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-weight: 600; color: #4a5568;">تقدم الميزانية</span>
                <span style="font-weight: 600; color: #4a5568;">{{ number_format($budgetUtilization, 1) }}%</span>
            </div>
            <div style="background: #e5e7eb; border-radius: 10px; height: 20px; overflow: hidden;">
                <div style="background: linear-gradient(135deg, {{ $budgetUtilization > 100 ? '#ef4444' : ($budgetUtilization > 80 ? '#f59e0b' : '#10b981') }} 0%, {{ $budgetUtilization > 100 ? '#dc2626' : ($budgetUtilization > 80 ? '#d97706' : '#059669') }} 100%); height: 100%; width: {{ min($budgetUtilization, 100) }}%; transition: width 0.3s ease;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Child Cost Centers -->
@if($costCenter->childCostCenters->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-sitemap" style="color: #8b5cf6;"></i>
        مراكز التكلفة الفرعية
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رمز المركز</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">اسم المركز</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الميزانية</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">المصروف</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($costCenter->childCostCenters as $child)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">{{ $child->code }}</td>
                        <td style="padding: 15px; color: #2d3748;">{{ $child->name }}</td>
                        <td style="padding: 15px; font-weight: 600; color: #3b82f6;">
                            {{ number_format($child->budget_amount, 2) }} {{ $child->currency_code }}
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: #f59e0b;">
                            {{ number_format($child->actual_amount, 2) }} {{ $child->currency_code }}
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="{{ route('tenant.inventory.accounting.cost-centers.show', $child) }}" 
                               style="background: #6366f1; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Related Accounts -->
@if($relatedAccounts->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-chart-tree" style="color: #ec4899;"></i>
        الحسابات المرتبطة
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رمز الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">اسم الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">نوع الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الرصيد الحالي</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($relatedAccounts as $account)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">{{ $account->account_code }}</td>
                        <td style="padding: 15px; color: #2d3748;">{{ $account->account_name }}</td>
                        <td style="padding: 15px;">
                            <span style="background: #f3f4f6; color: #374151; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $account->account_type }}
                            </span>
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: {{ $account->current_balance >= 0 ? '#059669' : '#dc2626' }};">
                            {{ number_format($account->current_balance, 2) }} {{ $account->currency_code }}
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="{{ route('tenant.inventory.accounting.chart-of-accounts.show', $account) }}" 
                               style="background: #ec4899; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Recent Journal Entries -->
@if($recentEntries->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-history" style="color: #f59e0b;"></i>
        آخر القيود المحاسبية
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">التاريخ</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رقم القيد</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الوصف</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">المبلغ</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEntries as $entry)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; color: #6b7280;">{{ $entry->entry_date->format('Y-m-d') }}</td>
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">
                            <a href="{{ route('tenant.inventory.accounting.journal-entries.show', $entry) }}" style="color: #6366f1; text-decoration: none;">
                                {{ $entry->journal_number }}
                            </a>
                        </td>
                        <td style="padding: 15px; color: #2d3748;">{{ $entry->description }}</td>
                        <td style="padding: 15px; font-weight: 600; color: #059669;">
                            {{ number_format($entry->total_debit, 2) }} {{ $entry->currency_code }}
                        </td>
                        <td style="padding: 15px;">
                            @php
                                $statusColors = [
                                    'draft' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                                    'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                    'approved' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    'posted' => ['bg' => '#dcfce7', 'text' => '#166534']
                                ];
                                $colors = $statusColors[$entry->status] ?? $statusColors['draft'];
                            @endphp
                            <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $entry->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
