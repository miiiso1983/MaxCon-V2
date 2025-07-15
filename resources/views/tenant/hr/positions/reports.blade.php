@extends('layouts.modern')

@section('title', 'تقارير المناصب')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تقارير المناصب</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تقارير شاملة وإحصائيات مفصلة للمناصب الوظيفية</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="exportAllReports()" style="background: #4299e1; color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-download"></i>
                    تصدير جميع التقارير
                </button>
                <a href="{{ route('tenant.hr.positions.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للمناصب
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-briefcase"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">12</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي المناصب</p>
            <div style="margin-top: 10px; font-size: 12px; color: #4299e1;">+2 منصب جديد</div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-user-check"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">45</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المناصب المشغولة</p>
            <div style="margin-top: 10px; font-size: 12px; color: #48bb78;">82% نسبة الإشغال</div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-user-times"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">10</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">المناصب الشاغرة</p>
            <div style="margin-top: 10px; font-size: 12px; color: #f56565;">18% نسبة الشغور</div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">2.8M</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">متوسط الراتب (دينار)</p>
            <div style="margin-top: 10px; font-size: 12px; color: #ed8936;">+5% من العام الماضي</div>
        </div>
    </div>

    <!-- Position Level Distribution -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-layer-group" style="margin-left: 10px; color: #ed8936;"></i>
            توزيع المناصب حسب المستوى
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">

            <!-- Executive Level -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #9f7aea;">
                <div style="background: #9f7aea; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px;">
                    <i class="fas fa-crown"></i>
                </div>
                <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">تنفيذي</h4>
                <div style="color: #9f7aea; font-size: 24px; font-weight: 700; margin-bottom: 5px;">2</div>
                <div style="color: #4a5568; font-size: 14px;">مناصب</div>
                <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 6px;">
                    <div style="background: #9f7aea; border-radius: 10px; height: 100%; width: 100%;"></div>
                </div>
            </div>

            <!-- Manager Level -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #48bb78;">
                <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px;">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">إداري</h4>
                <div style="color: #48bb78; font-size: 24px; font-weight: 700; margin-bottom: 5px;">4</div>
                <div style="color: #4a5568; font-size: 14px;">مناصب</div>
                <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 6px;">
                    <div style="background: #48bb78; border-radius: 10px; height: 100%; width: 100%;"></div>
                </div>
            </div>

            <!-- Senior Level -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #4299e1;">
                <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px;">
                    <i class="fas fa-medal"></i>
                </div>
                <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">أول</h4>
                <div style="color: #4299e1; font-size: 24px; font-weight: 700; margin-bottom: 5px;">3</div>
                <div style="color: #4a5568; font-size: 14px;">مناصب</div>
                <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 6px;">
                    <div style="background: #4299e1; border-radius: 10px; height: 100%; width: 75%;"></div>
                </div>
            </div>

            <!-- Mid Level -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #ed8936;">
                <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px;">
                    <i class="fas fa-user"></i>
                </div>
                <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">متوسط</h4>
                <div style="color: #ed8936; font-size: 24px; font-weight: 700; margin-bottom: 5px;">2</div>
                <div style="color: #4a5568; font-size: 14px;">مناصب</div>
                <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 6px;">
                    <div style="background: #ed8936; border-radius: 10px; height: 100%; width: 50%;"></div>
                </div>
            </div>

            <!-- Junior Level -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #f56565;">
                <div style="background: #f56565; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px;">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">مبتدئ</h4>
                <div style="color: #f56565; font-size: 24px; font-weight: 700; margin-bottom: 5px;">1</div>
                <div style="color: #4a5568; font-size: 14px;">مناصب</div>
                <div style="margin-top: 10px; background: #e2e8f0; border-radius: 10px; height: 6px;">
                    <div style="background: #f56565; border-radius: 10px; height: 100%; width: 25%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Position Analysis -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-building" style="margin-left: 10px; color: #ed8936;"></i>
            تحليل المناصب حسب القسم
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">

            <!-- HR Positions -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #48bb78;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">الموارد البشرية</h4>

                <div style="space-y: 10px;">
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #4a5568; font-size: 14px;">مدير الموارد البشرية</span>
                        <span style="background: #48bb78; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">مشغول</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #4a5568; font-size: 14px;">أخصائي توظيف</span>
                        <span style="background: #48bb78; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">مشغول</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0;">
                        <span style="color: #4a5568; font-size: 14px;">أخصائي رواتب</span>
                        <span style="background: #f56565; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">شاغر</span>
                    </div>
                </div>

                <div style="margin-top: 15px; text-align: center;">
                    <div style="color: #48bb78; font-size: 18px; font-weight: 700;">67%</div>
                    <div style="color: #4a5568; font-size: 12px;">نسبة الإشغال</div>
                </div>
            </div>

            <!-- Finance Positions -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #ed8936;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">المالية والمحاسبة</h4>

                <div style="space-y: 10px;">
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #4a5568; font-size: 14px;">مدير المالية</span>
                        <span style="background: #48bb78; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">مشغول</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #4a5568; font-size: 14px;">محاسب أول</span>
                        <span style="background: #48bb78; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">مشغول</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0;">
                        <span style="color: #4a5568; font-size: 14px;">أمين الصندوق</span>
                        <span style="background: #48bb78; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">مشغول</span>
                    </div>
                </div>

                <div style="margin-top: 15px; text-align: center;">
                    <div style="color: #ed8936; font-size: 18px; font-weight: 700;">100%</div>
                    <div style="color: #4a5568; font-size: 12px;">نسبة الإشغال</div>
                </div>
            </div>

            <!-- Sales Positions -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #4299e1;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">المبيعات والتسويق</h4>

                <div style="space-y: 10px;">
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #4a5568; font-size: 14px;">مدير المبيعات</span>
                        <span style="background: #48bb78; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">مشغول</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #4a5568; font-size: 14px;">مندوب مبيعات</span>
                        <span style="background: #f56565; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">شاغر</span>
                    </div>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 8px 0;">
                        <span style="color: #4a5568; font-size: 14px;">أخصائي تسويق</span>
                        <span style="background: #48bb78; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px;">مشغول</span>
                    </div>
                </div>

                <div style="margin-top: 15px; text-align: center;">
                    <div style="color: #4299e1; font-size: 18px; font-weight: 700;">67%</div>
                    <div style="color: #4a5568; font-size: 12px;">نسبة الإشغال</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-bolt" style="margin-left: 10px; color: #ed8936;"></i>
            إجراءات سريعة
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">

            <button onclick="generateVacancyReport()" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-user-times"></i>
                تقرير الشواغر
            </button>

            <button onclick="generateSalaryReport()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-money-bill-wave"></i>
                تقرير الرواتب
            </button>

            <button onclick="exportToExcel()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>

            <a href="{{ route('tenant.hr.positions.create') }}" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-plus"></i>
                إضافة منصب
            </a>
        </div>
    </div>
</div>

<script>
function exportAllReports() {
    alert('جاري تصدير جميع تقارير المناصب...\n\nسيتم تصدير:\n• تقرير توزيع المناصب\n• تقرير الشواغر\n• تقرير الرواتب\n• تحليل الأداء');
}

function generateVacancyReport() {
    alert('ميزة تقرير الشواغر قيد التطوير\n\nسيتضمن:\n• قائمة المناصب الشاغرة\n• متطلبات كل منصب\n• خطة التوظيف\n• الأولويات');
}

function generateSalaryReport() {
    alert('ميزة تقرير الرواتب قيد التطوير\n\nسيتضمن:\n• نطاقات الرواتب\n• مقارنات السوق\n• تحليل التكاليف\n• توصيات التحسين');
}

function exportToExcel() {
    alert('جاري تصدير التقرير إلى Excel...\n\nسيتم تصدير:\n• بيانات جميع المناصب\n• الإحصائيات والمقاييس\n• التحليلات المقارنة\n• الرسوم البيانية');
}
</script>

@endsection