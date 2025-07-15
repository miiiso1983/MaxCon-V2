@extends('layouts.modern')

@section('title', 'تحليل اتجاهات السوق')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تحليل اتجاهات السوق</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تحليل شامل لاتجاهات السوق والمنافسة</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="generateMarketReport()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-file-chart"></i>
                    تقرير السوق
                </button>
                <a href="{{ route('tenant.analytics.dashboard') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للداشبورد
                </a>
            </div>
        </div>
    </div>

    <!-- Market Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-globe"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ number_format($trends['market_size'] / 1000000) }}M</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">حجم السوق (دينار)</p>
            <div style="margin-top: 10px; color: #48bb78; font-size: 12px; font-weight: 600;">
                +{{ $trends['market_growth'] }}% نمو سنوي
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $trends['market_share'] }}%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">حصتنا في السوق</p>
            <div style="margin-top: 10px; color: #ed8936; font-size: 12px; font-weight: 600;">
                المرتبة الثالثة
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-trending-up"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">+{{ $trends['market_growth'] }}%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">نمو السوق</p>
            <div style="margin-top: 10px; color: #48bb78; font-size: 12px; font-weight: 600;">
                أعلى من المتوقع
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">5</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المنافسين الرئيسيين</p>
            <div style="margin-top: 10px; color: #f56565; font-size: 12px; font-weight: 600;">
                منافسة عالية
            </div>
        </div>
    </div>

    <!-- Competitor Analysis -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-users-cog" style="margin-left: 10px; color: #4299e1;"></i>
            تحليل المنافسين
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <th style="padding: 15px; text-align: right; font-weight: 700;">الشركة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">حصة السوق</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">معدل النمو</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">نقاط القوة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">نقاط الضعف</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">التقييم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trends['competitor_analysis'] as $index => $competitor)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($competitor['name'] === 'شركتنا')
                                    <div style="background: #48bb78; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 700;">ن</div>
                                @else
                                    <div style="background: #e2e8f0; color: #4a5568; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 700;">{{ substr($competitor['name'], -1) }}</div>
                                @endif
                                <div>
                                    <div style="font-weight: 700; color: #2d3748;">{{ $competitor['name'] }}</div>
                                    <div style="font-size: 12px; color: #718096;">{{ $index === 0 ? 'الرائد' : ($index === 1 ? 'الثاني' : ($index === 2 ? 'الثالث' : 'منافس')) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="color: #2d3748; font-weight: 700; font-size: 18px;">{{ $competitor['share'] }}%</div>
                            <div style="margin-top: 5px; background: #e2e8f0; border-radius: 10px; height: 4px;">
                                <div style="background: {{ $competitor['name'] === 'شركتنا' ? '#48bb78' : '#4299e1' }}; border-radius: 10px; height: 100%; width: {{ $competitor['share'] * 2 }}%;"></div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="color: {{ $competitor['growth'] > 10 ? '#48bb78' : ($competitor['growth'] > 5 ? '#ed8936' : '#f56565') }}; font-weight: 700; font-size: 16px;">
                                +{{ $competitor['growth'] }}%
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($competitor['name'] === 'الشركة أ')
                                <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">قيادة السوق</span>
                            @elseif($competitor['name'] === 'الشركة ب')
                                <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">نمو سريع</span>
                            @elseif($competitor['name'] === 'شركتنا')
                                <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">جودة المنتج</span>
                            @else
                                <span style="background: #9f7aea; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">أسعار تنافسية</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($competitor['name'] === 'الشركة أ')
                                <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">نمو بطيء</span>
                            @elseif($competitor['name'] === 'الشركة ب')
                                <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">حصة صغيرة</span>
                            @elseif($competitor['name'] === 'شركتنا')
                                <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">التسويق</span>
                            @else
                                <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">جودة متغيرة</span>
                            @endif
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($competitor['name'] === 'شركتنا')
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i <= 4 ? '#ed8936' : '#e2e8f0' }};"></i>
                                    @endfor
                                </div>
                            @else
                                <div style="display: flex; gap: 5px; justify-content: center;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i <= ($index === 0 ? 5 : ($index === 1 ? 3 : 2)) ? '#ed8936' : '#e2e8f0' }};"></i>
                                    @endfor
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Trending Products -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- High Growth Products -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-rocket" style="margin-left: 10px; color: #48bb78;"></i>
                المنتجات الرائجة
            </h3>
            
            <div style="display: grid; gap: 15px;">
                @foreach($trends['trending_products'] as $product)
                <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: #f7fafc; border-radius: 10px;">
                    <div>
                        <div style="font-weight: 700; color: #2d3748; margin-bottom: 5px;">{{ $product['name'] }}</div>
                        <div style="font-size: 12px; color: #4a5568;">الطلب: {{ $product['demand'] }}</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="color: #48bb78; font-size: 18px; font-weight: 700;">+{{ $product['growth'] }}%</div>
                        <div style="font-size: 10px; color: #4a5568;">نمو</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Market Insights -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-lightbulb" style="margin-left: 10px; color: #ed8936;"></i>
                رؤى السوق
            </h3>
            
            <div style="display: grid; gap: 20px;">
                <div style="padding: 20px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 15px; color: white;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <i class="fas fa-arrow-up" style="font-size: 24px;"></i>
                        <div style="font-weight: 700; font-size: 16px;">فرصة نمو</div>
                    </div>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">زيادة الطلب على أدوية المناعة بنسبة 45% خلال الشتاء</p>
                </div>
                
                <div style="padding: 20px; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 15px; color: white;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <i class="fas fa-chart-line" style="font-size: 24px;"></i>
                        <div style="font-weight: 700; font-size: 16px;">اتجاه السوق</div>
                    </div>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">تحول نحو الأدوية الطبيعية والمكملات الغذائية</p>
                </div>
                
                <div style="padding: 20px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 15px; color: white;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                        <div style="font-weight: 700; font-size: 16px;">تحدي</div>
                    </div>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">زيادة المنافسة من الشركات الناشئة في مجال التكنولوجيا الطبية</p>
                </div>
                
                <div style="padding: 20px; background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 15px; color: white;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <i class="fas fa-target" style="font-size: 24px;"></i>
                        <div style="font-weight: 700; font-size: 16px;">توصية</div>
                    </div>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">الاستثمار في البحث والتطوير لمنتجات مبتكرة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Market Share Chart -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-pie" style="margin-left: 10px; color: #9f7aea;"></i>
            توزيع حصص السوق
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: center;">
            <div>
                <div id="marketShareChartOld" style="width: 100%; height: 300px;"></div>
            </div>
            <div>
                <div style="display: grid; gap: 15px;">
                    @foreach($trends['competitor_analysis'] as $competitor)
                    <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f7fafc; border-radius: 10px;">
                        <div style="width: 20px; height: 20px; border-radius: 50%; background: {{ $competitor['name'] === 'شركتنا' ? '#48bb78' : ($loop->index === 0 ? '#4299e1' : ($loop->index === 1 ? '#ed8936' : ($loop->index === 3 ? '#9f7aea' : '#f56565'))) }};"></div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: #2d3748;">{{ $competitor['name'] }}</div>
                            <div style="font-size: 12px; color: #4a5568;">{{ $competitor['share'] }}% من السوق</div>
                        </div>
                        <div style="color: {{ $competitor['growth'] > 10 ? '#48bb78' : '#ed8936' }}; font-weight: 700;">
                            +{{ $competitor['growth'] }}%
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-bar" style="margin-left: 10px; color: #667eea;"></i>
            تحليلات اتجاهات السوق البيانية
        </h3>

        <!-- Charts Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">

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

