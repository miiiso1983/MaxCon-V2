<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إنشاء فاتورة جديدة - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">

    <!-- Force refresh cache -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <style>
        /* إعادة تعيين شاملة */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: #1a202c;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* تحسينات خاصة للجدول */
        .invoice-table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 25px 0;
            border: 1px solid #e2e8f0;
        }

        .invoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
        }

        .invoice-table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 12px;
            text-align: center;
            font-weight: 700;
            font-size: 15px;
            border: none;
            position: relative;
        }

        .invoice-table thead th:first-child {
            border-top-right-radius: 15px;
        }

        .invoice-table thead th:last-child {
            border-top-left-radius: 15px;
        }

        .invoice-table tbody td {
            padding: 15px 8px;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
            vertical-align: middle;
            background: white;
            transition: all 0.3s ease;
        }

        .invoice-table tbody tr:hover td {
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .invoice-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* تحسينات للحقول داخل الجدول */
        .table-select {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234a5568' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: left 12px center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            transition: all 0.3s ease;
            color: #2d3748;
            font-weight: 500;
        }

        .table-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background-color: #f7fafc;
        }

        .table-input {
            width: 100%;
            padding: 12px 10px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            text-align: center;
            transition: all 0.3s ease;
            background: white;
            color: #2d3748;
            font-weight: 500;
        }

        .table-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            background-color: #f7fafc;
        }

        .table-input[readonly] {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            color: #4a5568;
            font-weight: 700;
            border-color: #cbd5e0;
        }

        .table-btn-remove {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            border: none;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(245, 101, 101, 0.3);
        }

        .table-btn-remove:hover:not(:disabled) {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
        }

        .table-btn-remove:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        /* تحسينات للتصميم المتجاوب */
        @media (max-width: 768px) {
            .invoice-table-container {
                margin: 15px 0;
                border-radius: 10px;
            }

            .invoice-table thead th {
                padding: 12px 6px;
                font-size: 12px;
            }

            .invoice-table tbody td {
                padding: 10px 4px;
            }

            .table-select {
                padding: 8px 25px 8px 8px;
                font-size: 12px;
                background-size: 12px 8px;
                background-position: left 6px center;
            }

            .table-input {
                padding: 8px 6px;
                font-size: 12px;
            }

            .table-btn-remove {
                padding: 6px 8px;
                font-size: 11px;
            }
        }

        @media (max-width: 480px) {
            .invoice-table thead th:nth-child(4),
            .invoice-table tbody td:nth-child(4) {
                display: none; /* إخفاء عمود الخصم في الشاشات الصغيرة جداً */
            }
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 16px;
        }
        
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title i {
            color: #667eea;
            font-size: 20px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-label.required::after {
            content: ' *';
            color: #e53e3e;
        }
        
        .form-control {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .items-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            border: none;
        }

        .items-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            vertical-align: middle;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .items-table tr:hover {
            background: #f8fafc;
        }

        .items-table select,
        .items-table input {
            width: 100%;
            padding: 8px 6px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .items-table select {
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: left 8px center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            padding-left: 32px;
        }

        .items-table select:focus,
        .items-table input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        .items-table input[readonly] {
            background: #f7fafc;
            color: #4a5568;
            font-weight: 600;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }
        
        .btn-success {
            background: #48bb78;
            color: white;
        }
        
        .btn-success:hover {
            background: #38a169;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: #718096;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4a5568;
            transform: translateY(-1px);
        }
        
        .btn-add {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
        }

        .btn-remove {
            background: #f56565;
            color: white;
            padding: 8px 10px;
            font-size: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-remove:hover:not(:disabled) {
            background: #e53e3e;
            transform: translateY(-1px);
        }

        .btn-remove:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .sidebar {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .sidebar-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .sidebar-item:last-child {
            border-bottom: none;
        }
        
        .sidebar-label {
            color: #718096;
            font-size: 14px;
        }
        
        .sidebar-value {
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }
        
        .grid-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .items-table th,
            .items-table td {
                padding: 8px 4px;
                font-size: 12px;
            }

            .items-table select,
            .items-table input {
                padding: 6px 4px;
                font-size: 12px;
            }

            .items-table select {
                padding-left: 25px;
                background-size: 12px 8px;
                background-position: left 4px center;
            }

            .items-table th:first-child {
                min-width: 150px;
            }

            .items-table th:not(:first-child):not(:last-child) {
                min-width: 70px;
            }
        }
        
        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: #5a67d8;
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Link -->
        <a href="{{ route('tenant.invoices.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة الفواتير
        </a>
        
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-file-invoice"></i> إنشاء فاتورة جديدة</h1>
            <p>أنشئ فاتورة احترافية بسهولة وسرعة</p>
        </div>

        <div class="grid-layout">
            <!-- Main Form -->
            <div class="form-container">
                <form id="invoiceForm" method="POST" action="{{ route('tenant.invoices.store') }}">
                    @csrf

                    <!-- Customer Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-user"></i>
                            معلومات العميل
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">العميل</label>
                                <select name="customer_id" required class="form-control" id="customerSelect">
                                    <option value="">اختر العميل</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                                data-credit-limit="{{ $customer->credit_limit ?? 0 }}"
                                                data-previous-balance="{{ $customer->current_balance ?? 0 }}">
                                            {{ $customer->name }}
                                            @if($customer->customer_code) ({{ $customer->customer_code }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label required">تاريخ الفاتورة</label>
                                <input type="date" name="invoice_date" class="form-control"
                                       value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                @error('invoice_date')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">تاريخ الاستحقاق</label>
                                <input type="date" name="due_date" class="form-control"
                                       value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">المندوب</label>
                                <input type="text" name="sales_representative" class="form-control"
                                       placeholder="اسم المندوب" value="{{ old('sales_representative') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-list"></i>
                            عناصر الفاتورة
                        </div>

                        <div class="invoice-table-container">
                            <table class="invoice-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">المنتج</th>
                                        <th style="width: 12%;">الكمية</th>
                                        <th style="width: 16%;">السعر (د.ع)</th>
                                        <th style="width: 16%;">الخصم (د.ع)</th>
                                        <th style="width: 16%;">المجموع (د.ع)</th>
                                        <th style="width: 60px;">إجراء</th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceItems">
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][product_id]" required onchange="updateProductInfo(this, 0)" class="table-select">
                                                <option value="">اختر المنتج...</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                            data-price="{{ $product->selling_price ?? $product->unit_price ?? 0 }}">
                                                        {{ $product->name }}
                                                        @if($product->product_code) - {{ $product->product_code }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" min="1" step="1" required
                                                   placeholder="1" value="1" onchange="calculateItemTotal(0)" class="table-input">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                                                   placeholder="0.00" value="0" onchange="calculateItemTotal(0)" class="table-input">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][discount_amount]" min="0" step="0.01"
                                                   placeholder="0.00" value="0" onchange="calculateItemTotal(0)" class="table-input">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][total_amount]" readonly placeholder="0.00" value="0" class="table-input">
                                        </td>
                                        <td>
                                            <button type="button" onclick="removeItem(0)" disabled class="table-btn-remove">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" onclick="addItem()" class="btn btn-add">
                            <i class="fas fa-plus"></i>
                            إضافة منتج
                        </button>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i>
                            معلومات إضافية
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" class="form-control" rows="3"
                                          placeholder="ملاحظات إضافية...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="subtotal_amount" id="subtotalAmount" value="0">
                    <input type="hidden" name="discount_amount" id="discountAmount" value="0">
                    <input type="hidden" name="total_amount" id="totalAmount" value="0">

                    <!-- Action Buttons -->
                    <div class="actions">
                        <button type="submit" name="action" value="draft" class="btn btn-secondary">
                            <i class="fas fa-save"></i>
                            حفظ كمسودة
                        </button>
                        <button type="submit" name="action" value="finalize" class="btn btn-success">
                            <i class="fas fa-check-circle"></i>
                            إنهاء وحفظ الفاتورة
                        </button>
                        <a href="{{ route('tenant.invoices.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i>
                            العودة للفواتير
                        </a>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Customer Info -->
                <div id="customerInfo" style="display: none;">
                    <div class="sidebar-title">
                        <i class="fas fa-user"></i>
                        معلومات العميل
                    </div>
                    <div class="sidebar-item">
                        <span class="sidebar-label">سقف المديونية:</span>
                        <span class="sidebar-value" id="creditLimitDisplay">0.00 د.ع</span>
                    </div>
                    <div class="sidebar-item">
                        <span class="sidebar-label">المديونية السابقة:</span>
                        <span class="sidebar-value" id="previousBalanceDisplay">0.00 د.ع</span>
                    </div>
                </div>

                <!-- Invoice Totals -->
                <div>
                    <div class="sidebar-title">
                        <i class="fas fa-calculator"></i>
                        إجمالي الفاتورة
                    </div>
                    <div class="sidebar-item">
                        <span class="sidebar-label">المجموع الفرعي:</span>
                        <span class="sidebar-value" id="subtotalDisplay">0.00 د.ع</span>
                    </div>
                    <div class="sidebar-item">
                        <span class="sidebar-label">إجمالي الخصومات:</span>
                        <span class="sidebar-value" id="discountDisplay">0.00 د.ع</span>
                    </div>
                    <div class="sidebar-item" style="border-top: 2px solid #667eea; margin-top: 10px; padding-top: 10px; font-weight: 700;">
                        <span class="sidebar-label">الإجمالي النهائي:</span>
                        <span class="sidebar-value" id="totalDisplay">0.00 د.ع</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = 1;

        // Update product information when product is selected
        function updateProductInfo(selectElement, index) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price || 0);

            // Update price field
            const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
            if (priceInput) {
                priceInput.value = price.toFixed(2);
            }

            // Recalculate totals
            calculateItemTotal(index);
        }

        // Calculate item total
        function calculateItemTotal(index) {
            const quantity = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value || 0);
            const unitPrice = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value || 0);
            const discountAmount = parseFloat(document.querySelector(`input[name="items[${index}][discount_amount]"]`).value || 0);

            const lineTotal = (quantity * unitPrice) - discountAmount;

            // Update total field
            const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);
            if (totalInput) {
                totalInput.value = Math.max(0, lineTotal).toFixed(2);
            }

            // Recalculate grand total
            calculateGrandTotal();
        }

        // Calculate grand total
        function calculateGrandTotal() {
            let subtotal = 0;
            let totalDiscount = 0;

            // Sum all item totals
            document.querySelectorAll('input[name*="[total_amount]"]').forEach(input => {
                subtotal += parseFloat(input.value || 0);
            });

            // Sum all discounts
            document.querySelectorAll('input[name*="[discount_amount]"]').forEach(input => {
                totalDiscount += parseFloat(input.value || 0);
            });

            const grandTotal = subtotal;

            // Update displays
            document.getElementById('subtotalDisplay').textContent = (subtotal + totalDiscount).toFixed(2) + ' د.ع';
            document.getElementById('discountDisplay').textContent = totalDiscount.toFixed(2) + ' د.ع';
            document.getElementById('totalDisplay').textContent = grandTotal.toFixed(2) + ' د.ع';

            // Update hidden fields
            document.getElementById('subtotalAmount').value = (subtotal + totalDiscount).toFixed(2);
            document.getElementById('discountAmount').value = totalDiscount.toFixed(2);
            document.getElementById('totalAmount').value = grandTotal.toFixed(2);
        }

        // Add new item row
        function addItem() {
            const tbody = document.getElementById('invoiceItems');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';

            // Get product options
            const productSelect = document.querySelector('select[name*="[product_id]"]');
            const productOptions = productSelect.innerHTML;

            newRow.innerHTML = `
                <td>
                    <select name="items[${itemIndex}][product_id]" required onchange="updateProductInfo(this, ${itemIndex})" class="table-select">
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" step="1" required
                           placeholder="1" value="1" onchange="calculateItemTotal(${itemIndex})" class="table-input">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" required
                           placeholder="0.00" value="0" onchange="calculateItemTotal(${itemIndex})" class="table-input">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][discount_amount]" min="0" step="0.01"
                           placeholder="0.00" value="0" onchange="calculateItemTotal(${itemIndex})" class="table-input">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][total_amount]" readonly placeholder="0.00" value="0" class="table-input">
                </td>
                <td>
                    <button type="button" onclick="removeItem(${itemIndex})" class="table-btn-remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);
            itemIndex++;
            updateRemoveButtons();
        }

        // Remove item row
        function removeItem(index) {
            const row = document.querySelector(`tr:has(select[name="items[${index}][product_id]"])`);
            if (row) {
                row.remove();
                calculateGrandTotal();
                updateRemoveButtons();
            }
        }

        // Update remove buttons state
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('#invoiceItems tr');
            const removeButtons = document.querySelectorAll('.table-btn-remove');

            removeButtons.forEach((btn) => {
                btn.disabled = rows.length <= 1;
                if (rows.length <= 1) {
                    btn.classList.add('disabled');
                } else {
                    btn.classList.remove('disabled');
                }
            });
        }

        // Update customer information
        function updateCustomerInfo() {
            const customerSelect = document.getElementById('customerSelect');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];

            if (selectedOption.value) {
                const creditLimit = parseFloat(selectedOption.dataset.creditLimit || 0);
                const previousBalance = parseFloat(selectedOption.dataset.previousBalance || 0);

                document.getElementById('creditLimitDisplay').textContent = creditLimit.toFixed(2) + ' د.ع';
                document.getElementById('previousBalanceDisplay').textContent = previousBalance.toFixed(2) + ' د.ع';

                document.getElementById('customerInfo').style.display = 'block';
            } else {
                document.getElementById('customerInfo').style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add customer change listener
            document.getElementById('customerSelect').addEventListener('change', updateCustomerInfo);

            // Calculate initial totals
            calculateGrandTotal();

            // Update remove buttons
            updateRemoveButtons();

            // Add event listeners to existing inputs
            document.querySelectorAll('input[name*="[quantity]"], input[name*="[unit_price]"], input[name*="[discount_amount]"]').forEach(input => {
                input.addEventListener('input', function() {
                    const match = this.name.match(/items\[(\d+)\]/);
                    if (match) {
                        calculateItemTotal(parseInt(match[1]));
                    }
                });
            });
        });

        // Form validation before submit
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            const customerSelect = document.querySelector('select[name="customer_id"]');
            const productSelects = document.querySelectorAll('select[name*="[product_id]"]');

            if (!customerSelect.value) {
                e.preventDefault();
                alert('يرجى اختيار العميل');
                customerSelect.focus();
                return false;
            }

            let hasValidItems = false;
            productSelects.forEach(select => {
                if (select.value) {
                    hasValidItems = true;
                }
            });

            if (!hasValidItems) {
                e.preventDefault();
                alert('يرجى إضافة منتج واحد على الأقل');
                return false;
            }
        });
    </script>
</body>
</html>
