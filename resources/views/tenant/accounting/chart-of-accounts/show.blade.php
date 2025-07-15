@extends('layouts.modern')

@section('page-title', 'تفاصيل الحساب: ' . $chartOfAccount->account_name)
@section('page-description', 'عرض تفاصيل الحساب المحاسبي')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-alt" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $chartOfAccount->account_name }} 📋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $chartOfAccount->account_code }} - {{ $chartOfAccount->account_name_en }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-tag" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $chartOfAccount->account_type }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-coins" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($currentBalance, 2) }} {{ $chartOfAccount->currency_code }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.accounting.chart-of-accounts.edit', $chartOfAccount) }}" style="background: rgba(245, 158, 11, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-edit"></i>
                    تعديل الحساب
                </a>
                <a href="{{ route('tenant.accounting.chart-of-accounts.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- Account Details -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #3b82f6;"></i>
            تفاصيل الحساب
        </h3>
        
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">رمز الحساب:</span>
                <span style="color: #2d3748;">{{ $chartOfAccount->account_code }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">اسم الحساب:</span>
                <span style="color: #2d3748;">{{ $chartOfAccount->account_name }}</span>
            </div>
            
            @if($chartOfAccount->account_name_en)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">الاسم الإنجليزي:</span>
                <span style="color: #2d3748;">{{ $chartOfAccount->account_name_en }}</span>
            </div>
            @endif
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">نوع الحساب:</span>
                <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    {{ $chartOfAccount->account_type }}
                </span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">فئة الحساب:</span>
                <span style="color: #2d3748;">{{ $chartOfAccount->account_category }}</span>
            </div>
            
            @if($chartOfAccount->parentAccount)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">الحساب الأب:</span>
                <a href="{{ route('tenant.accounting.chart-of-accounts.show', $chartOfAccount->parentAccount) }}" style="color: #3b82f6; text-decoration: none;">
                    {{ $chartOfAccount->parentAccount->account_name }}
                </a>
            </div>
            @endif
            
            @if($chartOfAccount->costCenter)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">مركز التكلفة:</span>
                <span style="color: #2d3748;">{{ $chartOfAccount->costCenter->name }}</span>
            </div>
            @endif
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">العملة:</span>
                <span style="color: #2d3748;">{{ $chartOfAccount->currency_code }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">الحالة:</span>
                @if($chartOfAccount->is_active)
                    <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">نشط</span>
                @else
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير نشط</span>
                @endif
            </div>
            
            @if($chartOfAccount->description)
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 8px;">الوصف:</span>
                <span style="color: #2d3748;">{{ $chartOfAccount->description }}</span>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Balance Information -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-chart-line" style="color: #10b981;"></i>
            معلومات الرصيد
        </h3>
        
        <div style="display: grid; gap: 20px;">
            <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #0ea5e9;">
                <div style="color: #0c4a6e; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الرصيد الحالي</div>
                <div style="color: #0c4a6e; font-size: 28px; font-weight: 800;">{{ number_format($currentBalance, 2) }}</div>
                <div style="color: #075985; font-size: 12px;">{{ $chartOfAccount->currency_code }}</div>
            </div>
            
            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #22c55e;">
                <div style="color: #14532d; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الرصيد الشهري</div>
                <div style="color: #14532d; font-size: 28px; font-weight: 800;">{{ number_format($monthlyBalance, 2) }}</div>
                <div style="color: #166534; font-size: 12px;">{{ $chartOfAccount->currency_code }}</div>
            </div>
            
            <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #f59e0b;">
                <div style="color: #92400e; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الرصيد السنوي</div>
                <div style="color: #92400e; font-size: 28px; font-weight: 800;">{{ number_format($yearlyBalance, 2) }}</div>
                <div style="color: #a16207; font-size: 12px;">{{ $chartOfAccount->currency_code }}</div>
            </div>
            
            <div style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #8b5cf6;">
                <div style="color: #581c87; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الرصيد الافتتاحي</div>
                <div style="color: #581c87; font-size: 28px; font-weight: 800;">{{ number_format($chartOfAccount->opening_balance, 2) }}</div>
                <div style="color: #6b21a8; font-size: 12px;">{{ $chartOfAccount->currency_code }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Child Accounts -->
@if($chartOfAccount->childAccounts->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-sitemap" style="color: #6366f1;"></i>
        الحسابات الفرعية
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رمز الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">اسم الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الرصيد الحالي</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chartOfAccount->childAccounts as $child)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">{{ $child->account_code }}</td>
                        <td style="padding: 15px; color: #2d3748;">{{ $child->account_name }}</td>
                        <td style="padding: 15px; font-weight: 600; color: {{ $child->current_balance >= 0 ? '#059669' : '#dc2626' }};">
                            {{ number_format($child->current_balance, 2) }} {{ $child->currency_code }}
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="{{ route('tenant.accounting.chart-of-accounts.show', $child) }}" 
                               style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
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

<!-- Recent Entries -->
@if($recentEntries->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-history" style="color: #ec4899;"></i>
        آخر الحركات
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">التاريخ</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رقم القيد</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الوصف</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">مدين</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">دائن</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEntries as $entry)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; color: #6b7280;">{{ $entry->journalEntry->entry_date->format('Y-m-d') }}</td>
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">
                            <a href="{{ route('tenant.accounting.journal-entries.show', $entry->journalEntry) }}" style="color: #3b82f6; text-decoration: none;">
                                {{ $entry->journalEntry->journal_number }}
                            </a>
                        </td>
                        <td style="padding: 15px; color: #2d3748;">{{ $entry->description ?: $entry->journalEntry->description }}</td>
                        <td style="padding: 15px; font-weight: 600; color: #059669;">
                            {{ $entry->debit_amount > 0 ? number_format($entry->debit_amount, 2) : '-' }}
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: #dc2626;">
                            {{ $entry->credit_amount > 0 ? number_format($entry->credit_amount, 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('tenant.accounting.reports.account-ledger', ['account_id' => $chartOfAccount->id]) }}" 
           style="background: #6366f1; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="fas fa-book" style="margin-left: 8px;"></i>
            عرض دفتر الأستاذ الكامل
        </a>
    </div>
</div>
@endif
@endsection
