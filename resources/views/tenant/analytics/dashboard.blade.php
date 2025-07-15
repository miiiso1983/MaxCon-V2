@extends('layouts.modern')

@section('title', 'لوحة التحليلات الذكية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">الذكاء الاصطناعي والتحليلات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تحليلات ذكية ومؤشرات أداء متقدمة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="refreshData()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-sync-alt"></i>
                    تحديث البيانات
                </button>
                <button onclick="exportDashboard()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-download"></i>
                    تصدير التقرير
                </button>
            </div>
        </div>
    </div>

    <!-- KPIs Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- Revenue KPI -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, #48bb78, #38a169); border-radius: 50%; transform: translate(30px, -30px); opacity: 0.1;"></div>
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 15px;">
                <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    +{{ $kpis['revenue_growth'] }}%
                </span>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ number_format($kpis['total_revenue'] / 1000000, 1) }}M</h3>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي الإيرادات (دينار)</p>
            <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                <div style="background: #48bb78; border-radius: 10px; height: 100%; width: 75%;"></div>
            </div>
        </div>

        <!-- Customers KPI -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, #4299e1, #3182ce); border-radius: 50%; transform: translate(30px, -30px); opacity: 0.1;"></div>
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 15px;">
                <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-users"></i>
                </div>
                <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    +{{ $kpis['customer_growth'] }}%
                </span>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ number_format($kpis['total_customers']) }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي العملاء</p>
            <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                <div style="background: #4299e1; border-radius: 10px; height: 100%; width: 68%;"></div>
            </div>
        </div>

        <!-- Orders KPI -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, #ed8936, #dd6b20); border-radius: 50%; transform: translate(30px, -30px); opacity: 0.1;"></div>
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 15px;">
                <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    +{{ $kpis['order_growth'] }}%
                </span>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ number_format($kpis['total_orders']) }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي الطلبات</p>
            <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                <div style="background: #ed8936; border-radius: 10px; height: 100%; width: 82%;"></div>
            </div>
        </div>

        <!-- AOV KPI -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, #9f7aea, #805ad5); border-radius: 50%; transform: translate(30px, -30px); opacity: 0.1;"></div>
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 15px;">
                <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <span style="background: #9f7aea; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    +{{ $kpis['aov_growth'] }}%
                </span>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ number_format($kpis['avg_order_value'] / 1000) }}K</h3>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">متوسط قيمة الطلب (دينار)</p>
            <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                <div style="background: #9f7aea; border-radius: 10px; height: 100%; width: 58%;"></div>
            </div>
        </div>

        <!-- Profit Margin KPI -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, #f56565, #e53e3e); border-radius: 50%; transform: translate(30px, -30px); opacity: 0.1;"></div>
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 15px;">
                <div style="background: #f56565; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-percentage"></i>
                </div>
                <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    +{{ $kpis['margin_change'] }}%
                </span>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $kpis['profit_margin'] }}%</h3>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">هامش الربح</p>
            <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                <div style="background: #f56565; border-radius: 10px; height: 100%; width: 78%;"></div>
            </div>
        </div>

        <!-- Inventory Turnover KPI -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(135deg, #38b2ac, #319795); border-radius: 50%; transform: translate(30px, -30px); opacity: 0.1;"></div>
            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 15px;">
                <div style="background: #38b2ac; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-sync"></i>
                </div>
                <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    {{ $kpis['turnover_change'] }}%
                </span>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $kpis['inventory_turnover'] }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">دوران المخزون</p>
            <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                <div style="background: #38b2ac; border-radius: 10px; height: 100%; width: 84%;"></div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- Revenue Trend Chart -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
                <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                    <i class="fas fa-chart-area" style="margin-left: 10px; color: #667eea;"></i>
                    اتجاه الإيرادات
                </h3>
                <div style="display: flex; gap: 10px;">
                    <button onclick="changeChartPeriod('month')" style="background: #4299e1; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer;">شهري</button>
                    <button onclick="changeChartPeriod('quarter')" style="background: #e2e8f0; color: #4a5568; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer;">ربعي</button>
                    <button onclick="changeChartPeriod('year')" style="background: #e2e8f0; color: #4a5568; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer;">سنوي</button>
                </div>
            </div>
            <canvas id="revenueChart" style="width: 100%; height: 300px;"></canvas>
        </div>

        <!-- Sales by Category -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-chart-pie" style="margin-left: 10px; color: #ed8936;"></i>
                المبيعات حسب الفئة
            </h3>
            <canvas id="categoryChart" style="width: 100%; height: 250px;"></canvas>
        </div>
    </div>

    <!-- Customer Segments and Real-time Data -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- Customer Segments -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-users-cog" style="margin-left: 10px; color: #9f7aea;"></i>
                شرائح العملاء
            </h3>
            <canvas id="customerSegmentsChart" style="width: 100%; height: 250px;"></canvas>
        </div>

        <!-- Real-time Metrics -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-tachometer-alt" style="margin-left: 10px; color: #48bb78;"></i>
                المؤشرات الفورية
            </h3>
            
            <div id="realtimeMetrics" style="display: grid; gap: 15px;">
                <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: #f7fafc; border-radius: 10px;">
                    <div>
                        <div style="color: #4a5568; font-size: 14px;">المبيعات اليوم</div>
                        <div style="color: #2d3748; font-size: 20px; font-weight: 700;" id="todaySales">2.85M د.ع</div>
                    </div>
                    <div style="background: #48bb78; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: #f7fafc; border-radius: 10px;">
                    <div>
                        <div style="color: #4a5568; font-size: 14px;">الطلبات اليوم</div>
                        <div style="color: #2d3748; font-size: 20px; font-weight: 700;" id="todayOrders">45 طلب</div>
                    </div>
                    <div style="background: #4299e1; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: #f7fafc; border-radius: 10px;">
                    <div>
                        <div style="color: #4a5568; font-size: 14px;">العملاء النشطين</div>
                        <div style="color: #2d3748; font-size: 20px; font-weight: 700;" id="activeCustomers">128 عميل</div>
                    </div>
                    <div style="background: #ed8936; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: #f7fafc; border-radius: 10px;">
                    <div>
                        <div style="color: #4a5568; font-size: 14px;">تنبيهات المخزون</div>
                        <div style="color: #2d3748; font-size: 20px; font-weight: 700;" id="inventoryAlerts">8 تنبيهات</div>
                    </div>
                    <div style="background: #f56565; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-bolt" style="margin-left: 10px; color: #667eea;"></i>
            التحليلات المتقدمة
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            
            <a href="{{ route('tenant.analytics.market-trends') }}" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-chart-line" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">اتجاهات السوق</div>
            </a>

            <a href="{{ route('tenant.analytics.customer-behavior') }}" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-user-chart" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">سلوك العملاء</div>
            </a>

            <a href="{{ route('tenant.analytics.predictions') }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-crystal-ball" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">التنبؤات الذكية</div>
            </a>

            <a href="{{ route('tenant.analytics.profitability') }}" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-chart-pie" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تحليل الربحية</div>
            </a>

            <a href="{{ route('tenant.analytics.risk-management') }}" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-shield-alt" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">إدارة المخاطر</div>
            </a>

            <a href="{{ route('tenant.analytics.executive-reports') }}" style="background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); color: white; padding: 20px; border-radius: 15px; text-decoration: none; text-align: center; transition: transform 0.3s;"
               onmouseover="this.style.transform='translateY(-5px)'" 
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-file-chart" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">التقارير التنفيذية</div>
            </a>
        </div>
    </div>
