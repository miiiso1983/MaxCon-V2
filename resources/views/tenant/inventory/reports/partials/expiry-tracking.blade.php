<!-- Expiry Summary -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-times-circle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">منتهي الصلاحية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ $data['summary']['expired_count'] }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عنصر</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">ينتهي قريباً</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ $data['summary']['expiring_soon_count'] }}</div>
            <div style="font-size: 12px; opacity: 0.8;">عنصر</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-dollar-sign" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">قيمة منتهية</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['expired_value'], 0) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">دينار عراقي</div>
        </div>
    </div>
    
    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 15px; padding: 20px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -10px; right: -10px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <i class="fas fa-hourglass-half" style="font-size: 24px; opacity: 0.8;"></i>
                <span style="font-size: 12px; opacity: 0.8;">قيمة معرضة للخطر</span>
            </div>
            <div style="font-size: 28px; font-weight: 700;">{{ number_format($data['summary']['at_risk_value'], 0) }}</div>
            <div style="font-size: 12px; opacity: 0.8;">دينار عراقي</div>
        </div>
    </div>
</div>

<!-- Expiry Categories -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
    <!-- Expired Items -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-times-circle" style="color: #ef4444; margin-left: 10px;"></i>
            المنتجات منتهية الصلاحية
        </h3>
        
        @if($data['data']['expired']->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 10px; max-height: 300px; overflow-y: auto;">
                @foreach($data['data']['expired']->take(10) as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fee2e2; border-radius: 8px; border-right: 4px solid #ef4444;">
                        <div>
                            <div style="font-weight: 600; color: #991b1b;">{{ $item->product->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $item->warehouse->name }}</div>
                            <div style="font-size: 12px; color: #ef4444;">انتهت: {{ $item->expiry_date->format('Y-m-d') }}</div>
                        </div>
                        <div style="text-align: left;">
                            <div style="font-weight: 600; color: #ef4444;">{{ number_format($item->quantity) }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ number_format($item->quantity * $item->cost_price, 0) }} د.ع</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 20px; color: #6b7280;">
                <i class="fas fa-check-circle" style="font-size: 24px; margin-bottom: 10px; opacity: 0.5; color: #10b981;"></i>
                <p style="margin: 0;">لا توجد منتجات منتهية الصلاحية</p>
            </div>
        @endif
    </div>
    
    <!-- Expiring Soon -->
    <div class="content-card">
        <h3 style="font-size: 18px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; margin-left: 10px;"></i>
            المنتجات قريبة الانتهاء (30 يوم)
        </h3>
        
        @if($data['data']['expiring_soon']->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 10px; max-height: 300px; overflow-y: auto;">
                @foreach($data['data']['expiring_soon']->take(10) as $item)
                    @php
                        $daysLeft = now()->diffInDays($item->expiry_date, false);
                        $urgencyColor = $daysLeft <= 7 ? '#ef4444' : ($daysLeft <= 15 ? '#f59e0b' : '#3b82f6');
                    @endphp
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fef3c7; border-radius: 8px; border-right: 4px solid {{ $urgencyColor }};">
                        <div>
                            <div style="font-weight: 600; color: #92400e;">{{ $item->product->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $item->warehouse->name }}</div>
                            <div style="font-size: 12px; color: {{ $urgencyColor }};">
                                ينتهي: {{ $item->expiry_date->format('Y-m-d') }} ({{ abs($daysLeft) }} يوم)
                            </div>
                        </div>
                        <div style="text-align: left;">
                            <div style="font-weight: 600; color: #92400e;">{{ number_format($item->quantity) }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ number_format($item->quantity * $item->cost_price, 0) }} د.ع</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 20px; color: #6b7280;">
                <i class="fas fa-check-circle" style="font-size: 24px; margin-bottom: 10px; opacity: 0.5; color: #10b981;"></i>
                <p style="margin: 0;">لا توجد منتجات تنتهي قريباً</p>
            </div>
        @endif
    </div>
</div>

<!-- Expiry Timeline Chart -->
<div class="content-card" style="margin-bottom: 30px;">
    <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-chart-bar" style="color: #8b5cf6; margin-left: 10px;"></i>
        الجدول الزمني لانتهاء الصلاحية
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
        <div style="text-align: center; padding: 15px; background: #fee2e2; border-radius: 10px;">
            <div style="font-size: 24px; font-weight: 700; color: #ef4444;">{{ $data['data']['expired']->count() }}</div>
            <div style="font-size: 14px; color: #991b1b;">منتهي الصلاحية</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #fef3c7; border-radius: 10px;">
            <div style="font-size: 24px; font-weight: 700; color: #f59e0b;">{{ $data['data']['expiring_soon']->count() }}</div>
            <div style="font-size: 14px; color: #92400e;">خلال 30 يوم</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #dbeafe; border-radius: 10px;">
            <div style="font-size: 24px; font-weight: 700; color: #3b82f6;">{{ $data['data']['expiring_this_quarter']->count() }}</div>
            <div style="font-size: 14px; color: #1e40af;">خلال 90 يوم</div>
        </div>
        <div style="text-align: center; padding: 15px; background: #d1fae5; border-radius: 10px;">
            <div style="font-size: 24px; font-weight: 700; color: #10b981;">{{ $data['data']['long_term']->count() }}</div>
            <div style="font-size: 14px; color: #065f46;">طويل المدى</div>
        </div>
    </div>
    
    <div style="height: 200px; position: relative;">
        <canvas id="expiryTimelineChart"></canvas>
    </div>
</div>

<!-- Detailed Expiry Table -->
<div class="content-card" id="expiryTable">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
            <i class="fas fa-table" style="color: #8b5cf6; margin-left: 10px;"></i>
            تفاصيل انتهاء الصلاحية
        </h3>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="exportExpiryToCSV()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>
            <button onclick="printExpiry()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 15px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
    
    @php
        $allItems = collect()
            ->merge($data['data']['expired'])
            ->merge($data['data']['expiring_soon'])
            ->merge($data['data']['expiring_this_quarter'])
            ->sortBy('expiry_date');
    @endphp
    
    @if($allItems->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;" id="detailTable">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #4a5568;">المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">المستودع</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الكمية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">تاريخ الانتهاء</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الأيام المتبقية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #4a5568;">القيمة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allItems as $item)
                        @php
                            $daysLeft = now()->diffInDays($item->expiry_date, false);
                            $isExpired = $item->expiry_date < now();
                            
                            if ($isExpired) {
                                $statusColor = '#ef4444';
                                $statusBg = '#fee2e2';
                                $statusLabel = 'منتهي الصلاحية';
                            } elseif ($daysLeft <= 7) {
                                $statusColor = '#ef4444';
                                $statusBg = '#fee2e2';
                                $statusLabel = 'عاجل';
                            } elseif ($daysLeft <= 30) {
                                $statusColor = '#f59e0b';
                                $statusBg = '#fef3c7';
                                $statusLabel = 'ينتهي قريباً';
                            } else {
                                $statusColor = '#3b82f6';
                                $statusBg = '#dbeafe';
                                $statusLabel = 'مراقبة';
                            }
                        @endphp
                        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#f8fafc'" 
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->product->name }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->product->code }}</div>
                                @if($item->batch_number)
                                    <div style="font-size: 12px; color: #6b7280;">دفعة: {{ $item->batch_number }}</div>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->warehouse->name }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748; font-size: 16px;">{{ number_format($item->quantity, 0) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->product->unit ?? 'وحدة' }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #2d3748;">{{ $item->expiry_date->format('Y-m-d') }}</div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $item->expiry_date->format('M Y') }}</div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: {{ $statusColor }}; font-size: 16px;">
                                    {{ $isExpired ? 'منتهي' : abs($daysLeft) . ' يوم' }}
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="font-weight: 600; color: #1e40af; font-size: 16px;">{{ number_format($item->quantity * $item->cost_price, 2) }}</div>
                                <div style="font-size: 12px; color: #6b7280;">د.ع</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #6b7280;">
            <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5; color: #10b981;"></i>
            <h3 style="margin: 0 0 10px 0; color: #10b981;">ممتاز!</h3>
            <p style="margin: 0;">جميع المنتجات لها تواريخ صلاحية جيدة</p>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Expiry Timeline Chart