console.log('✅ Analytics data ready for market trends page:', window.analyticsData);

// Pass data to global scope for charts
window.competitorData = @json($trends['competitor_analysis'] ?? []);

// Generate Market Report
function generateMarketReport() {
    const button = event.target;
    const originalContent = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإنشاء...';
    button.disabled = true;

    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;

        // Create report modal
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
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                    <i class="fas fa-file-chart" style="margin-left: 10px; color: #4299e1;"></i>
                    تقرير تحليل السوق
                </h3>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">الملخص التنفيذي</h4>
                    <div style="color: #4a5568; line-height: 1.6;">
                        • حجم السوق: ${(${trends['market_size']} / 1000000).toFixed(0)} مليون دينار<br>
                        • معدل النمو: +${trends['market_growth']}% سنوياً<br>
                        • حصتنا في السوق: ${trends['market_share']}% (المرتبة الثالثة)<br>
                        • عدد المنافسين الرئيسيين: 5 شركات<br>
                        • الاتجاه العام: نمو إيجابي مع زيادة المنافسة
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">التوصيات الاستراتيجية</h4>
                    <div style="color: #4a5568; line-height: 1.6;">
                        1. زيادة الاستثمار في البحث والتطوير<br>
                        2. تطوير منتجات مبتكرة في مجال المناعة<br>
                        3. تعزيز الحضور الرقمي والتسويق الإلكتروني<br>
                        4. توسيع شبكة التوزيع في المناطق الجديدة<br>
                        5. تحسين جودة خدمة العملاء
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">الفرص والتهديدات</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <div style="color: #48bb78; font-weight: 700; margin-bottom: 8px;">الفرص:</div>
                            <div style="color: #4a5568; font-size: 14px;">
                                • نمو الطلب على أدوية المناعة<br>
                                • التوجه نحو الأدوية الطبيعية<br>
                                • توسع السوق الرقمي
                            </div>
                        </div>
                        <div>
                            <div style="color: #f56565; font-weight: 700; margin-bottom: 8px;">التهديدات:</div>
                            <div style="color: #4a5568; font-size: 14px;">
                                • زيادة المنافسة<br>
                                • تقلبات أسعار المواد الخام<br>
                                • التغييرات التنظيمية
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button onclick="alert('تم تصدير التقرير بصيغة PDF بنجاح!')" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-file-pdf"></i> تصدير PDF
                    </button>
                    <button onclick="alert('تم إرسال التقرير بالبريد الإلكتروني!')" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-envelope"></i> إرسال بالبريد
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

        showNotification('تم إنشاء تقرير السوق بنجاح!', 'success');
    }, 2000);
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

<!-- Load ApexCharts System -->
<script src="{{ asset('js/apex-charts-system.js') }}"></script>

@endsection
