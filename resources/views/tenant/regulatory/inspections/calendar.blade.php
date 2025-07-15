@extends('layouts.modern')

@section('title', 'تقويم التفتيشات')

@section('content')
<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <!-- Header -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 20px;">
            <div style="display: flex; align-items: center;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin-left: 20px; font-size: 32px;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h1 style="color: #2d3748; margin: 0; font-size: 32px; font-weight: 700;">تقويم التفتيشات</h1>
                    <p style="color: #718096; margin: 5px 0 0 0; font-size: 16px;">عرض جميع التفتيشات المجدولة في التقويم</p>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('tenant.inventory.regulatory.inspections.schedule') }}" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 15px 25px; border: none; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-plus"></i>
                    جدولة تفتيش
                </a>
                <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}" style="background: rgba(255,255,255,0.2); color: #667eea; padding: 15px 25px; border: 2px solid #667eea; border-radius: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 20px; margin-bottom: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <h3 style="color: #2d3748; margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">
            <i class="fas fa-info-circle" style="margin-left: 10px; color: #667eea;"></i>
            دليل الألوان
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #ed8936; border-radius: 4px;"></div>
                <span style="color: #2d3748; font-weight: 600;">مجدول</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #4299e1; border-radius: 4px;"></div>
                <span style="color: #2d3748; font-weight: 600;">قيد التنفيذ</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #48bb78; border-radius: 4px;"></div>
                <span style="color: #2d3748; font-weight: 600;">مكتمل</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #718096; border-radius: 4px;"></div>
                <span style="color: #2d3748; font-weight: 600;">ملغي</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #f56565; border-radius: 4px;"></div>
                <span style="color: #2d3748; font-weight: 600;">مؤجل</span>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div style="background: rgba(255,255,255,0.95); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); backdrop-filter: blur(10px);">
        <div id="calendar"></div>
    </div>
</div>

<!-- Event Details Modal -->
<div id="eventModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; backdrop-filter: blur(5px);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
            <h3 id="modalTitle" style="color: #2d3748; margin: 0; font-size: 24px; font-weight: 700;"></h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 24px; color: #718096; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="modalContent" style="color: #4a5568; line-height: 1.6;">
            <!-- Content will be populated by JavaScript -->
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <button onclick="editInspection()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-edit" style="margin-left: 8px;"></i>
                تعديل
            </button>
            <button onclick="closeModal()" style="background: #e2e8f0; color: #4a5568; padding: 12px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                <i class="fas fa-times" style="margin-left: 8px;"></i>
                إغلاق
            </button>
        </div>
    </div>
</div>

<!-- Include FullCalendar CSS and JS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var currentEventId = null;
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ar',
        direction: 'rtl',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'اليوم',
            month: 'شهر',
            week: 'أسبوع',
            day: 'يوم'
        },
        events: @json($events),
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        eventMouseEnter: function(info) {
            info.el.style.cursor = 'pointer';
            info.el.style.transform = 'scale(1.05)';
            info.el.style.transition = 'transform 0.2s';
        },
        eventMouseLeave: function(info) {
            info.el.style.transform = 'scale(1)';
        },
        height: 'auto',
        eventDisplay: 'block',
        dayMaxEvents: 3,
        moreLinkClick: 'popover',
        eventTextColor: '#ffffff',
        eventBorderWidth: 2,
        eventClassNames: function(arg) {
            return ['custom-event'];
        }
    });
    
    calendar.render();
    
    // Custom CSS for events
    var style = document.createElement('style');
    style.textContent = `
        .custom-event {
            border-radius: 8px !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }
        .fc-event-title {
            font-size: 12px !important;
            padding: 2px 4px !important;
        }
        .fc-daygrid-event {
            margin: 1px 2px !important;
        }
        .fc-toolbar-title {
            font-size: 1.5em !important;
            font-weight: 700 !important;
            color: #2d3748 !important;
        }
        .fc-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
        }
        .fc-button:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%) !important;
        }
        .fc-button-active {
            background: linear-gradient(135deg, #4c51bf 0%, #553c9a 100%) !important;
        }
    `;
    document.head.appendChild(style);
});

function showEventDetails(event) {
    currentEventId = event.id;
    const props = event.extendedProps;
    
    document.getElementById('modalTitle').textContent = event.title;
    
    const content = `
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-calendar" style="color: #667eea; width: 20px;"></i>
                <strong>التاريخ:</strong>
                <span>${formatDate(event.start)}</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-user-tie" style="color: #667eea; width: 20px;"></i>
                <strong>المفتش:</strong>
                <span>${props.inspector}</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-university" style="color: #667eea; width: 20px;"></i>
                <strong>الجهة:</strong>
                <span>${props.authority}</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-industry" style="color: #667eea; width: 20px;"></i>
                <strong>المنشأة:</strong>
                <span>${props.facility}</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-tags" style="color: #667eea; width: 20px;"></i>
                <strong>النوع:</strong>
                <span>${props.type}</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-clipboard-list" style="color: #667eea; width: 20px;"></i>
                <strong>الحالة:</strong>
                <span style="background: ${event.backgroundColor}; color: white; padding: 4px 8px; border-radius: 6px; font-size: 12px;">${props.status}</span>
            </div>
            
            ${props.compliance ? `
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-star" style="color: #667eea; width: 20px;"></i>
                <strong>تقييم الامتثال:</strong>
                <span>${props.compliance}</span>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('eventModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('eventModal').style.display = 'none';
    currentEventId = null;
}

function editInspection() {
    if (currentEventId) {
        window.location.href = '{{ route("tenant.inventory.regulatory.inspections.index") }}';
    }
}

function formatDate(date) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        weekday: 'long'
    };
    return new Date(date).toLocaleDateString('ar-SA', options);
}

// Close modal when clicking outside
document.getElementById('eventModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

@endsection
