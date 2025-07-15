@extends('layouts.modern')

@section('title', 'سجل التقارير')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 2;">
        <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 15px 0; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-history"></i>
            سجل التقارير
        </h1>
        <p style="font-size: 18px; opacity: 0.9; margin: 0;">
            تتبع جميع التقارير المنفذة مع إمكانية إعادة التشغيل والتحميل
        </p>
    </div>
    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"rgba(255,255,255,0.1)\"/></svg>') repeat; animation: float 20s infinite linear;"></div>
</div>

<!-- Filters -->
<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 25px;">
    <h3 style="font-size: 18px; font-weight: 600; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-filter" style="color: #667eea;"></i>
        تصفية السجل
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">الفترة الزمنية:</label>
            <select id="periodFilter" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <option value="all">جميع الفترات</option>
                <option value="today">اليوم</option>
                <option value="week">هذا الأسبوع</option>
                <option value="month">هذا الشهر</option>
                <option value="custom">فترة مخصصة</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">حالة التنفيذ:</label>
            <select id="statusFilter" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <option value="all">جميع الحالات</option>
                <option value="completed">مكتمل</option>
                <option value="failed">فشل</option>
                <option value="running">قيد التنفيذ</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; font-weight: 600; color: #4a5568; margin-bottom: 5px;">نوع التقرير:</label>
            <select id="categoryFilter" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <option value="all">جميع الأنواع</option>
                <option value="sales">المبيعات</option>
                <option value="financial">المالية</option>
                <option value="inventory">المخزون</option>
                <option value="customers">العملاء</option>
                <option value="products">المنتجات</option>
            </select>
        </div>
        
        <div style="display: flex; align-items: end;">
            <button onclick="applyFilters()" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; width: 100%;">
                <i class="fas fa-search"></i> تطبيق الفلاتر
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <i class="fas fa-check-circle" style="font-size: 36px; margin-bottom: 15px; opacity: 0.8;"></i>
        <h3 style="font-size: 28px; font-weight: 700; margin: 0 0 5px 0;" id="completedCount">0</h3>
        <p style="opacity: 0.9; margin: 0;">تقارير مكتملة</p>
    </div>
    
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 36px; margin-bottom: 15px; opacity: 0.8;"></i>
        <h3 style="font-size: 28px; font-weight: 700; margin: 0 0 5px 0;" id="failedCount">0</h3>
        <p style="opacity: 0.9; margin: 0;">تقارير فاشلة</p>
    </div>
    
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <i class="fas fa-clock" style="font-size: 36px; margin-bottom: 15px; opacity: 0.8;"></i>
        <h3 style="font-size: 28px; font-weight: 700; margin: 0 0 5px 0;" id="avgTime">0</h3>
        <p style="opacity: 0.9; margin: 0;">متوسط وقت التنفيذ (ثانية)</p>
    </div>
    
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 25px; border-radius: 15px; text-align: center;">
        <i class="fas fa-download" style="font-size: 36px; margin-bottom: 15px; opacity: 0.8;"></i>
        <h3 style="font-size: 28px; font-weight: 700; margin: 0 0 5px 0;" id="totalDownloads">0</h3>
        <p style="opacity: 0.9; margin: 0;">إجمالي التحميلات</p>
    </div>
</div>

<!-- Reports History Table -->
<div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0;">سجل التقارير المنفذة</h3>
        <button onclick="refreshHistory()" style="background: #e2e8f0; color: #4a5568; padding: 10px 15px; border: none; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-sync-alt"></i> تحديث
        </button>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; text-align: right; font-weight: 600; color: #2d3748;">اسم التقرير</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الحالة</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">تاريخ التنفيذ</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">وقت التنفيذ</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">عدد السجلات</th>
                    <th style="padding: 15px; text-align: center; font-weight: 600; color: #2d3748;">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="historyTableBody">
                <!-- Data will be loaded dynamically -->
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div id="pagination" style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
        <!-- Pagination will be loaded dynamically -->
    </div>