const ctx = document.getElementById('expiryTimelineChart').getContext('2d');

const chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['منتهي الصلاحية', 'خلال 30 يوم', 'خلال 90 يوم', 'طويل المدى'],
        datasets: [{
            data: [
                {{ $data['data']['expired']->count() }},
                {{ $data['data']['expiring_soon']->count() }},
                {{ $data['data']['expiring_this_quarter']->count() }},
                {{ $data['data']['long_term']->count() }}
            ],
            backgroundColor: [
                '#ef4444',
                '#f59e0b',
                '#3b82f6',
                '#10b981'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

function exportExpiryToCSV() {
    let csv = 'المنتج,الكود,المستودع,الكمية,تاريخ الانتهاء,الأيام المتبقية,الحالة,القيمة\n';
    
    @foreach($allItems as $item)
        @php
            $daysLeft = now()->diffInDays($item->expiry_date, false);
            $isExpired = $item->expiry_date < now();
            $statusLabel = $isExpired ? 'منتهي الصلاحية' : ($daysLeft <= 7 ? 'عاجل' : ($daysLeft <= 30 ? 'ينتهي قريباً' : 'مراقبة'));
        @endphp
        csv += '"{{ $item->product->name }}","{{ $item->product->code }}","{{ $item->warehouse->name }}",{{ $item->quantity }},"{{ $item->expiry_date->format('Y-m-d') }}","{{ $isExpired ? 'منتهي' : abs($daysLeft) . ' يوم' }}","{{ $statusLabel }}",{{ $item->quantity * $item->cost_price }}\n';
    @endforeach
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'expiry_tracking_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printExpiry() {
    const printContent = document.getElementById('detailTable').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>تقرير تتبع انتهاء الصلاحية</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; font-weight: bold; } @media print { body { margin: 0; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center; margin-bottom: 20px;">تقرير تتبع انتهاء الصلاحية</h1>');
    printWindow.document.write('<p style="text-align: center; margin-bottom: 20px;">تاريخ التقرير: ' + new Date().toLocaleDateString('ar-SA') + '</p>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>
