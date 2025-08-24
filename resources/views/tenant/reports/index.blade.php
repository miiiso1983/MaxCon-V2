@extends('layouts.modern')

@section('title', 'نظام التقارير الديناميكي')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 15px 0; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-chart-line"></i>
            نظام التقارير الديناميكي
        </h1>
        <p style="font-size: 18px; opacity: 0.9; margin: 0;">
            تقارير قابلة للتخصيص مع إمكانيات تصدير متقدمة - Excel، PDF، طباعة، إرسال بالبريد
        </p>
    </div>
    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"rgba(255,255,255,0.1)\"/></svg>') repeat; animation: float 20s infinite linear;"></div>
</div>

<!-- Quick Actions -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
    <button onclick="openReportBuilder()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; transition: all 0.3s ease; text-align: center;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
        <i class="fas fa-plus" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
        <span style="font-weight: 600;">إنشاء تقرير جديد</span>
    </button>

    <button onclick="showReportHistory()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; transition: all 0.3s ease; text-align: center;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
        <i class="fas fa-history" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
        <span style="font-weight: 600;">سجل التقارير</span>
    </button>

    <button onclick="showScheduledReports()" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; transition: all 0.3s ease; text-align: center;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
        <i class="fas fa-calendar-alt" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
        <span style="font-weight: 600;">التقارير المجدولة</span>
    </button>

    <button onclick="showExportOptions()" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; transition: all 0.3s ease; text-align: center;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
        <i class="fas fa-download" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
        <span style="font-weight: 600;">خيارات التصدير</span>
    </button>
</div>

<!-- Quick Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <div style="font-size: 36px; font-weight: 800; margin-bottom: 10px;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">15</div>
        <div style="opacity: 0.9;">تقرير متاح</div>
    </div>

    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <div style="font-size: 36px; font-weight: 800; margin-bottom: 10px;">
            <i class="fas fa-download"></i>
        </div>
        <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">5</div>
        <div style="opacity: 0.9;">تنسيق تصدير</div>
    </div>

    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <div style="font-size: 36px; font-weight: 800; margin-bottom: 10px;">
            <i class="fas fa-clock"></i>
        </div>
        <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">24/7</div>
        <div style="opacity: 0.9;">متاح دائماً</div>
    </div>

    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <div style="font-size: 36px; font-weight: 800; margin-bottom: 10px;">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div style="font-size: 24px; font-weight: 700; margin-bottom: 5px;">100%</div>
        <div style="opacity: 0.9;">آمن ومحمي</div>
    </div>
</div>

