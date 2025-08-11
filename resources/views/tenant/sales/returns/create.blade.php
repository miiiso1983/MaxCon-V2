@extends('layouts.modern')

@section('page-title', 'إنشاء طلب إرجاع جديد')
@section('page-description', 'إنشاء طلب إرجاع أو استبدال للمنتجات')

@push('styles')
<style>
    .add-item-section { text-align: center; padding: 1.5rem; background: #f8fafc; border-top: 2px solid #e5e7eb; }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
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
                            طلب إرجاع جديد 🔄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إنشاء طلب إرجاع أو استبدال للمنتجات مع تحديث المخزون
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.returns.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('tenant.sales.returns.store') }}" id="returnForm">
    @csrf

    <!-- Invoice Selection -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-file-invoice" style="color: #f093fb; margin-left: 10px;"></i>
            اختيار الفاتورة
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">رقم الفاتورة *</label>
                <input type="text" id="invoice_search" placeholder="ابحث برقم الفاتورة..." style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" value="{{ $invoice ? $invoice->invoice_number : '' }}">
                <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoice ? $invoice->id : '' }}">
                <div id="invoice_results" style="position: relative; z-index: 1000;"></div>
                @error('invoice_id')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">العميل</label>
                <input type="text" id="customer_name" readonly style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f8fafc;" value="{{ $invoice ? $invoice->customer->name : '' }}">
                <input type="hidden" name="customer_id" id="customer_id" value="{{ $invoice ? $invoice->customer_id : '' }}">
            </div>
        </div>

        @if($invoice)
            <div style="margin-top: 20px; padding: 15px; background: #f0fff4; border: 1px solid #c6f6d5; border-radius: 8px;">
                <h4 style="margin: 0 0 10px 0; color: #065f46;">تفاصيل الفاتورة المحددة:</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 14px;">
                    <div><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</div>
                    <div><strong>التاريخ:</strong> {{ $invoice->invoice_date->format('Y/m/d') }}</div>
                    <div><strong>المبلغ:</strong> {{ number_format($invoice->total_amount, 0) }} د.ع</div>
                    <div><strong>عدد الأصناف:</strong> {{ $invoice->items->count() }}</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Return Details -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-info-circle" style="color: #f093fb; margin-left: 10px;"></i>
            تفاصيل الإرجاع
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">تاريخ الإرجاع *</label>
                <input type="date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                @error('return_date')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نوع العملية *</label>
                <select name="type" id="return_type" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;" required>
                    <option value="return" {{ old('type') === 'return' ? 'selected' : '' }}>إرجاع (استرداد نقدي)</option>
                    <option value="exchange" {{ old('type') === 'exchange' ? 'selected' : '' }}>استبدال (تبديل منتج)</option>
                </select>
                @error('type')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">نطاق المرتجع *</label>
                <select name="return_scope" id="return_scope" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="partial" selected>مرتجع جزئي (حدد الأصناف والكميات)</option>
                    <option value="full">مرتجع كلي (إرجاع كامل الفاتورة)</option>
                </select>
            </div>

            <div id="refund_method_div">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">طريقة الاسترداد</label>
                <select name="refund_method" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="">اختر طريقة الاسترداد</option>
                    <option value="cash" {{ old('refund_method') === 'cash' ? 'selected' : '' }}>نقداً</option>
                    <option value="credit" {{ old('refund_method') === 'credit' ? 'selected' : '' }}>رصيد للعميل</option>
                    <option value="bank_transfer" {{ old('refund_method') === 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                </select>
                @error('refund_method')
                    <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">سبب الإرجاع *</label>
            <textarea name="reason" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 80px;" placeholder="اذكر سبب الإرجاع بالتفصيل..." required>{{ old('reason') }}</textarea>
            @error('reason')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;">ملاحظات إضافية</label>
            <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; height: 60px;" placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
            @error('notes')
                <div style="color: #f56565; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Return Items (invoice-like, multi-row) -->
    <div class="content-card" style="margin-bottom: 25px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-list" style="color: #f093fb; margin-left: 10px;"></i>
            الأصناف المرتجعة
        </h3>

        @if(!$invoice)
            <div style="background:#fff7ed; border:1px solid #fed7aa; color:#92400e; padding:12px; border-radius:8px; margin-bottom:10px;">
                يرجى اختيار الفاتورة أولاً لعرض أصنافها وإضافتها كمرتجع.
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">المنتج (من الفاتورة)</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">الكمية المرتجعة</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">حالة المنتج</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748;">سبب الإرجاع</th>
                        <th style="padding: 12px; text-align: right; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748; display:none;" id="exchange_header">منتج الاستبدال</th>
                        <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e2e8f0; font-weight: 700; color: #2d3748; width: 90px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="returnItems">
                    <!-- Rows added dynamically -->
                </tbody>
            </table>
            <div class="add-item-section" style="text-align:center; padding: 1rem; background:#f8fafc; border-top:2px solid #e5e7eb;">
                <button type="button" id="btnAddRow" class="btn" style="background: #4f46e5; color:white; border-radius:8px; padding:10px 16px; font-weight:600;" {{ $invoice ? '' : 'disabled' }}>
                    <i class="fas fa-plus"></i>
                    إضافة صف مرتجع
            @if($invoice)
                <template id="server_invoice_item_options">
                    @foreach($invoice->items as $it)
                        <option value="{{ $it->id }}" data-max="{{ (int)$it->quantity }}" data-name="{{ addslashes($it->product_name) }}" data-code="{{ addslashes($it->product_code) }}" data-price="{{ (float)$it->unit_price }}">{{ addslashes($it->product_name) }} ({{ addslashes($it->product_code) }})</option>
                    @endforeach
                </template>
            @endif
                </button>
            </div>
        </div>
            @if($invoice)
                <script type="application/json" id="server_invoice_items">{!! json_encode($invoice->items->map(fn($it) => ['id' => $it->id, 'qty' => (int)$it->quantity])->values(), JSON_UNESCAPED_UNICODE) !!}</script>
            @endif
    </div>

    <!-- Form Actions -->
    <div style="display: flex; gap: 15px; justify-content: flex-end;">
        <a href="{{ route('tenant.sales.returns.index') }}" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #4a5568; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-times"></i>
            إلغاء
        </a>
        <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-save"></i>
            إنشاء طلب الإرجاع
        </button>
    </div>
</form>

@push('scripts')
<script>
// Handle return type change
document.getElementById('return_type').addEventListener('change', function() {
    const isExchange = this.value === 'exchange';
    const refundMethodDiv = document.getElementById('refund_method_div');
    const exchangeHeader = document.getElementById('exchange_header');
    const exchangeColumns = document.querySelectorAll('.exchange-column');

    if (isExchange) {
        refundMethodDiv.style.display = 'none';
        exchangeHeader.style.display = 'table-cell';
        exchangeColumns.forEach(col => col.style.display = 'table-cell');
    } else {
        refundMethodDiv.style.display = 'block';
        exchangeHeader.style.display = 'none';
        exchangeColumns.forEach(col => col.style.display = 'none');
    }
});

    // Handle return scope change (partial vs full)
    const scopeSelect = document.getElementById('return_scope');
    if (scopeSelect) {
        scopeSelect.addEventListener('change', function() {
            const isFull = this.value === 'full';
            if (isFull) {
                const tbody = document.getElementById('returnItems');
                const runtimeItems = (window.__invoiceItemsForReturn || []).map(it => ({ id: it.id, qty: parseInt(it.quantity || 0, 10) }));
                const serverItems = (() => {
                    const el = document.getElementById('server_invoice_items');
                    if (!el) return [];
                    try { return JSON.parse(el.textContent || '[]'); } catch (_) { return []; }
                })();
                const items = runtimeItems.length ? runtimeItems : serverItems;
                if (items.length && tbody) {
                    tbody.innerHTML = '';
                    items.forEach(it => addReturnRow({ invoice_item_id: it.id, quantity_returned: it.qty }));
                } else {
                    alert('يرجى اختيار الفاتورة أولاً');
                    this.value = 'partial';
                }
            } else {
                // Partial: لا إجراء تلقائي
            }
        });
    }

// Invoice search functionality
let searchTimeout;
document.getElementById('invoice_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    if (query.length < 2) {
        document.getElementById('invoice_results').innerHTML = '';
        return;
    }
    searchTimeout = setTimeout(() => {
        fetch(`/tenant/sales/returns/find-invoice?invoice_number=${encodeURIComponent(query)}`, { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                const res = document.getElementById('invoice_results');
                if (!data.found) {
                    res.innerHTML = '<div style="margin-top:8px; color:#6b7280;">لا توجد فاتورة بهذا الرقم</div>';
                    document.getElementById('invoice_id').value = '';
                    document.getElementById('customer_id').value = '';
                    document.getElementById('customer_name').value = '';
                    document.getElementById('btnAddRow')?.setAttribute('disabled', 'disabled');
                    document.getElementById('returnItems').innerHTML = '';
                    return;
                }

                // Fill invoice and customer
                res.innerHTML = `<div style="margin-top:8px; background:#ecfeff; border:1px solid #67e8f9; padding:8px; border-radius:6px;">رقم الفاتورة: <b>${data.invoice.invoice_number}</b> | العميل: <b>${data.invoice.customer.name ?? ''}</b></div>`;
                document.getElementById('invoice_id').value = data.invoice.id;
                document.getElementById('customer_id').value = data.invoice.customer.id;
                document.getElementById('customer_name').value = data.invoice.customer.name ?? '';
                document.getElementById('btnAddRow')?.removeAttribute('disabled');

                // Rebuild product options for addReturnRow with this invoice's items
                window.__invoiceItemsForReturn = data.invoice.items;
                // Clear previous rows when selecting a new invoice
                document.getElementById('returnItems').innerHTML = '';
            })
            .catch(() => {
                document.getElementById('invoice_results').innerHTML = '<div style="margin-top:8px; color:#ef4444;">تعذر البحث الآن</div>';
            });
    }, 300);
});

