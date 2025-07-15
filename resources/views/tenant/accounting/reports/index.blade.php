@extends('layouts.modern')

@section('page-title', 'التقارير المالية')
@section('page-description', 'التقارير المالية والمحاسبية وفق المعايير الدولية')

@section('content')
<!-- Page Header -->
<div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-chart-line" style="font-size: 32px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            التقارير المالية 📊
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            تقارير مالية شاملة وفق المعايير المحاسبية الدولية
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-list" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $totalAccounts }} حساب</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-file-invoice" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $totalEntries }} قيد</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">{{ $pendingEntries }} معلق</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reports Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; margin-bottom: 30px;">
    <!-- Trial Balance -->
    <div class="content-card" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 2px solid #0ea5e9; transition: transform 0.3s ease, box-shadow 0.3s ease;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px rgba(14, 165, 233, 0.2)'" 
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
        <div style="text-align: center; padding: 20px;">
            <div style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-balance-scale"></i>
            </div>
            <h3 style="color: #0c4a6e; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">ميزان المراجعة</h3>
            <p style="color: #075985; margin: 0 0 20px 0; line-height: 1.6;">
                عرض أرصدة جميع الحسابات في فترة محددة للتأكد من توازن الدفاتر المحاسبية
            </p>
            <a href="{{ route('tenant.inventory.accounting.reports.trial-balance') }}"
               style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                عرض التقرير
            </a>
        </div>
    </div>

    <!-- Income Statement -->
    <div class="content-card" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #22c55e; transition: transform 0.3s ease, box-shadow 0.3s ease;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px rgba(34, 197, 94, 0.2)'" 
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
        <div style="text-align: center; padding: 20px;">
            <div style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 style="color: #14532d; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">قائمة الدخل</h3>
            <p style="color: #166534; margin: 0 0 20px 0; line-height: 1.6;">
                عرض الإيرادات والمصروفات وصافي الربح أو الخسارة خلال فترة محددة
            </p>
            <a href="{{ route('tenant.inventory.accounting.reports.income-statement') }}"
               style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                عرض التقرير
            </a>
        </div>
    </div>

    <!-- Balance Sheet -->
    <div class="content-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b; transition: transform 0.3s ease, box-shadow 0.3s ease;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px rgba(245, 158, 11, 0.2)'" 
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
        <div style="text-align: center; padding: 20px;">
            <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-building"></i>
            </div>
            <h3 style="color: #92400e; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">الميزانية العمومية</h3>
            <p style="color: #a16207; margin: 0 0 20px 0; line-height: 1.6;">
                عرض الأصول والخصوم وحقوق الملكية في تاريخ محدد
            </p>
            <a href="{{ route('tenant.inventory.accounting.reports.balance-sheet') }}"
               style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                عرض التقرير
            </a>
        </div>
    </div>

    <!-- Cash Flow -->
    <div class="content-card" style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); border: 2px solid #8b5cf6; transition: transform 0.3s ease, box-shadow 0.3s ease;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px rgba(139, 92, 246, 0.2)'" 
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
        <div style="text-align: center; padding: 20px;">
            <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-coins"></i>
            </div>
            <h3 style="color: #581c87; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">التدفقات النقدية</h3>
            <p style="color: #6b21a8; margin: 0 0 20px 0; line-height: 1.6;">
                تتبع حركة النقدية الداخلة والخارجة خلال فترة محددة
            </p>
            <a href="{{ route('tenant.inventory.accounting.reports.cash-flow') }}"
               style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                عرض التقرير
            </a>
        </div>
    </div>

    <!-- Account Ledger -->
    <div class="content-card" style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); border: 2px solid #ec4899; transition: transform 0.3s ease, box-shadow 0.3s ease;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px rgba(236, 72, 153, 0.2)'" 
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
        <div style="text-align: center; padding: 20px;">
            <div style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-book"></i>
            </div>
            <h3 style="color: #831843; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">دفتر الأستاذ</h3>
            <p style="color: #9d174d; margin: 0 0 20px 0; line-height: 1.6;">
                عرض تفصيلي لحركة حساب معين خلال فترة محددة
            </p>
            <a href="{{ route('tenant.inventory.accounting.reports.account-ledger') }}"
               style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                عرض التقرير
            </a>
        </div>
    </div>

    <!-- Custom Reports -->
    <div class="content-card" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border: 2px solid #6b7280; transition: transform 0.3s ease, box-shadow 0.3s ease;" 
         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 20px 40px rgba(107, 114, 128, 0.2)'" 
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)'">
        <div style="text-align: center; padding: 20px;">
            <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 32px;">
                <i class="fas fa-cogs"></i>
            </div>
            <h3 style="color: #1f2937; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">تقارير مخصصة</h3>
            <p style="color: #374151; margin: 0 0 20px 0; line-height: 1.6;">
                إنشاء تقارير مخصصة حسب مراكز التكلفة والفروع
            </p>
            <button onclick="alert('قريباً...')" 
                    style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-wrench"></i>
                قريباً
            </button>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="content-card">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-chart-bar" style="color: #8b5cf6;"></i>
        إحصائيات سريعة
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #1e40af; font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ $totalAccounts }}</div>
            <div style="color: #1e3a8a; font-weight: 600;">إجمالي الحسابات</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #166534; font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ $activeAccounts }}</div>
            <div style="color: #14532d; font-weight: 600;">الحسابات النشطة</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #d97706; font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ $totalEntries }}</div>
            <div style="color: #92400e; font-weight: 600;">إجمالي القيود</div>
        </div>
        
        <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); padding: 20px; border-radius: 12px; text-align: center;">
            <div style="color: #dc2626; font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ $pendingEntries }}</div>
            <div style="color: #991b1b; font-weight: 600;">القيود المعلقة</div>
        </div>
    </div>
</div>

<!-- Monthly Entries Chart -->
@if($monthlyEntries->count() > 0)
<div class="content-card" style="margin-top: 30px;">
    <h3 style="color: #2d3748; margin-bottom: 20px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
        <i class="fas fa-chart-area" style="color: #8b5cf6;"></i>
        القيود المحاسبية الشهرية
    </h3>
    
    <div style="height: 300px; display: flex; align-items: end; gap: 10px; padding: 20px; background: #f8fafc; border-radius: 8px;">
        @foreach($monthlyEntries->take(12) as $entry)
            @php
                $height = ($entry->count / $monthlyEntries->max('count')) * 250;
                $monthName = \Carbon\Carbon::create($entry->year, $entry->month)->format('M Y');
            @endphp
            <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
                <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); width: 100%; height: {{ $height }}px; border-radius: 4px 4px 0 0; margin-bottom: 8px; position: relative;">
                    <div style="position: absolute; top: -25px; left: 50%; transform: translateX(-50%); background: #2d3748; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: 600;">
                        {{ $entry->count }}
                    </div>
                </div>
                <div style="font-size: 12px; color: #6b7280; font-weight: 600; text-align: center;">{{ $monthName }}</div>
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection
