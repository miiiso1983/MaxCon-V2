@extends('layouts.modern')

@section('page-title', 'دفتر الأستاذ')
@section('page-description', 'دفتر الأستاذ للحسابات المحاسبية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-book" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            دفتر الأستاذ 📚
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تفاصيل حركة الحسابات المحاسبية
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <button onclick="printReport()" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); cursor: pointer;">
                    <i class="fas fa-print"></i>
                    طباعة
                </button>
                <button onclick="exportToExcel()" style="background: rgba(34, 197, 94, 0.2); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); cursor: pointer;">
                    <i class="fas fa-file-excel"></i>
                    تصدير Excel
                </button>
                <a href="{{ route('tenant.inventory.accounting.reports.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للتقارير
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 30px;">
    <form method="GET" action="{{ route('tenant.inventory.accounting.reports.account-ledger') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">الحساب المحاسبي *:</label>
                <select name="account_id" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">اختر الحساب</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->account_code }} - {{ $account->account_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">من تاريخ:</label>
                <input type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}" 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">إلى تاريخ:</label>
                <input type="date" name="date_to" value="{{ request('date_to', $dateTo) }}" 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #dc2626; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                عرض دفتر الأستاذ
            </button>
            <a href="{{ route('tenant.inventory.accounting.reports.account-ledger') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-refresh" style="margin-left: 8px;"></i>
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

