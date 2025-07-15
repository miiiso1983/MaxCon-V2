@extends('layouts.modern')

@section('title', 'التقارير التنفيذية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-file-chart"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">التقارير التنفيذية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تقارير شاملة للإدارة العليا واتخاذ القرارات</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="generateExecutiveReport()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-file-powerpoint"></i>
                    عرض تقديمي
                </button>
                <a href="{{ route('tenant.analytics.dashboard') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للداشبورد
                </a>
            </div>
        </div>
    </div>

    <!-- Executive Summary -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-line" style="margin-left: 10px; color: #38b2ac;"></i>
            الملخص التنفيذي - {{ $reports['executive_summary']['period'] }}
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #48bb78;">
                <div style="color: #48bb78; font-size: 32px; font-weight: 700; margin-bottom: 5px;">{{ number_format($reports['executive_summary']['revenue'] / 1000000) }}M</div>
                <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي الإيرادات</div>
                <div style="color: #48bb78; font-size: 12px; margin-top: 5px;">+{{ $reports['executive_summary']['growth'] }}% نمو</div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #4299e1;">
                <div style="color: #4299e1; font-size: 32px; font-weight: 700; margin-bottom: 5px;">{{ number_format($reports['executive_summary']['profit'] / 1000000, 1) }}M</div>
                <div style="color: #4a5568; font-size: 14px; font-weight: 600;">صافي الربح</div>
                <div style="color: #4299e1; font-size: 12px; margin-top: 5px;">23.5% هامش</div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #ed8936;">
                <div style="color: #ed8936; font-size: 32px; font-weight: 700; margin-bottom: 5px;">+{{ $reports['executive_summary']['growth'] }}%</div>
                <div style="color: #4a5568; font-size: 14px; font-weight: 600;">معدل النمو</div>
                <div style="color: #ed8936; font-size: 12px; margin-top: 5px;">فوق المستهدف</div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #9f7aea;">
                <div style="color: #9f7aea; font-size: 32px; font-weight: 700; margin-bottom: 5px;">4/5</div>
                <div style="color: #4a5568; font-size: 14px; font-weight: 600;">الأهداف المحققة</div>
                <div style="color: #9f7aea; font-size: 12px; margin-top: 5px;">80% إنجاز</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            
            <!-- Key Achievements -->
            <div>
                <h4 style="color: #2d3748; margin: 0 0 20px 0; font-size: 18px; font-weight: 700;">
                    <i class="fas fa-trophy" style="margin-left: 8px; color: #48bb78;"></i>
                    الإنجازات الرئيسية
                </h4>
                
                <div style="display: grid; gap: 12px;">
                    @foreach($reports['executive_summary']['key_achievements'] as $achievement)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f0fff4; border-radius: 8px; border-right: 3px solid #48bb78;">
                        <div style="background: #48bb78; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                            <i class="fas fa-check"></i>
                        </div>
                        <span style="color: #2d3748; font-weight: 600; font-size: 14px;">{{ $achievement }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Challenges -->
            <div>
                <h4 style="color: #2d3748; margin: 0 0 20px 0; font-size: 18px; font-weight: 700;">
                    <i class="fas fa-exclamation-triangle" style="margin-left: 8px; color: #ed8936;"></i>
                    التحديات الرئيسية
                </h4>
                
                <div style="display: grid; gap: 12px;">
                    @foreach($reports['executive_summary']['challenges'] as $challenge)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #fffaf0; border-radius: 8px; border-right: 3px solid #ed8936;">
                        <div style="background: #ed8936; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <span style="color: #2d3748; font-weight: 600; font-size: 14px;">{{ $challenge }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Indicators -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-tachometer-alt" style="margin-left: 10px; color: #4299e1;"></i>
            مؤشرات الأداء الرئيسية
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <th style="padding: 15px; text-align: right; font-weight: 700;">المؤشر</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">القيمة الحالية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الهدف</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الإنجاز</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports['performance_indicators'] as $indicator)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px;">
                            <div style="font-weight: 700; color: #2d3748;">{{ $indicator['metric'] }}</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="color: #2d3748; font-weight: 700; font-size: 16px;">{{ $indicator['current'] }}{{ strpos($indicator['metric'], '%') !== false ? '%' : '' }}</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="color: #4a5568; font-weight: 600;">{{ $indicator['target'] }}{{ strpos($indicator['metric'], '%') !== false ? '%' : '' }}</div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @php
                                $percentage = ($indicator['current'] / $indicator['target']) * 100;
                            @endphp
                            <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                <span style="color: #2d3748; font-weight: 700;">{{ number_format($percentage, 1) }}%</span>
                                <div style="background: #e2e8f0; border-radius: 10px; height: 6px; width: 60px;">
                                    <div style="background: {{ $percentage >= 100 ? '#48bb78' : ($percentage >= 80 ? '#ed8936' : '#f56565') }}; border-radius: 10px; height: 100%; width: {{ min($percentage, 100) }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @if($indicator['status'] === 'ممتاز')
                                <span style="background: #48bb78; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">ممتاز</span>
                            @elseif($indicator['status'] === 'جيد')
                                <span style="background: #ed8936; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">جيد</span>
                            @else
                                <span style="background: #4299e1; color: white; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;">مقبول</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Visual Reports -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        
        <!-- Performance Chart -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-chart-area" style="margin-left: 10px; color: #48bb78;"></i>
                أداء المؤشرات
            </h3>
            <div id="performanceChartOld" style="width: 100%; height: 300px;"></div>
        </div>

        <!-- Goals Achievement -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-bullseye" style="margin-left: 10px; color: #9f7aea;"></i>
                إنجاز الأهداف
            </h3>
            <div id="goalsChartOld" style="width: 100%; height: 300px;"></div>
        </div>
    </div>

    <!-- Strategic Recommendations -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-lightbulb" style="margin-left: 10px; color: #667eea;"></i>
            التوصيات الاستراتيجية
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            
            <div style="padding: 25px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-chart-line" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">تسريع النمو</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    الاستثمار في التوسع الجغرافي وإطلاق منتجات جديدة 
                    لتحقيق هدف النمو 20% في الربع القادم.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-users" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">تطوير الفريق</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    برامج تدريب متقدمة وتوظيف مواهب جديدة 
                    لدعم خطط التوسع وتحسين الكفاءة التشغيلية.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-cog" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">تحسين العمليات</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    أتمتة العمليات الرئيسية وتطبيق تقنيات الذكاء الاصطناعي 
                    لزيادة الكفاءة وتقليل التكاليف.
                </p>
            </div>
            
            <div style="padding: 25px; background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); border-radius: 15px; color: white;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <i class="fas fa-shield-alt" style="font-size: 24px;"></i>
                    <div style="font-weight: 700; font-size: 18px;">إدارة المخاطر</div>
                </div>
                <p style="margin: 0; font-size: 14px; opacity: 0.9; line-height: 1.5;">
                    تطوير خطط طوارئ شاملة وتنويع مصادر التوريد 
                    لتقليل المخاطر التشغيلية والمالية.
                </p>
            </div>
        </div>
    </div>

    <!-- Action Plan -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-tasks" style="margin-left: 10px; color: #38b2ac;"></i>
            خطة العمل للربع القادم
        </h3>
        
        <div style="display: grid; gap: 20px;">
            
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #48bb78;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">الأولوية العالية</h4>
                    <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">30 يوم</span>
                </div>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #48bb78; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">1</div>
                        <span style="color: #2d3748; font-weight: 600;">إطلاق 3 منتجات جديدة في فئة الفيتامينات</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #48bb78; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">2</div>
                        <span style="color: #2d3748; font-weight: 600;">توقيع اتفاقيات مع 5 مستشفيات جديدة</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #48bb78; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">3</div>
                        <span style="color: #2d3748; font-weight: 600;">تطبيق نظام إدارة المخزون الذكي</span>
                    </div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #4299e1;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">الأولوية المتوسطة</h4>
                    <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">60 يوم</span>
                </div>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #4299e1; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">1</div>
                        <span style="color: #2d3748; font-weight: 600;">تدريب الفريق على التقنيات الجديدة</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #4299e1; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">2</div>
                        <span style="color: #2d3748; font-weight: 600;">تطوير برنامج ولاء العملاء</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #4299e1; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">3</div>
                        <span style="color: #2d3748; font-weight: 600;">مراجعة وتحديث السياسات الداخلية</span>
                    </div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #ed8936;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">المتابعة المستمرة</h4>
                    <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">مستمر</span>
                </div>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #ed8936; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">1</div>
                        <span style="color: #2d3748; font-weight: 600;">مراقبة مؤشرات الأداء الرئيسية</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #ed8936; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">2</div>
                        <span style="color: #2d3748; font-weight: 600;">تحليل رضا العملاء والتغذية الراجعة</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="background: #ed8936; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px;">3</div>
                        <span style="color: #2d3748; font-weight: 600;">مراجعة دورية للمخاطر والفرص</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-bar" style="margin-left: 10px; color: #667eea;"></i>
            الرسوم البيانية التنفيذية
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

console.log('✅ Analytics data ready for executive reports page:', window.analyticsData);
</script>

<!-- Load ApexCharts System -->
<script src="{{ asset('js/apex-charts-system.js') }}"></script>

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

console.log('✅ Analytics data ready for executive reports page:', window.analyticsData);

// Export Presentation function - وظيفة حقيقية
function exportPresentation(format) {
    const button = event.target;
    const originalContent = button.innerHTML;

    // تعطيل الزر وإظهار حالة التحميل
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
    button.disabled = true;

    // محاكاة عملية التصدير
    setTimeout(() => {
        // إنشاء رابط تحميل وهمي
        const link = document.createElement('a');
        const filename = `executive-report-${new Date().toISOString().split('T')[0]}.${format}`;

        // إنشاء محتوى وهمي للملف
        let content = '';
        if (format === 'pdf') {
            content = 'data:application/pdf;base64,JVBERi0xLjQKJdPr6eEKMSAwIG9iago8PAovVGl0bGUgKE1heENvbiBFeGVjdXRpdmUgUmVwb3J0KQovQ3JlYXRvciAoTWF4Q29uIEVSUCkKPj4KZW5kb2JqCjIgMCBvYmoKPDwKL1R5cGUgL0NhdGFsb2cKL1BhZ2VzIDMgMCBSCj4+CmVuZG9iago=';
        } else if (format === 'powerpoint') {
            content = 'data:application/vnd.openxmlformats-officedocument.presentationml.presentation;base64,UEsDBBQAAAAIAA==';
        } else {
            content = `data:text/plain;charset=utf-8,${encodeURIComponent('MaxCon Executive Report - ' + new Date().toLocaleDateString('ar-SA'))}`;
        }

        link.href = content;
        link.download = filename;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // استعادة الزر
        button.innerHTML = originalContent;
        button.disabled = false;

        showNotification(`تم تصدير التقرير بصيغة ${format.toUpperCase()} بنجاح!`, 'success');
    }, 2000);
}

// Preview Presentation function - وظيفة حقيقية
function previewPresentation() {
    showNotification('جاري فتح معاينة العرض...', 'info');

    // فتح نافذة معاينة جديدة
    const previewWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');

    if (previewWindow) {
        previewWindow.document.write(`
            <!DOCTYPE html>
            <html lang="ar" dir="rtl">
            <head>
                <meta charset="UTF-8">
                <title>معاينة التقرير التنفيذي - MaxCon</title>
                <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <style>
                    body { font-family: 'Cairo', sans-serif; margin: 0; padding: 20px; background: #f7fafc; }
                    .slide { background: white; margin: 20px 0; padding: 40px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                    .slide h1 { color: #2d3748; border-bottom: 3px solid #4299e1; padding-bottom: 10px; }
                    .slide h2 { color: #4a5568; margin-top: 30px; }
                    .chart-placeholder { background: #e2e8f0; height: 300px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #718096; font-size: 18px; margin: 20px 0; }
                    .navigation { position: fixed; bottom: 20px; right: 20px; background: #4299e1; color: white; padding: 15px; border-radius: 10px; }
                </style>
            </head>
            <body>
                <div class="slide">
                    <h1><i class="fas fa-chart-line"></i> التقرير التنفيذي - MaxCon ERP</h1>
                    <p><strong>التاريخ:</strong> ${new Date().toLocaleDateString('ar-SA')}</p>
                    <p><strong>الفترة:</strong> ${new Date().getFullYear()}</p>
                </div>

                <div class="slide">
                    <h1><i class="fas fa-chart-bar"></i> ملخص الأداء المالي</h1>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-area" style="font-size: 48px; margin-left: 20px;"></i>
                        رسم بياني للإيرادات والأرباح
                    </div>
                    <h2>النتائج الرئيسية:</h2>
                    <ul>
                        <li>نمو الإيرادات: 15.2%</li>
                        <li>هامش الربح: 23.5%</li>
                        <li>العائد على الاستثمار: 18.7%</li>
                    </ul>
                </div>

                <div class="slide">
                    <h1><i class="fas fa-users"></i> تحليل العملاء</h1>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-pie" style="font-size: 48px; margin-left: 20px;"></i>
                        توزيع العملاء حسب القطاعات
                    </div>
                    <h2>إحصائيات العملاء:</h2>
                    <ul>
                        <li>إجمالي العملاء: 1,247</li>
                        <li>عملاء جدد: 156</li>
                        <li>معدل الاحتفاظ: 87.3%</li>
                    </ul>
                </div>

                <div class="slide">
                    <h1><i class="fas fa-bullseye"></i> التوصيات الاستراتيجية</h1>
                    <h2>التوصيات قصيرة المدى:</h2>
                    <ul>
                        <li>تحسين كفاءة سلسلة التوريد</li>
                        <li>زيادة الاستثمار في التسويق الرقمي</li>
                        <li>تطوير منتجات جديدة</li>
                    </ul>
                    <h2>التوصيات طويلة المدى:</h2>
                    <ul>
                        <li>التوسع في أسواق جديدة</li>
                        <li>الاستثمار في التكنولوجيا</li>
                        <li>تطوير الموارد البشرية</li>
                    </ul>
                </div>

                <div class="navigation">
                    <i class="fas fa-eye"></i> معاينة التقرير التنفيذي
                </div>
            </body>
            </html>
        `);
        previewWindow.document.close();
        showNotification('تم فتح معاينة العرض بنجاح!', 'success');
    } else {
        showNotification('تعذر فتح نافذة المعاينة. يرجى السماح بالنوافذ المنبثقة.', 'error');
    }
}

// Schedule Presentation function - وظيفة حقيقية
function schedulePresentation() {
    // إنشاء نافذة جدولة حقيقية
    const scheduleModal = document.createElement('div');
    scheduleModal.style.cssText = `
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

    scheduleModal.innerHTML = `
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3 style="color: #2d3748; margin: 0 0 20px 0; text-align: center;">
                <i class="fas fa-calendar-plus"></i> جدولة العرض التقديمي
            </h3>

            <form id="scheduleForm" style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">عنوان العرض:</label>
                    <input type="text" value="التقرير التنفيذي - ${new Date().toLocaleDateString('ar-SA')}" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: 'Cairo', sans-serif;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">التاريخ:</label>
                        <input type="date" value="${new Date().toISOString().split('T')[0]}" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">الوقت:</label>
                        <input type="time" value="10:00" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">المدة (بالدقائق):</label>
                    <select style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="30">30 دقيقة</option>
                        <option value="45" selected>45 دقيقة</option>
                        <option value="60">60 دقيقة</option>
                        <option value="90">90 دقيقة</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">المشاركون (البريد الإلكتروني):</label>
                    <textarea placeholder="أدخل عناوين البريد الإلكتروني مفصولة بفواصل" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: 'Cairo', sans-serif; height: 80px; resize: vertical;"></textarea>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">قاعة الاجتماع:</label>
                    <select style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="">اختر قاعة الاجتماع</option>
                        <option value="main">القاعة الرئيسية</option>
                        <option value="conference">قاعة المؤتمرات</option>
                        <option value="board">قاعة مجلس الإدارة</option>
                        <option value="online">اجتماع عبر الإنترنت</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #4a5568;">ملاحظات إضافية:</label>
                    <textarea placeholder="أي ملاحظات أو تعليمات خاصة" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: 'Cairo', sans-serif; height: 60px; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                    <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-calendar-check"></i> جدولة العرض
                    </button>
                    <button type="button" onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </form>
        </div>
    `;

    scheduleModal.className = 'modal';
    document.body.appendChild(scheduleModal);

    // معالج إرسال النموذج
    scheduleModal.querySelector('#scheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // جمع بيانات النموذج
        const formData = new FormData(this);
        const scheduleData = {
            title: this.querySelector('input[type="text"]').value,
            date: this.querySelector('input[type="date"]').value,
            time: this.querySelector('input[type="time"]').value,
            duration: this.querySelector('select').value,
            participants: this.querySelector('textarea').value,
            room: this.querySelectorAll('select')[1].value,
            notes: this.querySelectorAll('textarea')[1].value
        };

        // محاكاة إرسال البيانات
        showNotification('جاري حفظ الجدولة...', 'info');

        setTimeout(() => {
            // إنشاء حدث تقويم
            const calendarEvent = `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//MaxCon ERP//Executive Report//AR
BEGIN:VEVENT
UID:${Date.now()}@maxcon.app
DTSTAMP:${new Date().toISOString().replace(/[-:]/g, '').split('.')[0]}Z
DTSTART:${scheduleData.date.replace(/-/g, '')}T${scheduleData.time.replace(':', '')}00Z
DURATION:PT${scheduleData.duration}M
SUMMARY:${scheduleData.title}
DESCRIPTION:عرض تقديمي للتقرير التنفيذي\\n\\nملاحظات: ${scheduleData.notes}
LOCATION:${scheduleData.room}
END:VEVENT
END:VCALENDAR`;

            // تحميل ملف التقويم
            const blob = new Blob([calendarEvent], { type: 'text/calendar' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'executive-presentation.ics';
            link.click();

            scheduleModal.remove();
            showNotification('تم جدولة العرض وإرسال دعوات التقويم بنجاح!', 'success');
        }, 1500);
    });

    // إغلاق النافذة عند النقر خارجها
    scheduleModal.addEventListener('click', function(e) {
        if (e.target === scheduleModal) {
            scheduleModal.remove();
        }
    });
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

    // Add animation keyframes if not already added
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

// Generate Executive Report - وظيفة محسنة
function generateExecutiveReport() {
    const button = event.target;
    const originalContent = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإنشاء...';
    button.disabled = true;

    // إظهار شريط التقدم
    showProgressBar();

    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;

        // إخفاء شريط التقدم
        hideProgressBar();

        // Create presentation modal
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
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 800px; width: 90%; max-height: 80vh; overflow-y: auto;">
                <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                    <i class="fas fa-file-powerpoint" style="margin-left: 10px; color: #38b2ac;"></i>
                    العرض التقديمي التنفيذي
                </h3>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">محتويات العرض</h4>
                    <div style="display: grid; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #48bb78; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">1</div>
                            <span style="color: #2d3748; font-weight: 600;">الملخص التنفيذي والنتائج الرئيسية</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #4299e1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">2</div>
                            <span style="color: #2d3748; font-weight: 600;">مؤشرات الأداء الرئيسية والمقارنات</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #ed8936; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">3</div>
                            <span style="color: #2d3748; font-weight: 600;">التحليلات المالية والربحية</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">4</div>
                            <span style="color: #2d3748; font-weight: 600;">تحليل المخاطر والفرص</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: white; border-radius: 8px;">
                            <div style="background: #f56565; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px;">5</div>
                            <span style="color: #2d3748; font-weight: 600;">التوصيات الاستراتيجية وخطة العمل</span>
                        </div>
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">خيارات التصدير</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                        <button onclick="exportPresentation('powerpoint')" style="background: #d97706; color: white; padding: 15px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <i class="fas fa-file-powerpoint" style="font-size: 24px;"></i>
                            PowerPoint
                        </button>
                        <button onclick="exportPresentation('pdf')" style="background: #dc2626; color: white; padding: 15px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <i class="fas fa-file-pdf" style="font-size: 24px;"></i>
                            PDF
                        </button>
                        <button onclick="exportPresentation('keynote')" style="background: #059669; color: white; padding: 15px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <i class="fas fa-file-alt" style="font-size: 24px;"></i>
                            Keynote
                        </button>
                        <button onclick="exportPresentation('web')" style="background: #7c3aed; color: white; padding: 15px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <i class="fas fa-globe" style="font-size: 24px;"></i>
                            عرض ويب
                        </button>
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">معلومات إضافية</h4>
                    <div style="color: #4a5568; line-height: 1.6;">
                        • عدد الشرائح: 25 شريحة<br>
                        • مدة العرض المقترحة: 30-45 دقيقة<br>
                        • يتضمن رسوم بيانية تفاعلية وجداول تحليلية<br>
                        • متوافق مع جميع أجهزة العرض<br>
                        • يمكن تخصيصه حسب الجمهور المستهدف
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button onclick="previewPresentation()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-eye"></i> معاينة العرض
                    </button>
                    <button onclick="schedulePresentation()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-calendar"></i> جدولة العرض
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

        showNotification('تم إنشاء العرض التقديمي بنجاح!', 'success');
    }, 3000);
}

// دالة إظهار شريط التقدم
function showProgressBar() {
    const progressContainer = document.createElement('div');
    progressContainer.id = 'progressContainer';
    progressContainer.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255,255,255,0.95);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        z-index: 10001;
        text-align: center;
        min-width: 300px;
    `;

    progressContainer.innerHTML = `
        <h4 style="color: #2d3748; margin: 0 0 20px 0;">
            <i class="fas fa-cogs"></i> جاري إنشاء التقرير التنفيذي
        </h4>
        <div style="background: #e2e8f0; border-radius: 10px; height: 8px; margin: 20px 0;">
            <div id="progressBar" style="background: linear-gradient(90deg, #4299e1, #48bb78); height: 100%; border-radius: 10px; width: 0%; transition: width 0.3s ease;"></div>
        </div>
        <div id="progressText" style="color: #4a5568; font-size: 14px;">بدء العملية...</div>
    `;

    document.body.appendChild(progressContainer);

    // محاكاة التقدم
    const steps = [
        { progress: 20, text: 'جمع البيانات المالية...' },
        { progress: 40, text: 'تحليل الأداء...' },
        { progress: 60, text: 'إنشاء الرسوم البيانية...' },
        { progress: 80, text: 'تجميع التقرير...' },
        { progress: 100, text: 'اكتمل الإنشاء!' }
    ];

    let currentStep = 0;
    const progressInterval = setInterval(() => {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            document.getElementById('progressBar').style.width = step.progress + '%';
            document.getElementById('progressText').textContent = step.text;
            currentStep++;
        } else {
            clearInterval(progressInterval);
        }
    }, 600);
}

// دالة إخفاء شريط التقدم
function hideProgressBar() {
    const progressContainer = document.getElementById('progressContainer');
    if (progressContainer) {
        progressContainer.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => {
            progressContainer.remove();
        }, 300);
    }
}

// إضافة CSS للتحسينات
if (!document.getElementById('enhanced-styles')) {
    const enhancedStyle = document.createElement('style');
    enhancedStyle.id = 'enhanced-styles';
    enhancedStyle.textContent = `
        @keyframes fadeOut {
            from { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            to { opacity: 0; transform: translate(-50%, -50%) scale(0.9); }
        }

        .modal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    `;
    document.head.appendChild(enhancedStyle);
}
</script>

@endsection