<!-- Predefined Reports -->
<div style="margin-bottom: 30px;">
    <h2 style="font-size: 24px; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-star" style="color: #f59e0b;"></i>
        التقارير المحددة مسبقاً
    </h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px;">

        <!-- Sales Reports -->
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #10b981;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-shopping-bag" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #065f46; margin: 0;">تقارير المبيعات</h3>
                    <p style="color: #047857; margin: 5px 0 0 0;">مبيعات، عملاء، مندوبين، أرباح</p>
                </div>
            </div>

            <div style="display: grid; gap: 10px;">
                <button onclick="generateReport('تقرير_المبيعات_اليومية')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                    <i class="fas fa-chart-bar" style="margin-left: 8px;"></i>
                    تقرير المبيعات اليومية
                </button>
                <button onclick="generateReport('تقرير_أداء_المندوبين')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                    <i class="fas fa-users" style="margin-left: 8px;"></i>
                    تقرير أداء المندوبين
                </button>
                <button onclick="generateReport('تحليل_الأرباح')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                    <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                    تحليل الأرباح والخسائر
                </button>
                <button onclick="generateReport('العملاء_الأكثر_شراءً')" style="background: #f0fff4; border: 1px solid #10b981; color: #065f46; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fff4'">
                    <i class="fas fa-star" style="margin-left: 8px;"></i>
                    العملاء الأكثر شراءً
                </button>
            </div>
        </div>

        <!-- Financial Reports -->
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #ef4444;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-calculator" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #7f1d1d; margin: 0;">التقارير المالية</h3>
                    <p style="color: #dc2626; margin: 5px 0 0 0;">تدفقات، ذمم، تكاليف، ميزانيات</p>
                </div>
            </div>

            <div style="display: grid; gap: 10px;">
                <button onclick="generateReport('تقرير_التدفقات_النقدية')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <i class="fas fa-money-bill-wave" style="margin-left: 8px;"></i>
                    تقرير التدفقات النقدية
                </button>
                <button onclick="generateReport('الذمم_المدينة')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <i class="fas fa-file-invoice-dollar" style="margin-left: 8px;"></i>
                    تقرير الذمم المدينة
                </button>
                <button onclick="generateReport('تحليل_التكاليف')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <i class="fas fa-chart-pie" style="margin-left: 8px;"></i>
                    تحليل التكاليف
                </button>
                <button onclick="generateReport('الميزانية_العمومية')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                    <i class="fas fa-balance-scale" style="margin-left: 8px;"></i>
                    الميزانية العمومية
                </button>
            </div>
        </div>

        <!-- Inventory Reports -->
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #3b82f6;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-warehouse" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">تقارير المخزون</h3>
                    <p style="color: #1d4ed8; margin: 5px 0 0 0;">حركة، تقييم، نفاد، جرد</p>
                </div>
            </div>

            <div style="display: grid; gap: 10px;">
                <button onclick="generateReport('تقرير_مستويات_المخزون')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    <i class="fas fa-boxes" style="margin-left: 8px;"></i>
                    تقرير مستويات المخزون
                </button>
                <button onclick="generateReport('حركات_المخزون')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    <i class="fas fa-exchange-alt" style="margin-left: 8px;"></i>
                    تقرير حركات المخزون
                </button>
                <button onclick="generateReport('تقييم_المخزون')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    <i class="fas fa-calculator" style="margin-left: 8px;"></i>
                    تقرير تقييم المخزون
                </button>
                <button onclick="generateReport('تنبيهات_النفاد')" style="background: #eff6ff; border: 1px solid #3b82f6; color: #1e3a8a; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                    <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
                    تنبيهات نفاد المخزون
                </button>
            </div>
        </div>

        <!-- Products Reports -->
        <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #8b5cf6;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                    <i class="fas fa-tags" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #581c87; margin: 0;">تقارير المنتجات</h3>
                    <p style="color: #7c3aed; margin: 5px 0 0 0;">أداء، مبيعات، ربحية</p>
                </div>
            </div>

            <div style="display: grid; gap: 10px;">
                <button onclick="generateReport('المنتجات_الأكثر_مبيعاً')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                    <i class="fas fa-star" style="margin-left: 8px;"></i>
                    المنتجات الأكثر مبيعاً
                </button>
                <button onclick="generateReport('تحليل_ربحية_المنتجات')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                    <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                    تحليل ربحية المنتجات
                </button>
                <button onclick="generateReport('أداء_الفئات')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                    <i class="fas fa-layer-group" style="margin-left: 8px;"></i>
                    تقرير أداء الفئات
                </button>
                <button onclick="generateReport('المنتجات_البطيئة')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                    <i class="fas fa-turtle" style="margin-left: 8px;"></i>
                    المنتجات بطيئة الحركة
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Financial Reports -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #ef4444;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-calculator" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #7f1d1d; margin: 0;">التقارير المالية</h3>
                <p style="color: #dc2626; margin: 5px 0 0 0;">تقارير الحسابات والقوائم المالية</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="generateReport('trial_balance')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-balance-scale" style="margin-left: 8px;"></i>
                ميزان المراجعة
            </button>
            <button onclick="generateReport('income_statement')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-chart-line" style="margin-left: 8px;"></i>
                قائمة الدخل
            </button>
            <button onclick="generateReport('balance_sheet')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-file-alt" style="margin-left: 8px;"></i>
                الميزانية العمومية
            </button>
            <button onclick="generateReport('cash_flow')" style="background: #fef2f2; border: 1px solid #ef4444; color: #7f1d1d; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                <i class="fas fa-money-bill-wave" style="margin-left: 8px;"></i>
                التدفق النقدي
            </button>
        </div>
    </div>

    <!-- HR Reports -->
    <div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 2px solid #8b5cf6;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                <i class="fas fa-users" style="font-size: 24px;"></i>
            </div>
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #581c87; margin: 0;">تقارير الموارد البشرية</h3>
                <p style="color: #7c3aed; margin: 5px 0 0 0;">تقارير الموظفين والحضور والرواتب</p>
            </div>
        </div>

        <div style="display: grid; gap: 10px;">
            <button onclick="generateReport('employees_report')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-id-card" style="margin-left: 8px;"></i>
                تقرير الموظفين
            </button>
            <button onclick="generateReport('attendance_report')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-clock" style="margin-left: 8px;"></i>
                تقرير الحضور والانصراف
            </button>
            <button onclick="generateReport('payroll_report')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-money-check-alt" style="margin-left: 8px;"></i>
                كشف الرواتب
            </button>
            <button onclick="generateReport('leave_report')" style="background: #faf5ff; border: 1px solid #8b5cf6; color: #581c87; padding: 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; text-align: right;" onmouseover="this.style.background='#e9d5ff'" onmouseout="this.style.background='#faf5ff'">
                <i class="fas fa-calendar-times" style="margin-left: 8px;"></i>
                تقرير الإجازات
            </button>
        </div>
    </div>