</div>

<script>
let currentPage = 1;
let currentFilters = {};

// Load history on page load
document.addEventListener('DOMContentLoaded', function() {
    loadHistory();
    loadStatistics();
});

function loadHistory(page = 1) {
    currentPage = page;
    
    // Show loading
    document.getElementById('historyTableBody').innerHTML = `
        <tr>
            <td colspan="6" style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #667eea;"></i>
                <p style="margin-top: 10px; color: #718096;">جاري تحميل السجل...</p>
            </td>
        </tr>
    `;
    
    // Simulate API call
    setTimeout(() => {
        const sampleData = [
            {
                id: 1,
                report_name: 'تقرير المبيعات اليومية',
                status: 'completed',
                created_at: '2025-07-15 14:30:00',
                execution_time: 2.45,
                row_count: 156,
                file_path: '/reports/sales_daily_2025-07-15.pdf'
            },
            {
                id: 2,
                report_name: 'تقرير أداء المندوبين',
                status: 'completed',
                created_at: '2025-07-15 13:15:00',
                execution_time: 1.89,
                row_count: 12,
                file_path: '/reports/sales_reps_2025-07-15.xlsx'
            },
            {
                id: 3,
                report_name: 'تقرير التدفقات النقدية',
                status: 'failed',
                created_at: '2025-07-15 12:00:00',
                execution_time: null,
                row_count: null,
                file_path: null
            },
            {
                id: 4,
                report_name: 'تقرير مستويات المخزون',
                status: 'completed',
                created_at: '2025-07-15 11:45:00',
                execution_time: 3.21,
                row_count: 89,
                file_path: '/reports/inventory_levels_2025-07-15.pdf'
            },
            {
                id: 5,
                report_name: 'العملاء الأكثر شراءً',
                status: 'running',
                created_at: '2025-07-15 11:30:00',
                execution_time: null,
                row_count: null,
                file_path: null
            }
        ];
        
        renderHistoryTable(sampleData);
        renderPagination(1, 5, 20); // current page, total pages, total items
    }, 1000);
}

