@extends('layouts.modern')

@section('title', 'تقارير الأقسام')

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
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تقارير الأقسام</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تقارير شاملة وإحصائيات مفصلة للأقسام</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="exportAllReports()" style="background: #4299e1; color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-download"></i>
                    تصدير جميع التقارير
                </button>
                <a href="{{ route('tenant.hr.departments.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للأقسام
                </a>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 25px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 20px 0; font-size: 20px; font-weight: 700;">
            <i class="fas fa-filter" style="margin-left: 10px; color: #ed8936;"></i>
            فلاتر التقارير
        </h3>
        
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">القسم</label>
                <select name="department_id" style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <option value="">جميع الأقسام</option>
                    <option value="1">الإدارة العامة</option>
                    <option value="2">الموارد البشرية</option>
                    <option value="3">المالية والمحاسبة</option>
                    <option value="4">المبيعات والتسويق</option>
                    <option value="5">تقنية المعلومات</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الفترة الزمنية</label>
                <select name="period" style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <option value="current_month">الشهر الحالي</option>
                    <option value="last_month">الشهر الماضي</option>
                    <option value="current_quarter">الربع الحالي</option>
                    <option value="current_year">السنة الحالية</option>
                    <option value="custom">فترة مخصصة</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع التقرير</label>
                <select name="report_type" style="width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <option value="summary">تقرير موجز</option>
                    <option value="detailed">تقرير مفصل</option>
                    <option value="performance">تقرير الأداء</option>
                    <option value="budget">تقرير الميزانية</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-search"></i> تطبيق
                </button>
                <button type="button" onclick="resetFilters()" style="background: #e2e8f0; color: #4a5568; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-undo"></i> إعادة تعيين
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-building"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">4</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي الأقسام</p>
            <div style="margin-top: 10px; font-size: 12px; color: #48bb78;">+0% من الشهر الماضي</div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">55</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي الموظفين</p>
            <div style="margin-top: 10px; font-size: 12px; color: #4299e1;">+8% من الشهر الماضي</div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">125M</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي الرواتب (دينار)</p>
            <div style="margin-top: 10px; font-size: 12px; color: #ed8936;">+5% من الشهر الماضي</div>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">92%</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">متوسط الأداء</p>
            <div style="margin-top: 10px; font-size: 12px; color: #9f7aea;">+3% من الشهر الماضي</div>
        </div>
    </div>

    <!-- Department Performance Chart -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-chart-pie" style="margin-left: 10px; color: #ed8936;"></i>
            أداء الأقسام
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
            
            <!-- HR Department -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #48bb78;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">الموارد البشرية</h4>
                    <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">ممتاز</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الموظفين</span>
                        <span style="color: #2d3748; font-weight: 600;">15</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">نسبة الحضور</span>
                        <span style="color: #2d3748; font-weight: 600;">95%</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">الميزانية المستخدمة</span>
                        <span style="color: #2d3748; font-weight: 600;">85%</span>
                    </div>
                </div>
                
                <div style="background: #e2e8f0; border-radius: 10px; height: 8px; margin-bottom: 10px;">
                    <div style="background: #48bb78; border-radius: 10px; height: 100%; width: 95%;"></div>
                </div>
                <div style="text-align: center; color: #48bb78; font-weight: 600; font-size: 14px;">95% أداء عام</div>
            </div>

            <!-- Finance Department -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #ed8936;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">المالية والمحاسبة</h4>
                    <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">جيد جداً</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الموظفين</span>
                        <span style="color: #2d3748; font-weight: 600;">12</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">نسبة الحضور</span>
                        <span style="color: #2d3748; font-weight: 600;">92%</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">الميزانية المستخدمة</span>
                        <span style="color: #2d3748; font-weight: 600;">78%</span>
                    </div>
                </div>
                
                <div style="background: #e2e8f0; border-radius: 10px; height: 8px; margin-bottom: 10px;">
                    <div style="background: #ed8936; border-radius: 10px; height: 100%; width: 88%;"></div>
                </div>
                <div style="text-align: center; color: #ed8936; font-weight: 600; font-size: 14px;">88% أداء عام</div>
            </div>

            <!-- Sales Department -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #4299e1;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">المبيعات والتسويق</h4>
                    <span style="background: #4299e1; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">ممتاز</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الموظفين</span>
                        <span style="color: #2d3748; font-weight: 600;">20</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">نسبة الحضور</span>
                        <span style="color: #2d3748; font-weight: 600;">97%</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">الميزانية المستخدمة</span>
                        <span style="color: #2d3748; font-weight: 600;">92%</span>
                    </div>
                </div>
                
                <div style="background: #e2e8f0; border-radius: 10px; height: 8px; margin-bottom: 10px;">
                    <div style="background: #4299e1; border-radius: 10px; height: 100%; width: 97%;"></div>
                </div>
                <div style="text-align: center; color: #4299e1; font-weight: 600; font-size: 14px;">97% أداء عام</div>
            </div>

            <!-- IT Department -->
            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; border-right: 4px solid #f56565;">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: #2d3748; margin: 0; font-size: 18px; font-weight: 700;">تقنية المعلومات</h4>
                    <span style="background: #f56565; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">جيد</span>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">عدد الموظفين</span>
                        <span style="color: #2d3748; font-weight: 600;">8</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">نسبة الحضور</span>
                        <span style="color: #2d3748; font-weight: 600;">89%</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">الميزانية المستخدمة</span>
                        <span style="color: #2d3748; font-weight: 600;">65%</span>
                    </div>
                </div>
                
                <div style="background: #e2e8f0; border-radius: 10px; height: 8px; margin-bottom: 10px;">
                    <div style="background: #f56565; border-radius: 10px; height: 100%; width: 82%;"></div>
                </div>
                <div style="text-align: center; color: #f56565; font-weight: 600; font-size: 14px;">82% أداء عام</div>
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
            
            <button onclick="generateDetailedReport()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-file-alt"></i>
                تقرير مفصل
            </button>

            <button onclick="exportToExcel()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </button>

            <button onclick="generatePDF()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-file-pdf"></i>
                تصدير PDF
            </button>

            <button onclick="scheduleReport()" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-clock"></i>
                جدولة التقرير
            </button>

            <button onclick="emailReport()" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-envelope"></i>
                إرسال بالبريد
            </button>

            <a href="{{ route('tenant.hr.departments.chart') }}" style="background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 10px; justify-content: center;">
                <i class="fas fa-sitemap"></i>
                الهيكل التنظيمي
            </a>
        </div>
    </div>
