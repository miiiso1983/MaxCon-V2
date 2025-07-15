<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            direction: rtl;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .header .company-name {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .header .report-info {
            font-size: 12px;
            color: #718096;
        }
        
        .metadata {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        
        .metadata-item {
            display: table-cell;
            padding: 5px 10px;
            text-align: center;
            border-left: 1px solid #e2e8f0;
        }
        
        .metadata-item:last-child {
            border-left: none;
        }
        
        .metadata-label {
            font-weight: 600;
            color: #4a5568;
            display: block;
        }
        
        .metadata-value {
            color: #2d3748;
            font-size: 14px;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        .report-table th {
            background: #667eea;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #5a67d8;
        }
        
        .report-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .report-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .report-table tbody tr:hover {
            background: #edf2f7;
        }
        
        .currency {
            text-align: left;
            font-weight: 600;
            color: #10b981;
        }
        
        .number {
            text-align: left;
            font-weight: 600;
        }
        
        .date {
            font-size: 10px;
            color: #718096;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #718096;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                font-size: 11px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .report-table {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">شركة ماكس كون للأنظمة المتقدمة</div>
        <h1>{{ $report->name }}</h1>
        <div class="report-info">
            تم الإنشاء في: {{ $generated_at }} | 
            النوع: {{ $report->type }} | 
            الفئة: {{ $report->category }}
        </div>
    </div>

    <!-- Metadata -->
    <div class="metadata">
        <div class="metadata-item">
            <span class="metadata-label">عدد السجلات</span>
            <span class="metadata-value">{{ $execution->row_count ?? count($data) }}</span>
        </div>
        <div class="metadata-item">
            <span class="metadata-label">وقت التنفيذ</span>
            <span class="metadata-value">{{ $execution->execution_time ?? 0 }} ثانية</span>
        </div>
        <div class="metadata-item">
            <span class="metadata-label">المستخدم</span>
            <span class="metadata-value">{{ $execution->user->name ?? 'غير محدد' }}</span>
        </div>
        <div class="metadata-item">
            <span class="metadata-label">تاريخ التقرير</span>
            <span class="metadata-value">{{ now()->format('Y-m-d') }}</span>
        </div>
    </div>

    <!-- Report Table -->
    @if($data->count() > 0)
        <table class="report-table">
            <thead>
                <tr>
                    @foreach($report->formatted_columns as $column)
                        <th>{{ $column['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($report->formatted_columns as $column)
                            <td class="{{ $column['type'] }}">
                                @php
                                    $value = $row[$column['field']] ?? '';
                                    switch($column['type']) {
                                        case 'currency':
                                            echo number_format($value, 2) . ' د.ع';
                                            break;
                                        case 'number':
                                            echo number_format($value);
                                            break;
                                        case 'datetime':
                                            echo \Carbon\Carbon::parse($value)->format('Y-m-d H:i');
                                            break;
                                        case 'date':
                                            echo \Carbon\Carbon::parse($value)->format('Y-m-d');
                                            break;
                                        case 'percentage':
                                            echo number_format($value, 2) . '%';
                                            break;
                                        default:
                                            echo $value;
                                    }
                                @endphp
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #718096;">
            <i style="font-size: 48px; margin-bottom: 20px; display: block;">📊</i>
            <h3>لا توجد بيانات لعرضها</h3>
            <p>لم يتم العثور على بيانات تطابق المعايير المحددة</p>
        </div>
    @endif

    <!-- Summary Section -->
    @if($data->count() > 0)
        <div style="margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 8px;">
            <h3 style="font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 15px;">ملخص التقرير</h3>
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; padding: 10px;">
                    <strong>إجمالي السجلات:</strong> {{ $data->count() }}
                </div>
                @if($report->category === 'sales')
                    @php
                        $totalAmount = $data->sum('total_amount') ?? $data->sum('total_sales') ?? 0;
                    @endphp
                    @if($totalAmount > 0)
                        <div style="display: table-cell; padding: 10px;">
                            <strong>إجمالي المبلغ:</strong> {{ number_format($totalAmount, 2) }} د.ع
                        </div>
                    @endif
                @endif
                @if($report->category === 'inventory')
                    @php
                        $totalQuantity = $data->sum('quantity') ?? 0;
                        $totalValue = $data->sum('total_value') ?? 0;
                    @endphp
                    @if($totalQuantity > 0)
                        <div style="display: table-cell; padding: 10px;">
                            <strong>إجمالي الكمية:</strong> {{ number_format($totalQuantity) }}
                        </div>
                    @endif
                    @if($totalValue > 0)
                        <div style="display: table-cell; padding: 10px;">
                            <strong>إجمالي القيمة:</strong> {{ number_format($totalValue, 2) }} د.ع
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>تم إنشاء هذا التقرير بواسطة نظام ماكس كون ERP</p>
        <p>{{ now()->format('Y-m-d H:i:s') }} | الصفحة 1</p>
    </div>
</body>
</html>
