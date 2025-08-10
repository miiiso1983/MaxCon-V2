<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إدارة الفواتير - {{ config('app.name') }}</title>
    
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
            background: #f8fafc;
            color: #1a202c;
            line-height: 1.6;
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
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #718096;
            font-size: 14px;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
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
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .table th {
            background: #667eea;
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: 600;
        }
        
        .table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tr:hover {
            background: #f7fafc;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-draft {
            background: #fed7d7;
            color: #c53030;
        }
        
        .status-sent {
            background: #bee3f8;
            color: #2b6cb0;
        }
        
        .status-paid {
            background: #c6f6d5;
            color: #2f855a;
        }
        
        .status-overdue {
            background: #feebc8;
            color: #c05621;
        }
        
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .form-control {
            padding: 10px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-decoration: none;
            color: #4a5568;
        }
        
        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        @media (max-width: 768px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #cbd5e0;
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
        <a href="{{ route('tenant.dashboard') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى لوحة التحكم
        </a>
        
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-file-invoice"></i> إدارة الفواتير</h1>
            <p>إدارة شاملة للفواتير والمدفوعات</p>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">{{ $invoices->total() ?? 0 }}</div>
                <div class="stat-label">إجمالي الفواتير</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statusCounts['sent'] ?? 0 }}</div>
                <div class="stat-label">فواتير مرسلة</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statusCounts['paid'] ?? 0 }}</div>
                <div class="stat-label">فواتير مدفوعة</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $statusCounts['overdue'] ?? 0 }}</div>
                <div class="stat-label">فواتير متأخرة</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('tenant.sales.invoices.create-simple') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                إنشاء فاتورة جديدة
            </a>
            <a href="{{ route('tenant.sales.invoices.create-professional') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i>
                إنشاء فاتورة احترافية
            </a>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" action="{{ route('tenant.sales.invoices.index') }}">
                <div class="filter-row">
                    <div class="form-group">
                        <label class="form-label">البحث</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="رقم الفاتورة أو اسم العميل"
                               value="{{ request('search') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-control">
                            <option value="">جميع الحالات</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسلة</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">العميل</label>
                        <select name="customer_id" class="form-control">
                            <option value="">جميع العملاء</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Invoices Table -->
        <div class="table-container">
            @if($invoices->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة</th>
                            <th>العميل</th>
                            <th>التاريخ</th>
                            <th>المبلغ</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->customer->name ?? 'غير محدد' }}</td>
                                <td>{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : '' }}</td>
                                <td>{{ number_format($invoice->total_amount, 2) }} د.ع</td>
                                <td>
                                    <span class="status-badge status-{{ $invoice->status }}">
                                        @switch($invoice->status)
                                            @case('draft')
                                                مسودة
                                                @break
                                            @case('sent')
                                                مرسلة
                                                @break
                                            @case('paid')
                                                مدفوعة
                                                @break
                                            @case('overdue')
                                                متأخرة
                                                @break
                                            @default
                                                {{ $invoice->status }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px; justify-content: center;">
                                        <a href="{{ route('tenant.sales.invoices.show', ['invoice' => $invoice->id]) }}"
                                           class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;"
                                           target="_blank" rel="noopener"
                                           onclick="event.stopPropagation();">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tenant.sales.invoices.edit', $invoice) }}"
                                           class="btn btn-success" style="padding: 6px 12px; font-size: 12px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $invoices->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-file-invoice"></i>
                    <h3>لا توجد فواتير</h3>
                    <p>لم يتم إنشاء أي فواتير بعد</p>
                    <a href="{{ route('tenant.sales.invoices.create-simple') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        إنشاء أول فاتورة
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
