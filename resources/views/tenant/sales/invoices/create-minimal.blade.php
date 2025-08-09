<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إنشاء فاتورة - MaxCon</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .success {
            background: #28a745;
        }
        .success:hover {
            background: #1e7e34;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>إنشاء فاتورة جديدة</h1>
        
        <form method="POST" action="{{ route('tenant.invoices.store') }}">
            @csrf
            
            <div class="form-group">
                <label>العميل:</label>
                <select name="customer_id" required>
                    <option value="">اختر العميل</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>تاريخ الفاتورة:</label>
                <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required>
            </div>
            
            <h3>عناصر الفاتورة</h3>
            <table>
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>المجموع</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="items[0][product_id]" required>
                                <option value="">اختر المنتج</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" value="1" min="1" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][unit_price]" value="0" step="0.01" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][total_amount]" value="0" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <input type="hidden" name="subtotal_amount" value="0">
            <input type="hidden" name="total_amount" value="0">
            
            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" name="action" value="finalize" class="btn success">
                    حفظ الفاتورة
                </button>
                <a href="{{ route('tenant.invoices.index') }}" class="btn">العودة</a>
            </div>
        </form>
    </div>
</body>
</html>
