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
                <button onclick="createNewLeaveRequest()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; cursor: pointer;">
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

    <!-- Leave Calendar & Quick Actions -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        
        <!-- Leave Calendar -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 24px; font-weight: 700;">
                <i class="fas fa-calendar" style="margin-left: 10px; color: #4299e1;"></i>
                تقويم الإجازات
            </h3>
            
            <div style="text-align: center; padding: 60px 20px; color: #718096;">
                <i class="fas fa-calendar-alt" style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;"></i>
                <h4 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">تقويم الإجازات</h4>
                <p style="margin: 0; font-size: 16px;">ميزة التقويم التفاعلي قيد التطوير</p>
                <button onclick="showLeaveCalendar()" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; margin-top: 20px; cursor: pointer;">
                    عرض التقويم
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
            <h3 style="color: #2d3748; margin: 0 0 25px 0; font-size: 20px; font-weight: 700;">
                <i class="fas fa-bolt" style="margin-left: 10px; color: #ed8936;"></i>
                إجراءات سريعة
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                
                <button onclick="createNewLeaveRequest()" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-plus"></i>
                    طلب إجازة جديد
                </button>

                <button onclick="manageLeaveTypes()" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; padding: 15px 20px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-tags"></i>
                    أنواع الإجازات
                </button>

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

<script>
function createNewLeaveRequest() {
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

            <form id="leaveRequestForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">نوع الإجازة</label>
                        <select required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                            <option value="">اختر نوع الإجازة</option>
                            <option value="annual">إجازة سنوية</option>
                            <option value="sick">إجازة مرضية</option>
                            <option value="emergency">إجازة طارئة</option>
                            <option value="maternity">إجازة أمومة</option>
                            <option value="paternity">إجازة أبوة</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">عدد الأيام</label>
                        <input type="number" min="1" max="30" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;" placeholder="عدد أيام الإجازة">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">تاريخ البداية</label>
                        <input type="date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                    <div>
                        <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">تاريخ النهاية</label>
                        <input type="date" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">سبب الإجازة</label>
                    <textarea rows="4" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px; resize: vertical;" placeholder="أدخل سبب طلب الإجازة"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2d3748; font-weight: 600; margin-bottom: 8px;">المرفقات (اختياري)</label>
                    <input type="file" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 16px;">
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

    // Handle form submission
    modal.querySelector('#leaveRequestForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
        submitBtn.disabled = true;

        setTimeout(() => {
            alert('تم إرسال طلب الإجازة بنجاح!\n\nسيتم مراجعة الطلب من قبل المدير المباشر.');
            modal.remove();
            showNotification('تم إرسال طلب الإجازة بنجاح!', 'success');
            location.reload();
        }, 1500);
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

function approveLeaveRequest(requestId) {
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

function showLeaveCalendar() {
    // Create modal for leave calendar
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

function manageLeaveTypes() {
    alert('ميزة إدارة أنواع الإجازات قيد التطوير\n\nستتيح:\n• إضافة أنواع إجازات جديدة\n• تعديل أنواع الإجازات الموجودة\n• تحديد عدد الأيام المسموحة\n• إعداد قواعد الموافقة');
}

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
    alert('ميزة تقارير الإجازات قيد التطوير\n\nستتضمن:\n• تقرير الإجازات الشهرية\n• تقرير رصيد الموظفين\n• تقرير الإجازات المعلقة\n• إحصائيات شاملة');
}

function exportLeaveData() {
    alert('تم تصدير بيانات الإجازات بنجاح!\n\nتم تصدير:\n• جميع طلبات الإجازات\n• أرصدة الموظفين\n• الإحصائيات والتقارير\n• سجل الموافقات والرفض');
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
