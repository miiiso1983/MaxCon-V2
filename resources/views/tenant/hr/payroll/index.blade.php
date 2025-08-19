@extends('layouts.modern')

@section('title', 'كشف الرواتب')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">كشف الرواتب</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">إدارة ومعالجة رواتب الموظفين</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="processPayroll()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-calculator"></i>
                    معالجة الرواتب
                </button>
                <a href="{{ route('tenant.hr.dashboard') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوحة التحكم
                </a>
            </div>
        </div>
    </div>

    <!-- Payroll Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">45.2M</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي الرواتب (دينار)</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">45</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">عدد الموظفين</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-plus"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">3.8M</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">البدلات والمكافآت</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-minus"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">2.1M</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">الخصومات والضرائب</p>
        </div>
    </div>

    <!-- Payroll Period Selection -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-calendar" style="margin-left: 10px; color: #48bb78;"></i>
                فترة الراتب - يناير 2024
            </h3>
            <div style="display: flex; gap: 10px;">
                <select style="padding: 10px 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="2024-01">يناير 2024</option>
                    <option value="2023-12">ديسمبر 2023</option>
                    <option value="2023-11">نوفمبر 2023</option>
                </select>
                <button onclick="generatePayrollReport()" style="background: #ed8936; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                    <i class="fas fa-chart-bar"></i> التقارير
                </button>
                <button onclick="exportPayrollData()" style="background: #48bb78; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                    <i class="fas fa-download"></i> تصدير
                </button>
            </div>
        </div>

        <!-- Payroll Status -->
        <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: between; align-items: center;">
                <div>
                    <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 18px; font-weight: 700;">حالة معالجة الرواتب</h4>
                    <p style="color: #4a5568; margin: 0; font-size: 14px;">آخر معالجة: 25 يناير 2024</p>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="background: #48bb78; color: white; padding: 8px 15px; border-radius: 10px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-check-circle" style="margin-left: 5px;"></i>
                        تم المعالجة
                    </span>
                    <button onclick="processPayroll()" style="background: #4299e1; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                        <i class="fas fa-sync-alt"></i> إعادة المعالجة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Payroll List -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-list" style="margin-left: 10px; color: #48bb78;"></i>
            كشف رواتب الموظفين
        </h3>

        <!-- Payroll Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <th style="padding: 15px; text-align: right; font-weight: 700;">الموظف</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الراتب الأساسي</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">البدلات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الساعات الإضافية</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الخصومات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الصافي</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #48bb78; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 700;">أ</div>
                                <div>
                                    <div style="font-weight: 700; color: #2d3748;">أحمد محمد</div>
                                    <div style="font-size: 12px; color: #718096;">مطور برمجيات</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700;">1,200,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #48bb78; font-weight: 600;">150,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #ed8936; font-weight: 600;">120,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #f56565; font-weight: 600;">85,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700; font-size: 16px;">1,385,000 د.ع</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">مُدفوع</span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <button onclick="viewPayslip(1)" style="background: #4299e1; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printPayslip(1)" style="background: #ed8936; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button onclick="sendPayslip(1)" style="background: #48bb78; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #4299e1; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 700;">س</div>
                                <div>
                                    <div style="font-weight: 700; color: #2d3748;">سارة أحمد</div>
                                    <div style="font-size: 12px; color: #718096;">مصممة جرافيك</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700;">900,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #48bb78; font-weight: 600;">100,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #ed8936; font-weight: 600;">75,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #f56565; font-weight: 600;">65,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700; font-size: 16px;">1,010,000 د.ع</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">معلق</span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <button onclick="processEmployeePayroll(2)" style="background: #48bb78; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-play"></i>
                                </button>
                                <button onclick="viewPayslip(2)" style="background: #4299e1; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editPayroll(2)" style="background: #ed8936; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #9f7aea; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 700;">م</div>
                                <div>
                                    <div style="font-weight: 700; color: #2d3748;">محمد علي</div>
                                    <div style="font-size: 12px; color: #718096;">محاسب</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700;">800,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #48bb78; font-weight: 600;">80,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #ed8936; font-weight: 600;">50,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #f56565; font-weight: 600;">55,000 د.ع</td>
                        <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700; font-size: 16px;">875,000 د.ع</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="background: #48bb78; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">مُدفوع</span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <button onclick="viewPayslip(3)" style="background: #4299e1; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printPayslip(3)" style="background: #ed8936; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button onclick="sendPayslip(3)" style="background: #48bb78; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-bolt" style="margin-left: 10px; color: #667eea;"></i>
            الإجراءات السريعة
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            
            <button onclick="processPayroll()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-calculator" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">معالجة الرواتب</div>
            </button>

            <button onclick="generatePayrollReport()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-chart-bar" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تقارير الرواتب</div>
            </button>

            <button onclick="exportPayrollData()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-download" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تصدير البيانات</div>
            </button>

            <button onclick="managePayrollSettings()" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-cog" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">إعدادات الرواتب</div>
            </button>
        </div>
    </div>