</div>

<script>
function resetFilters() {
    document.querySelector('form').reset();
}

function exportAllReports() {
    alert('جاري تصدير جميع التقارير...\n\nسيتم تصدير:\n• تقرير الأداء العام\n• تقرير الحضور والغياب\n• تقرير الميزانيات\n• تقرير المقارنات');
}

function generateDetailedReport() {
    alert('ميزة التقرير المفصل قيد التطوير\n\nسيتضمن:\n• تحليل مفصل لكل قسم\n• مقارنات زمنية\n• توصيات للتحسين\n• رسوم بيانية تفاعلية');
}

function exportToExcel() {
    alert('جاري تصدير التقرير إلى Excel...\n\nسيتم تصدير:\n• بيانات جميع الأقسام\n• الإحصائيات والمقاييس\n• الرسوم البيانية\n• التحليلات المقارنة');
}

function generatePDF() {
    alert('جاري إنشاء ملف PDF...\n\nسيتضمن:\n• تقرير شامل منسق\n• رسوم بيانية عالية الجودة\n• جداول مفصلة\n• ملخص تنفيذي');
}

function scheduleReport() {
    alert('ميزة جدولة التقارير قيد التطوير\n\nستتيح:\n• جدولة تقارير دورية\n• إرسال تلقائي بالبريد\n• تخصيص المحتوى\n• إشعارات التذكير');
}

function emailReport() {
    alert('ميزة إرسال التقرير بالبريد قيد التطوير\n\nستتيح:\n• إرسال للمدراء\n• تخصيص المحتوى\n• إرفاق ملفات\n• جدولة الإرسال');
}
</script>

@endsection
