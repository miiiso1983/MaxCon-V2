<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إنشاء فاتورة - MaxCon</title>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .form-content {
            padding: 40px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin: 25px 0;
        }

        .invoice-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 15px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
        }

        .invoice-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }

        .invoice-table tr:hover {
            background: #f8fafc;
        }

        .table-input, .table-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .table-input:focus, .table-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Select2 Custom Styling */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 50px !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 8px 12px !important;
            background: #f8fafc !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
            color: #2d3748 !important;
            font-size: 16px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 8px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
            background: white !important;
        }

        .select2-dropdown {
            border: 2px solid #667eea !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #667eea !important;
        }

        .table-select2 {
            width: 100%;
        }

        .table-select2 .select2-container--default .select2-selection--single {
            height: 40px !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 4px 8px !important;
        }

        .table-select2 .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            font-size: 14px !important;
        }

        .table-select2 .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            margin: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
        }

        .btn-add {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            margin: 20px 0;
        }

        .btn-remove {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
            padding: 8px 12px;
            font-size: 12px;
        }

        .actions {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
        }

        .totals-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 25px;
            border-radius: 15px;
            margin: 25px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 16px;
        }

        .total-row.final {
            border-top: 2px solid #667eea;
            margin-top: 15px;
            padding-top: 15px;
            font-weight: 700;
            font-size: 20px;
            color: #667eea;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .container {
                margin: 10px;
                border-radius: 15px;
            }

            .form-content {
                padding: 20px;
            }

            .invoice-table th,
            .invoice-table td {
                padding: 10px 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🧾 إنشاء فاتورة جديدة</h1>
            <p>نظام MaxCon للإدارة الصيدلانية المتقدم</p>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <form method="POST" action="/tenant/sales/invoices" id="invoiceForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Customer Information -->
                <div class="section-title">
                    👤 معلومات العميل
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">العميل *</label>
                        <select name="customer_id" required class="form-control customer-select" id="customerSelect">
                            <option value="">اختر العميل...</option>
                            <option value="1" data-balance="1500.00" data-credit="10000.00" data-phone="07901234567">شركة الأدوية المتقدمة - CUST001 (07901234567)</option>
                            <option value="2" data-balance="750.50" data-credit="5000.00" data-phone="07801234567">صيدلية النور الطبية - CUST002 (07801234567)</option>
                            <option value="3" data-balance="2250.75" data-credit="15000.00" data-phone="07701234567">مستشفى بغداد التخصصي - CUST003 (07701234567)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">تاريخ الفاتورة *</label>
                        <input type="date" name="invoice_date" value="2025-08-09" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">تاريخ الاستحقاق</label>
                        <input type="date" name="due_date" value="2025-09-08" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">المندوب</label>
                        <input type="text" name="sales_representative" placeholder="اسم المندوب..." class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">نوع الفاتورة</label>
                        <select name="type" class="form-control">
                            <option value="sales">فاتورة مبيعات</option>
                            <option value="proforma">فاتورة أولية</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ملاحظات</label>
                        <input type="text" name="notes" placeholder="ملاحظات إضافية..." class="form-control">
                    </div>
                </div>

                <!-- Customer Info Display -->
                <div id="customerInfo" style="display: none;" class="totals-section">
                    <div class="section-title">
                        👤 معلومات العميل المختار
                    </div>

                    <div class="total-row">
                        <span>المديونية السابقة:</span>
                        <span id="currentBalanceDisplay">0.00 د.ع</span>
                    </div>

                    <div class="total-row">
                        <span>سقف المديونية:</span>
                        <span id="creditLimitDisplay">0.00 د.ع</span>
                    </div>

                    <div class="total-row">
                        <span>المديونية بعد هذه الفاتورة:</span>
                        <span id="newBalanceDisplay">0.00 د.ع</span>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="section-title">
                    📦 عناصر الفاتورة
                </div>

                <table class="invoice-table" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 30%;">المنتج</th>
                            <th style="width: 12%;">الكمية</th>
                            <th style="width: 15%;">السعر (د.ع)</th>
                            <th style="width: 12%;">الخصم (د.ع)</th>
                            <th style="width: 12%;">العينات المجانية</th>
                            <th style="width: 15%;">المجموع (د.ع)</th>
                            <th style="width: 60px;">إجراء</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceItems">
                        <tr>
                            <td>
                                <select name="items[0][product_id]" required class="table-select product-select" onchange="updateProductPrice(this, 0)">
                                    <option value="">اختر المنتج...</option>
                                    <option value="1" data-price="15.50" data-stock="100">باراسيتامول 500 مجم - PARA500 (المخزون: 100)</option>
                                    <option value="2" data-price="25.00" data-stock="75">أموكسيسيلين 250 مجم - AMOX250 (المخزون: 75)</option>
                                    <option value="3" data-price="35.75" data-stock="50">فيتامين د 1000 وحدة - VITD1000 (المخزون: 50)</option>
                                    <option value="4" data-price="45.25" data-stock="30">أوميجا 3 كبسولات - OMEGA3 (المخزون: 30)</option>
                                    <option value="5" data-price="12.00" data-stock="200">أسبرين 100 مجم - ASP100 (المخزون: 200)</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="items[0][quantity]" value="1" min="1" required class="table-input" onchange="calculateTotal(0)">
                            </td>
                            <td>
                                <input type="number" name="items[0][unit_price]" value="0" step="0.01" required class="table-input" onchange="calculateTotal(0)">
                            </td>
                            <td>
                                <input type="number" name="items[0][discount_amount]" value="0" step="0.01" class="table-input" onchange="calculateTotal(0)">
                            </td>
                            <td>
                                <input type="number" name="items[0][free_samples]" value="0" min="0" class="table-input" placeholder="0">
                            </td>
                            <td>
                                <input type="number" name="items[0][total_amount]" value="0" readonly class="table-input" style="background: #f8fafc; font-weight: 600;">
                            </td>
                            <td>
                                <button type="button" onclick="removeItem(0)" class="btn-remove" disabled>
                                    🗑️
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" onclick="addItem()" class="btn btn-add">
                    ➕ إضافة منتج جديد
                </button>
            
                <!-- Totals Section -->
                <div class="totals-section">
                    <div class="section-title">
                        🧮 ملخص الفاتورة
                    </div>

                    <div class="total-row">
                        <span>المجموع الفرعي:</span>
                        <span id="subtotalDisplay">0.00 د.ع</span>
                    </div>

                    <div class="total-row">
                        <span>إجمالي الخصومات:</span>
                        <span id="discountDisplay">0.00 د.ع</span>
                    </div>

                    <div class="total-row final">
                        <span>الإجمالي النهائي:</span>
                        <span id="totalDisplay">0.00 د.ع</span>
                    </div>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="subtotal_amount" id="subtotalAmount" value="0">
                <input type="hidden" name="discount_amount" id="discountAmount" value="0">
                <input type="hidden" name="total_amount" id="totalAmount" value="0">

                <!-- Action Buttons -->
                <div class="actions">
                    <button type="submit" name="action" value="draft" class="btn btn-primary">
                        💾 حفظ كمسودة
                    </button>
                    <button type="submit" name="action" value="finalize" class="btn btn-success">
                        ✅ إنهاء وحفظ الفاتورة
                    </button>
                    <a href="/tenant/sales/invoices" class="btn btn-primary">
                        ↩️ العودة للفواتير
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemIndex = 1;

        // Initialize Select2 for all select elements
        function initializeSelect2() {
            // Customer select
            $('.customer-select').select2({
                placeholder: 'ابحث عن العميل...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            // Product selects
            $('.product-select').select2({
                placeholder: 'ابحث عن المنتج...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });
        }

        // Update customer info when customer is selected
        function updateCustomerInfo() {
            const customerSelect = document.getElementById('customerSelect');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];

            if (selectedOption.value) {
                const currentBalance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;
                const creditLimit = parseFloat(selectedOption.getAttribute('data-credit')) || 0;

                document.getElementById('currentBalanceDisplay').textContent = currentBalance.toFixed(2) + ' د.ع';
                document.getElementById('creditLimitDisplay').textContent = creditLimit.toFixed(2) + ' د.ع';

                document.getElementById('customerInfo').style.display = 'block';
                updateNewBalance();
            } else {
                document.getElementById('customerInfo').style.display = 'none';
            }
        }

        // Update new balance after invoice
        function updateNewBalance() {
            const customerSelect = document.getElementById('customerSelect');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];

            if (selectedOption.value) {
                const currentBalance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;
                const invoiceTotal = parseFloat(document.getElementById('totalAmount').value) || 0;
                const newBalance = currentBalance + invoiceTotal;

                document.getElementById('newBalanceDisplay').textContent = newBalance.toFixed(2) + ' د.ع';

                // Check credit limit
                const creditLimit = parseFloat(selectedOption.getAttribute('data-credit')) || 0;
                const newBalanceElement = document.getElementById('newBalanceDisplay');

                if (newBalance > creditLimit) {
                    newBalanceElement.style.color = '#e53e3e';
                    newBalanceElement.style.fontWeight = 'bold';
                } else {
                    newBalanceElement.style.color = '#38a169';
                    newBalanceElement.style.fontWeight = 'normal';
                }
            }
        }

        // Update product price when product is selected
        function updateProductPrice(select, index) {
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            const stock = selectedOption.getAttribute('data-stock') || 0;

            const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
            priceInput.value = price;

            // Show stock warning if low
            if (stock < 10 && stock > 0) {
                alert(`تحذير: المخزون المتبقي من هذا المنتج: ${stock} وحدة فقط`);
            } else if (stock == 0) {
                alert('تحذير: هذا المنتج غير متوفر في المخزون');
            }

            calculateTotal(index);
        }

        // Calculate total for a specific item
        function calculateTotal(index) {
            const quantity = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value) || 0;
            const unitPrice = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value) || 0;
            const discount = parseFloat(document.querySelector(`input[name="items[${index}][discount_amount]"]`).value) || 0;

            const total = (quantity * unitPrice) - discount;
            document.querySelector(`input[name="items[${index}][total_amount]"]`).value = total.toFixed(2);

            updateGrandTotal();
        }

        // Update grand total
        function updateGrandTotal() {
            let subtotal = 0;
            let totalDiscount = 0;

            document.querySelectorAll('input[name*="[total_amount]"]').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });

            document.querySelectorAll('input[name*="[discount_amount]"]').forEach(input => {
                totalDiscount += parseFloat(input.value) || 0;
            });

            const grandTotal = subtotal;

            document.getElementById('subtotalDisplay').textContent = (subtotal + totalDiscount).toFixed(2) + ' د.ع';
            document.getElementById('discountDisplay').textContent = totalDiscount.toFixed(2) + ' د.ع';
            document.getElementById('totalDisplay').textContent = grandTotal.toFixed(2) + ' د.ع';

            document.getElementById('subtotalAmount').value = (subtotal + totalDiscount).toFixed(2);
            document.getElementById('discountAmount').value = totalDiscount.toFixed(2);
            document.getElementById('totalAmount').value = grandTotal.toFixed(2);

            // Update customer new balance
            updateNewBalance();
        }

        // Add new item row
        function addItem() {
            const tbody = document.getElementById('invoiceItems');
            const newRow = document.createElement('tr');

            // Get product options from the first select
            const firstProductSelect = document.querySelector('select[name*="[product_id]"]');
            const productOptions = firstProductSelect.innerHTML;

            newRow.innerHTML = `
                <td>
                    <select name="items[${itemIndex}][product_id]" required class="table-select product-select" onchange="updateProductPrice(this, ${itemIndex})">
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" required class="table-input" onchange="calculateTotal(${itemIndex})">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" value="0" step="0.01" required class="table-input" onchange="calculateTotal(${itemIndex})">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][discount_amount]" value="0" step="0.01" class="table-input" onchange="calculateTotal(${itemIndex})">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][free_samples]" value="0" min="0" class="table-input" placeholder="0">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][total_amount]" value="0" readonly class="table-input" style="background: #f8fafc; font-weight: 600;">
                </td>
                <td>
                    <button type="button" onclick="removeItem(${itemIndex})" class="btn-remove">
                        🗑️
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);

            // Initialize Select2 for the new product select
            $(newRow).find('.product-select').select2({
                placeholder: 'ابحث عن المنتج...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                }
            });

            itemIndex++;
            updateRemoveButtons();
        }

        // Remove item row
        function removeItem(index) {
            const row = document.querySelector(`tr:has(select[name="items[${index}][product_id]"])`);
            if (row) {
                row.remove();
                updateGrandTotal();
                updateRemoveButtons();
            }
        }

        // Update remove buttons state
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('#invoiceItems tr');
            const removeButtons = document.querySelectorAll('.btn-remove');

            removeButtons.forEach(btn => {
                btn.disabled = rows.length <= 1;
                btn.style.opacity = rows.length <= 1 ? '0.5' : '1';
            });
        }

        // Form validation
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('#invoiceItems tr');
            if (rows.length === 0) {
                e.preventDefault();
                alert('يرجى إضافة منتج واحد على الأقل');
                return false;
            }

            const total = parseFloat(document.getElementById('totalAmount').value);
            if (total <= 0) {
                e.preventDefault();
                alert('يرجى التأكد من صحة المبالغ المدخلة');
                return false;
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            initializeSelect2();

            // Add customer change event
            $('#customerSelect').on('change', updateCustomerInfo);

            updateRemoveButtons();
            updateGrandTotal();
        });
    </script>
</body>
</html>
