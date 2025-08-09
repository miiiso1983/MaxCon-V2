@extends('layouts.modern')

@section('title', 'عرض الفاتورة - ' . $invoice->invoice_number)

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-file-invoice text-primary me-2"></i>
                        فاتورة رقم: {{ $invoice->invoice_number }}
                    </h1>
                    <p class="text-muted mb-0">تاريخ الإنشاء: {{ $invoice->invoice_date->format('Y-m-d') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('tenant.sales.invoices.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                    @if($invoice->canBeEdited())
                    <a href="{{ route('tenant.sales.invoices.edit', $invoice) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>تعديل
                    </a>
                    @endif
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-print me-2"></i>طباعة وإرسال
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('tenant.sales.invoices.print', $invoice) }}" target="_blank">
                                <i class="fas fa-print me-2"></i>طباعة A4
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('tenant.sales.invoices.print-thermal', $invoice) }}" target="_blank">
                                <i class="fas fa-receipt me-2"></i>طباعة حرارية
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('tenant.sales.invoices.download-pdf', $invoice) }}">
                                <i class="fas fa-download me-2"></i>تحميل PDF
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="sendEmail()">
                                <i class="fas fa-envelope me-2"></i>إرسال بالإيميل
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="sendWhatsApp()">
                                <i class="fab fa-whatsapp me-2"></i>إرسال بالواتساب
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Invoice Details -->
        <div class="col-lg-8">
            <!-- Invoice Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-building me-2"></i>معلومات الشركة
                            </h5>
                            <div class="company-info">
                                <h6 class="fw-bold">{{ auth()->user()->tenant->name ?? 'اسم الشركة' }}</h6>
                                <p class="text-muted mb-1">العنوان: بغداد - العراق</p>
                                <p class="text-muted mb-1">الهاتف: +964 770 123 4567</p>
                                <p class="text-muted mb-0">البريد: info@company.com</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-user me-2"></i>معلومات العميل
                            </h5>
                            <div class="customer-info">
                                <h6 class="fw-bold">{{ $invoice->customer->name }}</h6>
                                <p class="text-muted mb-1">الهاتف: {{ $invoice->customer->phone ?? 'غير محدد' }}</p>
                                <p class="text-muted mb-1">العنوان: {{ $invoice->customer->address ?? 'غير محدد' }}</p>
                                <p class="text-muted mb-0">كود العميل: {{ $invoice->customer->customer_code ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="status-item">
                                <div class="status-icon bg-{{ $invoice->getStatusColor() }} bg-opacity-10 text-{{ $invoice->getStatusColor() }} mb-2">
                                    <i class="fas fa-file-invoice fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">حالة الفاتورة</h6>
                                <span class="badge bg-{{ $invoice->getStatusColor() }}">{{ $invoice->getStatusLabel() }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="status-item">
                                <div class="status-icon bg-{{ $invoice->payment_status == 'paid' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $invoice->payment_status == 'paid' ? 'success' : 'warning' }} mb-2">
                                    <i class="fas fa-credit-card fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">حالة الدفع</h6>
                                <span class="badge bg-{{ $invoice->payment_status == 'paid' ? 'success' : ($invoice->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                    {{ $invoice->getPaymentStatusLabel() }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="status-item">
                                <div class="status-icon bg-info bg-opacity-10 text-info mb-2">
                                    <i class="fas fa-warehouse fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">المخزن</h6>
                                <p class="mb-0 text-muted">{{ $invoice->warehouse->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="status-item">
                                <div class="status-icon bg-secondary bg-opacity-10 text-secondary mb-2">
                                    <i class="fas fa-user-tie fa-2x"></i>
                                </div>
                                <h6 class="fw-bold">مندوب المبيعات</h6>
                                <p class="mb-0 text-muted">{{ $invoice->salesRep->name ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>عناصر الفاتورة
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>سعر الوحدة</th>
                                    <th>الخصم</th>
                                    <th>الضريبة</th>
                                    <th>الإجمالي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td>
                                        <div>
                                            <div class="fw-medium">{{ $item->product_name }}</div>
                                            <small class="text-muted">كود: {{ $item->product_code }}</small>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                    <td>{{ number_format($item->selling_price, 2) }} د.ع</td>
                                    <td>
                                        @if($item->discount_percentage > 0)
                                            {{ $item->discount_percentage }}%
                                        @else
                                            {{ number_format($item->discount_amount, 2) }} د.ع
                                        @endif
                                    </td>
                                    <td>{{ $item->tax_percentage }}%</td>
                                    <td class="fw-bold">{{ number_format($item->line_total, 2) }} د.ع</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Invoice Totals -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($invoice->notes)
                            <h6 class="fw-bold">ملاحظات:</h6>
                            <p class="text-muted">{{ $invoice->notes }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="invoice-totals">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>المجموع الفرعي:</span>
                                    <span>{{ number_format($invoice->subtotal, 2) }} د.ع</span>
                                </div>
                                @if($invoice->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>الخصم:</span>
                                    <span>-{{ number_format($invoice->discount_amount, 2) }} د.ع</span>
                                </div>
                                @endif
                                @if($invoice->tax_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>الضريبة ({{ $invoice->tax_percentage }}%):</span>
                                    <span>{{ number_format($invoice->tax_amount, 2) }} د.ع</span>
                                </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between mb-3 fs-5 fw-bold">
                                    <span>المجموع الكلي:</span>
                                    <span class="text-primary">{{ number_format($invoice->total_amount, 2) }} د.ع</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>المبلغ المدفوع:</span>
                                    <span>{{ number_format($invoice->paid_amount, 2) }} د.ع</span>
                                </div>
                                <div class="d-flex justify-content-between text-danger">
                                    <span>المبلغ المتبقي:</span>
                                    <span>{{ number_format($invoice->remaining_amount, 2) }} د.ع</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- QR Code -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-qrcode me-2"></i>QR Code
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div id="qr-code" class="mb-3">
                        {!! QrCode::size(150)->generate(json_encode($invoice->qr_code_data)) !!}
                    </div>
                    <p class="text-muted small">امسح الكود للحصول على تفاصيل الفاتورة</p>
                </div>
            </div>

            <!-- Customer Debt Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>معلومات المديونية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="debt-info">
                        <div class="d-flex justify-content-between mb-2">
                            <span>المديونية السابقة:</span>
                            <span class="text-warning">{{ number_format($invoice->previous_debt, 2) }} د.ع</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>مبلغ هذه الفاتورة:</span>
                            <span>{{ number_format($invoice->total_amount, 2) }} د.ع</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2 fw-bold">
                            <span>إجمالي المديونية:</span>
                            <span class="text-danger">{{ number_format($invoice->current_debt, 2) }} د.ع</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>سقف المديونية:</span>
                            <span class="text-info">{{ number_format($invoice->credit_limit, 2) }} د.ع</span>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-{{ $invoice->current_debt > $invoice->credit_limit ? 'danger' : 'warning' }}" 
                                 style="width: {{ min(($invoice->current_debt / max($invoice->credit_limit, 1)) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($invoice->payments->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>تاريخ المدفوعات
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($invoice->payments as $payment)
                    <div class="payment-item d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-medium">{{ $payment->getPaymentMethodLabel() }}</div>
                            <small class="text-muted">{{ $payment->payment_date->format('Y-m-d') }}</small>
                        </div>
                        <div class="text-success fw-bold">
                            {{ number_format($payment->amount, 2) }} د.ع
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add Payment -->
            @if($invoice->remaining_amount > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>إضافة دفعة
                    </h6>
                </div>
                <div class="card-body">
                    <form id="payment-form">
                        <div class="mb-3">
                            <label class="form-label">المبلغ</label>
                            <input type="number" class="form-control" name="amount" max="{{ $invoice->remaining_amount }}" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">طريقة الدفع</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="cash">نقداً</option>
                                <option value="bank_transfer">تحويل بنكي</option>
                                <option value="check">شيك</option>
                                <option value="credit_card">بطاقة ائتمان</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رقم المرجع</label>
                            <input type="text" class="form-control" name="reference_number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus me-2"></i>إضافة الدفعة
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.status-item {
    padding: 1rem;
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.invoice-totals {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
}

.debt-info {
    font-size: 0.9rem;
}

.payment-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.payment-item:last-child {
    border-bottom: none;
}
</style>

<script>
function sendEmail() {
    // Email sending logic
    alert('سيتم إضافة وظيفة إرسال الإيميل قريباً');
}

function sendWhatsApp() {
    // WhatsApp sending logic
    alert('سيتم إضافة وظيفة إرسال الواتساب قريباً');
}

// Payment form submission
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`{{ route('tenant.sales.invoices.add-payment', $invoice) }}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة الدفعة');
    });
});
</script>
@endsection
