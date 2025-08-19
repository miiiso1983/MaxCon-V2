@extends('layouts.modern')

@section('title', 'إدارة الإجازات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">إدارة الإجازات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">طلبات الإجازات والموافقات</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <button onclick="openLeaveRequestModal()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fas fa-plus"></i>
                    طلب إجازة جديد
                </button>
            </div>
        </div>
    </div>

    <!-- Leave Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-clock"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">4</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">طلبات معلقة</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #48bb78; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-check"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">12</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجازات موافق عليها</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #f56565; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-times"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">2</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">إجازات مرفوضة</p>
        </div>

        <div style="background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #4299e1; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 20px;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 24px; font-weight: 700;">2</h4>
            <p style="color: #718096; margin: 0; font-size: 14px;">في إجازة اليوم</p>
        </div>
    </div>

    <!-- Pending Requests -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
            <i class="fas fa-clock" style="margin-left: 10px; color: #ed8936;"></i>
            طلبات الإجازات المعلقة
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">

            <!-- Sample Pending Request 1 -->
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 15px; padding: 20px; transition: transform 0.3s, box-shadow 0.3s;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700;">
                        أم
                    </div>
                    <div style="flex: 1;">
                        <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 16px; font-weight: 700;">أحمد محمد</h4>
                        <p style="color: #718096; margin: 0; font-size: 14px;">الإدارة العامة</p>
                    </div>
                    <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">معلق</span>
                </div>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">نوع الإجازة:</span>
                        <span style="color: #2d3748; font-size: 14px;">إجازة سنوية</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">من:</span>
                        <span style="color: #2d3748; font-size: 14px;">{{ now()->addDays(5)->format('Y-m-d') }}</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">إلى:</span>
                        <span style="color: #2d3748; font-size: 14px;">{{ now()->addDays(8)->format('Y-m-d') }}</span>
                    </div>
                    <div style="display: flex; justify-content: between;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">المدة:</span>
                        <span style="color: #2d3748; font-size: 14px; font-weight: 700;">3 أيام</span>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                    <button onclick="approveLeaveRequest(1)" style="background: #48bb78; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-check"></i> موافقة
                    </button>
                    <button onclick="rejectLeaveRequest(1)" style="background: #f56565; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-times"></i> رفض
                    </button>
                    <button onclick="viewLeaveRequest(1)" style="background: #4299e1; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-eye"></i> عرض
                    </button>
                </div>
            </div>

            <!-- Sample Pending Request 2 -->
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 15px; padding: 20px; transition: transform 0.3s, box-shadow 0.3s;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">

                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="background: #ed8936; color: white; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700;">
                        سأ
                    </div>
                    <div style="flex: 1;">
                        <h4 style="color: #2d3748; margin: 0 0 5px 0; font-size: 16px; font-weight: 700;">سارة أحمد</h4>
                        <p style="color: #718096; margin: 0; font-size: 14px;">الموارد البشرية</p>
                    </div>
                    <span style="background: #ed8936; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">معلق</span>
                </div>

                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">نوع الإجازة:</span>
                        <span style="color: #2d3748; font-size: 14px;">إجازة مرضية</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">من:</span>
                        <span style="color: #2d3748; font-size: 14px;">{{ now()->addDays(2)->format('Y-m-d') }}</span>
                    </div>
                    <div style="display: flex; justify-content: between; margin-bottom: 8px;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">إلى:</span>
                        <span style="color: #2d3748; font-size: 14px;">{{ now()->addDays(3)->format('Y-m-d') }}</span>
                    </div>
                    <div style="display: flex; justify-content: between;">
                        <span style="color: #4a5568; font-size: 14px; font-weight: 600;">المدة:</span>
                        <span style="color: #2d3748; font-size: 14px; font-weight: 700;">2 أيام</span>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; justify-content: center; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                    <button onclick="approveLeaveRequest(2)" style="background: #48bb78; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-check"></i> موافقة
                    </button>
                    <button onclick="rejectLeaveRequest(2)" style="background: #f56565; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-times"></i> رفض
                    </button>
                    <button onclick="viewLeaveRequest(2)" style="background: #4299e1; color: white; padding: 8px 15px; border: none; border-radius: 8px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-eye"></i> عرض
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(isset($leaves) && $leaves->count())
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 24px;">
        <h3 style="color:#2d3748; margin:0 0 16px 0; font-size:20px; font-weight:700;">أحدث طلبات الإجازة</h3>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px;">
            @foreach($leaves as $lv)
                <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="font-weight:700; color:#111827;">{{ $lv->leaveType->name ?? '—' }}</div>
                        <span style="font-size:12px; background:#edf2f7; color:#2d3748; padding:3px 8px; border-radius:8px;">{{ $lv->status_label }}</span>
                    </div>
                    <div style="color:#4a5568; font-size:12px; margin-top:6px;">{{ $lv->start_date?->format('Y-m-d') }} → {{ $lv->end_date?->format('Y-m-d') }}</div>
                    <div style="color:#1f2937; font-size:12px; margin-top:6px;">أيام: {{ $lv->days_requested }}</div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:12px;">{{ $leaves->links() }}</div>
    </div>
    @endif
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css">
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <!-- Leave Calendar & Quick Actions -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

        <!-- Leave Calendar -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-calendar" style="margin-left: 10px; color: #4299e1;"></i>
                تقويم الإجازات
            </h3>

            <!-- Filters -->
            <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px; align-items:end;">
                <div>
                    <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">الموظف</label>
                    <select id="cal_employee" style="min-width:180px; padding:8px 10px; border:1px solid #e2e8f0; border-radius:8px;">
                        <option value="">الكل</option>
                        @if(isset($employees))
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name_arabic ?? ($emp->full_name_english ?? ($emp->first_name.' '.$emp->last_name)) }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">النوع</label>
                    <select id="cal_leave_type" style="min-width:160px; padding:8px 10px; border:1px solid #e2e8f0; border-radius:8px;">
                        <option value="">الكل</option>
                        @if(isset($leaveTypes))
                            @foreach($leaveTypes as $lt)
                                <option value="{{ $lt->id }}">{{ $lt->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">الحالة</label>
                    <select id="cal_status" style="min-width:140px; padding:8px 10px; border:1px solid #e2e8f0; border-radius:8px;">
                        <option value="">الكل</option>
                        <option value="pending">قيد الانتظار</option>
                        <option value="approved">موافق عليها</option>
                        <option value="rejected">مرفوضة</option>
                        <option value="cancelled">ملغاة</option>
                        <option value="completed">مكتملة</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">من</label>
                    <input id="cal_from" type="date" style="padding:8px 10px; border:1px solid #e2e8f0; border-radius:8px;" />
                </div>
                <div>
                    <label style="display:block; font-weight:600; color:#2d3748; margin-bottom:6px;">إلى</label>
                    <input id="cal_to" type="date" style="padding:8px 10px; border:1px solid #e2e8f0; border-radius:8px;" />
                </div>
                <div style="display:flex; gap:8px;">
                    <button id="cal_apply" style="background:#4299e1; color:#fff; border:none; padding:10px 14px; border-radius:8px; font-weight:600; cursor:pointer;">تطبيق</button>
                    <button id="cal_clear" style="background:#e2e8f0; color:#2d3748; border:none; padding:10px 14px; border-radius:8px; font-weight:600; cursor:pointer;">مسح</button>
                </div>
            </div>

            <div>
                <div id="leaves-calendar" style="height: 650px;"></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-bolt" style="margin-left: 10px; color: #ed8936;"></i>
                إجراءات سريعة
            </h3>

            <div style="display: flex; flex-direction: column; gap: 15px;">

                <button onclick="openLeaveRequestModal()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-plus"></i>
                    طلب إجازة جديد
                </button>

                <a href="{{ route('tenant.hr.leave-types.index') }}" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px; text-decoration:none;">
                    <i class="fas fa-tags"></i>
                    أنواع الإجازات
                </a>

                <button onclick="showLeaveBalance()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-wallet"></i>
                    رصيد الإجازات
                </button>

                <button onclick="generateLeaveReports()" style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-chart-bar"></i>
                    تقارير الإجازات
                </button>

                <button onclick="exportLeaveData()" style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </button>
            </div>

            <!-- Leave Balance Summary -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 16px; font-weight: 700;">رصيد الإجازات</h4>

                <div style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">إجازة سنوية</span>
                        <span style="color: #2d3748; font-size: 14px; font-weight: 600;">18/30</span>
                    </div>
                    <div style="background: #e2e8f0; border-radius: 10px; height: 8px;">
                        <div style="background: #48bb78; border-radius: 10px; height: 8px; width: 60%;"></div>
                    </div>
                </div>

                <div style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">إجازة مرضية</span>
                        <span style="color: #2d3748; font-size: 14px; font-weight: 600;">5/15</span>
                    </div>
                    <div style="background: #e2e8f0; border-radius: 10px; height: 8px;">
                        <div style="background: #ed8936; border-radius: 10px; height: 8px; width: 33%;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: between; margin-bottom: 5px;">
                        <span style="color: #4a5568; font-size: 14px;">إجازة طارئة</span>
                        <span style="color: #2d3748; font-size: 14px; font-weight: 600;">2/7</span>
                    </div>
                    <div style="background: #e2e8f0; border-radius: 10px; height: 8px;">
                        <div style="background: #f56565; border-radius: 10px; height: 8px; width: 28%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="leave-approve-form" method="POST" data-base="{{ route('tenant.hr.leaves.approve', ['leave' => '__ID__']) }}" style="display:none;">@csrf</form>
<form id="leave-reject-form" method="POST" data-base="{{ route('tenant.hr.leaves.reject', ['leave' => '__ID__']) }}" style="display:none;">@csrf<input type="hidden" name="reason" id="leave-reject-reason" value="" /></form>
<form id="leave-create-form" method="POST" action="{{ route('tenant.hr.leaves.store') }}" enctype="multipart/form-data" style="display:none;">@csrf
    <input type="hidden" name="leave_type_id" id="leave_type_id" />
    <input type="hidden" name="days_requested" id="days_requested" />
    <input type="hidden" name="start_date" id="start_date" />
    <input type="hidden" name="end_date" id="end_date" />
    <input type="hidden" name="reason" id="leave_reason" />
</form>

<script>
function openLeaveRequestModal() {
    // Create modal for new leave request
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
                طلب إجازة جديد
            </h3>

            <form id="leaveRequestForm" enctype="multipart/form-data">@csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع الإجازة</label>
                        <select id="ui_leave_type_id" name="leave_type_id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                            <option value="">اختر نوع الإجازة</option>
                            @if(isset($leaveTypes))
                                @foreach($leaveTypes as $lt)
                                    <option value="{{ $lt->id }}">{{ $lt->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div id="err_leave_type_id" style="color:#e53e3e; font-size:12px; margin-top:6px;"></div>
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">عدد الأيام</label>
                        <input id="ui_days_requested" name="days_requested" type="number" min="1" max="365" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;" placeholder="عدد أيام الإجازة (اختياري)">
                        <div id="err_days_requested" style="color:#e53e3e; font-size:12px; margin-top:6px;"></div>
                        <div style="margin-top:6px; display:flex; gap:8px; align-items:center;">
                            <label style="display:flex; align-items:center; gap:6px; white-space:nowrap;">
                                <input type="checkbox" id="ui_is_half_day" style="width:18px; height:18px;"> نصف يوم
                            </label>
                            <select id="ui_half_day_session" style="display:none; padding:8px 10px; border:1px solid #e2e8f0; border-radius:8px;">
                                <option value="morning">صباحاً</option>
                                <option value="afternoon">مساءً</option>
                            </select>
                        </div>
                    </div>
                    @can('manage hr leaves')
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">الموظف (اختياري)</label>
                        <select id="ui_employee_id" name="employee_id" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                            <option value="">اختر الموظف</option>
                            @if(isset($employees))
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->full_name_arabic ?? ($emp->full_name_english ?? ($emp->first_name.' '.$emp->last_name)) }}</option>
                                @endforeach
                        <div id="err_employee_id" style="color:#e53e3e; font-size:12px; margin-top:6px;"></div>
                            @endif
                        </select>
                    </div>
                    @endcan
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">تاريخ البداية</label>
                        <input id="ui_start_date" name="start_date" type="date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">تاريخ النهاية</label>
                        <input id="ui_end_date" name="end_date" type="date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">سبب الإجازة</label>
                    <textarea id="ui_reason" name="reason" rows="4" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; resize: vertical;" placeholder="أدخل سبب طلب الإجازة"></textarea>
                    <div id="err_reason" style="color:#e53e3e; font-size:12px; margin-top:6px;"></div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المرفقات (اختياري)</label>
                    <input id="ui_attachments" type="file" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    <div id="err_attachments" style="color:#e53e3e; font-size:12px; margin-top:6px;"></div>
                    <small style="color: #718096; font-size: 12px;">يمكن إرفاق ملفات PDF، صور، أو مستندات Word</small>
                </div>

                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button type="submit" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-paper-plane"></i> إرسال الطلب
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

    // Auto-calc days and guard dates (Fri/Sat are weekends)
    const startInput = modal.querySelector('#ui_start_date');
    const endInput = modal.querySelector('#ui_end_date');
    const daysInput = modal.querySelector('#ui_days_requested');
    const errEnd = modal.querySelector('#err_end_date');

    function calcWorkingDays(startStr, endStr) {
        if (!startStr) return '';
        const s = new Date(startStr);
        if (!endStr) return 1;
        const e = new Date(endStr);
        if (isNaN(s.getTime()) || isNaN(e.getTime())) return '';
        let count = 0;
        const cur = new Date(s);
        while (cur <= e) {
            const d = cur.getDay(); // 0=Sun .. 6=Sat
            if (d !== 5 && d !== 6) count++;
            cur.setDate(cur.getDate() + 1);
        }
        return Math.max(1, count);
    }

    function validateDates() {
        if (!startInput || !endInput) return true;
        if (startInput.value) {
            endInput.min = startInput.value; // prevent selecting a date before start
        }
        if (startInput.value && endInput.value && endInput.value < startInput.value) {
            if (errEnd) errEnd.textContent = 'لا يمكن أن يكون تاريخ النهاية قبل تاريخ البداية';
            return false;
        }
        if (errEnd) errEnd.textContent = '';
        return true;
    }

    const halfDayChk = modal.querySelector('#ui_is_half_day');
    const halfDaySess = modal.querySelector('#ui_half_day_session');

    function recalcDays(){
        // If end is empty, set it to start on first selection for convenience
        if (startInput && startInput.value) {
            endInput.min = startInput.value;
            if (endInput && !endInput.value) {
                endInput.value = startInput.value;
            }
        }
        if (!validateDates()) return;
        let val = calcWorkingDays(startInput?.value, endInput?.value);
        if (halfDayChk?.checked) {
            val = 0.5;
            halfDaySess.style.display = '';
        } else {
            halfDaySess.style.display = 'none';
        }
        if (val !== '') { daysInput.value = val; }
    }

    halfDayChk?.addEventListener('change', recalcDays);

    startInput?.addEventListener('change', recalcDays);
    endInput?.addEventListener('change', () => { validateDates(); recalcDays(); });

    // Handle form submission
    modal.querySelector('#leaveRequestForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
        submitBtn.disabled = true;

        // Copy values into hidden form and submit
        document.getElementById('leave_type_id').value = document.getElementById('ui_leave_type_id').value;
        document.getElementById('days_requested').value = document.getElementById('ui_days_requested').value;
        document.getElementById('start_date').value = document.getElementById('ui_start_date').value;
        document.getElementById('end_date').value = document.getElementById('ui_end_date').value;
        document.getElementById('leave_reason').value = document.getElementById('ui_reason').value;
        const uiEmp = document.getElementById('ui_employee_id');
        if (uiEmp) {
            const hiddenEmp = hiddenForm.querySelector('input[name="employee_id"]') || document.createElement('input');
            hiddenEmp.type = 'hidden';
            hiddenEmp.name = 'employee_id';
            hiddenEmp.value = uiEmp.value;
            hiddenForm.appendChild(hiddenEmp);
        }

        const hiddenForm = document.getElementById('leave-create-form');

        // If attachments exist, build a FormData and submit via fetch to preserve files
        const filesInput = document.getElementById('ui_attachments');
        const formData = new FormData(hiddenForm);
        if (filesInput && filesInput.files && filesInput.files.length > 0) {
            for (const f of filesInput.files) {
                formData.append('attachments[]', f);
            }
        }
        // Half-day fields
        const isHalf = modal.querySelector('#ui_is_half_day')?.checked;
        const sess = modal.querySelector('#ui_half_day_session')?.value;
        if (isHalf) {
            formData.append('is_half_day', '1');
            if (sess) formData.append('half_day_session', sess);
        }

        fetch(hiddenForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': hiddenForm.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData,
            credentials: 'same-origin'
        }).then(async (res) => {
            const raw = await res.text();
            if (!res.ok) {
                let msg = 'تعذر إرسال الطلب. يرجى التحقق من المدخلات.';
                try { const data = JSON.parse(raw); if (data?.message) msg = data.message; } catch (e) { if (raw) msg = raw; }
                throw new Error(msg);
            }
            let data;
            try { data = JSON.parse(raw); } catch (e) { data = { message: 'تم إرسال طلب الإجازة بنجاح!' }; }
            return data;
        }).then((data) => {
            // Clear inline errors
            ['leave_type_id','days_requested','start_date','end_date','reason','attachments','employee_id'].forEach(id => {
                const el = document.getElementById('err_'+id);
                if (el) el.textContent = '';
            });

            modal.remove();
            showNotification(data?.message || 'تم إرسال طلب الإجازة بنجاح!', 'success');
            window.location.reload();
        }).catch((err) => {
            submitBtn.innerHTML = 'إرسال الطلب';
            submitBtn.disabled = false;

            // Try to parse per-field errors and show inline
            try {
                const json = JSON.parse(err.message);
                if (json && json.errors) {
                    Object.keys(json.errors).forEach(k => {
                        const el = document.getElementById('err_'+k);
                        if (el) el.textContent = Array.isArray(json.errors[k]) ? json.errors[k][0] : String(json.errors[k]);
                    });
                    return;
                }
            } catch(e) {}

            alert(err?.message || 'تعذر إرسال الطلب. يرجى المحاولة لاحقاً.');
        });
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function approveLeaveRequest(requestId) {
    const btn = event.target.closest('button');
    const original = btn ? btn.innerHTML : null;
    if (confirm('هل أنت متأكد من الموافقة على طلب الإجازة؟')) {
        // Show loading state
        const button = event.target;
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        setTimeout(() => {
            alert(`تم الموافقة على طلب الإجازة رقم ${requestId} بنجاح!`);
            showNotification('تم الموافقة على الطلب!', 'success');
            location.reload();
        }, 1000);
    }
}

function rejectLeaveRequest(requestId) {
    const btn = event.target.closest('button');
    const original = btn ? btn.innerHTML : null;
    const reason = prompt('يرجى إدخال سبب رفض الطلب:');
    if (reason && reason.trim()) {
        // Show loading state
        const button = event.target;
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        setTimeout(() => {
            alert(`تم رفض طلب الإجازة رقم ${requestId}\nالسبب: ${reason}`);
            showNotification('تم رفض الطلب!', 'error');
            location.reload();
        }, 1000);
    }
}

function viewLeaveRequest(requestId) {
    // Create modal for viewing leave request details
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

    const requestData = {
        1: {name: 'أحمد محمد', type: 'إجازة سنوية', days: 3, start: '2024-01-15', end: '2024-01-17', reason: 'سفر عائلي'},
        2: {name: 'سارة أحمد', type: 'إجازة مرضية', days: 2, start: '2024-01-20', end: '2024-01-21', reason: 'مراجعة طبية'}
    };

    const request = requestData[requestId] || requestData[1];

    modal.innerHTML = `
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%;">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700; text-align: center;">
                <i class="fas fa-eye" style="margin-left: 10px; color: #4299e1;"></i>
                تفاصيل طلب الإجازة
            </h3>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                <div style="display: grid; gap: 15px;">
                    <div style="display: flex; justify-content: between;">
                        <span style="color: #4a5568; font-weight: 600;">اسم الموظف:</span>
                        <span style="color: #2d3748; font-weight: 700;">${request.name}</span>
                    </div>
                    <div style="display: flex; justify-content: between;">
                        <span style="color: #4a5568; font-weight: 600;">نوع الإجازة:</span>
                        <span style="color: #2d3748; font-weight: 700;">${request.type}</span>
                    </div>
                    <div style="display: flex; justify-content: between;">
                        <span style="color: #4a5568; font-weight: 600;">عدد الأيام:</span>
                        <span style="color: #2d3748; font-weight: 700;">${request.days} أيام</span>
                    </div>
                    <div style="display: flex; justify-content: between;">
                        <span style="color: #4a5568; font-weight: 600;">تاريخ البداية:</span>
                        <span style="color: #2d3748; font-weight: 700;">${request.start}</span>
                    </div>
                    <div style="display: flex; justify-content: between;">
                        <span style="color: #4a5568; font-weight: 600;">تاريخ النهاية:</span>
                        <span style="color: #2d3748; font-weight: 700;">${request.end}</span>
                    </div>
                    <div>
                        <span style="color: #4a5568; font-weight: 600; display: block; margin-bottom: 8px;">السبب:</span>
                        <span style="color: #2d3748; font-weight: 700;">${request.reason}</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="approveLeaveRequest(${requestId}); this.closest('.modal').remove();" style="background: #48bb78; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-check"></i> موافقة
                </button>
                <button onclick="rejectLeaveRequest(${requestId}); this.closest('.modal').remove();" style="background: #f56565; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-times"></i> رفض
                </button>
                <button onclick="this.closest('.modal').remove()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-arrow-left"></i> إغلاق
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

document.addEventListener('DOMContentLoaded', function initLeavesCalendar() {
    // Initialize FullCalendar
    const el = document.getElementById('leaves-calendar');
    if (!el) return;
    const calendar = new FullCalendar.Calendar(el, {
        initialView: 'dayGridMonth',
        locale: 'ar',
        direction: 'rtl',
        selectable: true,
        editable: @can('manage hr leaves') true @else false @endcan,
        headerToolbar: { start: 'title', center: 'dayGridMonth,timeGridWeek,listWeek', end: 'prev,next today' },
        events: {
            url: '{{ route('tenant.hr.leaves.calendar-feed') }}',
            extraParams: () => ({
                employee_id: document.getElementById('cal_employee')?.value || '',
                leave_type_id: document.getElementById('cal_leave_type')?.value || '',
                status: document.getElementById('cal_status')?.value || '',
                from: document.getElementById('cal_from')?.value || '',
                to: document.getElementById('cal_to')?.value || ''
            }),
            failure: () => alert('تعذر تحميل أحداث التقويم')
        },
    document.getElementById('cal_apply')?.addEventListener('click', () => calendar.refetchEvents());
    document.getElementById('cal_clear')?.addEventListener('click', () => {
        ['cal_employee','cal_leave_type','cal_status','cal_from','cal_to'].forEach(id => { const el = document.getElementById(id); if (el) el.value=''; });
        calendar.refetchEvents();
    });
        select: (info) => {
            // prefill dates in modal and open
            openLeaveRequestModal();
            setTimeout(() => {
                const s = document.querySelector('#ui_start_date');
                const e = document.querySelector('#ui_end_date');
                if (s) s.value = info.startStr;
                if (e) e.value = info.endStr ? new Date(new Date(info.endStr).getTime()-86400000).toISOString().slice(0,10) : info.startStr;
                const ev = new Event('change'); s?.dispatchEvent(ev); e?.dispatchEvent(ev);
            }, 0);
        },
        eventDrop: (info) => {
            updateLeaveDates(info);
        },
        eventResize: (info) => {
            updateLeaveDates(info);
        }
    });
    calendar.render();
});

    function updateLeaveDates(info) {
        const id = info.event.id;
        const newStart = info.event.startStr;
        const newEndExcl = info.event.endStr || info.event.startStr; // end exclusive
        const newEnd = new Date(new Date(newEndExcl).getTime()-86400000).toISOString().slice(0,10);
        fetch(`{{ url('/tenant/hr/leaves') }}/${id}/dates`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ start_date: newStart, end_date: newEnd })
        }).then(async (res) => {
            const raw = await res.text();
            if (!res.ok) {
                try { const j = JSON.parse(raw); alert(j?.message || 'تعذر تحديث التواريخ'); } catch(e) { alert('تعذر تحديث التواريخ'); }
                info.revert();
            } else {
                try { const j = JSON.parse(raw); showNotification(j?.message || 'تم تحديث التواريخ', 'success'); } catch(e) { showNotification('تم تحديث التواريخ', 'success'); }
            }
        }).catch(() => { alert('تعذر تحديث التواريخ'); info.revert(); });
    }

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
                <i class="fas fa-calendar-alt" style="margin-left: 10px; color: #4299e1;"></i>
                تقويم الإجازات - يناير 2024
            </h3>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; text-align: center; margin-bottom: 15px;">
                    <div style="font-weight: 700; color: #2d3748; padding: 10px;">الأحد</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px;">الاثنين</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px;">الثلاثاء</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px;">الأربعاء</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px;">الخميس</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px;">الجمعة</div>
                    <div style="font-weight: 700; color: #2d3748; padding: 10px;">السبت</div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center;">
                    ${Array.from({length: 31}, (_, i) => {
                        const day = i + 1;
                        let bgColor = '#ffffff';
                        let textColor = '#2d3748';
                        let content = day;

                        if (day === 15 || day === 16 || day === 17) {
                            bgColor = '#48bb78';
                            textColor = 'white';
                            content = `${day}<br><small>أحمد</small>`;
                        } else if (day === 20 || day === 21) {
                            bgColor = '#f56565';
                            textColor = 'white';
                            content = `${day}<br><small>سارة</small>`;
                        }

                        return `<div style="background: ${bgColor}; color: ${textColor}; padding: 8px; border-radius: 8px; font-size: 12px; min-height: 40px; display: flex; flex-direction: column; justify-content: center;">${content}</div>`;
                    }).join('')}
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 20px; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #48bb78; border-radius: 4px;"></div>
                    <span style="color: #2d3748; font-size: 14px;">إجازة مُوافق عليها</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #f56565; border-radius: 4px;"></div>
                    <span style="color: #2d3748; font-size: 14px;">إجازة مرضية</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #ed8936; border-radius: 4px;"></div>
                    <span style="color: #2d3748; font-size: 14px;">في انتظار الموافقة</span>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="alert('تم تصدير التقويم بنجاح!')" style="background: #4299e1; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-download"></i> تصدير التقويم
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

function manageLeaveTypes() {}


function showLeaveBalance() {
    // Create modal for leave balance
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
                <i class="fas fa-wallet" style="margin-left: 10px; color: #4299e1;"></i>
                رصيد الإجازات
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-bottom: 25px;">
                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #48bb78;">
                    <div style="color: #48bb78; font-size: 28px; font-weight: 700; margin-bottom: 5px;">21</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجازة سنوية متبقية</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #f56565;">
                    <div style="color: #f56565; font-size: 28px; font-weight: 700; margin-bottom: 5px;">5</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجازة مرضية متبقية</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #ed8936;">
                    <div style="color: #ed8936; font-size: 28px; font-weight: 700; margin-bottom: 5px;">3</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجازة طارئة متبقية</div>
                </div>

                <div style="background: #f7fafc; border-radius: 15px; padding: 20px; text-align: center; border-top: 4px solid #9f7aea;">
                    <div style="color: #9f7aea; font-size: 28px; font-weight: 700; margin-bottom: 5px;">9</div>
                    <div style="color: #4a5568; font-size: 14px; font-weight: 600;">إجمالي المستخدمة</div>
                </div>
            </div>

            <div style="background: #f7fafc; border-radius: 15px; padding: 20px; margin-bottom: 20px;">
                <h4 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">تفاصيل الاستخدام</h4>
                <div style="display: grid; gap: 10px;">
                    <div style="display: flex; justify-content: between; padding: 8px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568;">إجازة سنوية مستخدمة:</span>
                        <span style="color: #2d3748; font-weight: 600;">9 أيام</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 8px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568;">إجازة مرضية مستخدمة:</span>
                        <span style="color: #2d3748; font-weight: 600;">0 أيام</span>
                    </div>
                    <div style="display: flex; justify-content: between; padding: 8px; background: white; border-radius: 8px;">
                        <span style="color: #4a5568;">إجازة طارئة مستخدمة:</span>
                        <span style="color: #2d3748; font-weight: 600;">0 أيام</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button onclick="alert('تم تصدير تقرير الرصيد بنجاح!')" style="background: #4299e1; color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-download"></i> تصدير التقرير
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

function generateLeaveReports() {
    window.location.href = "{{ route('tenant.hr.reports.leaves') }}";
}

function exportLeaveData() {
    window.location.href = "{{ route('tenant.hr.leaves.export') }}";
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
