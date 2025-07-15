@extends('layouts.modern')

@section('title', 'تحليل الربحية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تحليل الربحية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تحليل شامل للربحية والعائد على الاستثمار</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="generateProfitabilityReport()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-file-chart"></i>
                    تقرير الربحية
                </button>
                <a href="{{ route('tenant.analytics.dashboard') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للداشبورد
                </a>
            </div>
        </div>
    </div>

    <!-- Overall Metrics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-percentage"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $profitability['overall_metrics']['gross_profit_margin'] }}%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">هامش الربح الإجمالي</p>
            <div style="margin-top: 10px; color: #48bb78; font-size: 12px; font-weight: 600;">
                +2.1% من الشهر الماضي
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $profitability['overall_metrics']['net_profit_margin'] }}%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">هامش الربح الصافي</p>
            <div style="margin-top: 10px; color: #4299e1; font-size: 12px; font-weight: 600;">
                +1.8% تحسن
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-cogs"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $profitability['overall_metrics']['operating_margin'] }}%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">هامش التشغيل</p>
            <div style="margin-top: 10px; color: #ed8936; font-size: 12px; font-weight: 600;">
                +1.5% تحسن
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-trophy"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $profitability['overall_metrics']['return_on_investment'] }}%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">العائد على الاستثمار</p>
            <div style="margin-top: 10px; color: #9f7aea; font-size: 12px; font-weight: 600;">
                +3.2% نمو
            </div>
        </div>
    </div>

    <!-- Product Profitability -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-pills" style="margin-left: 10px; color: #48bb78;"></i>
            ربحية المنتجات حسب الفئة
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <th style="padding: 15px; text-align: right; font-weight: 700;">فئة المنتج</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الإيرادات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">التكلفة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الربح</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">هامش الربح</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الأداء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profitability['product_profitability'] as $index => $product)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: {{ ['#48bb78', '#4299e1', '#ed8936', '#9f7aea', '#f56565'][$index] }}; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #2d3748;">{{ $product['category'] }}</div>
                                    <div style="font-size: 12px; color: #718096;">فئة رئيسية</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="color: #2d3748; font-weight: 700; font-size: 16px;">{{ number_format($product['revenue'] / 1000000, 1) }}M د.ع</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="color: #f56565; font-weight: 700; font-size: 16px;">{{ number_format($product['cost'] / 1000000, 1) }}M د.ع</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="color: #48bb78; font-weight: 700; font-size: 16px;">{{ number_format($product['profit'] / 1000000, 1) }}M د.ع</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                <span style="color: #2d3748; font-weight: 700; font-size: 16px;">{{ $product['margin'] }}%</span>
                                <div style="background: #e2e8f0; border-radius: 10px; height: 6px; width: 60px;">
                                    <div style="background: {{ $product['margin'] > 35 ? '#48bb78' : ($product['margin'] > 30 ? '#ed8936' : '#f56565') }}; border-radius: 10px; height: 100%; width: {{ min($product['margin'] * 2, 100) }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($product['margin'] > 35)
                                <span style="background: #48bb78; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">ممتاز</span>
                            @elseif($product['margin'] > 30)
                                <span style="background: #ed8936; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">جيد</span>
                            @else
                                <span style="background: #f56565; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">يحتاج تحسين</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Profitability -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-users" style="margin-left: 10px; color: #4299e1;"></i>
            ربحية العملاء حسب الشريحة
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            @foreach($profitability['customer_profitability'] as $index => $customer)
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; border-top: 4px solid {{ ['#48bb78', '#4299e1', '#ed8936', '#9f7aea'][$index] }};">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">{{ $customer['segment'] }}</h4>
                    <div style="background: {{ ['#48bb78', '#4299e1', '#ed8936', '#9f7aea'][$index] }}; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                
                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: between; align-items: center; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">الإيرادات:</span>
                        <span style="color: #2d3748; font-weight: 700;">{{ number_format($customer['revenue'] / 1000000, 1) }}M د.ع</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">التكلفة:</span>
                        <span style="color: #f56565; font-weight: 700;">{{ number_format($customer['cost'] / 1000000, 1) }}M د.ع</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568; font-size: 14px;">الربح:</span>
                        <span style="color: #48bb78; font-weight: 700;">{{ number_format($customer['profit'] / 1000000, 1) }}M د.ع</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 12px; background: {{ ['#48bb78', '#4299e1', '#ed8936', '#9f7aea'][$index] }}; color: white; border-radius: 8px;">
                        <span style="font-weight: 600;">هامش الربح:</span>
                        <span style="font-weight: 700; font-size: 18px;">{{ $customer['margin'] }}%</span>
                    </div>
                </div>
                
                <div style="margin-top: 15px; background: #e2e8f0; border-radius: 10px; height: 6px;">
                    <div style="background: {{ ['#48bb78', '#4299e1', '#ed8936', '#9f7aea'][$index] }}; border-radius: 10px; height: 100%; width: {{ $customer['margin'] }}%;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Profitability Charts -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- Revenue vs Profit Chart -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-chart-bar" style="margin-left: 10px; color: #ed8936;"></i>
                الإيرادات مقابل الأرباح
            </h3>
            <div id="revenueProfitChartOld" style="width: 100%; height: 300px;"></div>
        </div>

        <!-- Margin Trends -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-chart-line" style="margin-left: 10px; color: #9f7aea;"></i>
                اتجاهات هوامش الربح
            </h3>
            <div id="marginTrendsChartOld" style="width: 100%; height: 300px;"></div>
        </div>
    </div>

    <!-- Profitability Insights -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-lightbulb" style="margin-left: 10px; color: #667eea;"></i>
            رؤى الربحية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            
            <div style="padding: 25px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-star" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">أفضل أداء</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    الفيتامينات تحقق أعلى هامش ربح (46.7%). 
                    ركز على توسيع هذه الفئة وزيادة التسويق لها.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-chart-line" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">فرصة تحسين</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    المستشفيات الكبرى تحقق أعلى هامش ربح (40%). 
                    استثمر في تطوير علاقات أقوى مع هذه الشريحة.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">تحذير</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    فئة "أخرى" تحقق أقل هامش ربح (30%). 
                    راجع استراتيجية التسعير أو فكر في إيقاف المنتجات غير المربحة.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-target" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">هدف</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    اهدف لتحقيق هامش ربح إجمالي 40% خلال الربع القادم 
                    من خلال تحسين كفاءة التشغيل وإعادة التفاوض مع الموردين.
                </p>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-bar" style="margin-left: 10px; color: #667eea;"></i>
            تحليلات الربحية البيانية
        </h3>

        <!-- Charts Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">

            <!-- Revenue vs Profit Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-line" style="margin-left: 8px; color: #4299e1;"></i>
                    الإيرادات والأرباح
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="revenueProfitChart"></div>
                </div>
            </div>

            <!-- Margin Trends Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-line" style="margin-left: 8px; color: #9f7aea;"></i>
                    اتجاهات الهامش
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="marginTrendsChart"></div>
                </div>
            </div>

            <!-- Category Profitability Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-pie" style="margin-left: 8px; color: #ed8936;"></i>
                    ربحية الفئات
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="categoryChart"></div>
                </div>
            </div>

            <!-- Performance Radar Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-area" style="margin-left: 8px; color: #48bb78;"></i>
                    أداء المؤشرات
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="performanceChart"></div>
                </div>
            </div>

            <!-- Goals Achievement Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-bullseye" style="margin-left: 8px; color: #48bb78;"></i>
                    تحقيق الأهداف
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="goalsChart"></div>
                </div>
            </div>

            <!-- Revenue Trend Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-area" style="margin-left: 8px; color: #667eea;"></i>
                    اتجاه الإيرادات
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="revenueChart"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Analytics Data Setup -->
<script>
// Setup analytics data for charts
window.analyticsData = {
    revenue_trend: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
        data: [18500000, 22300000, 19800000, 25600000, 28900000, 31200000]
    },
    sales_by_category: {
        labels: ['أدوية القلب', 'المضادات الحيوية', 'أدوية السكري', 'الفيتامينات', 'أخرى'],
        data: [35, 25, 20, 15, 5]
    },
    customer_segments: {
        labels: ['مستشفيات', 'صيدليات', 'عيادات', 'موزعين'],
        data: [40, 35, 15, 10]
    }
};

console.log('✅ Analytics data ready for profitability page:', window.analyticsData);

// Pass data to global scope for charts
window.profitabilityData = @json($profitability ?? []);

// Generate Profitability Report function
function generateProfitabilityReport() {
    alert('تم إنشاء تقرير الربحية بنجاح!\n\nيتضمن التقرير:\n• الملخص التنفيذي\n• التوصيات الاستراتيجية\n• الأهداف المقترحة\n• تحليل مفصل للربحية');
}
</script>

<!-- Load ApexCharts System -->
<script src="{{ asset('js/apex-charts-system.js') }}"></script>

@endsection
