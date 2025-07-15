@extends('layouts.modern')

@section('page-title', 'تفاصيل القيد المحاسبي: ' . $journalEntry->journal_number)
@section('page-description', 'عرض تفاصيل القيد المحاسبي والموافقات')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-invoice" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $journalEntry->journal_number }} 📝
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            {{ $journalEntry->entry_date->format('Y-m-d') }} - {{ $journalEntry->description }}
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-coins" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ number_format($journalEntry->total_debit, 2) }} {{ $journalEntry->currency_code }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $statuses[$journalEntry->status] }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                @if($journalEntry->canBeEdited())
                    <a href="{{ route('tenant.inventory.accounting.journal-entries.edit', $journalEntry) }}" style="background: rgba(245, 158, 11, 0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-edit"></i>
                        تعديل القيد
                    </a>
                @endif
                <a href="{{ route('tenant.inventory.accounting.journal-entries.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقيود
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
    <!-- Journal Entry Details -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-info-circle" style="color: #ec4899;"></i>
            تفاصيل القيد
        </h3>
        
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">رقم القيد:</span>
                <span style="color: #2d3748;">{{ $journalEntry->journal_number }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">تاريخ القيد:</span>
                <span style="color: #2d3748;">{{ $journalEntry->entry_date->format('Y-m-d') }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">نوع القيد:</span>
                <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    {{ $types[$journalEntry->entry_type] }}
                </span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">الحالة:</span>
                @php
                    $statusColors = [
                        'draft' => ['bg' => '#f3f4f6', 'text' => '#374151'],
                        'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'approved' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                        'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                        'posted' => ['bg' => '#dcfce7', 'text' => '#166534']
                    ];
                    $colors = $statusColors[$journalEntry->status] ?? $statusColors['draft'];
                @endphp
                <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    {{ $statuses[$journalEntry->status] }}
                </span>
            </div>
            
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">العملة:</span>
                <span style="color: #2d3748;">{{ $journalEntry->currency_code }}</span>
            </div>
            
            @if($journalEntry->exchange_rate != 1)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">سعر الصرف:</span>
                <span style="color: #2d3748;">{{ $journalEntry->exchange_rate }}</span>
            </div>
            @endif
            
            @if($journalEntry->reference_number)
            <div style="display: flex; justify-content: space-between; padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568;">رقم المرجع:</span>
                <span style="color: #2d3748;">{{ $journalEntry->reference_number }}</span>
            </div>
            @endif
            
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 8px;">الوصف:</span>
                <span style="color: #2d3748;">{{ $journalEntry->description }}</span>
            </div>
            
            @if($journalEntry->notes)
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="font-weight: 600; color: #4a5568; display: block; margin-bottom: 8px;">ملاحظات:</span>
                <span style="color: #2d3748;">{{ $journalEntry->notes }}</span>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Balance Information -->
    <div class="content-card">
        <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            <i class="fas fa-balance-scale" style="color: #10b981;"></i>
            معلومات التوازن
        </h3>
        
        <div style="display: grid; gap: 20px;">
            <div style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #22c55e;">
                <div style="color: #166534; font-size: 14px; font-weight: 600; margin-bottom: 8px;">إجمالي المدين</div>
                <div style="color: #166534; font-size: 28px; font-weight: 800;">{{ number_format($journalEntry->total_debit, 2) }}</div>
                <div style="color: #14532d; font-size: 12px;">{{ $journalEntry->currency_code }}</div>
            </div>
            
            <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid #ef4444;">
                <div style="color: #991b1b; font-size: 14px; font-weight: 600; margin-bottom: 8px;">إجمالي الدائن</div>
                <div style="color: #991b1b; font-size: 28px; font-weight: 800;">{{ number_format($journalEntry->total_credit, 2) }}</div>
                <div style="color: #7f1d1d; font-size: 12px;">{{ $journalEntry->currency_code }}</div>
            </div>
            
            @php
                $isBalanced = abs($journalEntry->total_debit - $journalEntry->total_credit) < 0.01;
            @endphp
            
            <div style="background: linear-gradient(135deg, {{ $isBalanced ? '#dbeafe' : '#fef3c7' }} 0%, {{ $isBalanced ? '#bfdbfe' : '#fde68a' }} 100%); padding: 20px; border-radius: 12px; text-align: center; border: 2px solid {{ $isBalanced ? '#3b82f6' : '#f59e0b' }};">
                <div style="color: {{ $isBalanced ? '#1e40af' : '#d97706' }}; font-size: 14px; font-weight: 600; margin-bottom: 8px;">حالة التوازن</div>
                <div style="color: {{ $isBalanced ? '#1e40af' : '#d97706' }}; font-size: 18px; font-weight: 800;">
                    {{ $isBalanced ? 'متوازن ✅' : 'غير متوازن ⚠️' }}
                </div>
                @if(!$isBalanced)
                    <div style="color: #92400e; font-size: 12px;">الفرق: {{ number_format(abs($journalEntry->total_debit - $journalEntry->total_credit), 2) }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Journal Entry Details -->
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-list" style="color: #6366f1;"></i>
        تفاصيل القيد المحاسبي
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">رقم السطر</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">الوصف</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">مدين</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">دائن</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748; border: 1px solid #e2e8f0;">مركز التكلفة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journalEntry->details as $detail)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px; font-weight: 600; color: #4a5568; border: 1px solid #e2e8f0;">{{ $detail->line_number }}</td>
                        <td style="padding: 15px; border: 1px solid #e2e8f0;">
                            <div style="font-weight: 600; color: #2d3748;">{{ $detail->account->account_name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $detail->account->account_code }}</div>
                        </td>
                        <td style="padding: 15px; color: #2d3748; border: 1px solid #e2e8f0;">{{ $detail->description ?: '-' }}</td>
                        <td style="padding: 15px; text-align: right; font-weight: 600; color: #059669; border: 1px solid #e2e8f0;">
                            {{ $detail->debit_amount > 0 ? number_format($detail->debit_amount, 2) : '-' }}
                        </td>
                        <td style="padding: 15px; text-align: right; font-weight: 600; color: #dc2626; border: 1px solid #e2e8f0;">
                            {{ $detail->credit_amount > 0 ? number_format($detail->credit_amount, 2) : '-' }}
                        </td>
                        <td style="padding: 15px; color: #6b7280; border: 1px solid #e2e8f0;">
                            {{ $detail->costCenter ? $detail->costCenter->name : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0; font-weight: 600;">
                    <td colspan="3" style="padding: 15px; text-align: right; color: #2d3748; border: 1px solid #e2e8f0;">الإجمالي:</td>
                    <td style="padding: 15px; text-align: right; color: #059669; border: 1px solid #e2e8f0;">{{ number_format($journalEntry->total_debit, 2) }}</td>
                    <td style="padding: 15px; text-align: right; color: #dc2626; border: 1px solid #e2e8f0;">{{ number_format($journalEntry->total_credit, 2) }}</td>
                    <td style="padding: 15px; border: 1px solid #e2e8f0;"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Actions -->
@if($journalEntry->status !== 'posted')
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-cogs" style="color: #f59e0b;"></i>
        الإجراءات المتاحة
    </h3>
    
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        @if($journalEntry->status === 'draft')
            <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.submit', $journalEntry) }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-paper-plane" style="margin-left: 8px;"></i>
                    إرسال للاعتماد
                </button>
            </form>
        @endif
        
        @if($journalEntry->canBeApproved())
            <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.approve', $journalEntry) }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check" style="margin-left: 8px;"></i>
                    اعتماد القيد
                </button>
            </form>
        @endif
        
        @if($journalEntry->status === 'pending')
            <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.reject', $journalEntry) }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: #dc2626; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-times" style="margin-left: 8px;"></i>
                    رفض القيد
                </button>
            </form>
        @endif
        
        @if($journalEntry->canBePosted())
            <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.post', $journalEntry) }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: #8b5cf6; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-upload" style="margin-left: 8px;"></i>
                    ترحيل القيد
                </button>
            </form>
        @endif
        
        @if($journalEntry->canBeEdited())
            <form method="POST" action="{{ route('tenant.inventory.accounting.journal-entries.destroy', $journalEntry) }}" 
                  style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا القيد؟')">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: #ef4444; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-trash" style="margin-left: 8px;"></i>
                    حذف القيد
                </button>
            </form>
        @endif
    </div>
</div>
@endif
@endsection
