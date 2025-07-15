@extends('layouts.modern')

@section('page-title', 'إضافة قيد محاسبي جديد')
@section('page-description', 'إضافة قيد محاسبي جديد مع التحقق من التوازن')

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
                        <i class="fas fa-plus-circle" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            إضافة قيد محاسبي ➕
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إضافة قيد محاسبي جديد مع التحقق من التوازن
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.accounting.journal-entries.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقيود
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Create Form -->
<div class="content-card">
    <form method="POST" action="{{ route('tenant.accounting.journal-entries.store') }}" id="journalForm">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- Basic Information -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    <i class="fas fa-info-circle" style="color: #10b981;"></i>
                    معلومات القيد
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ القيد *</label>
                    <input type="date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('entry_date')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">وصف القيد *</label>
                    <textarea name="description" rows="3" required
                              style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s; resize: vertical;"
                              placeholder="وصف مفصل للقيد المحاسبي..."
                              onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">{{ old('description') }}</textarea>
                    @error('description')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم المرجع</label>
                    <input type="text" name="reference_number" value="{{ old('reference_number') }}"
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           placeholder="رقم الفاتورة أو المستند المرجعي"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('reference_number')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- Additional Settings -->
            <div>
                <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    <i class="fas fa-cogs" style="color: #6366f1;"></i>
                    الإعدادات
                </h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع القيد *</label>
                    <select name="entry_type" required
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                        @foreach($types as $key => $value)
                            <option value="{{ $key }}" {{ old('entry_type', 'manual') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('entry_type')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العملة *</label>
                    <select name="currency_code" required
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="IQD" {{ old('currency_code', 'IQD') == 'IQD' ? 'selected' : '' }}>دينار عراقي (IQD)</option>
                        <option value="USD" {{ old('currency_code') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                        <option value="EUR" {{ old('currency_code') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                    </select>
                    @error('currency_code')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سعر الصرف *</label>
                    <input type="number" name="exchange_rate" value="{{ old('exchange_rate', 1.0000) }}" step="0.0001" min="0.0001" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('exchange_rate')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">مركز التكلفة</label>
                    <select name="cost_center_id"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">
                        <option value="">لا يوجد</option>
                        @foreach($costCenters as $costCenter)
                            <option value="{{ $costCenter->id }}" {{ old('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                {{ $costCenter->code }} - {{ $costCenter->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('cost_center_id')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات</label>
                    <textarea name="notes" rows="2"
                              style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; transition: border-color 0.2s; resize: vertical;"
                              placeholder="ملاحظات إضافية..."
                              onfocus="this.style.borderColor='#10b981'" onblur="this.style.borderColor='#e2e8f0'">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span style="color: #ef4444; font-size: 14px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Journal Entry Details -->
        <div>
            <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                <i class="fas fa-list" style="color: #ec4899;"></i>
                تفاصيل القيد
                <div style="margin-right: auto; display: flex; gap: 10px;">
                    <button type="button" onclick="addDetailRow()" style="background: #10b981; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; cursor: pointer;">
                        <i class="fas fa-plus" style="margin-left: 5px;"></i>
                        إضافة سطر
                    </button>
                </div>
            </h3>
            
            <div style="overflow-x: auto;">
                <table id="detailsTable" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: #2d3748; width: 30%;">الحساب</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: #2d3748; width: 25%;">الوصف</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: #2d3748; width: 15%;">مدين</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: #2d3748; width: 15%;">دائن</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600; color: #2d3748; width: 10%;">مركز التكلفة</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600; color: #2d3748; width: 5%;">حذف</th>
                        </tr>
                    </thead>
                    <tbody id="detailsBody">
                        <!-- Rows will be added dynamically -->
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0; font-weight: 600;">
                            <td colspan="2" style="padding: 12px; text-align: right; color: #2d3748;">الإجمالي:</td>
                            <td style="padding: 12px; text-align: right; color: #059669;" id="totalDebit">0.00</td>
                            <td style="padding: 12px; text-align: right; color: #dc2626;" id="totalCredit">0.00</td>
                            <td colspan="2" style="padding: 12px; text-align: center;">
                                <span id="balanceStatus" style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">غير متوازن</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            @error('details')
                <div style="color: #ef4444; font-size: 14px; margin-bottom: 20px; padding: 12px; background: #fee2e2; border-radius: 8px;">
                    {{ $message }}
                </div>
            @enderror
        </div>
        
        <!-- Form Actions -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e2e8f0; display: flex; gap: 15px; justify-content: flex-end;">
            <a href="{{ route('tenant.accounting.journal-entries.index') }}" 
               style="background: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
            <button type="submit" id="submitBtn" disabled
                    style="background: #9ca3af; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: not-allowed; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i>
                حفظ القيد
            </button>
        </div>
    </form>
</div>

<script>
let detailRowCount = 0;
const accounts = @json($accounts);
const costCenters = @json($costCenters);

// Add initial rows
document.addEventListener('DOMContentLoaded', function() {
    addDetailRow();
    addDetailRow();
});

function addDetailRow() {
    detailRowCount++;
    const tbody = document.getElementById('detailsBody');
    const row = document.createElement('tr');
    row.style.borderBottom = '1px solid #e2e8f0';
    row.innerHTML = `
        <td style="padding: 8px;">
            <select name="details[${detailRowCount}][account_id]" required onchange="updateAccountInfo(this)"
                    style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;">
                <option value="">اختر الحساب</option>
                ${accounts.map(account => `<option value="${account.id}">${account.account_code} - ${account.account_name}</option>`).join('')}
            </select>
        </td>
        <td style="padding: 8px;">
            <input type="text" name="details[${detailRowCount}][description]" 
                   style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;"
                   placeholder="وصف السطر">
        </td>
        <td style="padding: 8px;">
            <input type="number" name="details[${detailRowCount}][debit_amount]" step="0.01" min="0" value="0"
                   style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;"
                   onchange="updateTotals(); validateAmounts(this)">
        </td>
        <td style="padding: 8px;">
            <input type="number" name="details[${detailRowCount}][credit_amount]" step="0.01" min="0" value="0"
                   style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;"
                   onchange="updateTotals(); validateAmounts(this)">
        </td>
        <td style="padding: 8px;">
            <select name="details[${detailRowCount}][cost_center_id]"
                    style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 14px;">
                <option value="">لا يوجد</option>
                ${costCenters.map(center => `<option value="${center.id}">${center.code}</option>`).join('')}
            </select>
        </td>
        <td style="padding: 8px; text-align: center;">
            <button type="button" onclick="removeDetailRow(this)" 
                    style="background: #ef4444; color: white; padding: 6px 8px; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}

function removeDetailRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateTotals();
}

function validateAmounts(input) {
    const row = input.closest('tr');
    const debitInput = row.querySelector('input[name*="[debit_amount]"]');
    const creditInput = row.querySelector('input[name*="[credit_amount]"]');
    
    const debit = parseFloat(debitInput.value) || 0;
    const credit = parseFloat(creditInput.value) || 0;
    
    // Ensure only one amount is entered per row
    if (input.name.includes('debit') && debit > 0) {
        creditInput.value = 0;
    } else if (input.name.includes('credit') && credit > 0) {
        debitInput.value = 0;
    }
    
    updateTotals();
}

function updateTotals() {
    const debitInputs = document.querySelectorAll('input[name*="[debit_amount]"]');
    const creditInputs = document.querySelectorAll('input[name*="[credit_amount]"]');
    
    let totalDebit = 0;
    let totalCredit = 0;
    
    debitInputs.forEach(input => {
        totalDebit += parseFloat(input.value) || 0;
    });
    
    creditInputs.forEach(input => {
        totalCredit += parseFloat(input.value) || 0;
    });
    
    document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
    document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);
    
    const isBalanced = Math.abs(totalDebit - totalCredit) < 0.01 && totalDebit > 0;
    const balanceStatus = document.getElementById('balanceStatus');
    const submitBtn = document.getElementById('submitBtn');
    
    if (isBalanced) {
        balanceStatus.textContent = 'متوازن';
        balanceStatus.style.background = '#dcfce7';
        balanceStatus.style.color = '#166534';
        submitBtn.disabled = false;
        submitBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        submitBtn.style.cursor = 'pointer';
    } else {
        balanceStatus.textContent = 'غير متوازن';
        balanceStatus.style.background = '#fee2e2';
        balanceStatus.style.color = '#991b1b';
        submitBtn.disabled = true;
        submitBtn.style.background = '#9ca3af';
        submitBtn.style.cursor = 'not-allowed';
    }
}

function updateAccountInfo(select) {
    const accountId = select.value;
    if (accountId) {
        // You can add AJAX call here to get account details
        console.log('Selected account:', accountId);
    }
}
</script>
@endsection