</div>

<!-- Export Options -->
<div style="background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 30px;">
    <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0 0 20px 0; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-download" style="color: #667eea;"></i>
        خيارات التصدير
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <button onclick="exportReport('pdf')" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; justify-content: center;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-file-pdf"></i>
            تصدير PDF
        </button>

        <button onclick="exportReport('excel')" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; justify-content: center;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-file-excel"></i>
            تصدير Excel
        </button>

        <button onclick="exportReport('csv')" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; justify-content: center;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-file-csv"></i>
            تصدير CSV
        </button>

        <button onclick="exportReport('print')" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; justify-content: center;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-print"></i>
            طباعة
        </button>

        <button onclick="exportReport('email')" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 15px; border: none; border-radius: 10px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; justify-content: center;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-envelope"></i>
            إرسال بالإيميل
        </button>
    </div>
</div>

<script>
let currentExecution = null;
let reportParameters = {};

function generateReport(reportType) {
    // Show parameters modal first
    showParametersModal(reportType);
}

function showParametersModal(reportType) {
    const modalContent = `
        <div id="parametersModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0; display: flex; align-items: center;">
                        <i class="fas fa-filter" style="color: #667eea; margin-left: 10px;"></i>
                        معايير التقرير
                    </h3>
                    <button onclick="closeParametersModal()" style="background: none; border: none; font-size: 24px; color: #a0aec0; cursor: pointer; padding: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 18px; font-weight: 600; color: #4a5568; margin-bottom: 15px;">${reportType.replace(/_/g, ' ')}</h4>
                </div>

                <!-- Date Range -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">الفترة الزمنية:</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="date" id="dateFrom" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <input type="date" id="dateTo" style="padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    </div>
                </div>

                <!-- Format Selection -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 8px;">تنسيق التقرير:</label>
                    <select id="reportFormat" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="html">عرض في المتصفح</option>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button onclick="executeReport('${reportType}')" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-play"></i>
                        تشغيل التقرير
                    </button>
                    <button onclick="closeParametersModal()" style="background: #e2e8f0; color: #4a5568; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalContent);

    // Set default dates
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    document.getElementById('dateFrom').value = firstDay.toISOString().split('T')[0];
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
}

function closeParametersModal() {
    const modal = document.getElementById('parametersModal');
    if (modal) {
        modal.remove();
    }
}

function executeReport(reportType) {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const format = document.getElementById('reportFormat').value;

    reportParameters = {
        date_from: dateFrom,
        date_to: dateTo,
        report_type: reportType
    };

    closeParametersModal();

    // Show loading
    showLoadingModal();

    // Simulate API call
    fetch(`/tenant/reports/generate/${reportType}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            parameters: reportParameters,
            format: format
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingModal();
        if (data.success) {
            showReportResults(data, format);
        } else {
            alert('خطأ في تشغيل التقرير: ' + data.error);
        }
    })
    .catch(error => {
        hideLoadingModal();
        alert('خطأ في الاتصال: ' + error.message);
    });
}