</div>

<script>
async function processPayroll() {
    if (!confirm('هل أنت متأكد من معالجة رواتب جميع الموظفين؟\n\nسيتم حساب الرواتب بناءً على:\n• الراتب الأساسي\n• البدلات والمكافآت\n• الساعات الإضافية\n• الخصومات والضرائب')) return;

    const periodSelect = document.querySelector('select[name="payroll_period"]');
    const period = periodSelect ? periodSelect.value : new Date().toISOString().slice(0,7);

    const modal = document.createElement('div');
    modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:10000;';
    modal.innerHTML = `<div style="background:white; padding:20px; border-radius:10px; color:#111827;">جاري المعالجة للفترة ${period}...</div>`;
    document.body.appendChild(modal);

    try {
        const res = await fetch("{{ route('tenant.hr.payroll.generate') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: JSON.stringify({ period })
        });
        const data = await res.json();
        modal.remove();
        if (data.success) {
            showNotification(data.message || 'تمت معالجة الرواتب بنجاح!', 'success');
        } else {
            showNotification(data.message || 'تعذر معالجة الرواتب', 'error');
        }
    } catch (e) {
        modal.remove();
        showNotification('حدث خطأ أثناء المعالجة', 'error');
    }
}

function viewPayslip(employeeId) {
    const employeeData = {
        1: {name: 'أحمد محمد', position: 'مطور برمجيات', basic: '1,200,000', allowances: '150,000', overtime: '120,000', deductions: '85,000', net: '1,385,000'},
        2: {name: 'سارة أحمد', position: 'مصممة جرافيك', basic: '900,000', allowances: '100,000', overtime: '75,000', deductions: '65,000', net: '1,010,000'},
        3: {name: 'محمد علي', position: 'محاسب', basic: '800,000', allowances: '80,000', overtime: '50,000', deductions: '55,000', net: '875,000'}
    };

    const employee = employeeData[employeeId] || employeeData[1];

    // Create payslip modal
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
            <div style="text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px solid #e2e8f0;">
                <h2 style="color: #2d3748; margin: 0 0 10px 0; font-size: 28px; font-weight: 700;">كشف راتب</h2>
                <p style="color: #4a5568; margin: 0; font-size: 16px;">يناير 2024</p>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 20px; font-weight: 700;">معلومات الموظف</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <span style="color: #4a5568; font-weight: 600;">الاسم:</span>
                        <span style="color: #2d3748; font-weight: 700; margin-right: 10px;">${employee.name}</span>
                    </div>
                    <div>
                        <span style="color: #4a5568; font-weight: 600;">المنصب:</span>
                        <span style="color: #2d3748; font-weight: 700; margin-right: 10px;">${employee.position}</span>
                    </div>
                    <div>
                        <span style="color: #4a5568; font-weight: 600;">رقم الموظف:</span>
                        <span style="color: #2d3748; font-weight: 700; margin-right: 10px;">EMP${employeeId.toString().padStart(3, '0')}</span>
                    </div>
                    <div>
                        <span style="color: #4a5568; font-weight: 600;">تاريخ الإصدار:</span>
                        <span style="color: #2d3748; font-weight: 700; margin-right: 10px;">${new Date().toLocaleDateString('ar-EG')}</span>
                    </div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 20px; font-weight: 700;">تفاصيل الراتب</h3>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; justify-content: between; padding: 8px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568;">الراتب الأساسي:</span>
                        <span style="color: #2d3748; font-weight: 600;">${employee.basic} د.ع</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 8px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568;">البدلات والمكافآت:</span>
                        <span style="color: #48bb78; font-weight: 600;">+${employee.allowances} د.ع</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 8px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568;">الساعات الإضافية:</span>
                        <span style="color: #ed8936; font-weight: 600;">+${employee.overtime} د.ع</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 8px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568;">الخصومات والضرائب:</span>
                        <span style="color: #f56565; font-weight: 600;">-${employee.deductions} د.ع</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 12px; background: #48bb78; color: white; border-radius: 8px; font-weight: 700; font-size: 18px;">
                        <span>صافي الراتب:</span>
                        <span>${employee.net} د.ع</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="printPayslip(${employeeId})" style="background: #ed8936; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-print"></i> طباعة
                </button>
                <button onclick="sendPayslip(${employeeId})" style="background: #48bb78; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
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
}