@if($account)
<!-- Account Ledger -->
<div class="content-card" id="reportContent">
    <div style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
        <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 800;">دفتر الأستاذ</h2>
        <h3 style="color: #dc2626; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">
            {{ $account->account_code }} - {{ $account->account_name }}
        </h3>
        <p style="color: #6b7280; margin: 0; font-size: 16px;">
            من {{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }} إلى {{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}
        </p>
    </div>
    
    <!-- Account Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border: 2px solid #3b82f6; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #1e40af; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الرصيد الافتتاحي</div>
            <div style="color: #1e40af; font-size: 24px; font-weight: 800;">{{ number_format($openingBalance, 2) }} دينار</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border: 2px solid #22c55e; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #166534; font-size: 14px; font-weight: 600; margin-bottom: 8px;">إجمالي المدين</div>
            <div style="color: #166534; font-size: 24px; font-weight: 800;">{{ number_format($totalDebits, 2) }} دينار</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 2px solid #ef4444; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #991b1b; font-size: 14px; font-weight: 600; margin-bottom: 8px;">إجمالي الدائن</div>
            <div style="color: #991b1b; font-size: 24px; font-weight: 800;">{{ number_format($totalCredits, 2) }} دينار</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border: 2px solid #374151; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #1f2937; font-size: 14px; font-weight: 600; margin-bottom: 8px;">الرصيد الختامي</div>
            <div style="color: #1f2937; font-size: 24px; font-weight: 800;">{{ number_format($closingBalance, 2) }} دينار</div>
        </div>
    </div>
    
    <!-- Ledger Entries -->
    @if(count($ledgerEntries) > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0; min-width: 100px;">التاريخ</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0; min-width: 120px;">رقم القيد</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0; min-width: 200px;">البيان</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0; min-width: 100px;">مدين</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0; min-width: 100px;">دائن</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0; min-width: 120px;">الرصيد</th>
                </tr>
            </thead>
            <tbody>
                <!-- Opening Balance Row -->
                <tr style="background: #f0f9ff; border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; text-align: center; color: #6b7280; border: 1px solid #e2e8f0;">{{ \Carbon\Carbon::parse($dateFrom)->format('Y/m/d') }}</td>
                    <td style="padding: 12px; text-align: center; color: #6b7280; border: 1px solid #e2e8f0;">-</td>
                    <td style="padding: 12px; text-align: right; font-weight: 600; color: #1e40af; border: 1px solid #e2e8f0;">الرصيد الافتتاحي</td>
                    <td style="padding: 12px; text-align: center; color: #6b7280; border: 1px solid #e2e8f0;">-</td>
                    <td style="padding: 12px; text-align: center; color: #6b7280; border: 1px solid #e2e8f0;">-</td>
                    <td style="padding: 12px; text-align: center; font-weight: 600; color: #1e40af; border: 1px solid #e2e8f0;">
                        {{ number_format($openingBalance, 2) }}
                    </td>
                </tr>
                
                @php $runningBalance = $openingBalance; @endphp
                @foreach($ledgerEntries as $entry)
                    @php
                        if ($account->account_type == 'asset' || $account->account_type == 'expense') {
                            $runningBalance += $entry->debit_amount - $entry->credit_amount;
                        } else {
                            $runningBalance += $entry->credit_amount - $entry->debit_amount;
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #e2e8f0; {{ $loop->iteration % 2 == 0 ? 'background: #f8fafc;' : '' }}">
                        <td style="padding: 12px; text-align: center; color: #6b7280; border: 1px solid #e2e8f0;">
                            {{ \Carbon\Carbon::parse($entry->date)->format('Y/m/d') }}
                        </td>
                        <td style="padding: 12px; text-align: center; border: 1px solid #e2e8f0;">
                            <a href="{{ route('tenant.inventory.accounting.journal-entries.show', $entry->journal_entry_id) }}" 
                               style="color: #dc2626; text-decoration: none; font-weight: 600;">
                                {{ $entry->journal_number }}
                            </a>
                        </td>
                        <td style="padding: 12px; text-align: right; color: #2d3748; border: 1px solid #e2e8f0;">
                            {{ $entry->description }}
                        </td>
                        <td style="padding: 12px; text-align: center; font-weight: 600; color: #059669; border: 1px solid #e2e8f0;">
                            {{ $entry->debit_amount > 0 ? number_format($entry->debit_amount, 2) : '-' }}
                        </td>
                        <td style="padding: 12px; text-align: center; font-weight: 600; color: #dc2626; border: 1px solid #e2e8f0;">
                            {{ $entry->credit_amount > 0 ? number_format($entry->credit_amount, 2) : '-' }}
                        </td>
                        <td style="padding: 12px; text-align: center; font-weight: 600; color: {{ $runningBalance >= 0 ? '#059669' : '#dc2626' }}; border: 1px solid #e2e8f0;">
                            {{ number_format($runningBalance, 2) }}
                        </td>
                    </tr>
                @endforeach
                
                <!-- Closing Balance Row -->
                <tr style="background: #f0fdf4; border-top: 2px solid #22c55e; font-weight: 700;">
                    <td style="padding: 15px; text-align: center; color: #166534; border: 1px solid #e2e8f0;">{{ \Carbon\Carbon::parse($dateTo)->format('Y/m/d') }}</td>
                    <td style="padding: 15px; text-align: center; color: #166534; border: 1px solid #e2e8f0;">-</td>
                    <td style="padding: 15px; text-align: right; color: #166534; border: 1px solid #e2e8f0;">الرصيد الختامي</td>
                    <td style="padding: 15px; text-align: center; color: #166534; border: 1px solid #e2e8f0;">{{ number_format($totalDebits, 2) }}</td>
                    <td style="padding: 15px; text-align: center; color: #166534; border: 1px solid #e2e8f0;">{{ number_format($totalCredits, 2) }}</td>
                    <td style="padding: 15px; text-align: center; color: #166534; border: 1px solid #e2e8f0;">{{ number_format($closingBalance, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 40px; background: #f8fafc; border-radius: 8px; color: #6b7280;">
        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
        <h3 style="margin: 0 0 10px 0; font-size: 18px;">لا توجد حركات</h3>
        <p style="margin: 0;">لا توجد حركات محاسبية لهذا الحساب في الفترة المحددة</p>
    </div>
    @endif
    
    <!-- Account Analysis -->
    @if(count($ledgerEntries) > 0)
    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #6366f1; font-size: 14px; font-weight: 600; margin-bottom: 8px;">عدد الحركات</div>
            <div style="color: #4f46e5; font-size: 24px; font-weight: 800;">{{ count($ledgerEntries) }}</div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #8b5cf6; font-size: 14px; font-weight: 600; margin-bottom: 8px;">متوسط الحركة</div>
            <div style="color: #7c3aed; font-size: 24px; font-weight: 800;">
                {{ count($ledgerEntries) > 0 ? number_format(($totalDebits + $totalCredits) / count($ledgerEntries), 2) : 0 }}
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #ec4899; font-size: 14px; font-weight: 600; margin-bottom: 8px;">صافي الحركة</div>
            <div style="color: #db2777; font-size: 24px; font-weight: 800;">
                {{ number_format($closingBalance - $openingBalance, 2) }}
            </div>
        </div>
        
        <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center;">
            <div style="color: #059669; font-size: 14px; font-weight: 600; margin-bottom: 8px;">نشاط الحساب</div>
            <div style="color: #047857; font-size: 24px; font-weight: 800;">
                {{ count($ledgerEntries) > 10 ? 'عالي' : (count($ledgerEntries) > 5 ? 'متوسط' : 'منخفض') }}
            </div>
        </div>
    </div>
    @endif
    
    <!-- Report Footer -->
    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; text-align: center; color: #6b7280; font-size: 14px;">
        <p style="margin: 0;">تم إنتاج التقرير في: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p style="margin: 5px 0 0 0;">النظام المحاسبي المتكامل - MaxCon ERP</p>
    </div>
</div>
@else
<div class="content-card">
    <div style="text-align: center; padding: 40px; color: #6b7280;">
        <i class="fas fa-search" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
        <h3 style="margin: 0 0 10px 0; font-size: 18px;">اختر حساب محاسبي</h3>
        <p style="margin: 0;">يرجى اختيار حساب محاسبي من القائمة أعلاه لعرض دفتر الأستاذ</p>
    </div>
</div>
@endif

<script>
function printReport() {
    const printContent = document.getElementById('reportContent').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="font-family: Arial, sans-serif; direction: rtl; text-align: right;">
            ${printContent}
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

function exportToExcel() {
    alert('سيتم تصدير التقرير إلى Excel قريباً...');
}
</script>
@endsection