// Dynamic return rows behavior
let returnRowIndex = 0;
const returnItemsTbody = document.getElementById('returnItems');
const addRowBtn = document.getElementById('btnAddRow');
function esc(t){return (t||'').toString().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\"/g,'&quot;').replace(/'/g,'&#039;');}

function addReturnRow(prefill = {}) {
    if (!returnItemsTbody) return;
    const idx = returnRowIndex++;

    // Build product selector from invoice items (prefer runtime items from AJAX)
    let productOptions = '<option value="">اختر الصنف من الفاتورة</option>';
    if (Array.isArray(window.__invoiceItemsForReturn) && window.__invoiceItemsForReturn.length) {
        window.__invoiceItemsForReturn.forEach(it => {
            const name = esc(it.product_name || '');
            const code = esc(it.product_code || '');
            const price = parseFloat(it.unit_price || 0);
            const qty = parseInt(it.quantity || 0, 10);
            productOptions += `<option value="${it.id}" data-max="${qty}" data-name="${name}" data-code="${code}" data-price="${price}">${name} (${code})</option>`;
        });
    } else {
        const tpl = document.getElementById('server_invoice_item_options');
        if (tpl) {
            productOptions += tpl.innerHTML.trim();
        }
    }

    const row = document.createElement('tr');
    row.innerHTML = `
        <td style="padding:12px; border-bottom:1px solid #e2e8f0;">
            <select name="items[${idx}][invoice_item_id]" class="form-control" required onchange="onReturnItemChange(this)">${productOptions}</select>
            <div style="font-size:12px; color:#6b7280; margin-top:4px;" class="mini-info"></div>
        </td>
        <td style="padding:12px; border-bottom:1px solid #e2e8f0;">
            <input type="number" name="items[${idx}][quantity_returned]" min="1" step="1" class="form-control" placeholder="1" required>
        </td>
        <td style="padding:12px; border-bottom:1px solid #e2e8f0;">
            <select name="items[${idx}][condition]" class="form-control" required>
                <option value="good">جيد</option>
                <option value="damaged">تالف</option>
                <option value="expired">منتهي الصلاحية</option>
            </select>
        </td>
        <td style="padding:12px; border-bottom:1px solid #e2e8f0;">
            <input type="text" name="items[${idx}][reason]" class="form-control" placeholder="سبب الإرجاع لهذا الصنف..">
        </td>
        <td style="padding:12px; border-bottom:1px solid #e2e8f0; display:none;" class="exchange-column">
            <select name="items[${idx}][exchange_product_id]" class="form-control">
                <option value="">اختر منتج الاستبدال</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price ?? 0 }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <input type="number" name="items[${idx}][exchange_quantity]" min="1" class="form-control" placeholder="الكمية" style="margin-top:6px;">
        </td>
        <td style="padding:12px; border-bottom:1px solid #e2e8f0; text-align:center;">
            <button type="button" class="btn" style="background:#ef4444; color:white; border-radius:8px;" onclick="this.closest('tr').remove()"><i class="fas fa-trash"></i></button>
        </td>
    `;

    returnItemsTbody.appendChild(row);

    // Prefill if provided
    if (prefill.invoice_item_id) {
        const sel = row.querySelector('select[name*="[invoice_item_id]"]');
        sel.value = prefill.invoice_item_id;
        onReturnItemChange(sel);
    }
    if (prefill.quantity_returned) {
        row.querySelector('input[name*="[quantity_returned]"]').value = prefill.quantity_returned;
    }
}

function onReturnItemChange(selectEl) {
    const option = selectEl.options[selectEl.selectedIndex];
    const max = parseInt(option.getAttribute('data-max') || '0', 10);
    const price = parseFloat(option.getAttribute('data-price') || '0');
    const info = selectEl.closest('td').querySelector('.mini-info');
    const qtyInput = selectEl.closest('tr').querySelector('input[name*="[quantity_returned]"]');
    if (qtyInput) {
        qtyInput.max = max > 0 ? max : '';
        qtyInput.value = max > 0 ? Math.min(parseInt(qtyInput.value || '1', 10), max) : (qtyInput.value || '1');
    }
    if (info) {
        info.textContent = max ? `حد أقصى للإرجاع: ${max} | سعر الوحدة: ${price.toLocaleString()} د.ع` : '';
    }
}

if (addRowBtn) {
    addRowBtn.addEventListener('click', function() { addReturnRow(); });
}

// Toggle exchange columns with type
(function() {
    const typeSel = document.getElementById('return_type');
    const exchangeHeader = document.getElementById('exchange_header');
    function updateExchangeCols() {
        const isExchange = typeSel && typeSel.value === 'exchange';
        if (exchangeHeader) exchangeHeader.style.display = isExchange ? 'table-cell' : 'none';
        document.querySelectorAll('.exchange-column').forEach(col => col.style.display = isExchange ? 'table-cell' : 'none');
    }
    if (typeSel) {
        typeSel.addEventListener('change', updateExchangeCols);
        updateExchangeCols();
    }
})();

// Form validation
document.getElementById('returnForm').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('#returnItems tr');
    if (!rows.length) {
        e.preventDefault();
        alert('يرجى إضافة صنف واحد على الأقل للإرجاع');
        return false;
    }

    let valid = true;
    rows.forEach(row => {
        const itemSel = row.querySelector('select[name*="[invoice_item_id]"]');
        const qty = row.querySelector('input[name*="[quantity_returned]"]');
        if (!itemSel || !itemSel.value || !qty || !(parseInt(qty.value, 10) > 0)) {
            valid = false;
        }
    });

    if (!valid) {
        e.preventDefault();
        alert('يرجى اختيار الصنف وإدخال كمية صحيحة لكل صف');
        return false;
    }
});

// Initialize form state
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('return_type').dispatchEvent(new Event('change'));
    const scope = document.getElementById('return_scope');
    if (scope) scope.dispatchEvent(new Event('change'));
});
</script>
@endpush

@endsection
