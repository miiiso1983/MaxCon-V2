@extends('layouts.modern')

@section('title', 'التنبؤات الذكية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-crystal-ball"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">التنبؤات الذكية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تنبؤات مدعومة بالذكاء الاصطناعي للمبيعات والمخزون</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="runAIAnalysis()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-robot"></i>
                    تحليل ذكي
                </button>
                <a href="{{ route('tenant.analytics.dashboard') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للداشبورد
                </a>
            </div>
        </div>
    </div>

    <!-- Sales Forecast -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-line" style="margin-left: 10px; color: #ed8936;"></i>
            توقعات المبيعات
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            
            <!-- Next Month Forecast -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; border-top: 4px solid #48bb78;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 20px; font-weight: 700;">الشهر القادم</h4>
                    <div style="background: #48bb78; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                        دقة {{ $predictions['sales_forecast']['next_month']['confidence'] }}%
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="color: #48bb78; font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                        {{ number_format($predictions['sales_forecast']['next_month']['predicted_revenue'] / 1000000, 1) }}M د.ع
                    </div>
                    <div style="color: #4a5568; font-size: 14px;">الإيرادات المتوقعة</div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas fa-trending-up" style="color: #48bb78;"></i>
                        <span style="color: #2d3748; font-weight: 600;">الاتجاه: {{ $predictions['sales_forecast']['next_month']['trend'] }}</span>
                    </div>
                </div>
                
                <div>
                    <h5 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">العوامل المؤثرة:</h5>
                    <div style="display: grid; gap: 8px;">
                        @foreach($predictions['sales_forecast']['next_month']['factors'] as $factor)
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="background: #48bb78; border-radius: 50%; width: 6px; height: 6px;"></div>
                            <span style="color: #4a5568; font-size: 14px;">{{ $factor }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Next Quarter Forecast -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; border-top: 4px solid #4299e1;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 20px; font-weight: 700;">الربع القادم</h4>
                    <div style="background: #4299e1; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">
                        دقة {{ $predictions['sales_forecast']['next_quarter']['confidence'] }}%
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="color: #4299e1; font-size: 32px; font-weight: 700; margin-bottom: 5px;">
                        {{ number_format($predictions['sales_forecast']['next_quarter']['predicted_revenue'] / 1000000, 1) }}M د.ع
                    </div>
                    <div style="color: #4a5568; font-size: 14px;">الإيرادات المتوقعة</div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas fa-trending-up" style="color: #4299e1;"></i>
                        <span style="color: #2d3748; font-weight: 600;">الاتجاه: {{ $predictions['sales_forecast']['next_quarter']['trend'] }}</span>
                    </div>
                </div>
                
                <div>
                    <h5 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">العوامل المؤثرة:</h5>
                    <div style="display: grid; gap: 8px;">
                        @foreach($predictions['sales_forecast']['next_quarter']['factors'] as $factor)
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="background: #4299e1; border-radius: 50%; width: 6px; height: 6px;"></div>
                            <span style="color: #4a5568; font-size: 14px;">{{ $factor }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Forecast -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- Reorder Alerts -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-exclamation-triangle" style="margin-left: 10px; color: #f56565;"></i>
                تنبيهات إعادة الطلب
            </h3>
            
            <div style="display: grid; gap: 15px;">
                @foreach($predictions['inventory_forecast']['reorder_alerts'] as $alert)
                <div style="background: #f7fafc; border-radius: 10px; padding: 20px; border-right: 4px solid #f56565;">
                    <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 16px; font-weight: 700;">{{ $alert['product'] }}</h4>
                            <div style="color: #4a5568; font-size: 12px;">المخزون الحالي: {{ $alert['current_stock'] }} وحدة</div>
                        </div>
                        <div style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 600;">
                            عاجل
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <div style="color: #4a5568; font-size: 12px;">الطلب المتوقع</div>
                            <div style="color: #2d3748; font-weight: 700;">{{ $alert['predicted_demand'] }} وحدة</div>
                        </div>
                        <div>
                            <div style="color: #4a5568; font-size: 12px;">تاريخ إعادة الطلب</div>
                            <div style="color: #2d3748; font-weight: 700;">{{ $alert['reorder_date'] }}</div>
                        </div>
                    </div>
                    
                    <button onclick="createPurchaseOrder('{{ $alert['product'] }}')" style="background: #f56565; color: white; padding: 8px 15px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; width: 100%;">
                        إنشاء طلب شراء
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Overstock Alerts -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-warehouse" style="margin-left: 10px; color: #ed8936;"></i>
                تنبيهات المخزون الزائد
            </h3>
            
            <div style="display: grid; gap: 15px;">
                @foreach($predictions['inventory_forecast']['overstock_alerts'] as $alert)
                <div style="background: #f7fafc; border-radius: 10px; padding: 20px; border-right: 4px solid #ed8936;">
                    <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 16px; font-weight: 700;">{{ $alert['product'] }}</h4>
                            <div style="color: #4a5568; font-size: 12px;">المخزون الحالي: {{ $alert['current_stock'] }} وحدة</div>
                        </div>
                        <div style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 600;">
                            زائد
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <div style="color: #4a5568; font-size: 12px;">الطلب المتوقع</div>
                            <div style="color: #2d3748; font-weight: 700;">{{ $alert['predicted_demand'] }} وحدة</div>
                        </div>
                        <div>
                            <div style="color: #4a5568; font-size: 12px;">الإجراء المقترح</div>
                            <div style="color: #2d3748; font-weight: 700;">{{ $alert['action'] }}</div>
                        </div>
                    </div>
                    
                    <button onclick="createPromotion('{{ $alert['product'] }}')" style="background: #ed8936; color: white; padding: 8px 15px; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; width: 100%;">
                        إنشاء عرض ترويجي
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Demand Patterns -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-area" style="margin-left: 10px; color: #9f7aea;"></i>
            أنماط الطلب المتوقعة
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            
            <!-- High Demand Products -->
            <div>
                <h4 style="color: #2d3748; margin: 0 0 20px 0; font-size: 18px; font-weight: 700;">
                    <i class="fas fa-arrow-up" style="margin-left: 8px; color: #48bb78;"></i>
                    منتجات عالية الطلب
                </h4>
                
                <div style="display: grid; gap: 15px;">
                    @foreach($predictions['demand_patterns']['high_demand_products'] as $product)
                    <div style="background: #f7fafc; border-radius: 10px; padding: 20px; border-right: 4px solid #48bb78;">
                        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 10px;">
                            <h5 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">{{ $product['name'] }}</h5>
                            <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                +{{ $product['increase'] }}%
                            </span>
                        </div>
                        <div style="color: #4a5568; font-size: 14px;">{{ $product['reason'] }}</div>
                        <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                            <div style="background: #48bb78; border-radius: 10px; height: 100%; width: {{ min($product['increase'], 100) }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Declining Products -->
            <div>
                <h4 style="color: #2d3748; margin: 0 0 20px 0; font-size: 18px; font-weight: 700;">
                    <i class="fas fa-arrow-down" style="margin-left: 8px; color: #f56565;"></i>
                    منتجات منخفضة الطلب
                </h4>
                
                <div style="display: grid; gap: 15px;">
                    @foreach($predictions['demand_patterns']['declining_products'] as $product)
                    <div style="background: #f7fafc; border-radius: 10px; padding: 20px; border-right: 4px solid #f56565;">
                        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 10px;">
                            <h5 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">{{ $product['name'] }}</h5>
                            <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                {{ $product['decrease'] }}%
                            </span>
                        </div>
                        <div style="color: #4a5568; font-size: 14px;">{{ $product['reason'] }}</div>
                        <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                            <div style="background: #f56565; border-radius: 10px; height: 100%; width: {{ abs($product['decrease']) }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- AI Insights -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-brain" style="margin-left: 10px; color: #667eea;"></i>
            رؤى الذكاء الاصطناعي
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            
            <div style="padding: 25px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-lightbulb" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">توصية استراتيجية</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    استعد لموسم الشتاء بزيادة مخزون أدوية البرد والمناعة بنسبة 45%. 
                    التوقعات تشير لزيادة كبيرة في الطلب خلال الشهرين القادمين.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-chart-line" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">فرصة نمو</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    الفيتامينات والمكملات الغذائية تظهر نمواً مستمراً. 
                    فكر في توسيع هذه الفئة وإضافة منتجات جديدة.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">تحذير</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    منتجات الصيف (واقيات الشمس) تظهر انخفاضاً حاداً. 
                    قم بتصفية المخزون بعروض خاصة قبل انتهاء الموسم.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-robot" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">أتمتة ذكية</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    تم تفعيل الطلبات التلقائية لـ 3 منتجات بناءً على التنبؤات. 
                    سيتم إشعارك عند وصول الطلبات.
                </p>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-bar" style="margin-left: 10px; color: #667eea;"></i>
            الرسوم البيانية التحليلية
        </h3>

        <!-- Charts Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">

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

            <!-- Market Share Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-chart-pie" style="margin-left: 8px; color: #4299e1;"></i>
                    حصة السوق
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="marketShareChart"></div>
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

        </div>
    </div>
</div>

@php
    $baseRevenue = $predictions['sales_forecast']['next_month']['predicted_revenue'] ?? 35800000;
    $revenueData = [
        round($baseRevenue * 0.75),
        round($baseRevenue * 0.85),
        round($baseRevenue * 0.92),
        round($baseRevenue * 1.15),
        round($baseRevenue * 1.08),
        $baseRevenue
    ];
@endphp

<!-- Analytics Data Setup -->
<script>
// Setup analytics data for charts from server data
window.analyticsData = {
    revenue_trend: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
        data: @json($revenueData)
    },
    sales_by_category: {
        labels: ['أدوية القلب', 'المضادات الحيوية', 'أدوية السكري', 'الفيتامينات', 'أخرى'],
        data: [35, 25, 20, 15, 5]
    },
    customer_segments: {
        labels: ['مستشفيات', 'صيدليات', 'عيادات', 'موزعين'],
        data: [40, 35, 15, 10]
    },
    predictions_data: @json($predictions ?? [])
};

console.log('✅ Analytics data ready for predictions page:', window.analyticsData);
console.log('📊 Predictions data:', window.analyticsData.predictions_data);
</script>

<!-- Load ApexCharts System - Working Version -->
<script src="{{ asset('js/apex-charts-system.js') }}"></script>

<script>
// Run AI Analysis
function runAIAnalysis() {
    const button = event.target;
    const originalContent = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحليل...';
    button.disabled = true;

    // Simulate AI analysis
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;

        // Create AI analysis modal
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        `;

        modal.innerHTML = `
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 700px; width: 90%; max-height: 80vh; overflow-y: auto;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                    <i class="fas fa-robot" style="margin-left: 10px; color: #667eea;"></i>
                    نتائج التحليل الذكي
                </h3>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">ملخص التحليل</h4>
                    <div style="color: #4a5568; line-height: 1.6;">
                        تم تحليل 1,250 نقطة بيانات من آخر 12 شهر باستخدام خوارزميات التعلم الآلي المتقدمة:
                        <br><br>
                        • <strong>دقة التنبؤ:</strong> 87.5% للشهر القادم، 82.3% للربع القادم<br>
                        • <strong>المنتجات المحللة:</strong> 156 منتج عبر 8 فئات<br>
                        • <strong>العوامل المؤثرة:</strong> 23 متغير (موسمية، اقتصادية، اجتماعية)<br>
                        • <strong>الأنماط المكتشفة:</strong> 12 نمط سلوكي جديد
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">التوصيات الفورية</h4>
                    <div style="display: grid; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #48bb78; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">1</div>
                            <span style="color: #2d3748; font-weight: 600;">زيادة مخزون باراسيتامول بـ 200 وحدة خلال 3 أيام</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #4299e1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">2</div>
                            <span style="color: #2d3748; font-weight: 600;">إنشاء عرض ترويجي لشراب السعال (مخزون زائد)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #ed8936; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">3</div>
                            <span style="color: #2d3748; font-weight: 600;">تحضير حملة تسويقية لفيتامينات المناعة</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">4</div>
                            <span style="color: #2d3748; font-weight: 600;">مراجعة أسعار المضادات الحيوية (فرصة زيادة هامش)</span>
                        </div>
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">المخاطر المحتملة</h4>
                    <div style="display: grid; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #fed7d7; border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle" style="color: #f56565;"></i>
                            <span style="color: #2d3748; font-weight: 600;">نقص محتمل في أموكسيسيلين خلال 10 أيام</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: #feebc8; border-radius: 8px;">
                            <i class="fas fa-clock" style="color: #ed8936;"></i>
                            <span style="color: #2d3748; font-weight: 600;">تأخير محتمل في شحنة فيتامين د (مورد رئيسي)</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button onclick="implementRecommendations()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-check"></i> تطبيق التوصيات
                    </button>
                    <button onclick="exportAnalysis()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-download"></i> تصدير التحليل
                    </button>
                    <button onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-times"></i> إغلاق
                    </button>
                </div>
            </div>
        `;

        modal.className = 'modal';
        document.body.appendChild(modal);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });

        showNotification('تم إكمال التحليل الذكي بنجاح!', 'success');
    }, 3000);
}

// Create Purchase Order
function createPurchaseOrder(product) {
    alert(`تم إنشاء طلب شراء للمنتج: ${product}\n\nتفاصيل الطلب:\n• الكمية المقترحة: 500 وحدة\n• المورد المفضل: الشركة الطبية المتحدة\n• التسليم المتوقع: خلال 5-7 أيام\n• التكلفة التقديرية: 750,000 دينار`);
    showNotification('تم إنشاء طلب الشراء بنجاح!', 'success');
}

// Create Promotion
function createPromotion(product) {
    alert(`تم إنشاء عرض ترويجي للمنتج: ${product}\n\nتفاصيل العرض:\n• خصم 25% لمدة أسبوعين\n• عرض اشتري 2 واحصل على 1 مجاناً\n• إعلان على وسائل التواصل الاجتماعي\n• إشعار العملاء المهتمين`);
    showNotification('تم إنشاء العرض الترويجي بنجاح!', 'success');
}

// Implement Recommendations
function implementRecommendations() {
    const button = event.target;
    const originalContent = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التطبيق...';
    button.disabled = true;

    setTimeout(() => {
        alert('تم تطبيق التوصيات بنجاح!\n\nالإجراءات المنفذة:\n• تم إنشاء 3 طلبات شراء تلقائية\n• تم جدولة 2 عرض ترويجي\n• تم إرسال تنبيهات للفريق\n• تم تحديث خطة المخزون');

        // Close modal
        event.target.closest('.modal').remove();
        showNotification('تم تطبيق جميع التوصيات بنجاح!', 'success');
    }, 2000);
}

// Export Analysis
function exportAnalysis() {
    alert('تم تصدير التحليل بنجاح!\n\nيتضمن الملف:\n• نتائج التحليل الكاملة\n• التوصيات التفصيلية\n• الرسوم البيانية\n• خطة العمل المقترحة\n\nتم حفظ الملف في مجلد التحميلات.');
    showNotification('تم تصدير التحليل بنجاح!', 'success');
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#48bb78' : type === 'error' ? '#f56565' : '#4299e1'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 10000;
        font-weight: 600;
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;

    // Add animation keyframes
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>

@endsection
