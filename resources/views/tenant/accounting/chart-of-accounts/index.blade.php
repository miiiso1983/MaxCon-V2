@extends('layouts.modern')

@section('page-title', 'دليل الحسابات')
@section('page-description', 'إدارة دليل الحسابات المحاسبي')

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
                        <i class="fas fa-chart-tree" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            دليل الحسابات 📊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة الحسابات المحاسبية وفق المعايير الدولية
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-list" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $accounts->total() }} حساب</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $accounts->where('is_active', true)->count() }} نشط</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.accounting.chart-of-accounts.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i>
                    إضافة حساب جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="content-card" style="margin-bottom: 30px;">
    <form method="GET" action="{{ route('tenant.inventory.accounting.chart-of-accounts.index') }}">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع الحساب:</label>
                <select name="account_type" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">جميع الأنواع</option>
                    @foreach($accountTypes as $key => $value)
                        <option value="{{ $key }}" {{ request('account_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">فئة الحساب:</label>
                <select name="account_category" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">جميع الفئات</option>
                    @foreach($accountCategories as $key => $value)
                        <option value="{{ $key }}" {{ request('account_category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم الحساب أو الرمز..." 
                       style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #6366f1; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-search" style="margin-left: 8px;"></i>
                بحث
            </button>
            <a href="{{ route('tenant.inventory.accounting.chart-of-accounts.index') }}" style="background: #6b7280; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-times" style="margin-left: 8px;"></i>
                إلغاء
            </a>
        </div>
    </form>
</div>

<!-- Accounts Table -->
<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">رمز الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">اسم الحساب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">النوع</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الفئة</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الحساب الأب</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الرصيد الحالي</th>
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">الحالة</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.2s;" 
                        onmouseover="this.style.backgroundColor='#f8fafc'" 
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 15px; font-weight: 600; color: #4a5568;">{{ $account->account_code }}</td>
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($account->level > 1)
                                    <span style="color: #9ca3af;">{{ str_repeat('└─ ', $account->level - 1) }}</span>
                                @endif
                                <span style="font-weight: 600; color: #2d3748;">{{ $account->account_name }}</span>
                                @if($account->is_parent)
                                    <span style="background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">أب</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 15px;">
                            <span style="background: {{ $account->account_type == 'asset' ? '#dcfce7' : ($account->account_type == 'liability' ? '#fef3c7' : ($account->account_type == 'equity' ? '#e0e7ff' : ($account->account_type == 'revenue' ? '#d1fae5' : '#fee2e2'))) }}; 
                                         color: {{ $account->account_type == 'asset' ? '#166534' : ($account->account_type == 'liability' ? '#92400e' : ($account->account_type == 'equity' ? '#3730a3' : ($account->account_type == 'revenue' ? '#065f46' : '#991b1b'))) }}; 
                                         padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $accountTypes[$account->account_type] }}
                            </span>
                        </td>
                        <td style="padding: 15px; color: #6b7280; font-size: 14px;">{{ $accountCategories[$account->account_category] }}</td>
                        <td style="padding: 15px; color: #6b7280; font-size: 14px;">
                            {{ $account->parentAccount ? $account->parentAccount->account_name : '-' }}
                        </td>
                        <td style="padding: 15px; font-weight: 600; color: {{ $account->current_balance >= 0 ? '#059669' : '#dc2626' }};">
                            {{ number_format($account->current_balance, 2) }} {{ $account->currency_code }}
                        </td>
                        <td style="padding: 15px;">
                            @if($account->is_active)
                                <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">نشط</span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير نشط</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('tenant.inventory.accounting.chart-of-accounts.show', $account) }}" 
                                   style="background: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenant.inventory.accounting.chart-of-accounts.edit', $account) }}" 
                                   style="background: #f59e0b; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($account->canBeDeleted())
                                    <form method="POST" action="{{ route('tenant.inventory.accounting.chart-of-accounts.destroy', $account) }}" 
                                          style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟')">
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
                                <i class="fas fa-chart-tree" style="font-size: 48px; color: #d1d5db;"></i>
                                <div>
                                    <h3 style="margin: 0 0 8px 0; color: #374151;">لا توجد حسابات</h3>
                                    <p style="margin: 0; color: #6b7280;">ابدأ بإضافة حسابات جديدة لدليل الحسابات</p>
                                </div>
                                <a href="{{ route('tenant.inventory.accounting.chart-of-accounts.create') }}" 
                                   style="background: #6366f1; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                                    <i class="fas fa-plus" style="margin-left: 8px;"></i>
                                    إضافة حساب جديد
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($accounts->hasPages())
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            {{ $accounts->links() }}
        </div>
    @endif
</div>
@endsection