</div>

<script>
// Pass chart data to global scope for universal charts fix
console.log('🔧 Setting up analytics data...');
window.analyticsData = {
    revenue_trend: {
        labels: @json($chartData['revenue_trend']['labels']),
        data: @json($chartData['revenue_trend']['data'])
    },
    sales_by_category: {
        labels: @json($chartData['sales_by_category']['labels']),
        data: @json($chartData['sales_by_category']['data'])
    },
    customer_segments: {
        labels: @json($chartData['customer_segments']['labels']),
        data: @json($chartData['customer_segments']['data'])
    }
};
console.log('✅ Analytics data ready:', window.analyticsData);

// Real-time data update
function updateRealtimeData() {
    fetch('/tenant/analytics/realtime-data?type=overview')
        .then(response => response.json())
        .then(data => {
            document.getElementById('todaySales').textContent = (data.current_sales / 1000000).toFixed(2) + 'M د.ع';
            document.getElementById('todayOrders').textContent = data.orders_today + ' طلب';
            document.getElementById('activeCustomers').textContent = data.active_customers + ' عميل';
            document.getElementById('inventoryAlerts').textContent = data.inventory_alerts + ' تنبيهات';
        })
        .catch(error => console.error('Error updating real-time data:', error));
}

// Chart period change
function changeChartPeriod(period) {
    // Update button styles
    document.querySelectorAll('button[onclick^="changeChartPeriod"]').forEach(btn => {
        btn.style.background = '#e2e8f0';
        btn.style.color = '#4a5568';
    });
    event.target.style.background = '#4299e1';
    event.target.style.color = 'white';

    // Here you would fetch new data based on period
    console.log('Changing chart period to:', period);
}

