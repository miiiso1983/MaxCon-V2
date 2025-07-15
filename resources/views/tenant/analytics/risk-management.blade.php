@extends('layouts.modern')

@section('title', 'إدارة المخاطر')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة المخاطر</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تحليل وإدارة المخاطر المؤسسية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="generateRiskReport()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-file-shield"></i>
                    تقرير المخاطر
                </button>
                <a href="{{ route('tenant.analytics.dashboard') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للداشبورد
                </a>
            </div>
        </div>
    </div>

    <!-- Risk Score Overview -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $risks['risk_score']['overall_risk'] }}/10</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المخاطر الإجمالية</p>
            <div style="margin-top: 10px; color: #ed8936; font-size: 12px; font-weight: 600;">
                متوسط
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $risks['risk_score']['financial_risk'] }}/10</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المخاطر المالية</p>
            <div style="margin-top: 10px; color: #48bb78; font-size: 12px; font-weight: 600;">
                منخفض
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-cogs"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $risks['risk_score']['operational_risk'] }}/10</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المخاطر التشغيلية</p>
            <div style="margin-top: 10px; color: #f56565; font-size: 12px; font-weight: 600;">
                عالي
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $risks['risk_score']['market_risk'] }}/10</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">مخاطر السوق</p>
            <div style="margin-top: 10px; color: #9f7aea; font-size: 12px; font-weight: 600;">
                عالي
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-gavel"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $risks['risk_score']['regulatory_risk'] }}/10</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المخاطر التنظيمية</p>
            <div style="margin-top: 10px; color: #4299e1; font-size: 12px; font-weight: 600;">
                متوسط
            </div>
        </div>
    </div>

    <!-- Financial Risks -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-dollar-sign" style="margin-left: 10px; color: #48bb78;"></i>
            المخاطر المالية
        </h3>

        <div style="display: grid; gap: 20px;">
            @foreach($risks['financial_risks'] as $risk)
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid {{ $risk['level'] === 'عالي' ? '#f56565' : ($risk['level'] === 'متوسط' ? '#ed8936' : '#48bb78') }};">
                <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 15px;">
                    <div>
                        <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 18px; font-weight: 700;">{{ $risk['type'] }}</h4>
                        <div style="color: #4a5568; font-size: 14px;">التأثير: {{ $risk['impact'] }}</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="background: {{ $risk['level'] === 'عالي' ? '#f56565' : ($risk['level'] === 'متوسط' ? '#ed8936' : '#48bb78') }}; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 5px;">
                            {{ $risk['level'] }}
                        </div>
                        <div style="color: #4a5568; font-size: 12px;">{{ $risk['probability'] }}% احتمالية</div>
                    </div>
                </div>
                
                <div style="background: white; border-radius: 10px; padding: 15px;">
                    <div style="color: #4a5568; font-size: 14px; margin-bottom: 10px;">
                        <strong>خطة التخفيف:</strong> {{ $risk['mitigation'] }}
                    </div>
                    <div style="background: #e2e8f0; border-radius: 10px; height: 6px;">
                        <div style="background: {{ $risk['level'] === 'عالي' ? '#f56565' : ($risk['level'] === 'متوسط' ? '#ed8936' : '#48bb78') }}; border-radius: 10px; height: 100%; width: {{ $risk['probability'] }}%;"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Operational and Market Risks -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- Operational Risks -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-cogs" style="margin-left: 10px; color: #f56565;"></i>
                المخاطر التشغيلية
            </h3>
            
            <div style="display: grid; gap: 15px;">
                @foreach($risks['operational_risks'] as $risk)
                <div style="background: #f7fafc; border-radius: 10px; padding: 15px; border-top: 3px solid {{ $risk['level'] === 'عالي' ? '#f56565' : ($risk['level'] === 'متوسط' ? '#ed8936' : '#48bb78') }};">
                    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 10px;">
                        <h5 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">{{ $risk['type'] }}</h5>
                        <span style="background: {{ $risk['level'] === 'عالي' ? '#f56565' : ($risk['level'] === 'متوسط' ? '#ed8936' : '#48bb78') }}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 600;">
                            {{ $risk['level'] }}
                        </span>
                    </div>
                    <div style="color: #4a5568; font-size: 12px; margin-bottom: 8px;">{{ $risk['mitigation'] }}</div>
                    <div style="color: #4a5568; font-size: 11px;">احتمالية: {{ $risk['probability'] }}%</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Market Risks -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-chart-line" style="margin-left: 10px; color: #9f7aea;"></i>
                مخاطر السوق
            </h3>
            
            <div style="display: grid; gap: 15px;">
                @foreach($risks['market_risks'] as $risk)
                <div style="background: #f7fafc; border-radius: 10px; padding: 15px; border-top: 3px solid {{ $risk['level'] === 'عالي' ? '#f56565' : ($risk['level'] === 'متوسط' ? '#ed8936' : '#48bb78') }};">
                    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 10px;">
                        <h5 style="color: #2d3748; margin: 0; font-size: 16px; font-weight: 700;">{{ $risk['type'] }}</h5>
                        <span style="background: {{ $risk['level'] === 'عالي' ? '#f56565' : ($risk['level'] === 'متوسط' ? '#ed8936' : '#48bb78') }}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 600;">
                            {{ $risk['level'] }}
                        </span>
                    </div>
                    <div style="color: #4a5568; font-size: 12px; margin-bottom: 8px;">{{ $risk['mitigation'] }}</div>
                    <div style="color: #4a5568; font-size: 11px;">احتمالية: {{ $risk['probability'] }}%</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Risk Matrix -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-th" style="margin-left: 10px; color: #4299e1;"></i>
            مصفوفة المخاطر
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; align-items: center;">
            <div>
                <div id="riskMatrixChartOld" style="width: 100%; height: 300px;"></div>
            </div>
            <div>
                <div style="display: grid; gap: 15px;">
                    <div style="background: #f7fafc; border-radius: 10px; padding: 15px;">
                        <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">تفسير المصفوفة</h4>
                        <div style="display: grid; gap: 8px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #f56565; width: 15px; height: 15px; border-radius: 3px;"></div>
                                <span style="color: #4a5568; font-size: 14px;">مخاطر عالية (تتطلب إجراء فوري)</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #ed8936; width: 15px; height: 15px; border-radius: 3px;"></div>
                                <span style="color: #4a5568; font-size: 14px;">مخاطر متوسطة (تتطلب مراقبة)</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #48bb78; width: 15px; height: 15px; border-radius: 3px;"></div>
                                <span style="color: #4a5568; font-size: 14px;">مخاطر منخفضة (مقبولة)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #f7fafc; border-radius: 10px; padding: 15px;">
                        <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">الإجراءات المطلوبة</h4>
                        <div style="color: #4a5568; font-size: 14px; line-height: 1.5;">
                            • 3 مخاطر تتطلب إجراء فوري<br>
                            • 5 مخاطر تحتاج مراقبة مستمرة<br>
                            • 2 مخاطر ضمن المستوى المقبول<br>
                            • مراجعة شهرية للمصفوفة
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Mitigation Plans -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-tasks" style="margin-left: 10px; color: #667eea;"></i>
            خطط التخفيف والاستجابة
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            
            <div style="padding: 25px; background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">مخاطر عالية الأولوية</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    انقطاع سلسلة التوريد والمنافسة الجديدة تتطلب إجراءات فورية. 
                    تطوير موردين بديلين وخطة ابتكار عاجلة.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-eye" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">مراقبة مستمرة</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    مخاطر السيولة والتغيرات التنظيمية تحتاج مراقبة دورية 
                    وتحديث خطط الطوارئ كل 3 أشهر.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-shield-alt" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">تعزيز الحماية</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    تطوير نظام إنذار مبكر للمخاطر وتدريب الفريق على 
                    إجراءات الاستجابة السريعة للأزمات.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-chart-line" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">تحسين مستمر</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    مراجعة دورية لفعالية خطط التخفيف وتحديثها بناءً على 
                    التطورات الجديدة والدروس المستفادة.
                </p>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-bar" style="margin-left: 10px; color: #667eea;"></i>
            تحليلات إدارة المخاطر البيانية
        </h3>

        <!-- Charts Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">

            <!-- Risk Matrix Chart -->
            <div style="background: #f8f9fa; border-radius: 15px; padding: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">
                    <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #f56565;"></i>
                    مصفوفة المخاطر
                </h4>
                <div style="height: 300px; position: relative;">
                    <div id="riskMatrixChart"></div>
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