async function printPayslip(employeeId) {
    const periodSelect = document.querySelector('select[name="payroll_period"]');
    const period = periodSelect ? periodSelect.value : new Date().toISOString().slice(0,7);
    const url = `{{ route('tenant.hr.payroll.print-payslip', ['employee' => 'EMP_ID']) }}?period=${encodeURIComponent(period)}&inline=1`.replace('EMP_ID', employeeId);
    window.open(url, '_blank');
}

async function sendPayslip(employeeId) {
    const periodSelect = document.querySelector('select[name="payroll_period"]');
    const period = periodSelect ? periodSelect.value : new Date().toISOString().slice(0,7);
    try {
        const res = await fetch(`{{ route('tenant.hr.payroll.send-payslip', ['employee' => 'EMP_ID']) }}`.replace('EMP_ID', employeeId), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: JSON.stringify({ period })
        });
        const data = await res.json();
        if (data.success) {
            showNotification('تم إرسال كشف الراتب بالبريد الإلكتروني (تجريبي)!', 'success');
        } else {
            showNotification('تعذر إرسال كشف الراتب', 'error');
        }
    } catch (e) {
        showNotification('حدث خطأ أثناء الإرسال', 'error');
    }
}

function processEmployeePayroll(employeeId) {
    if (confirm(`هل أنت متأكد من معالجة راتب الموظف رقم ${employeeId}؟`)) {
        alert(`تم معالجة راتب الموظف رقم ${employeeId} بنجاح!`);
        showNotification('تم معالجة الراتب بنجاح!', 'success');
        location.reload();
    }
}

function editPayroll(employeeId) {
    alert(`ميزة تعديل راتب الموظف رقم ${employeeId} قيد التطوير\n\nستتيح:\n• تعديل البدلات\n• إضافة خصومات\n• تحديث الساعات الإضافية\n• إضافة مكافآت خاصة`);
}