function showLoadingModal() {
    const loadingContent = `
        <div id="loadingModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10001; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 20px; padding: 40px; text-align: center;">
                <div style="font-size: 48px; color: #667eea; margin-bottom: 20px;">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <h3 style="font-size: 20px; font-weight: 600; color: #2d3748; margin-bottom: 10px;">جاري تشغيل التقرير...</h3>
                <p style="color: #718096;">يرجى الانتظار حتى اكتمال المعالجة</p>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', loadingContent);
}

function hideLoadingModal() {
    const modal = document.getElementById('loadingModal');
    if (modal) {
        modal.remove();
    }
}

function showReportResults(data, format) {
    if (format === 'html') {
        showReportModal(data);
    } else {
        // For other formats, show download link
        alert(`تم إنشاء التقرير بنجاح!\n\nالتقرير: ${data.report_name}\nعدد السجلات: ${data.metadata.row_count}\nوقت التنفيذ: ${data.metadata.execution_time} ثانية\n\nسيتم تحميل الملف تلقائياً.`);
    }
}

function showReportModal(data) {
    const modalContent = `
        <div id="reportModal" style="display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 20px; padding: 30px; max-width: 95%; width: 1200px; max-height: 90vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e2e8f0;">
                    <h3 style="font-size: 24px; font-weight: 700; color: #2d3748; margin: 0;">
                        ${data.report_name || 'نتائج التقرير'}
                    </h3>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button onclick="exportCurrentReport('pdf')" style="background: #ef4444; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                        <button onclick="exportCurrentReport('excel')" style="background: #10b981; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button onclick="printReport()" style="background: #3b82f6; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer;">
                            <i class="fas fa-print"></i> طباعة
                        </button>
                        <button onclick="closeReportModal()" style="background: none; border: none; font-size: 24px; color: #a0aec0; cursor: pointer; padding: 5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Report Metadata -->
                <div style="background: #f8fafc; padding: 15px; border-radius: 10px; margin-bottom: 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <strong>عدد السجلات:</strong> ${data.metadata.row_count}
                    </div>
                    <div>
                        <strong>وقت التنفيذ:</strong> ${data.metadata.execution_time} ثانية
                    </div>
                    <div>
                        <strong>تاريخ الإنشاء:</strong> ${new Date().toLocaleString('ar-EG')}
                    </div>
                </div>

                <!-- Report Data Table -->
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #e2e8f0;">
                        <thead>
                            <tr style="background: #f8fafc;">
                                ${generateTableHeaders(data.data[0] || {})}
                            </tr>
                        </thead>
                        <tbody>
                            ${generateTableRows(data.data)}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalContent);
}

function generateTableHeaders(sampleRow) {
    return Object.keys(sampleRow).map(key =>
        `<th style="padding: 12px; text-align: right; border: 1px solid #e2e8f0; font-weight: 600; color: #2d3748;">${key}</th>`
    ).join('');
}

function generateTableRows(data) {
    return data.map(row =>
        `<tr style="border-bottom: 1px solid #e2e8f0;">
            ${Object.values(row).map(value =>
                `<td style="padding: 12px; border: 1px solid #e2e8f0;">${value || '-'}</td>`
            ).join('')}
        </tr>`
    ).join('');
}

function closeReportModal() {
    const modal = document.getElementById('reportModal');
    if (modal) {
        modal.remove();
    }
}

function exportCurrentReport(format) {
    alert(`تصدير التقرير بصيغة ${format}...\n\nسيتم تحميل الملف قريباً.`);
}

function printReport() {
    window.print();
}

function openReportBuilder() {
    // الانتقال مباشرةً إلى واجهة منشئ التقارير المخصص
    window.location.href = '{{ route("tenant.reports.builder") }}';
}

function showReportHistory() {
    alert('📋 سجل التقارير\n\nسيتم عرض:\n• التقارير المنفذة مؤخراً\n• حالة التنفيذ\n• إمكانية إعادة تشغيل التقارير\n• تحميل النتائج السابقة');
}

function showScheduledReports() {
    alert('📅 التقارير المجدولة\n\nسيتم عرض:\n• التقارير المجدولة للتشغيل التلقائي\n• إعدادات التكرار\n• المستلمين\n• إدارة الجدولة');
}

function showExportOptions() {
    alert('📤 خيارات التصدير المتقدمة\n\nالتنسيقات المدعومة:\n• PDF - للطباعة والأرشفة\n• Excel - للتحليل والمعالجة\n• CSV - للاستيراد في أنظمة أخرى\n• HTML - للعرض في المتصفح\n• إرسال بالبريد الإلكتروني');
}

// Add animation effects
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Dynamic Reports System loaded successfully!');

    // Add CSRF token to meta if not exists
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
});
</script>

@endsection