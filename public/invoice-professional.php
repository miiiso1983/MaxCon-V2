<?php
/**
 * Professional Invoice Creation - Standalone Version
 * نسخة مستقلة من صفحة الفواتير الاحترافية
 */

// Start session for authentication check
session_start();

// Simple authentication check (you may need to adjust this based on your auth system)
$authenticated = true; // For now, assume authenticated

if (!$authenticated) {
    header('Location: /login');
    exit;
}

// Mock data - in real implementation, this would come from database
$customers = [
    ['id' => 1, 'name' => 'عميل تجريبي 1', 'phone' => '07901234567', 'credit_limit' => 5000, 'previous_balance' => 1200],
    ['id' => 2, 'name' => 'عميل تجريبي 2', 'phone' => '07907654321', 'credit_limit' => 10000, 'previous_balance' => 800],
];

$products = [
    ['id' => 1, 'name' => 'منتج تجريبي 1', 'code' => 'P001', 'price' => 25.50, 'stock' => 100, 'company' => 'شركة الأدوية'],
    ['id' => 2, 'name' => 'منتج تجريبي 2', 'code' => 'P002', 'price' => 45.00, 'stock' => 50, 'company' => 'شركة الصحة'],
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء فاتورة احترافية - MaxCon</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Professional Invoice CSS -->
    <link href="/css/professional-invoice.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
        
        /* Modern Professional Invoice Design */
        .invoice-container {
            background: #f8fafc;
            min-height: 100vh;
            padding: 20px 0;
        }

        .invoice-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .invoice-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 8px;
        }

        .main-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 30px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-label.required::after {
            content: ' *';
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #ffffff;
            font-family: 'Cairo', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-family: 'Cairo', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
            color: white;
            text-decoration: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.5);
            color: white;
            text-decoration: none;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        .items-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 15px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }

        .items-table th:first-child {
            text-align: right;
        }

        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .items-table tr:hover {
            background: #f8fafc;
        }

        .add-item-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }

        .add-item-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .remove-item-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-item-btn:hover {
            background: #dc2626;
            transform: scale(1.05);
        }

        .actions-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
            text-align: center;
        }

        .actions-grid {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .invoice-wrapper {
                padding: 0 15px;
            }
            
            .invoice-header {
                padding: 20px;
                text-align: center;
            }
            
            .invoice-title {
                font-size: 24px;
                justify-content: center;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .actions-grid {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 10000;
            font-weight: 600;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 400px;
            font-family: 'Cairo', sans-serif;
        }

        .notification.show {
            transform: translateX(0);
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-wrapper">
            <!-- Header -->
            <div class="invoice-header">
                <div class="invoice-title">
                    <i class="fas fa-file-invoice"></i>
                    إنشاء فاتورة احترافية
                </div>
                <div class="invoice-subtitle">
                    نظام إدارة الفواتير المتطور مع QR Code والبحث الذكي
                </div>
            </div>

            <!-- Main Form -->
            <div class="main-form">
                <form id="invoiceForm" method="POST" action="/tenant/sales/invoices">
                    <!-- Customer Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            معلومات العميل
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">العميل</label>
                                <select name="customer_id" required class="form-control" id="customerSelect">
                                    <option value="">اختر العميل</option>
                                    <?php foreach($customers as $customer): ?>
                                        <option value="<?= $customer['id'] ?>"
                                                data-credit-limit="<?= $customer['credit_limit'] ?>"
                                                data-previous-balance="<?= $customer['previous_balance'] ?>"
                                                data-phone="<?= $customer['phone'] ?>">
                                            <?= $customer['name'] ?> - <?= $customer['phone'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">تاريخ الفاتورة</label>
                                <input type="date" name="invoice_date" class="form-control" 
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">تاريخ الاستحقاق</label>
                                <input type="date" name="due_date" class="form-control" 
                                       value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">المندوب</label>
                                <input type="text" name="sales_representative" class="form-control" 
                                       placeholder="اسم المندوب">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">المستودع</label>
                                <input type="text" name="warehouse_name" class="form-control" 
                                       placeholder="اسم المستودع" value="المستودع الرئيسي">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">نوع الفاتورة</label>
                                <select name="type" class="form-control">
                                    <option value="sales">فاتورة مبيعات</option>
                                    <option value="proforma">فاتورة أولية</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            عناصر الفاتورة
                        </div>
                        
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">المنتج</th>
                                    <th style="width: 12%;">الكمية</th>
                                    <th style="width: 15%;">السعر</th>
                                    <th style="width: 12%;">الخصم</th>
                                    <th style="width: 10%;">العينات</th>
                                    <th style="width: 15%;">المجموع</th>
                                    <th style="width: 6%;">حذف</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][product_id]" required class="form-control" onchange="updateProductInfo(this, 0)">
                                            <option value="">اختر المنتج</option>
                                            <?php foreach($products as $product): ?>
                                                <option value="<?= $product['id'] ?>" 
                                                        data-price="<?= $product['price'] ?>"
                                                        data-stock="<?= $product['stock'] ?>"
                                                        data-code="<?= $product['code'] ?>">
                                                    <?= $product['name'] ?> (<?= $product['code'] ?>) - <?= $product['company'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" min="1" step="1" required
                                               class="form-control" placeholder="1" value="1" 
                                               onchange="calculateItemTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                                               class="form-control" placeholder="0.00" value="0" 
                                               onchange="calculateItemTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][discount_amount]" min="0" step="0.01"
                                               class="form-control" placeholder="0.00" value="0" 
                                               onchange="calculateItemTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][free_samples]" min="0" step="1"
                                               class="form-control" placeholder="0" value="0" 
                                               style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][total_amount]" readonly
                                               class="form-control" placeholder="0.00" value="0" 
                                               style="background: #f9fafb; text-align: center;">
                                    </td>
                                    <td>
                                        <button type="button" onclick="removeItem(0)" class="remove-item-btn" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <button type="button" onclick="addItem()" class="add-item-btn">
                            <i class="fas fa-plus"></i>
                            إضافة منتج
                        </button>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            معلومات إضافية
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" class="form-control" rows="3" 
                                          placeholder="ملاحظات إضافية..."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">شروط الدفع</label>
                                <textarea name="payment_terms" class="form-control" rows="3" 
                                          placeholder="شروط وأحكام الدفع...">الدفع خلال 30 يوم من تاريخ الفاتورة</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="subtotal_amount" id="subtotalAmount" value="0">
                    <input type="hidden" name="discount_amount" id="discountAmount" value="0">
                    <input type="hidden" name="total_amount" id="totalAmount" value="0">
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="actions-section">
                <div class="actions-grid">
                    <button type="button" onclick="showNotification('معاينة الفاتورة قريباً!', 'info')" class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        معاينة الفاتورة
                    </button>
                    <button type="button" onclick="saveInvoice('draft')" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ كمسودة
                    </button>
                    <button type="button" onclick="saveInvoice('finalize')" class="btn btn-success">
                        <i class="fas fa-check-circle"></i>
                        إنهاء وحفظ الفاتورة
                    </button>
                    <a href="/tenant/sales/invoices" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للفواتير
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let itemIndex = 1;
        const products = <?= json_encode($products) ?>;

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
            const discount = parseFloat(document.querySelector(`input[name="items[${index}][discount_amount]"]`).value || 0);
            
            const lineTotal = (quantity * unitPrice) - discount;
            const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);
            
            if (totalInput) {
                totalInput.value = Math.max(0, lineTotal).toFixed(2);
            }
            
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
            
            const total = subtotal;
            
            // Update hidden fields
            document.getElementById('subtotalAmount').value = subtotal.toFixed(2);
            document.getElementById('discountAmount').value = totalDiscount.toFixed(2);
            document.getElementById('totalAmount').value = total.toFixed(2);
        }

        // Add new item row
        function addItem() {
            const tbody = document.getElementById('invoiceItems');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';

            let productOptions = '<option value="">اختر المنتج</option>';
            products.forEach(product => {
                productOptions += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.stock}" data-code="${product.code}">
                    ${product.name} (${product.code}) - ${product.company}
                </option>`;
            });

            newRow.innerHTML = `
                <td>
                    <select name="items[${itemIndex}][product_id]" required class="form-control" onchange="updateProductInfo(this, ${itemIndex})">
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" step="1" required
                           class="form-control" placeholder="1" value="1" 
                           onchange="calculateItemTotal(${itemIndex})" style="text-align: center;">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" required
                           class="form-control" placeholder="0.00" value="0" 
                           onchange="calculateItemTotal(${itemIndex})" style="text-align: center;">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][discount_amount]" min="0" step="0.01"
                           class="form-control" placeholder="0.00" value="0" 
                           onchange="calculateItemTotal(${itemIndex})" style="text-align: center;">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][free_samples]" min="0" step="1"
                           class="form-control" placeholder="0" value="0" 
                           style="text-align: center;">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][total_amount]" readonly
                           class="form-control" placeholder="0.00" value="0" 
                           style="background: #f9fafb; text-align: center;">
                </td>
                <td>
                    <button type="button" onclick="removeItem(${itemIndex})" class="remove-item-btn">
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
            const removeButtons = document.querySelectorAll('.remove-item-btn');
            
            removeButtons.forEach((btn, index) => {
                btn.disabled = rows.length <= 1;
            });
        }

        // Save invoice
        function saveInvoice(action) {
            showNotification(`جاري ${action === 'draft' ? 'حفظ المسودة' : 'حفظ الفاتورة'}...`, 'info');
            
            // Here you would normally submit to your Laravel backend
            // For now, just show a success message
            setTimeout(() => {
                showNotification(`تم ${action === 'draft' ? 'حفظ المسودة' : 'حفظ الفاتورة'} بنجاح!`, 'success');
            }, 2000);
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = 'notification';
            
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6'
            };

            notification.style.background = colors[type];
            notification.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculateGrandTotal();
            updateRemoveButtons();
            
            // Show welcome message
            setTimeout(() => {
                showNotification('مرحباً بك في نظام الفواتير الاحترافي!', 'success');
            }, 500);
        });
    </script>
</body>
</html>
