<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة مع QR كود المنتجات</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8fafc;
        }
        
        .invoice {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .company-info h1 {
            color: #2d3748;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        
        .company-info p {
            color: #6b7280;
            margin: 5px 0;
        }
        
        .invoice-details {
            text-align: left;
        }
        
        .invoice-details h2 {
            color: #3b82f6;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .invoice-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #4a5568;
        }
        
        .invoice-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .total-section {
            text-align: left;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            min-width: 200px;
        }
        
        .total-row.final {
            font-weight: bold;
            font-size: 18px;
            color: #059669;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        
        .qr-section {
            text-align: center;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 2px dashed #d1d5db;
        }
        
        .qr-section h3 {
            color: #2d3748;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        
        .qr-section p {
            color: #6b7280;
            font-size: 12px;
            margin: 10px 0 0 0;
            line-height: 1.4;
        }
        
        #qrcode {
            margin: 10px 0;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice {
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>صيدلية الشفاء</h1>
                <p>العنوان: شارع الجامعة، بغداد، العراق</p>
                <p>الهاتف: +964 770 123 4567</p>
                <p>البريد الإلكتروني: info@pharmacy.com</p>
            </div>
            <div class="invoice-details">
                <h2>فاتورة رقم: INV-001</h2>
                <p><strong>التاريخ:</strong> {{ date('Y-m-d') }}</p>
                <p><strong>العميل:</strong> أحمد محمد</p>
                <p><strong>الهاتف:</strong> +964 771 234 5678</p>
            </div>
        </div>
        
        <!-- Invoice Items -->
        <table class="invoice-table">
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
                    <td>باراسيتامول 500 مجم</td>
                    <td>2</td>
                    <td>1,500 د.ع</td>
                    <td>3,000 د.ع</td>
                </tr>
                <tr>
                    <td>فيتامين سي 1000 مجم</td>
                    <td>1</td>
                    <td>2,500 د.ع</td>
                    <td>2,500 د.ع</td>
                </tr>
                <tr>
                    <td>شراب السعال للأطفال</td>
                    <td>1</td>
                    <td>3,200 د.ع</td>
                    <td>3,200 د.ع</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <!-- QR Code Section -->
            <div class="qr-section">
                <h3>🛍️ جميع المنتجات المتوفرة</h3>
                <div id="qrcode"></div>
                <p>
                    امسح الكود لعرض جميع المنتجات المتوفرة<br>
                    مع الأسعار والتفاصيل الكاملة<br>
                    <strong>{{ $productsCount ?? 25 }} منتج متوفر</strong>
                </p>
            </div>
            
            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <span>المجموع الفرعي:</span>
                    <span>8,700 د.ع</span>
                </div>
                <div class="total-row">
                    <span>الضريبة (0%):</span>
                    <span>0 د.ع</span>
                </div>
                <div class="total-row">
                    <span>الخصم:</span>
                    <span>200 د.ع</span>
                </div>
                <div class="total-row final">
                    <span>المجموع النهائي:</span>
                    <span>8,500 د.ع</span>
                </div>
            </div>
        </div>
        
        <!-- Footer Note -->
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; color: #6b7280; font-size: 14px;">
            <p>شكراً لتسوقكم معنا! نتمنى لكم الصحة والعافية</p>
            <p>للاستفسارات: +964 770 123 4567 | info@pharmacy.com</p>
        </div>
    </div>

    <!-- Include QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <script>
        // Sample QR data - في التطبيق الحقيقي، ستأتي هذه البيانات من الخادم
        const qrData = {
            type: 'invoice_catalog',
            tenant_id: {{ auth()->user()->tenant_id ?? 1 }},
            generated_at: '{{ date("Y-m-d H:i") }}',
            count: {{ $productsCount ?? 25 }},
            products: [
                {
                    id: 1,
                    name: 'باراسيتامول 500 مجم',
                    code: 'PRD-000001',
                    price: '1500.00',
                    currency: 'IQD',
                    brand: 'فايزر',
                    category: 'مسكنات الألم',
                    unit: 'علبة'
                },
                {
                    id: 2,
                    name: 'فيتامين سي 1000 مجم',
                    code: 'PRD-000002',
                    price: '2500.00',
                    currency: 'IQD',
                    brand: 'نوفارتيس',
                    category: 'فيتامينات',
                    unit: 'علبة'
                },
                {
                    id: 3,
                    name: 'شراب السعال للأطفال',
                    code: 'PRD-000003',
                    price: '3200.00',
                    currency: 'IQD',
                    brand: 'جلاكسو',
                    category: 'أدوية الأطفال',
                    unit: 'زجاجة'
                }
                // يمكن إضافة المزيد من المنتجات هنا
            ]
        };
        
        // Generate QR Code
        QRCode.toCanvas(document.getElementById('qrcode'), JSON.stringify(qrData), {
            width: 120,
            height: 120,
            margin: 1,
            color: {
                dark: '#2d3748',
                light: '#ffffff'
            }
        }, function (error) {
            if (error) {
                console.error('Error generating QR code:', error);
                document.getElementById('qrcode').innerHTML = '<p style="color: #ef4444;">خطأ في إنشاء QR كود</p>';
            }
        });
        
        // Print function
        function printInvoice() {
            window.print();
        }
        
        // Add print button (optional)
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.location.search.includes('print=1')) {
                const printBtn = document.createElement('button');
                printBtn.innerHTML = '<i class="fas fa-print"></i> طباعة الفاتورة';
                printBtn.style.cssText = `
                    position: fixed;
                    top: 20px;
                    left: 20px;
                    background: #3b82f6;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 600;
                    z-index: 1000;
                `;
                printBtn.onclick = printInvoice;
                document.body.appendChild(printBtn);
            }
        });
    </script>
</body>
</html>
