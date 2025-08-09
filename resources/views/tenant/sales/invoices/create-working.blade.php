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
    
    <style>
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
        
        .invoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        
        .invoice-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: 600;
        }
        
        .invoice-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .invoice-table tr:hover {
            background: #f8fafc;
        }
        
        .table-input, .table-select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
        }
        
        .table-select {
            cursor: pointer;
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
        
        .btn-success {
            background: #48bb78;
            color: white;
        }
        
        .btn-add {
            background: #48bb78;
            color: white;
            margin: 20px 0;
        }
        
        .btn-remove {
            background: #f56565;
            color: white;
            padding: 6px 10px;
            font-size: 12px;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
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
                            <label class="form-label">العميل</label>
                            <select name="customer_id" required class="form-control">
                                <option value="">اختر العميل</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">تاريخ الفاتورة</label>
                            <input type="date" name="invoice_date" class="form-control" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Invoice Items -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-list"></i>
                        عناصر الفاتورة
                    </div>
                    
                    <table class="invoice-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width: 40%;">المنتج</th>
                                <th style="width: 15%;">الكمية</th>
                                <th style="width: 20%;">السعر</th>
                                <th style="width: 20%;">المجموع</th>
                                <th style="width: 5%;">حذف</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems">
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" required class="table-select">
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price ?? 0 }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="items[0][quantity]" min="1" value="1" required class="table-input">
                                </td>
                                <td>
                                    <input type="number" name="items[0][unit_price]" min="0" step="0.01" value="0" required class="table-input">
                                </td>
                                <td>
                                    <input type="number" name="items[0][total_amount]" readonly value="0" class="table-input">
                                </td>
                                <td>
                                    <button type="button" onclick="removeItem(0)" class="btn-remove" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <button type="button" onclick="addItem()" class="btn btn-add">
                        <i class="fas fa-plus"></i>
                        إضافة منتج
                    </button>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="subtotal_amount" value="0">
                <input type="hidden" name="total_amount" value="0">

                <!-- Action Buttons -->
                <div class="actions">
                    <button type="submit" name="action" value="finalize" class="btn btn-success">
                        <i class="fas fa-check-circle"></i>
                        حفظ الفاتورة
                    </button>
                    <a href="{{ route('tenant.invoices.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        العودة
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemIndex = 1;

        function addItem() {
            const tbody = document.getElementById('invoiceItems');
            const newRow = document.createElement('tr');
            
            const productSelect = document.querySelector('select[name*="[product_id]"]');
            const productOptions = productSelect.innerHTML;
            
            newRow.innerHTML = `
                <td>
                    <select name="items[${itemIndex}][product_id]" required class="table-select">
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" required class="table-input">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" value="0" required class="table-input">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][total_amount]" readonly value="0" class="table-input">
                </td>
                <td>
                    <button type="button" onclick="removeItem(${itemIndex})" class="btn-remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            itemIndex++;
            updateRemoveButtons();
        }

        function removeItem(index) {
            const row = document.querySelector(`tr:has(select[name="items[${index}][product_id]"])`);
            if (row) {
                row.remove();
                updateRemoveButtons();
            }
        }

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('#invoiceItems tr');
            const removeButtons = document.querySelectorAll('.btn-remove');
            
            removeButtons.forEach((btn) => {
                btn.disabled = rows.length <= 1;
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
    </script>
</body>
</html>
