<?php

namespace App\Http\Controllers\Tenant\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function dashboard()
    {
        // Sample KPIs data
        $kpis = [
            'total_revenue' => 125000000,
            'revenue_growth' => 15.5,
            'total_customers' => 1250,
            'customer_growth' => 8.2,
            'total_orders' => 3450,
            'order_growth' => 12.3,
            'avg_order_value' => 362000,
            'aov_growth' => 5.8,
            'profit_margin' => 23.5,
            'margin_change' => 2.1,
            'inventory_turnover' => 4.2,
            'turnover_change' => -0.3
        ];

        // Sample chart data
        $chartData = [
            'revenue_trend' => [
                'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                'data' => [18500000, 22300000, 19800000, 25600000, 28900000, 31200000]
            ],
            'sales_by_category' => [
                'labels' => ['أدوية القلب', 'المضادات الحيوية', 'أدوية السكري', 'الفيتامينات', 'أخرى'],
                'data' => [35, 25, 20, 15, 5]
            ],
            'customer_segments' => [
                'labels' => ['مستشفيات', 'صيدليات', 'عيادات', 'موزعين'],
                'data' => [40, 35, 15, 10]
            ]
        ];

        return view('tenant.analytics.dashboard', compact('kpis', 'chartData'));
    }

    /**
     * Market trends analysis
     */
    public function marketTrends()
    {
        // Sample market trends data
        $trends = [
            'market_size' => 850000000,
            'market_growth' => 12.8,
            'market_share' => 14.7,
            'competitor_analysis' => [
                ['name' => 'الشركة أ', 'share' => 25.3, 'growth' => 8.5],
                ['name' => 'الشركة ب', 'share' => 18.9, 'growth' => 15.2],
                ['name' => 'شركتنا', 'share' => 14.7, 'growth' => 12.8],
                ['name' => 'الشركة ج', 'share' => 12.1, 'growth' => 6.3],
                ['name' => 'أخرى', 'share' => 29.0, 'growth' => 9.1]
            ],
            'trending_products' => [
                ['name' => 'دواء كوفيد الجديد', 'growth' => 245.6, 'demand' => 'عالي جداً'],
                ['name' => 'فيتامين د المحسن', 'growth' => 89.3, 'demand' => 'عالي'],
                ['name' => 'مضاد حيوي طبيعي', 'growth' => 67.8, 'demand' => 'متوسط'],
                ['name' => 'مكمل المناعة', 'growth' => 45.2, 'demand' => 'متوسط']
            ]
        ];

        return view('tenant.analytics.market-trends', compact('trends'));
    }

    /**
     * Customer behavior analysis
     */
    public function customerBehavior()
    {
        // Sample customer behavior data
        $behavior = [
            'customer_lifetime_value' => 2850000,
            'avg_purchase_frequency' => 3.2,
            'customer_retention_rate' => 78.5,
            'churn_rate' => 21.5,
            'purchase_patterns' => [
                'peak_hours' => ['09:00-11:00', '14:00-16:00', '19:00-21:00'],
                'peak_days' => ['الأحد', 'الثلاثاء', 'الخميس'],
                'seasonal_trends' => [
                    'الشتاء' => ['أدوية البرد', 'فيتامين د', 'مقويات المناعة'],
                    'الصيف' => ['واقيات الشمس', 'مضادات الحساسية', 'مرطبات']
                ]
            ],
            'customer_segments_behavior' => [
                [
                    'segment' => 'العملاء المميزين',
                    'percentage' => 15,
                    'avg_order_value' => 850000,
                    'frequency' => 'أسبوعي',
                    'loyalty' => 95
                ],
                [
                    'segment' => 'العملاء المنتظمين',
                    'percentage' => 35,
                    'avg_order_value' => 450000,
                    'frequency' => 'شهري',
                    'loyalty' => 75
                ],
                [
                    'segment' => 'العملاء الجدد',
                    'percentage' => 25,
                    'avg_order_value' => 280000,
                    'frequency' => 'غير منتظم',
                    'loyalty' => 45
                ],
                [
                    'segment' => 'العملاء النادرين',
                    'percentage' => 25,
                    'avg_order_value' => 180000,
                    'frequency' => 'نادر',
                    'loyalty' => 25
                ]
            ]
        ];

        return view('tenant.analytics.customer-behavior', compact('behavior'));
    }

    /**
     * AI predictions for sales and inventory
     */
    public function predictions()
    {
        // Sample prediction data
        $predictions = [
            'sales_forecast' => [
                'next_month' => [
                    'predicted_revenue' => 35800000,
                    'confidence' => 87.5,
                    'trend' => 'صاعد',
                    'factors' => ['موسم الشتاء', 'حملة تسويقية', 'منتجات جديدة']
                ],
                'next_quarter' => [
                    'predicted_revenue' => 108500000,
                    'confidence' => 82.3,
                    'trend' => 'صاعد',
                    'factors' => ['نمو السوق', 'توسع العملاء', 'تحسن الاقتصاد']
                ]
            ],
            'inventory_forecast' => [
                'reorder_alerts' => [
                    ['product' => 'باراسيتامول 500mg', 'current_stock' => 150, 'predicted_demand' => 800, 'reorder_date' => '2024-02-15'],
                    ['product' => 'أموكسيسيلين 250mg', 'current_stock' => 75, 'predicted_demand' => 450, 'reorder_date' => '2024-02-10'],
                    ['product' => 'فيتامين د 1000 وحدة', 'current_stock' => 200, 'predicted_demand' => 600, 'reorder_date' => '2024-02-20']
                ],
                'overstock_alerts' => [
                    ['product' => 'شراب السعال للأطفال', 'current_stock' => 500, 'predicted_demand' => 120, 'action' => 'تخفيض السعر'],
                    ['product' => 'كريم الحساسية', 'current_stock' => 300, 'predicted_demand' => 80, 'action' => 'عرض خاص']
                ]
            ],
            'demand_patterns' => [
                'high_demand_products' => [
                    ['name' => 'أدوية البرد والإنفلونزا', 'increase' => 45, 'reason' => 'موسم الشتاء'],
                    ['name' => 'فيتامينات المناعة', 'increase' => 38, 'reason' => 'الوعي الصحي'],
                    ['name' => 'مسكنات الألم', 'increase' => 22, 'reason' => 'زيادة الطلب']
                ],
                'declining_products' => [
                    ['name' => 'واقيات الشمس', 'decrease' => -35, 'reason' => 'انتهاء الصيف'],
                    ['name' => 'أدوية الحساسية الموسمية', 'decrease' => -28, 'reason' => 'انتهاء موسم الحساسية']
                ]
            ]
        ];

        return view('tenant.analytics.predictions', compact('predictions'));
    }

    /**
     * Profitability analysis
     */
    public function profitability()
    {
        // Sample profitability data
        $profitability = [
            'overall_metrics' => [
                'gross_profit_margin' => 35.8,
                'net_profit_margin' => 23.5,
                'operating_margin' => 28.2,
                'return_on_investment' => 18.7
            ],
            'product_profitability' => [
                ['category' => 'أدوية القلب', 'revenue' => 45000000, 'cost' => 28000000, 'profit' => 17000000, 'margin' => 37.8],
                ['category' => 'المضادات الحيوية', 'revenue' => 32000000, 'cost' => 20000000, 'profit' => 12000000, 'margin' => 37.5],
                ['category' => 'أدوية السكري', 'revenue' => 28000000, 'cost' => 18000000, 'profit' => 10000000, 'margin' => 35.7],
                ['category' => 'الفيتامينات', 'revenue' => 15000000, 'cost' => 8000000, 'profit' => 7000000, 'margin' => 46.7],
                ['category' => 'أخرى', 'revenue' => 5000000, 'cost' => 3500000, 'profit' => 1500000, 'margin' => 30.0]
            ],
            'customer_profitability' => [
                ['segment' => 'مستشفيات كبرى', 'revenue' => 50000000, 'cost' => 30000000, 'profit' => 20000000, 'margin' => 40.0],
                ['segment' => 'سلاسل صيدليات', 'revenue' => 35000000, 'cost' => 23000000, 'profit' => 12000000, 'margin' => 34.3],
                ['segment' => 'صيدليات مستقلة', 'revenue' => 25000000, 'cost' => 17000000, 'profit' => 8000000, 'margin' => 32.0],
                ['segment' => 'عيادات خاصة', 'revenue' => 15000000, 'cost' => 10000000, 'profit' => 5000000, 'margin' => 33.3]
            ]
        ];

        return view('tenant.analytics.profitability', compact('profitability'));
    }

    /**
     * Risk management analysis
     */
    public function riskManagement()
    {
        // Sample risk data
        $risks = [
            'financial_risks' => [
                ['type' => 'مخاطر السيولة', 'level' => 'متوسط', 'probability' => 35, 'impact' => 'متوسط', 'mitigation' => 'تحسين إدارة النقد'],
                ['type' => 'مخاطر أسعار الصرف', 'level' => 'عالي', 'probability' => 65, 'impact' => 'عالي', 'mitigation' => 'التحوط المالي'],
                ['type' => 'مخاطر الائتمان', 'level' => 'منخفض', 'probability' => 20, 'impact' => 'متوسط', 'mitigation' => 'تقييم العملاء']
            ],
            'operational_risks' => [
                ['type' => 'انقطاع سلسلة التوريد', 'level' => 'عالي', 'probability' => 45, 'impact' => 'عالي', 'mitigation' => 'موردين بديلين'],
                ['type' => 'مشاكل الجودة', 'level' => 'متوسط', 'probability' => 25, 'impact' => 'عالي', 'mitigation' => 'رقابة الجودة'],
                ['type' => 'نقص الموظفين', 'level' => 'متوسط', 'probability' => 30, 'impact' => 'متوسط', 'mitigation' => 'خطط التوظيف']
            ],
            'market_risks' => [
                ['type' => 'تغيرات الطلب', 'level' => 'متوسط', 'probability' => 40, 'impact' => 'متوسط', 'mitigation' => 'تنويع المنتجات'],
                ['type' => 'منافسة جديدة', 'level' => 'عالي', 'probability' => 55, 'impact' => 'عالي', 'mitigation' => 'الابتكار والتطوير'],
                ['type' => 'تغيرات تنظيمية', 'level' => 'متوسط', 'probability' => 35, 'impact' => 'عالي', 'mitigation' => 'متابعة التشريعات']
            ],
            'risk_score' => [
                'overall_risk' => 6.8,
                'financial_risk' => 5.5,
                'operational_risk' => 7.2,
                'market_risk' => 7.5,
                'regulatory_risk' => 6.0
            ]
        ];

        return view('tenant.analytics.risk-management', compact('risks'));
    }

    /**
     * Generate executive reports
     */
    public function executiveReports()
    {
        // Sample executive report data
        $reports = [
            'executive_summary' => [
                'period' => 'Q4 2023',
                'revenue' => 125000000,
                'profit' => 29375000,
                'growth' => 15.5,
                'key_achievements' => [
                    'تجاوز أهداف المبيعات بنسبة 12%',
                    'إطلاق 5 منتجات جديدة بنجاح',
                    'زيادة حصة السوق إلى 14.7%',
                    'تحسين هامش الربح بـ 2.1%'
                ],
                'challenges' => [
                    'تقلبات أسعار المواد الخام',
                    'زيادة المنافسة في السوق',
                    'تحديات سلسلة التوريد'
                ]
            ],
            'performance_indicators' => [
                ['metric' => 'العائد على الاستثمار', 'current' => 18.7, 'target' => 20.0, 'status' => 'جيد'],
                ['metric' => 'رضا العملاء', 'current' => 87.5, 'target' => 90.0, 'status' => 'جيد'],
                ['metric' => 'دوران المخزون', 'current' => 4.2, 'target' => 4.5, 'status' => 'مقبول'],
                ['metric' => 'نمو المبيعات', 'current' => 15.5, 'target' => 15.0, 'status' => 'ممتاز']
            ]
        ];

        return view('tenant.analytics.executive-reports', compact('reports'));
    }

    /**
     * Get real-time analytics data via API
     */
    public function getRealtimeData(Request $request)
    {
        $type = $request->get('type', 'overview');
        
        // Sample real-time data
        $data = [
            'overview' => [
                'current_sales' => 2850000,
                'orders_today' => 45,
                'active_customers' => 128,
                'inventory_alerts' => 8
            ],
            'sales' => [
                'hourly_sales' => [1200000, 1850000, 2100000, 2850000],
                'top_products' => [
                    ['name' => 'باراسيتامول', 'sales' => 450000],
                    ['name' => 'أموكسيسيلين', 'sales' => 380000],
                    ['name' => 'فيتامين د', 'sales' => 320000]
                ]
            ]
        ];

        return response()->json($data[$type] ?? []);
    }
}