function renderHistoryTable(data) {
    const tbody = document.getElementById('historyTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #718096;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>لا توجد تقارير في السجل</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = data.map(item => `
        <tr style="border-bottom: 1px solid #e2e8f0; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
            <td style="padding: 15px;">
                <div style="font-weight: 600; color: #2d3748;">${item.report_name}</div>
                <div style="font-size: 12px; color: #718096; margin-top: 2px;">ID: ${item.id}</div>
            </td>
            <td style="padding: 15px; text-align: center;">
                ${getStatusBadge(item.status)}
            </td>
            <td style="padding: 15px; text-align: center; color: #4a5568;">
                ${formatDateTime(item.created_at)}
            </td>
            <td style="padding: 15px; text-align: center; color: #4a5568;">
                ${item.execution_time ? item.execution_time + ' ثانية' : '-'}
            </td>
            <td style="padding: 15px; text-align: center; color: #4a5568;">
                ${item.row_count ? item.row_count.toLocaleString() : '-'}
            </td>
            <td style="padding: 15px; text-align: center;">
                ${getActionButtons(item)}
            </td>
        </tr>
    `).join('');
}

function getStatusBadge(status) {
    const badges = {
        'completed': '<span style="background: #10b981; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-check"></i> مكتمل</span>',
        'failed': '<span style="background: #ef4444; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-times"></i> فشل</span>',
        'running': '<span style="background: #f59e0b; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-spinner fa-spin"></i> قيد التنفيذ</span>',
        'pending': '<span style="background: #6b7280; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-clock"></i> في الانتظار</span>'
    };
    
    return badges[status] || badges['pending'];
}

function getActionButtons(item) {
    let buttons = '';
    
    if (item.status === 'completed' && item.file_path) {
        buttons += `
            <button onclick="downloadReport(${item.id})" style="background: #10b981; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; margin: 0 2px; font-size: 12px;" title="تحميل">
                <i class="fas fa-download"></i>
            </button>
        `;
    }
    
    buttons += `
        <button onclick="rerunReport(${item.id})" style="background: #3b82f6; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; margin: 0 2px; font-size: 12px;" title="إعادة تشغيل">
            <i class="fas fa-redo"></i>
        </button>
    `;
    
    if (item.status === 'completed') {
        buttons += `
            <button onclick="shareReport(${item.id})" style="background: #8b5cf6; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; margin: 0 2px; font-size: 12px;" title="مشاركة">
                <i class="fas fa-share"></i>
            </button>
        `;
    }
    
    buttons += `
        <button onclick="deleteExecution(${item.id})" style="background: #ef4444; color: white; padding: 6px 10px; border: none; border-radius: 6px; cursor: pointer; margin: 0 2px; font-size: 12px;" title="حذف">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    return buttons;
}

function renderPagination(currentPage, totalPages, totalItems) {
    const pagination = document.getElementById('pagination');
    
    let paginationHTML = `
        <span style="color: #718096; margin-left: 20px;">
            إجمالي ${totalItems} عنصر
        </span>
    `;
    
    if (totalPages > 1) {
        // Previous button
        if (currentPage > 1) {
            paginationHTML += `
                <button onclick="loadHistory(${currentPage - 1})" style="background: #e2e8f0; color: #4a5568; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; margin: 0 2px;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
        }
        
        // Page numbers
        for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
            const isActive = i === currentPage;
            paginationHTML += `
                <button onclick="loadHistory(${i})" style="background: ${isActive ? '#3b82f6' : '#e2e8f0'}; color: ${isActive ? 'white' : '#4a5568'}; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; margin: 0 2px; font-weight: ${isActive ? '600' : 'normal'};">
                    ${i}
                </button>
            `;
        }
        
        // Next button
        if (currentPage < totalPages) {
            paginationHTML += `
                <button onclick="loadHistory(${currentPage + 1})" style="background: #e2e8f0; color: #4a5568; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; margin: 0 2px;">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
        }
    }
    
    pagination.innerHTML = paginationHTML;
}

function loadStatistics() {
    // Simulate loading statistics
    setTimeout(() => {
        document.getElementById('completedCount').textContent = '156';
        document.getElementById('failedCount').textContent = '3';
        document.getElementById('avgTime').textContent = '2.4';
        document.getElementById('totalDownloads').textContent = '89';
    }, 500);
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('ar-EG', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function applyFilters() {
    currentFilters = {
        period: document.getElementById('periodFilter').value,
        status: document.getElementById('statusFilter').value,
        category: document.getElementById('categoryFilter').value
    };
    
    loadHistory(1);
}

function refreshHistory() {
    loadHistory(currentPage);
    loadStatistics();
}

function downloadReport(id) {
    alert(`📥 تحميل التقرير رقم ${id}\n\nسيتم تحميل الملف تلقائياً...`);
}

function rerunReport(id) {
    if (confirm('هل تريد إعادة تشغيل هذا التقرير؟')) {
        alert(`🔄 إعادة تشغيل التقرير رقم ${id}\n\nسيتم تشغيل التقرير بنفس المعايير السابقة...`);
    }
}

function shareReport(id) {
    alert(`📤 مشاركة التقرير رقم ${id}\n\nخيارات المشاركة:\n• إرسال بالبريد الإلكتروني\n• إنشاء رابط مشاركة\n• تصدير لوسائل التواصل`);
}

function deleteExecution(id) {
    if (confirm('هل تريد حذف هذا السجل؟\n\nلن يمكن استرداده بعد الحذف.')) {
        alert(`🗑️ حذف سجل التقرير رقم ${id}\n\nتم حذف السجل بنجاح.`);
        loadHistory(currentPage);
    }
}
</script>

@endsection
