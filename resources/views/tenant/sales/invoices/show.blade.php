@extends('layouts.modern')

@section('page-title', 'فاتورة رقم ' . $invoice->invoice_number)
@section('page-description', 'تفاصيل الفاتورة')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body, .content-card, .invoice-header, .invoice-info {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .invoice-container {
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
    }

    .invoice-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .company-logo {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
    }

    .invoice-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin: 30px 0;
    }

    .detail-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 25px;
        border-left: 4px solid #ed8936;
    }

    .items-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .items-table thead {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        color: white;
    }

    .items-table th {
        padding: 20px 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 14px;
    }

    .items-table td {
        padding: 20px 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .items-table tbody tr:hover {
        background: #f7fafc;
        transition: background 0.3s ease;
    }

    .totals-section {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border-radius: 12px;
        padding: 30px;
        margin-top: 30px;
    }

    .qr-section {
        background: linear-gradient(135deg, #e6fffa 0%, #f0fff4 100%);
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        border: 2px solid #38b2ac;
    }

    .qr-code-container {
        display: inline-block;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin: 20px 0;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-file-invoice-dollar" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            {{ $invoice->tenant->name ?? 'شركة ماكس كون' }} 📄
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            فاتورة رقم {{ $invoice->invoice_number }} • {{ $invoice->customer->name }} • {{ $invoice->invoice_date->format('Y/m/d') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.sales.invoices.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 15px 25px; border-radius: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)';"
                   onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)';">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Status -->
<div class="content-card" style="margin-bottom: 25px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">حالة الفاتورة</h3>
        </div>
        <div>
            @if($invoice->status === 'draft')
                <span style="background: #fed7d7; color: #c53030; padding: 8px 16px; border-radius: 20px; font-weight: 600;">
                    <i class="fas fa-edit"></i> مسودة
                </span>
            @elseif($invoice->status === 'pending')
                <span style="background: #fef5e7; color: #d69e2e; padding: 8px 16px; border-radius: 20px; font-weight: 600;">
                    <i class="fas fa-clock"></i> في الانتظار
                </span>
            @elseif($invoice->status === 'paid')
                <span style="background: #c6f6d5; color: #38a169; padding: 8px 16px; border-radius: 20px; font-weight: 600;">
                    <i class="fas fa-check-circle"></i> مدفوعة
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Invoice Details -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
    <!-- Customer Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-user" style="color: #ed8936; margin-left: 10px;"></i>
            معلومات العميل
        </h3>
        
        <div style="space-y: 10px;">
            <div style="margin-bottom: 10px;">
                <strong>الاسم:</strong> {{ $invoice->customer->name }}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>رقم العميل:</strong> {{ $invoice->customer->customer_code }}
            </div>
            @if($invoice->customer->email)
            <div style="margin-bottom: 10px;">
                <strong>البريد الإلكتروني:</strong> {{ $invoice->customer->email }}
            </div>
            @endif
            @if($invoice->customer->phone)
            <div style="margin-bottom: 10px;">
                <strong>الهاتف:</strong> {{ $invoice->customer->phone }}
            </div>
            @endif
        </div>
    </div>
    
    <!-- Invoice Information -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-file-invoice" style="color: #ed8936; margin-left: 10px;"></i>
            معلومات الفاتورة
        </h3>
        
        <div style="space-y: 10px;">
            <div style="margin-bottom: 10px;">
                <strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>تاريخ الفاتورة:</strong> {{ $invoice->invoice_date->format('Y/m/d') }}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>تاريخ الاستحقاق:</strong> {{ $invoice->due_date->format('Y/m/d') }}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>العملة:</strong> {{ $invoice->currency }}
            </div>
            <div style="margin-bottom: 10px;">
                <strong>النوع:</strong>
                @if($invoice->type === 'sales')
                    فاتورة مبيعات
                @elseif($invoice->type === 'proforma')
                    فاتورة أولية
                @endif
            </div>
            @if($invoice->sales_representative)
            <div style="margin-bottom: 10px;">
                <strong>المندوب:</strong> {{ $invoice->sales_representative }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Invoice Items -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-list" style="color: #9f7aea; margin-left: 10px;"></i>
        عناصر الفاتورة
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">سعر الوحدة</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الخصم</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 15px;">
                        <div>
                            <div style="font-weight: 600; color: #2d3748;">{{ $item->product_name }}</div>
                            <div style="font-size: 14px; color: #718096;">{{ $item->product_code }}</div>
                            @if($item->notes)
                                <div style="font-size: 12px; color: #a0aec0; margin-top: 5px;">{{ $item->notes }}</div>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 15px; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 15px; text-align: center;">{{ number_format($item->unit_price, 2) }} {{ $invoice->currency }}</td>
                    <td style="padding: 15px; text-align: center;">
                        @if($item->discount_amount > 0)
                            {{ number_format($item->discount_amount, 2) }}
                            @if($item->discount_type === 'percentage') % @else {{ $invoice->currency }} @endif
                        @else
                            -
                        @endif
                    </td>
                    <td style="padding: 15px; text-align: center; font-weight: 600;">{{ number_format($item->total_amount, 2) }} {{ $invoice->currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Invoice Totals -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-calculator" style="color: #059669; margin-left: 10px;"></i>
        إجماليات الفاتورة
    </h3>
    
    <div style="max-width: 400px; margin-right: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: 600; color: #4a5568;">المجموع الفرعي:</span>
            <span style="font-weight: 700; color: #2d3748;">{{ number_format($invoice->subtotal_amount, 2) }} {{ $invoice->currency }}</span>
        </div>
        
        @if($invoice->discount_amount > 0)
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: 600; color: #4a5568;">الخصم:</span>
            <span style="font-weight: 700; color: #f56565;">{{ number_format($invoice->discount_amount, 2) }} {{ $invoice->currency }}</span>
        </div>
        @endif
        
        @if($invoice->shipping_cost > 0 || $invoice->additional_charges > 0)
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: 600; color: #4a5568;">الشحن والرسوم:</span>
            <span style="font-weight: 700; color: #2d3748;">{{ number_format($invoice->shipping_cost + $invoice->additional_charges, 2) }} {{ $invoice->currency }}</span>
        </div>
        @endif
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: 600; color: #4a5568;">ضريبة القيمة المضافة:</span>
            <span style="font-weight: 700; color: #2d3748;">{{ number_format($invoice->tax_amount, 2) }} {{ $invoice->currency }}</span>
        </div>

        @if($invoice->previous_balance > 0)
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
            <span style="font-weight: 600; color: #4a5568;">المديونية السابقة:</span>
            <span style="font-weight: 700; color: #dc2626;">{{ number_format($invoice->previous_balance, 2) }} {{ $invoice->currency }}</span>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #ed8936;">
            <span style="font-weight: 600; color: #4a5568;">إجمالي الفاتورة:</span>
            <span style="font-weight: 700; color: #2d3748;">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</span>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 8px; color: white;">
            <span style="font-weight: 700; font-size: 18px;">إجمالي المديونية:</span>
            <span style="font-weight: 800; font-size: 20px;">{{ number_format($invoice->total_amount + $invoice->previous_balance, 2) }} {{ $invoice->currency }}</span>
        </div>
        @else
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 8px; color: white;">
            <span style="font-weight: 700; font-size: 18px;">الإجمالي النهائي:</span>
            <span style="font-weight: 800; font-size: 20px;">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</span>
        </div>
        @endif
    </div>
</div>

<!-- QR Codes Section -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px;">
    <!-- Invoice QR Code -->
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="margin: 0 0 20px 0; color: #2d3748; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-file-invoice" style="color: #6366f1;"></i>
            رمز QR للتحقق من الفاتورة
        </h3>
        <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
            <div style="flex-shrink: 0;">
                @if($invoice->qr_code)
                    <img src="data:image/png;base64,{{ $invoice->qr_code }}" alt="QR Code" style="width: 120px; height: 120px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f7fafc;">
                @else
                    <div style="width: 120px; height: 120px; border: 2px dashed #cbd5e0; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: #f7fafc; color: #a0aec0; font-size: 12px; text-align: center;">
                        لا يوجد رمز QR
                    </div>
                @endif
            </div>
            <div style="text-align: center;">
                <p style="margin: 0 0 8px 0; color: #4a5568; font-size: 13px; font-weight: 600;">
                    للتحقق من صحة الفاتورة
                </p>
                <p style="margin: 0; color: #6b7280; font-size: 11px; line-height: 1.4;">
                    يحتوي على معلومات الفاتورة<br>
                    والمبالغ والضرائب
                </p>
            </div>
        </div>
    </div>

    <!-- Products Catalog QR Code -->
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <h3 style="margin: 0 0 20px 0; color: #2d3748; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-qrcode" style="color: #10b981;"></i>
            جميع المنتجات المتوفرة
        </h3>
        <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
            <div style="flex-shrink: 0;">
                <div id="productsCatalogQR" style="width: 120px; height: 120px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f7fafc; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-spinner fa-spin" style="color: #10b981; font-size: 24px;"></i>
                </div>
            </div>
            <div style="text-align: center;">
                <p style="margin: 0 0 8px 0; color: #4a5568; font-size: 13px; font-weight: 600;">
                    🛍️ امسح لعرض المنتجات
                </p>
                <p style="margin: 0; color: #6b7280; font-size: 11px; line-height: 1.4;">
                    <span id="productsCount">جاري التحميل...</span><br>
                    مع الأسعار والتفاصيل
                </p>
            </div>
        </div>
    </div>
</div>

@if($invoice->notes)
<!-- Notes -->
<div class="content-card" style="margin-bottom: 25px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-sticky-note" style="color: #6366f1; margin-left: 10px;"></i>
        ملاحظات
    </h3>
    
    <div style="background: #f7fafc; padding: 15px; border-radius: 8px; border-right: 4px solid #6366f1;">
        {{ $invoice->notes }}
    </div>
</div>
@endif

<!-- Actions -->
<div style="display: flex; gap: 15px; justify-content: flex-end;">
    @if($invoice->status === 'draft')
        <a href="{{ route('tenant.sales.invoices.edit', $invoice) }}" style="padding: 12px 24px; border: 2px solid #ed8936; border-radius: 8px; background: white; color: #ed8936; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-edit"></i>
            تعديل
        </a>
    @endif
    
    <button onclick="window.print()" style="padding: 12px 24px; border: 2px solid #6b7280; border-radius: 8px; background: white; color: #6b7280; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-print"></i>
        طباعة
    </button>
</div>
@endsection

@push('scripts')
<!-- Include QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate Products Catalog QR Code
    generateProductsCatalogQR();
});

async function generateProductsCatalogQR() {
    try {
        // Fetch QR data for invoice products catalog
        const response = await fetch('{{ route("tenant.inventory.qr.generate.invoice") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                type: 'all',
                limit: 50
            })
        });

        const data = await response.json();

        if (data.success) {
            // Generate QR code
            const qrContainer = document.getElementById('productsCatalogQR');
            qrContainer.innerHTML = '';

            QRCode.toCanvas(data.qr_data, {
                width: 120,
                height: 120,
                margin: 1,
                color: {
                    dark: '#2d3748',
                    light: '#ffffff'
                }
            }, function (error, canvas) {
                if (error) {
                    console.error('Error generating QR code:', error);
                    qrContainer.innerHTML = '<div style="color: #ef4444; font-size: 12px; text-align: center;">خطأ في إنشاء QR</div>';
                    return;
                }

                qrContainer.appendChild(canvas);

                // Update products count
                document.getElementById('productsCount').textContent = data.products_count + ' منتج متوفر';
            });
        } else {
            document.getElementById('productsCatalogQR').innerHTML = '<div style="color: #ef4444; font-size: 12px; text-align: center;">فشل في التحميل</div>';
        }
    } catch (error) {
        console.error('Error fetching QR data:', error);
        document.getElementById('productsCatalogQR').innerHTML = '<div style="color: #ef4444; font-size: 12px; text-align: center;">خطأ في الاتصال</div>';
    }
}
</script>
@endpush


