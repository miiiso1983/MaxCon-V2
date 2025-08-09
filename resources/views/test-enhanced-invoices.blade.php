<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار نظام الفواتير المحسن</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .container {
            max-width: 1200px;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .feature-description {
            color: #666;
            line-height: 1.6;
        }

        .test-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin: 30px 0;
        }

        .btn-test {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: all 0.3s ease;
        }

        .btn-test:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
        }

        .sample-data {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .sample-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .sample-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
            border-left: 3px solid #667eea;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
        }

        .status-danger {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero-card text-center">
            <h1 class="display-4 mb-4">
                <i class="fas fa-file-invoice-dollar text-primary me-3"></i>
                نظام الفواتير المحسن
            </h1>
            <p class="lead text-muted">
                نظام إدارة فواتير متكامل مع QR Code وطباعة احترافية وإرسال WhatsApp والإيميل
            </p>
            
            <!-- System Status -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['tenants'] }}</div>
                    <div class="stat-label">المؤسسات</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['customers'] }}</div>
                    <div class="stat-label">العملاء</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['products'] }}</div>
                    <div class="stat-label">المنتجات</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['warehouses'] }}</div>
                    <div class="stat-label">المستودعات</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['invoices'] }}</div>
                    <div class="stat-label">الفواتير</div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="feature-title">QR Code متقدم</div>
                <div class="feature-description">
                    كل فاتورة تحتوي على QR Code يضم جميع بيانات الفاتورة: رقم الفاتورة، التاريخ، العميل، المنتجات، الإجمالي، والمخزن
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-print"></i>
                </div>
                <div class="feature-title">طباعة احترافية</div>
                <div class="feature-description">
                    دعم طباعة A4 كاملة وطباعة حرارية مع تصميم عربي RTL احترافي وتخطيط محسن للطباعة
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="feature-title">إرسال WhatsApp</div>
                <div class="feature-description">
                    إرسال نسخة PDF من الفاتورة مباشرة إلى واتساب العميل مع قوالب نصية مخصصة
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="feature-title">إرسال الإيميل</div>
                <div class="feature-description">
                    إرسال الفواتير بالبريد الإلكتروني مع قوالب احترافية وإرفاق PDF عالي الجودة
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="feature-title">تتبع المديونية</div>
                <div class="feature-description">
                    عرض المديونية السابقة والحالية وسقف المديونية مع تحديث تلقائي للحسابات
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="feature-title">إدارة المخازن</div>
                <div class="feature-description">
                    ربط الفواتير بالمخازن مع خصم تلقائي للكميات وتتبع المخزون المتاح
                </div>
            </div>
        </div>

        <!-- Sample Data -->
        @if($sampleCustomer || $sampleProducts->count() > 0 || $sampleWarehouse)
        <div class="test-section">
            <h3 class="text-center mb-4">
                <i class="fas fa-database me-2"></i>
                البيانات التجريبية المتاحة
            </h3>
            
            <div class="row">
                @if($sampleCustomer)
                <div class="col-md-4">
                    <div class="sample-data">
                        <div class="sample-title">عميل تجريبي:</div>
                        <div class="sample-item">
                            <strong>{{ $sampleCustomer->name }}</strong><br>
                            <small>{{ $sampleCustomer->phone ?? 'لا يوجد هاتف' }}</small>
                        </div>
                    </div>
                </div>
                @endif

                @if($sampleProducts->count() > 0)
                <div class="col-md-4">
                    <div class="sample-data">
                        <div class="sample-title">منتجات تجريبية:</div>
                        @foreach($sampleProducts as $product)
                        <div class="sample-item">
                            <strong>{{ $product->name }}</strong><br>
                            <small>{{ number_format($product->selling_price ?? 0, 2) }} د.ع</small>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($sampleWarehouse)
                <div class="col-md-4">
                    <div class="sample-data">
                        <div class="sample-title">مستودع تجريبي:</div>
                        <div class="sample-item">
                            <strong>{{ $sampleWarehouse->name }}</strong><br>
                            <small>{{ $sampleWarehouse->location ?? 'الموقع غير محدد' }}</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Test Actions -->
        <div class="test-section text-center">
            <h3 class="mb-4">
                <i class="fas fa-rocket me-2"></i>
                اختبار النظام
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <h5>إدارة الفواتير</h5>
                    <a href="{{ route('tenant.sales.invoices.index') }}" class="btn-test">
                        <i class="fas fa-list me-2"></i>
                        عرض جميع الفواتير
                    </a>
                    <a href="{{ route('tenant.sales.invoices.create') }}" class="btn-test">
                        <i class="fas fa-plus me-2"></i>
                        إنشاء فاتورة جديدة
                    </a>
                </div>
                
                <div class="col-md-6">
                    <h5>اختبارات النظام</h5>
                    <a href="/invoice-create-real" class="btn-test">
                        <i class="fas fa-file-invoice me-2"></i>
                        صفحة إنشاء الفاتورة التجريبية
                    </a>
                    <a href="/test-enhanced-invoices" class="btn-test">
                        <i class="fas fa-sync me-2"></i>
                        تحديث هذه الصفحة
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="test-section">
            <h3 class="text-center mb-4">
                <i class="fas fa-heartbeat me-2"></i>
                حالة النظام
            </h3>
            
            <div class="row text-center">
                <div class="col-md-3">
                    <span class="status-badge {{ $stats['tenants'] > 0 ? 'status-success' : 'status-danger' }}">
                        المؤسسات: {{ $stats['tenants'] > 0 ? 'متاح' : 'غير متاح' }}
                    </span>
                </div>
                <div class="col-md-3">
                    <span class="status-badge {{ $stats['customers'] > 0 ? 'status-success' : 'status-warning' }}">
                        العملاء: {{ $stats['customers'] > 0 ? 'متاح' : 'يحتاج بيانات' }}
                    </span>
                </div>
                <div class="col-md-3">
                    <span class="status-badge {{ $stats['products'] > 0 ? 'status-success' : 'status-warning' }}">
                        المنتجات: {{ $stats['products'] > 0 ? 'متاح' : 'يحتاج بيانات' }}
                    </span>
                </div>
                <div class="col-md-3">
                    <span class="status-badge {{ $stats['warehouses'] > 0 ? 'status-success' : 'status-warning' }}">
                        المستودعات: {{ $stats['warehouses'] > 0 ? 'متاح' : 'يحتاج إعداد' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