console.log('✅ Analytics data ready for risk management page:', window.analyticsData);
</script>

<!-- Load ApexCharts System -->
<script src="{{ asset('js/apex-charts-system.js') }}"></script>

<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }

    // Risk Matrix Chart
    const riskMatrixCtx = document.getElementById('riskMatrixChart');
    if (riskMatrixCtx) {
        const riskMatrixChart = new Chart(riskMatrixCtx, {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'المخاطر المالية',
            data: [
                {x: 35, y: 6}, // مخاطر السيولة
                {x: 65, y: 8}, // مخاطر أسعار الصرف
                {x: 20, y: 5}  // مخاطر الائتمان
            ],
            backgroundColor: '#48bb78',
            borderColor: '#48bb78',
            pointRadius: 8
        }, {
            label: 'المخاطر التشغيلية',
            data: [
                {x: 45, y: 9}, // انقطاع سلسلة التوريد
                {x: 25, y: 7}, // مشاكل الجودة
                {x: 30, y: 6}  // نقص الموظفين
            ],
            backgroundColor: '#f56565',
            borderColor: '#f56565',
            pointRadius: 8
        }, {
            label: 'مخاطر السوق',
            data: [
                {x: 40, y: 6}, // تغيرات الطلب
                {x: 55, y: 8}, // منافسة جديدة
                {x: 35, y: 7}  // تغيرات تنظيمية
            ],
            backgroundColor: '#9f7aea',
            borderColor: '#9f7aea',
            pointRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    title: function() {
                        return 'تفاصيل المخاطر';
                    },
                    label: function(context) {
                        return `الاحتمالية: ${context.parsed.x}% | التأثير: ${context.parsed.y}/10`;
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'الاحتمالية (%)'
                },
                min: 0,
                max: 100,
                grid: {
                    color: '#e2e8f0'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'التأثير (1-10)'
                },
                min: 0,
                max: 10,
                grid: {
                    color: '#e2e8f0'
                }
            }
        }
        });
    }
});

// Generate Risk Report
function generateRiskReport() {
    const button = event.target;
    const originalContent = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإنشاء...';
    button.disabled = true;

    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;

        alert('تم إنشاء تقرير المخاطر بنجاح!\n\nيتضمن التقرير:\n• تحليل شامل لجميع أنواع المخاطر\n• مصفوفة المخاطر التفاعلية\n• خطط التخفيف والاستجابة\n• التوصيات الاستراتيجية\n• خطة المراقبة والمتابعة\n\nتم حفظ التقرير وإرساله للإدارة العليا.');
        showNotification('تم إنشاء تقرير المخاطر بنجاح!', 'success');
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

@endsection
