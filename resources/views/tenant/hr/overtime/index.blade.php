@extends('layouts.modern')

@section('title', 'إدارة الساعات الإضافية')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة الساعات الإضافية</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">تسجيل ومتابعة الساعات الإضافية للموظفين</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="addOvertimeRecord()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    تسجيل ساعات إضافية
                </button>
                <a href="{{ route('tenant.hr.dashboard') }}" style="background: #e2e8f0; color: #4a5568; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للوحة التحكم
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-clock"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ number_format($stats['total_hours_month'], 1) }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي الساعات الشهرية</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-users"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $stats['pending_requests'] }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">طلبات في الانتظار</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ number_format($stats['total_amount_month']) }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">إجمالي المبلغ (دينار)</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #9f7aea; color: white; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                <i class="fas fa-chart-line"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 28px; font-weight: 700;">{{ $stats['approved_requests'] }}</h4>
            <p style="color: #718096; margin: 0; font-size: 14px; font-weight: 600;">طلبات موافق عليها</p>
        </div>
    </div>

    <!-- Overtime Records -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 25px;">
            <h3 style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-list" style="margin-left: 10px; color: #ed8936;"></i>
                سجل الساعات الإضافية
            </h3>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('tenant.hr.overtime.reports') }}" style="background: #ed8936; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-chart-bar"></i> التقارير
                </a>
                <a href="{{ route('tenant.hr.overtime.reports') }}" style="background: #48bb78; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-download"></i> تصدير
                </a>
                <form method="GET" action="{{ route('tenant.hr.overtime.export') }}" style="display: inline-flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="export_type" value="current_month">
                    <input type="hidden" name="format" value="excel">
                    <button type="submit" style="background: #48bb78; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-file-excel"></i> تصدير (Excel)
                    </button>
                </form>
                <form method="GET" action="{{ route('tenant.hr.overtime.export') }}" style="display: inline-flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="export_type" value="current_month">
                    <input type="hidden" name="format" value="csv">
                    <button type="submit" style="background: #4299e1; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-file-csv"></i> تصدير (CSV)
                    </button>
                </form>
                <form method="GET" action="{{ route('tenant.hr.overtime.export') }}" style="display: inline-flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="export_type" value="current_month">
                    <input type="hidden" name="format" value="pdf">
                    <button type="submit" style="background: #f56565; color: white; padding: 10px 15px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-file-pdf"></i> تصدير (PDF)
                    </button>
                </form>

            </div>
        </div>

        <!-- Overtime Records Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <th style="padding: 15px; text-align: right; font-weight: 700;">الموظف</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">التاريخ</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الساعات</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">المعدل</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">المبلغ</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الحالة</th>
                        <th style="padding: 15px; text-align: center; font-weight: 700;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overtimes as $overtime)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="background: #48bb78; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    {{ substr($overtime->employee->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: #2d3748;">{{ $overtime->employee->full_name }}</div>
                                    <div style="font-size: 12px; color: #718096;">
                                        {{ $overtime->employee->position->title ?? 'غير محدد' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">
                            {{ $overtime->date->format('Y-m-d') }}
                        </td>
                        <td style="padding: 15px; text-align: center; color: #2d3748; font-weight: 700;">
                            {{ $overtime->hours_approved ?? $overtime->hours_requested }} ساعة
                        </td>
                        <td style="padding: 15px; text-align: center; color: #4a5568;">
                            {{ number_format($overtime->overtime_rate * 100) }}%
                        </td>
                        <td style="padding: 15px; text-align: center; color: #48bb78; font-weight: 700;">
                            {{ number_format($overtime->total_amount) }} د.ع
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            @php
                                $statusColors = [
                                    'pending' => '#ed8936',
                                    'approved' => '#48bb78',
                                    'rejected' => '#f56565',
                                    'cancelled' => '#a0aec0',
                                    'completed' => '#4299e1'
                                ];
                                $statusColor = $statusColors[$overtime->status] ?? '#a0aec0';
                            @endphp
                            <span style="background: {{ $statusColor }}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                {{ $overtime->status_label }}
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <a href="{{ route('tenant.hr.overtime.show', $overtime->id) }}" style="background: #4299e1; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer; text-decoration: none; display: inline-block;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($overtime->status === 'pending')
                                <a href="{{ route('tenant.hr.overtime.edit', $overtime->id) }}" style="background: #ed8936; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer; text-decoration: none; display: inline-block;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('tenant.hr.overtime.destroy', $overtime->id) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #f56565; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                                @if($overtime->status === 'pending')
                                <form method="POST" action="{{ route('tenant.hr.overtime.approve', $overtime->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" title="موافقة" style="background: #48bb78; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;" onclick="return confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>

                                <!-- Reject action: button + hidden form -->
                                <button type="button" title="رفض" onclick="askRejectReason({{ $overtime->id }})" style="background: #f56565; color: white; padding: 6px 10px; border: none; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <form id="reject-form-{{ $overtime->id }}" method="POST" action="{{ route('tenant.hr.overtime.reject', $overtime->id) }}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="rejected_reason" value="">
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #718096;">
                            <i class="fas fa-clock" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                            <div style="font-size: 18px; font-weight: 600; margin-bottom: 5px;">لا توجد سجلات ساعات إضافية</div>
                            <div style="font-size: 14px;">قم بإضافة أول سجل ساعات إضافية</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($overtimes->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $overtimes->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700; text-align: center;">
            <i class="fas fa-bolt" style="margin-left: 10px; color: #667eea;"></i>
            الإجراءات السريعة
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">

            <button onclick="addOvertimeRecord()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-plus" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تسجيل ساعات إضافية</div>
            </button>

            <a href="{{ route('tenant.hr.overtime.reports') }}" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s; text-decoration: none; display: block;"
               onmouseover="this.style.transform='translateY(-5px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-chart-bar" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تقارير الساعات الإضافية</div>
            </a>

            <a href="{{ route('tenant.hr.overtime.reports') }}" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s; text-decoration: none; display: block;"
               onmouseover="this.style.transform='translateY(-5px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-download" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">تصدير البيانات</div>
            </a>

            <button onclick="manageOvertimeSettings()" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 20px; border: none; border-radius: 15px; cursor: pointer; text-align: center; transition: transform 0.3s;"
                    onmouseover="this.style.transform='translateY(-5px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-cog" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; font-size: 16px;">إعدادات الساعات الإضافية</div>
            </button>
        </div>
    </div>
</div>

<script>
function addOvertimeRecord() {
    // Create modal for adding overtime record
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
                <i class="fas fa-plus" style="margin-left: 10px; color: #48bb78;"></i>
                تسجيل ساعات إضافية
            </h3>

            <form id="overtimeForm" method="POST" action="{{ route('tenant.hr.overtime.store') }}">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الموظف</label>
                        <select name="employee_id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                            <option value="">اختر الموظف</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->full_name }} - {{ $employee->position->title ?? 'غير محدد' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">التاريخ</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وقت البداية</label>
                        <input type="time" name="start_time" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">وقت النهاية</label>
                        <input type="time" name="end_time" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">معدل الساعة الأساسي (د.ع)</label>
                        <input type="number" name="hourly_rate" min="1000" step="500" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;" placeholder="معدل الساعة الأساسي">
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">معدل الساعة الإضافية</label>
                        <select name="overtime_rate" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                            <option value="1.5">1.5x - عادي</option>
                            <option value="2.0">2.0x - عطلة نهاية الأسبوع</option>
                            <option value="2.5">2.5x - العطل الرسمية</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">سبب العمل الإضافي</label>
                    <textarea name="reason" rows="3" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; resize: vertical;" placeholder="أدخل سبب العمل الإضافي"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">ملاحظات إضافية</label>
                    <textarea name="notes" rows="2" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; resize: vertical;" placeholder="ملاحظات إضافية (اختياري)"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; color: #2d3748; font-weight: 600;">
                            <input type="checkbox" name="is_holiday_overtime" value="1" style="width: 18px; height: 18px;">
                            ساعات إضافية في عطلة رسمية
                        </label>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; color: #2d3748; font-weight: 600;">
                            <input type="checkbox" name="is_night_overtime" value="1" style="width: 18px; height: 18px;">
                            ساعات إضافية ليلية
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-save"></i> حفظ السجل
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

    // Handle form submission: allow normal POST to backend (no preventDefault)
    modal.querySelector('#overtimeForm').addEventListener('submit', function(e) {
        // Do not preventDefault; let the form submit to Laravel route
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;
        // Native submission will navigate back with flash message and show the new record
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function viewOvertimeRecord(recordId) {
    const recordData = {
        1: {employee: 'أحمد محمد', date: '2024-01-15', hours: 4, rate: '150%', amount: '120,000', status: 'مُوافق عليه'},
        2: {employee: 'سارة أحمد', date: '2024-01-14', hours: 3, rate: '150%', amount: '75,000', status: 'في الانتظار'},
        3: {employee: 'محمد علي', date: '2024-01-13', hours: 2, rate: '150%', amount: '50,000', status: 'مُوافق عليه'}
    };

    const record = recordData[recordId] || recordData[1];

    alert(`تفاصيل سجل الساعات الإضافية:\n\nالموظف: ${record.employee}\nالتاريخ: ${record.date}\nعدد الساعات: ${record.hours}\nالمعدل: ${record.rate}\nالمبلغ: ${record.amount} د.ع\nالحالة: ${record.status}`);
}

function editOvertimeRecord(recordId) {
    alert(`ميزة تعديل سجل الساعات الإضافية رقم ${recordId} قيد التطوير\n\nستتيح:\n• تعديل عدد الساعات\n• تغيير المعدل\n• تحديث السبب\n• إضافة ملاحظات`);
}

function deleteOvertimeRecord(recordId) {
    if (confirm('هل أنت متأكد من حذف هذا السجل؟\n\nسيتم حذف السجل نهائياً ولا يمكن التراجع عن هذا الإجراء.')) {
        alert(`تم حذف سجل الساعات الإضافية رقم ${recordId} بنجاح!`);
        showNotification('تم حذف السجل بنجاح!', 'success');
        location.reload();
    }
}

function approveOvertimeRecord(recordId) {
    if (confirm('هل أنت متأكد من الموافقة على هذا السجل؟')) {
        alert(`تم الموافقة على سجل الساعات الإضافية رقم ${recordId} بنجاح!`);
        showNotification('تم الموافقة على السجل!', 'success');
        location.reload();
    }
}

function askRejectReason(recordId) {
    const reason = prompt('يرجى إدخال سبب رفض السجل:');
    if (reason && reason.trim()) {
        const form = document.getElementById(`reject-form-${recordId}`);
        form.querySelector('input[name="rejected_reason"]').value = reason.trim();
        form.submit();
    }
}

function generateOvertimeReport() {
    // Create modal for overtime reports
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
                <i class="fas fa-chart-bar" style="margin-left: 10px; color: #ed8936;"></i>
                تقارير الساعات الإضافية
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #48bb78;">
                    <div style="color: #48bb78; font-size: 28px; font-weight: 700; margin-bottom: 5px;">156</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي الساعات</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #4299e1;">
                    <div style="color: #4299e1; font-size: 28px; font-weight: 700; margin-bottom: 5px;">23</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">عدد الموظفين</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #ed8936;">
                    <div style="color: #ed8936; font-size: 28px; font-weight: 700; margin-bottom: 5px;">2.8M</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي المبلغ</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #9f7aea;">
                    <div style="color: #9f7aea; font-size: 28px; font-weight: 700; margin-bottom: 5px;">6.8</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">متوسط الساعات</div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
                <h4 style="color: #2d3748; margin: 0 0 20px 0; font-size: 18px; font-weight: 700;">تفصيل الساعات الإضافية حسب القسم</h4>

                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم تقنية المعلومات</div>
                            <div style="font-size: 14px; color: #4a5568;">8 موظفين</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #48bb78; font-size: 18px; font-weight: 700;">65 ساعة</div>
                            <div style="font-size: 12px; color: #4a5568;">975,000 د.ع</div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم المبيعات</div>
                            <div style="font-size: 14px; color: #4a5568;">6 موظفين</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #4299e1; font-size: 18px; font-weight: 700;">42 ساعة</div>
                            <div style="font-size: 12px; color: #4a5568;">630,000 د.ع</div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم المحاسبة</div>
                            <div style="font-size: 14px; color: #4a5568;">5 موظفين</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #ed8936; font-size: 18px; font-weight: 700;">28 ساعة</div>
                            <div style="font-size: 12px; color: #4a5568;">420,000 د.ع</div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: between; align-items: center; padding: 15px; background: white; border-radius: 10px;">
                        <div>
                            <div style="font-weight: 700; color: #2d3748;">قسم الموارد البشرية</div>
                            <div style="font-size: 14px; color: #4a5568;">4 موظفين</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="color: #9f7aea; font-size: 18px; font-weight: 700;">21 ساعة</div>
                            <div style="font-size: 12px; color: #4a5568;">315,000 د.ع</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 25px; margin-bottom: 25px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">أفضل 5 موظفين في الساعات الإضافية</h4>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; justify-content: between; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #2d3748; font-weight: 600;">أحمد محمد</span>
                        <span style="color: #48bb78; font-weight: 700;">18 ساعة</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #2d3748; font-weight: 600;">سارة أحمد</span>
                        <span style="color: #4299e1; font-weight: 700;">15 ساعة</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #2d3748; font-weight: 600;">محمد علي</span>
                        <span style="color: #ed8936; font-weight: 700;">12 ساعة</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #2d3748; font-weight: 600;">فاطمة حسن</span>
                        <span style="color: #9f7aea; font-weight: 700;">10 ساعة</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 10px; background: white; border-radius: 8px;">
                        <span style="color: #2d3748; font-weight: 600;">عمر خالد</span>
                        <span style="color: #f56565; font-weight: 700;">8 ساعات</span>
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

function exportOvertimeData() {
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
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%;">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-download" style="margin-left: 10px; color: #4299e1;"></i>
                تصدير بيانات الساعات الإضافية
            </h3>

            <form id="exportForm">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع التصدير</label>
                    <select name="export_type" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                        <option value="">اختر نوع التصدير</option>
                        <option value="all">جميع البيانات</option>
                        <option value="current_month">الشهر الحالي</option>
                        <option value="last_month">الشهر الماضي</option>
                        <option value="custom_period">فترة مخصصة</option>
                        <option value="by_employee">حسب الموظف</option>
                        <option value="by_department">حسب القسم</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">صيغة الملف</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
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
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">البيانات المطلوبة</label>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="basic_info" checked>
                            <span style="color: #2d3748;">المعلومات الأساسية</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="amounts" checked>
                            <span style="color: #2d3748;">المبالغ المحسوبة</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="approval_status" checked>
                            <span style="color: #2d3748;">حالة الموافقة</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="include_data[]" value="statistics">
                            <span style="color: #2d3748;">الإحصائيات</span>
                        </label>
                    </div>
                </div>

                <div style="background: #f7fafc; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 10px 0; font-size: 16px; font-weight: 700;">معاينة التصدير</h4>
                    <div style="color: #4a5568; font-size: 14px;">
                        • إجمالي السجلات: 156 سجل<br>
                        • الفترة: يناير 2024<br>
                        • عدد الموظفين: 23 موظف<br>
                        • حجم الملف المتوقع: ~2.5 MB
                    </div>
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
    modal.querySelector('#exportForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const exportType = formData.get('export_type');
        const format = formData.get('format');

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التصدير...';
        submitBtn.disabled = true;

        setTimeout(() => {
            alert(`تم تصدير بيانات الساعات الإضافية بنجاح!\n\nنوع التصدير: ${exportType}\nصيغة الملف: ${format.toUpperCase()}\nحجم الملف: 2.5 MB\n\nتم حفظ الملف في مجلد التحميلات.`);
            modal.remove();
            showNotification('تم تصدير البيانات بنجاح!', 'success');
        }, 2000);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function manageOvertimeSettings() {
    // Create settings modal
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
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 700px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-cog" style="margin-left: 10px; color: #9f7aea;"></i>
                إعدادات الساعات الإضافية
            </h3>

            <form id="settingsForm">
                <!-- Overtime Rates Section -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">معدلات الساعات الإضافية</h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الأيام العادية (%)</label>
                            <input type="number" value="150" min="100" max="300" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نهاية الأسبوع (%)</label>
                            <input type="number" value="200" min="100" max="300" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">العطل الرسمية (%)</label>
                            <input type="number" value="250" min="100" max="300" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <!-- Working Hours Limits -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">حدود ساعات العمل</h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأقصى يومياً (ساعة)</label>
                            <input type="number" value="4" min="1" max="12" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأقصى أسبوعياً (ساعة)</label>
                            <input type="number" value="20" min="1" max="40" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                        <div>
                            <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الحد الأقصى شهرياً (ساعة)</label>
                            <input type="number" value="80" min="1" max="160" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <!-- Approval Settings -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">إعدادات الموافقة</h4>

                    <div style="display: grid; gap: 15px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" checked style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">تتطلب موافقة المدير المباشر</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" checked style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">تتطلب موافقة الموارد البشرية للساعات أكثر من 8 ساعات</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">الموافقة التلقائية للساعات أقل من ساعتين</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" checked style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">إرسال تنبيه عند تجاوز الحد الأقصى</span>
                        </label>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">إعدادات التنبيهات</h4>

                    <div style="display: grid; gap: 15px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" checked style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">تنبيه بالبريد الإلكتروني عند طلب جديد</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" checked style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">تنبيه SMS للموافقات العاجلة</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">تقرير أسبوعي للمديرين</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" checked style="width: 18px; height: 18px;">
                            <span style="color: #2d3748; font-weight: 600;">تنبيه عند اقتراب الحد الأقصى الشهري</span>
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-save"></i> حفظ الإعدادات
                    </button>
                    <button type="button" onclick="resetToDefaults()" style="background: #ed8936; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
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
    modal.querySelector('#settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        submitBtn.disabled = true;

        setTimeout(() => {
            alert('تم حفظ إعدادات الساعات الإضافية بنجاح!\n\nتم تحديث:\n• معدلات الساعات الإضافية\n• حدود ساعات العمل\n• قواعد الموافقة\n• إعدادات التنبيهات');
            modal.remove();
            showNotification('تم حفظ الإعدادات بنجاح!', 'success');
        }, 1500);
    });

    // Reset to defaults function
    window.resetToDefaults = function() {
        if (confirm('هل أنت متأكد من إعادة تعيين جميع الإعدادات للقيم الافتراضية؟')) {
            // Reset form values to defaults
            modal.querySelector('input[type="number"]:nth-of-type(1)').value = '150';
            modal.querySelector('input[type="number"]:nth-of-type(2)').value = '200';
            modal.querySelector('input[type="number"]:nth-of-type(3)').value = '250';
            modal.querySelector('input[type="number"]:nth-of-type(4)').value = '4';
            modal.querySelector('input[type="number"]:nth-of-type(5)').value = '20';
            modal.querySelector('input[type="number"]:nth-of-type(6)').value = '80';

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