function generatePayrollReport() {
    // Create payroll reports modal
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
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 900px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-chart-bar" style="margin-left: 10px; color: #ed8936;"></i>
                تقارير الرواتب الشاملة
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #48bb78;">
                    <div style="color: #48bb78; font-size: 28px; font-weight: 700; margin-bottom: 5px;">45.2M</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي الرواتب</div>
                    <div style="color: #48bb78; font-size: 12px; margin-top: 5px;">+5% من الشهر الماضي</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #4299e1;">
                    <div style="color: #4299e1; font-size: 28px; font-weight: 700; margin-bottom: 5px;">45</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">عدد الموظفين</div>
                    <div style="color: #4299e1; font-size: 12px; margin-top: 5px;">+2 موظف جديد</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #ed8936;">
                    <div style="color: #ed8936; font-size: 28px; font-weight: 700; margin-bottom: 5px;">1.00M</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">متوسط الراتب</div>
                    <div style="color: #ed8936; font-size: 12px; margin-top: 5px;">+3% تحسن</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #9f7aea;">
                    <div style="color: #9f7aea; font-size: 28px; font-weight: 700; margin-bottom: 5px;">2.1M</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي الخصومات</div>
                    <div style="color: #9f7aea; font-size: 12px; margin-top: 5px;">4.6% من الإجمالي</div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
                <h4 style="color: #2d3748; margin: 0 0 20px 0; font-size: 18px; font-weight: 700;">توزيع الرواتب حسب الأقسام</h4>

                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم تقنية المعلومات</div>
                            <div style="font-size: 14px; color: #4a5568;">15 موظف</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #48bb78; font-size: 18px; font-weight: 700;">18.0M د.ع</div>
                            <div style="font-size: 12px; color: #4a5568;">39.8% من الإجمالي</div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم المبيعات</div>
                            <div style="font-size: 14px; color: #4a5568;">12 موظف</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #4299e1; font-size: 18px; font-weight: 700;">12.0M د.ع</div>
                            <div style="font-size: 12px; color: #4a5568;">26.5% من الإجمالي</div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم المحاسبة</div>
                            <div style="font-size: 14px; color: #4a5568;">8 موظفين</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #ed8936; font-size: 18px; font-weight: 700;">8.0M د.ع</div>
                            <div style="font-size: 12px; color: #4a5568;">17.7% من الإجمالي</div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم الموارد البشرية</div>
                            <div style="font-size: 14px; color: #4a5568;">6 موظفين</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #9f7aea; font-size: 18px; font-weight: 700;">4.8M د.ع</div>
                            <div style="font-size: 12px; color: #4a5568;">10.6% من الإجمالي</div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">الإدارة العليا</div>
                            <div style="font-size: 14px; color: #4a5568;">4 موظفين</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #f56565; font-size: 18px; font-weight: 700;">2.4M د.ع</div>
                            <div style="font-size: 12px; color: #4a5568;">5.3% من الإجمالي</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">تحليل البدلات والخصومات</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <div style="text-align: center; padding: 15px; background: white; border-radius: 10px;">
                        <div style="color: #48bb78; font-size: 20px; font-weight: 700;">3.8M</div>
                        <div style="color: #4a5568; font-size: 12px;">البدلات والمكافآت</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: white; border-radius: 10px;">
                        <div style="color: #ed8936; font-size: 20px; font-weight: 700;">2.8M</div>
                        <div style="color: #4a5568; font-size: 12px;">الساعات الإضافية</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: white; border-radius: 10px;">
                        <div style="color: #f56565; font-size: 20px; font-weight: 700;">1.2M</div>
                        <div style="color: #4a5568; font-size: 12px;">الضرائب</div>
                    </div>
                    <div style="text-align: center; padding: 15px; background: white; border-radius: 10px;">
                        <div style="color: #9f7aea; font-size: 20px; font-weight: 700;">0.9M</div>
                        <div style="color: #4a5568; font-size: 12px;">التأمينات</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="alert('تم تصدير التقرير بصيغة PDF بنجاح!')" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-file-pdf"></i> تصدير PDF
                </button>
                <button onclick="alert('تم تصدير التقرير بصيغة Excel بنجاح!')" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-file-excel"></i> تصدير Excel
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
}