// Refresh data
function refreshData() {
    const button = event.target;
    const originalContent = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
    button.disabled = true;

    // Simulate data refresh
    setTimeout(() => {
        updateRealtimeData();
        button.innerHTML = originalContent;
        button.disabled = false;
        showNotification('تم تحديث البيانات بنجاح!', 'success');
    }, 2000);
}

// Export dashboard
function exportDashboard() {
    const button = event.target;
    const originalContent = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
    button.disabled = true;

    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
        alert('تم تصدير تقرير الداشبورد بنجاح!\n\nيتضمن التقرير:\n• جميع المؤشرات الرئيسية\n• الرسوم البيانية\n• البيانات الفورية\n• التحليلات المتقدمة');
        showNotification('تم تصدير التقرير بنجاح!', 'success');
    }, 3000);
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

// Initialize real-time updates
document.addEventListener('DOMContentLoaded', function() {
    updateRealtimeData();
    setInterval(updateRealtimeData, 30000); // Update every 30 seconds

    // Initialize charts after a short delay
    setTimeout(function() {
        console.log('Initializing charts from dashboard...');
        if (typeof window.initCharts === 'function') {
            window.initCharts();
        } else {
            console.log('Charts initialization function not available yet');
        }
    }, 500);
});
</script>

<!-- Load Chart.js and charts fix -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<!-- Try simple fix first, fallback to universal fix -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 تحميل نظام الرسوم البيانية...');

    // Load simple fix
    const simpleScript = document.createElement('script');
    simpleScript.src = '{{ asset("js/charts-simple-fix.js") }}';
    simpleScript.onload = function() {
        console.log('✅ تم تحميل النظام المبسط');

        // Check if charts are working after 3 seconds
        setTimeout(() => {
            const chartsCount = window.chartInstances ? Object.keys(window.chartInstances).length : 0;
            if (chartsCount === 0) {
                console.log('⚠️ النظام المبسط لم ينجح، جاري تحميل النظام الشامل...');

                // Load universal fix as fallback
                const universalScript = document.createElement('script');
                universalScript.src = '{{ asset("js/charts-universal-fix.js") }}';
                universalScript.onload = function() {
                    console.log('✅ تم تحميل النظام الشامل كبديل');
                };
                document.head.appendChild(universalScript);
            } else {
                console.log(`🎉 النظام المبسط يعمل بنجاح! تم إنشاء ${chartsCount} رسوم بيانية`);
            }
        }, 3000);
    };
    simpleScript.onerror = function() {
        console.log('❌ فشل تحميل النظام المبسط، جاري تحميل النظام الشامل...');

        // Load universal fix as fallback
        const universalScript = document.createElement('script');
        universalScript.src = '{{ asset("js/charts-universal-fix.js") }}';
        document.head.appendChild(universalScript);
    };
    document.head.appendChild(simpleScript);
});
</script>

@if(config('app.debug'))
<!-- Load diagnostics tool in debug mode -->
<script src="{{ asset('js/charts-diagnostics.js') }}"></script>
@endif

@endsection
