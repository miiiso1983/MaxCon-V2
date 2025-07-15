@extends('layouts.modern')

@section('title', 'إحصائيات سحب المنتجات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إحصائيات سحب المنتجات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تقارير وإحصائيات شاملة لعمليات سحب المنتجات</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.product-recalls.create') }}" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    بدء سحب جديد
                </a>
                <a href="{{ route('tenant.inventory.regulatory.product-recalls.index') }}" style="background: rgba(255,255,255,0.2); color: #667eea; padding: 15px 25px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- Total Recalls -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $statistics['total_recalls'] }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي عمليات السحب</p>
        </div>

        <!-- Active Recalls -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-play-circle"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $statistics['active_recalls'] }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">عمليات سحب نشطة</p>
        </div>

        <!-- Completed Recalls -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $statistics['completed_recalls'] }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">عمليات سحب مكتملة</p>
        </div>

        <!-- High Risk Recalls -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $statistics['high_risk_recalls'] }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">سحب عالي المخاطر</p>
        </div>

        <!-- Recovery Rate -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-undo"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;">{{ $statistics['recovery_rate'] }}%</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">معدل الاسترداد</p>
        </div>

        <!-- Total Affected Quantity -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px); text-align: center;">
            <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-boxes"></i>
            </div>
            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 24px; font-weight: 700;">{{ number_format($statistics['total_affected_quantity']) }}</h3>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجمالي الكمية المتأثرة</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-bottom: 30px;">
        
        <!-- Recalls by Type Chart -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-chart-pie" style="margin-left: 10px; color: #667eea;"></i>
                توزيع السحب حسب النوع
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @if(isset($statistics['recalls_by_type']))
                    @foreach($statistics['recalls_by_type'] as $type => $count)
                        @php
                            $percentage = $statistics['total_recalls'] > 0 ? round(($count / $statistics['total_recalls']) * 100, 1) : 0;
                            $colors = ['طوعي' => '#48bb78', 'إجباري' => '#f56565', 'سحب من السوق' => '#ed8936'];
                            $color = $colors[$type] ?? '#667eea';
                        @endphp
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 5px;">
                                    <span style="color: #2d3748; font-weight: 600;">{{ $type }}</span>
                                    <span style="color: #4a5568; font-size: 14px;">{{ $count }} ({{ $percentage }}%)</span>
                                </div>
                                <div style="background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden;">
                                    <div style="background: {{ $color }}; height: 100%; width: {{ $percentage }}%; transition: width 0.3s;"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Recalls by Risk Level Chart -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
                <i class="fas fa-chart-bar" style="margin-left: 10px; color: #667eea;"></i>
                توزيع السحب حسب مستوى المخاطر
            </h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @if(isset($statistics['recalls_by_risk_level']))
                    @foreach($statistics['recalls_by_risk_level'] as $level => $count)
                        @php
                            $percentage = $statistics['total_recalls'] > 0 ? round(($count / $statistics['total_recalls']) * 100, 1) : 0;
                            $colors = ['الفئة الأولى' => '#f56565', 'الفئة الثانية' => '#ed8936', 'الفئة الثالثة' => '#ecc94b'];
                            $color = $colors[$level] ?? '#667eea';
                        @endphp
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 5px;">
                                    <span style="color: #2d3748; font-weight: 600;">{{ $level }}</span>
                                    <span style="color: #4a5568; font-size: 14px;">{{ $count }} ({{ $percentage }}%)</span>
                                </div>
                                <div style="background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden;">
                                    <div style="background: {{ $color }}; height: 100%; width: {{ $percentage }}%; transition: width 0.3s;"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Trend Chart -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-chart-line" style="margin-left: 10px; color: #667eea;"></i>
            اتجاه السحب الشهري
        </h3>
        <div style="display: flex; align-items: end; gap: 15px; height: 200px; padding: 20px; background: #f7fafc; border-radius: 15px;">
            @if(isset($statistics['recalls_by_month']))
                @php $maxValue = max($statistics['recalls_by_month']); @endphp
                @foreach($statistics['recalls_by_month'] as $month => $count)
                    @php $height = $maxValue > 0 ? ($count / $maxValue) * 150 : 0; @endphp
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px 8px 0 0; width: 100%; height: {{ $height }}px; display: flex; align-items: end; justify-content: center; color: white; font-weight: 600; padding-bottom: 5px; transition: height 0.3s;">
                            {{ $count }}
                        </div>
                        <span style="color: #4a5568; font-size: 12px; font-weight: 600; text-align: center;">{{ $month }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Recent Recalls -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-clock" style="margin-left: 10px; color: #667eea;"></i>
            أحدث عمليات السحب
        </h3>
        
        @if($statistics['recent_recalls']->isNotEmpty())
            <div style="overflow-x: auto; border-radius: 15px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse; background: white;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <th style="padding: 15px; text-align: right; font-weight: 700;">عنوان السحب</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">المنتج</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">الحالة</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">مستوى المخاطر</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">تاريخ البدء</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">معدل الاسترداد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statistics['recent_recalls'] as $recall)
                            @php
                                $recoveryRate = $recall->affected_quantity > 0 ? round(($recall->recovered_quantity / $recall->affected_quantity) * 100, 1) : 0;
                                $statusColors = ['initiated' => '#ed8936', 'in_progress' => '#4299e1', 'completed' => '#48bb78', 'terminated' => '#718096'];
                                $riskColors = ['class_1' => '#f56565', 'class_2' => '#ed8936', 'class_3' => '#ecc94b'];
                                $statusLabels = ['initiated' => 'بدأ', 'in_progress' => 'قيد التنفيذ', 'completed' => 'مكتمل', 'terminated' => 'منتهي'];
                                $riskLabels = ['class_1' => 'الفئة الأولى', 'class_2' => 'الفئة الثانية', 'class_3' => 'الفئة الثالثة'];
                            @endphp
                            <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.3s;" 
                                onmouseover="this.style.backgroundColor='#f7fafc'" 
                                onmouseout="this.style.backgroundColor='white'">
                                
                                <td style="padding: 15px; color: #2d3748; font-weight: 600;">{{ $recall->recall_title }}</td>
                                <td style="padding: 15px; color: #4a5568;">{{ $recall->product_name }}</td>
                                <td style="padding: 15px;">
                                    <span style="background: {{ $statusColors[$recall->recall_status] ?? '#718096' }}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $statusLabels[$recall->recall_status] ?? $recall->recall_status }}
                                    </span>
                                </td>
                                <td style="padding: 15px;">
                                    <span style="background: {{ $riskColors[$recall->risk_level] ?? '#718096' }}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                        {{ $riskLabels[$recall->risk_level] ?? $recall->risk_level }}
                                    </span>
                                </td>
                                <td style="padding: 15px; color: #4a5568;">{{ $recall->initiation_date->format('Y-m-d') }}</td>
                                <td style="padding: 15px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="flex: 1; background: #e2e8f0; border-radius: 10px; height: 6px; overflow: hidden;">
                                            <div style="background: {{ $recoveryRate >= 75 ? '#48bb78' : ($recoveryRate >= 50 ? '#ed8936' : '#f56565') }}; height: 100%; width: {{ $recoveryRate }}%; transition: width 0.3s;"></div>
                                        </div>
                                        <span style="color: #4a5568; font-size: 12px; font-weight: 600; min-width: 40px;">{{ $recoveryRate }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px; color: #718096;">
                <i class="fas fa-chart-bar" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <p style="margin: 0; font-size: 16px;">لا توجد عمليات سحب حديثة</p>
            </div>
        @endif
    </div>
</div>

@endsection