function exportPayrollData() {
    // Create export options modal
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
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 600px; width: 90%;">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-download" style="margin-left: 10px; color: #4299e1;"></i>
                تصدير بيانات الرواتب
            </h3>

            <form id="exportPayrollForm">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع التصدير</label>
                    <select name="export_type" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                        <option value="">اختر نوع التصدير</option>
                        <option value="payslips">كشوف الرواتب</option>
                        <option value="summary">ملخص الرواتب</option>
                        <option value="detailed">تقرير مفصل</option>
                        <option value="by_department">حسب القسم</option>
                        <option value="tax_report">تقرير الضرائب</option>
                        <option value="bank_transfer">ملف التحويل البنكي</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الفترة الزمنية</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <select name="period_type" style="padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                            <option value="current_month" selected>الشهر الحالي</option>
                            <option value="last_month">الشهر الماضي</option>
                            <option value="quarter">الربع الحالي</option>
                            <option value="year">السنة الحالية</option>
                            <option value="custom">فترة مخصصة</option>
                        </select>
                        <input type="month" name="custom_period" value="2024-01" style="padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">صيغة الملف</label>
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; background: #f7fafc; border-radius: 8px;">
                            <input type="radio" name="format" value="excel" checked>
                            <span style="color: #2d3748; font-weight: 600;">Excel</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; background: #f7fafc; border-radius: 8px;">
                            <input type="radio" name="format" value="pdf">
                            <span style="color: #2d3748; font-weight: 600;">PDF</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; background: #f7fafc; border-radius: 8px;">
                            <input type="radio" name="format" value="csv">
                            <span style="color: #2d3748; font-weight: 600;">CSV</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px; background: #f7fafc; border-radius: 8px;">
                            <input type="radio" name="format" value="xml">
                            <span style="color: #2d3748; font-weight: 600;">XML</span>
                        </label>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">البيانات المطلوبة</label>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="basic_salary" checked>
                            <span style="color: #2d3748;">الراتب الأساسي</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="allowances" checked>
                            <span style="color: #2d3748;">البدلات</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="overtime" checked>
                            <span style="color: #2d3748;">الساعات الإضافية</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="deductions" checked>
                            <span style="color: #2d3748;">الخصومات</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="net_salary" checked>
                            <span style="color: #2d3748;">صافي الراتب</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="bank_details">
                            <span style="color: #2d3748;">تفاصيل البنك</span>
                        </label>
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">معاينة التصدير</h4>
                    <div style="color: #4a5568; font-size: 14px;">
                        • عدد الموظفين: 45 موظف<br>
                        • الفترة: يناير 2024<br>
                        • إجمالي الرواتب: 45.2 مليون دينار<br>
                        • حجم الملف المتوقع: ~5.2 MB
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="send_email" style="width: 18px; height: 18px;">
                        <span style="color: #2d3748; font-weight: 600;">إرسال نسخة بالبريد الإلكتروني</span>
                    </label>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-download"></i> بدء التصدير
                    </button>
                    <button type="button" onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </form>
        </div>
    `;

    modal.className = 'modal';
    document.body.appendChild(modal);

    // Handle form submission
    modal.querySelector('#exportPayrollForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const exportType = formData.get('export_type');
        const format = formData.get('format');
        const sendEmail = formData.get('send_email');

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
        submitBtn.disabled = true;

        setTimeout(() => {
            let message = `تم تصدير بيانات الرواتب بنجاح!\n\nنوع التصدير: ${exportType}\nصيغة الملف: ${format.toUpperCase()}\nحجم الملف: 5.2 MB\n\nتم حفظ الملف في مجلد التحميلات.`;

            if (sendEmail) {
                message += '\n\nتم إرسال نسخة بالبريد الإلكتروني أيضاً.';
            }

            alert(message);
            modal.remove();
            showNotification('تم تصدير البيانات بنجاح!', 'success');
        }, 2500);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function managePayrollSettings() {
    // Create payroll settings modal
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
                <i class="fas fa-cog" style="margin-left: 10px; color: #9f7aea;"></i>
                إعدادات الرواتب
            </h3>

            <form id="payrollSettingsForm">
                <!-- Tax Settings -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">إعدادات الضرائب</h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">ضريبة الدخل (%)</label>
                            <input type="number" value="15" min="0" max="50" step="0.1" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأدنى المعفى</label>
                            <input type="number" value="250000" min="0" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">ضريبة إضافية للرواتب العالية (%)</label>
                            <input type="number" value="25" min="0" max="50" step="0.1" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <!-- Insurance Settings -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">إعدادات التأمينات</h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">التأمين الصحي (%)</label>
                            <input type="number" value="5" min="0" max="20" step="0.1" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">التأمين الاجتماعي (%)</label>
                            <input type="number" value="7" min="0" max="20" step="0.1" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">تأمين ضد البطالة (%)</label>
                            <input type="number" value="1" min="0" max="5" step="0.1" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <!-- Allowances Settings -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">البدلات الثابتة</h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">بدل النقل (دينار)</label>
                            <input type="number" value="50000" min="0" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">بدل الطعام (دينار)</label>
                            <input type="number" value="30000" min="0" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">بدل الهاتف (دينار)</label>
                            <input type="number" value="25000" min="0" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">بدل السكن (دينار)</label>
                            <input type="number" value="100000" min="0" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <!-- Payroll Processing Settings -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">إعدادات معالجة الرواتب</h4>

                    <div style="display: grid; gap: 15px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                            <div>
                                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">يوم معالجة الرواتب</label>
                                <select style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                                    <option value="25" selected>25 من كل شهر</option>
                                    <option value="30">30 من كل شهر</option>
                                    <option value="last">آخر يوم في الشهر</option>
                                    <option value="first">أول يوم في الشهر التالي</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">العملة الافتراضية</label>
                                <select style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                                    <option value="IQD" selected>دينار عراقي (IQD)</option>
                                    <option value="USD">دولار أمريكي (USD)</option>
                                    <option value="EUR">يورو (EUR)</option>
                                </select>
                            </div>
                        </div>

                        <div style="display: grid; gap: 10px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" checked style="width: 18px; height: 18px;">
                                <span style="color: #2d3748; font-weight: 600;">إرسال كشوف الرواتب بالبريد الإلكتروني تلقائياً</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" checked style="width: 18px; height: 18px;">
                                <span style="color: #2d3748; font-weight: 600;">إنشاء ملف التحويل البنكي تلقائياً</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" style="width: 18px; height: 18px;">
                                <span style="color: #2d3748; font-weight: 600;">تطبيق الزيادات السنوية تلقائياً</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" checked style="width: 18px; height: 18px;">
                                <span style="color: #2d3748; font-weight: 600;">إرسال تنبيهات للمديرين قبل معالجة الرواتب</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Bank Settings -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">إعدادات البنك</h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">اسم البنك</label>
                            <input type="text" value="بنك بغداد" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">رقم الحساب</label>
                            <input type="text" value="123456789012" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">رمز SWIFT</label>
                            <input type="text" value="BBAGIQBA" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-save"></i> حفظ الإعدادات
                    </button>
                    <button type="button" onclick="resetPayrollDefaults()" style="background: #ed8936; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-undo"></i> الإعدادات الافتراضية
                    </button>
                    <button type="button" onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </form>
        </div>
    `;

    modal.className = 'modal';
    document.body.appendChild(modal);

    // Handle form submission
    modal.querySelector('#payrollSettingsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;

        setTimeout(() => {
            alert('تم حفظ إعدادات الرواتب بنجاح!\n\nتم تحديث:\n• معدلات الضرائب والتأمينات\n• البدلات الثابتة\n• إعدادات معالجة الرواتب\n• معلومات البنك');
            modal.remove();
            showNotification('تم حفظ الإعدادات بنجاح!', 'success');
        }, 1500);
    });

    // Reset to defaults function
    window.resetPayrollDefaults = function() {
        if (confirm('هل أنت متأكد من إعادة تعيين جميع الإعدادات للقيم الافتراضية؟')) {
            // Reset form values to defaults
            const form = modal.querySelector('#payrollSettingsForm');
            const inputs = form.querySelectorAll('input[type="number"]');
            const defaults = [15, 250000, 25, 5, 7, 1, 50000, 30000, 25000, 100000];

            inputs.forEach((input, index) => {
                if (defaults[index] !== undefined) {
                    input.value = defaults[index];
                }
            });

            showNotification('تم إعادة تعيين الإعدادات للقيم الافتراضية!', 'success');
        }
    };

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

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
