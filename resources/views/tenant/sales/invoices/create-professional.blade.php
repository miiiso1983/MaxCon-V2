<!DOCTYPE html>
<!-- ENHANCED INVOICE CREATION WITH MODERN DESIGN - UPDATED 2024 -->
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إنشاء فاتورة احترافية - {{ config('app.name') }}</title>

    <!-- Block all external script loading -->
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline'; object-src 'none';">

    <!-- Prevent external script injection -->
    <script>
        // Override script loading to prevent external scripts
        (function() {
            const originalAppendChild = Node.prototype.appendChild;
            const originalInsertBefore = Node.prototype.insertBefore;

            function blockExternalScripts(node) {
                if (node.tagName === 'SCRIPT' && node.src &&
                    (node.src.includes('jquery') || node.src.includes('select2') ||
                     node.src.includes('code.jquery.com') || node.src.includes('charts'))) {
                    console.warn('🚫 Blocked external script:', node.src);
                    return document.createTextNode('');
                }
                return node;
            }

            Node.prototype.appendChild = function(node) {
                return originalAppendChild.call(this, blockExternalScripts(node));
            };

            Node.prototype.insertBefore = function(node, referenceNode) {
                return originalInsertBefore.call(this, blockExternalScripts(node), referenceNode);
            };
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome with fallback -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Fallback Font Awesome -->
    <script>
        if (!window.FontAwesome) {
            document.write('<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">');
        }
    </script>

    <!-- Custom Select Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom-select.css') }}">

    <!-- MaxCon Enhanced Styles -->
    <link rel="stylesheet" href="{{ asset('css/maxcon-enhancements.css') }}">

    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f8fafc;
        }
    /* Modern Professional Invoice Design */
    .invoice-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: calc(100vh - 70px);
        padding: 30px 0;
    }

    .invoice-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
    }

    .invoice-title {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .invoice-subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin-top: 8px;
    }

    .invoice-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
        align-items: start;
    }

    .main-form {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .sidebar-info {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        padding: 25px;
        height: fit-content;
        position: sticky;
        top: 20px;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .form-section {
        padding: 30px;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        opacity: 0.1;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f5f9;
    }

    .section-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
        align-items: end;
    }

    .form-group {
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-label.required::after {
        content: ' *';
        color: #ef4444;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        padding: 16px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
        font-family: 'Cairo', sans-serif;
        min-height: 50px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .form-control:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
    }

    /* Custom Select Styling */
    .custom-select-wrapper {
        position: relative;
    }

    .custom-select-wrapper .form-control {
        padding-right: 45px;
        cursor: pointer;
    }

    .custom-select-wrapper::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    /* Invoice Items Table */
    .items-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        margin: 0 -30px;
        padding: 35px;
        border-radius: 0 0 20px 20px;
    }

    .items-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin-bottom: 25px;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .items-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 15px;
        text-align: center;
        font-weight: 700;
        font-size: 14px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        position: relative;
    }

    .items-table th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: rgba(255,255,255,0.2);
    }

    .items-table th:first-child {
        text-align: right;
    }

    .items-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        background: white;
        transition: all 0.2s ease;
    }

    .items-table tr:last-child td {
        border-bottom: none;
    }

    .items-table tr:hover td {
        background: #f8fafc;
        transform: scale(1.005);
    }

    .item-row {
        animation: slideInUp 0.3s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .add-item-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 16px 28px;
        border-radius: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin: 20px auto 0;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        font-size: 14px;
        min-width: 200px;
        position: relative;
        overflow: hidden;
    }

    .add-item-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .add-item-btn:hover::before {
        left: 100%;
    }

    .add-item-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
    }

    .remove-item-btn {
        background: #ef4444;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .remove-item-btn:hover {
        background: #dc2626;
        transform: scale(1.05);
    }

    /* Sidebar Info Styling */
    .info-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #667eea;
    }

    .info-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f5f9;
    }

    .info-title i {
        color: #667eea;
        font-size: 18px;
        background: rgba(102, 126, 234, 0.1);
        padding: 8px;
        border-radius: 8px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: rgba(102, 126, 234, 0.02);
        margin: 0 -10px;
        padding: 12px 10px;
        border-radius: 8px;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #64748b;
        font-size: 14px;
    }

    .info-value {
        font-weight: 700;
        color: #1e293b;
        font-size: 14px;
        background: rgba(102, 126, 234, 0.05);
        padding: 4px 8px;
        border-radius: 6px;
    }

    .debt-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #f59e0b;
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        color: #92400e;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .debt-danger {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        border: 1px solid #ef4444;
        color: #991b1b;
    }

    /* Enhanced Financial Department Styles */
    .financial-department-section {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .financial-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        border: 1px solid rgba(102, 126, 234, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .financial-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    }

    .financial-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        overflow: hidden;
    }

    .financial-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .financial-icon-wrapper {
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .financial-icon-wrapper i {
        font-size: 20px;
        color: white;
    }

    .financial-title-group {
        flex: 1;
    }

    .financial-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: white;
    }

    .financial-subtitle {
        font-size: 13px;
        opacity: 0.9;
        margin: 4px 0 0 0;
        color: white;
    }

    .financial-status-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.15);
        padding: 8px 12px;
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .status-dot.status-good { background: #10b981; }
    .status-dot.status-warning { background: #f59e0b; }
    .status-dot.status-danger { background: #ef4444; }

    .status-text {
        font-size: 12px;
        font-weight: 600;
        color: white;
    }

    .financial-metrics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding: 20px;
    }

    .metric-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        padding: 18px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s ease;
    }

    .metric-card:hover::before {
        width: 8px;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .metric-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 16px;
    }

    .metric-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .metric-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    .metric-value {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
    }

    .metric-progress {
        margin-top: 8px;
    }

    .progress-bar {
        background: #e2e8f0;
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .progress-fill {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    .progress-text {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }

    .trend-icon {
        font-size: 12px;
        color: #10b981;
    }

    .trend-text {
        font-size: 11px;
        color: #10b981;
        font-weight: 500;
    }

    .metric-change {
        margin-top: 4px;
    }

    .change-amount {
        font-size: 12px;
        color: #667eea;
        font-weight: 600;
        display: block;
    }

    .change-label {
        font-size: 10px;
        color: #64748b;
    }

    .metric-status {
        margin-top: 6px;
    }

    .status-badge {
        background: #dcfce7;
        color: #166534;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        display: inline-block;
    }

    .status-badge.status-available { background: #dcfce7; color: #166534; }
    .status-badge.status-limited { background: #fef3c7; color: #92400e; }
    .status-badge.status-exceeded { background: #fee2e2; color: #991b1b; }

    .credit-risk-assessment {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 15px 20px;
        margin: 0 -1px -1px -1px;
        border-radius: 0 0 19px 19px;
    }

    .risk-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 10px;
    }

    .risk-level {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .risk-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .risk-indicator.risk-low {
        background: #dcfce7;
        color: #166534;
    }

    .risk-indicator.risk-medium {
        background: #fef3c7;
        color: #92400e;
    }

    .risk-indicator.risk-high {
        background: #fee2e2;
        color: #991b1b;
    }

    .risk-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .invoice-status-badge {
        background: rgba(255,255,255,0.15);
        padding: 6px 12px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .badge-text {
        font-size: 11px;
        font-weight: 600;
        color: white;
    }

    .totals-breakdown {
        padding: 20px;
    }

    .breakdown-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .breakdown-item:last-child {
        border-bottom: none;
    }

    .breakdown-item:hover {
        background: #f8fafc;
        margin: 0 -20px;
        padding: 12px 20px;
        border-radius: 10px;
    }

    .breakdown-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .breakdown-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .breakdown-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    .breakdown-value {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
    }

    .breakdown-value.discount-value {
        color: #dc2626;
    }

    .breakdown-value.samples-value {
        color: #059669;
    }

    .breakdown-value.total-value {
        color: #667eea;
        font-size: 18px;
    }

    .breakdown-percentage {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 12px;
    }

    .breakdown-unit {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .breakdown-currency {
        font-size: 11px;
        color: #667eea;
        font-weight: 600;
        background: #e0e7ff;
        padding: 4px 8px;
        border-radius: 12px;
    }

    .breakdown-separator {
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1px;
        margin: 10px 0;
    }

    .total-item {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        border: 1px solid #c7d2fe;
        border-radius: 12px;
        margin: 10px -20px 0 -20px;
        padding: 15px 20px !important;
    }

    .payment-terms-preview {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 15px 20px;
        margin: 0 -1px -1px -1px;
        border-radius: 0 0 19px 19px;
    }

    .terms-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 10px;
    }

    .terms-content {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .term-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .term-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    .term-value {
        font-size: 12px;
        color: #1e293b;
        font-weight: 600;
    }

    .financial-alerts-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .financial-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid;
        transition: all 0.3s ease;
    }

    .financial-alert.alert-danger {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-color: #f87171;
        color: #dc2626;
    }

    .financial-alert.alert-info {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #60a5fa;
        color: #2563eb;
    }

    .financial-alert.alert-success {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-color: #4ade80;
        color: #16a34a;
    }

    .alert-icon {
        background: rgba(255,255,255,0.8);
        border-radius: 8px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .alert-message {
        font-size: 12px;
        opacity: 0.9;
    }

    .alert-action {
        display: flex;
        align-items: center;
    }

    .alert-btn {
        background: rgba(255,255,255,0.8);
        border: none;
        border-radius: 6px;
        padding: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .alert-btn:hover {
        background: rgba(255,255,255,1);
        transform: scale(1.1);
    }

    /* Fallback styles for enhanced features */
    .enhanced-select {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
    }

    .enhanced-select:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    }



    /* Enhanced input fallback */
    .enhanced-input {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 10px 12px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .enhanced-input:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    }

    /* Enhanced Product Option Styling */
    .product-name {
        font-weight: 600;
        color: #1e293b;
    }

    .product-code {
        color: #64748b;
        font-size: 0.9em;
        margin-left: 5px;
    }

    .product-company {
        color: #059669;
        font-size: 0.9em;
        font-style: italic;
    }

    /* Enhanced Select Option Hover */
    .enhanced-select option:hover {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
    }

    /* UNIFIED STYLING FOR ALL PRODUCT DROPDOWNS */
    select[name*="[product_id]"],
    .enhanced-select,
    select.form-control[data-custom-select] {
        /* Base styling - identical for all */
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 12px 16px 12px 16px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
        color: #1e293b !important;
        width: 100% !important;
        min-height: 48px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;

        /* Custom dropdown arrow */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: left 12px center !important;
        background-repeat: no-repeat !important;
        background-size: 16px 16px !important;
        padding-left: 40px !important;

        /* Shadow and effects */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06) !important;
    }

    /* Focus state - identical for all */
    select[name*="[product_id]"]:focus,
    .enhanced-select:focus,
    select.form-control[data-custom-select]:focus {
        outline: none !important;
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        background: white !important;
        transform: translateY(-1px) !important;
    }

    /* Hover state - identical for all */
    select[name*="[product_id]"]:hover:not(:focus),
    .enhanced-select:hover:not(:focus),
    select.form-control[data-custom-select]:hover:not(:focus) {
        border-color: #c7d2fe !important;
        background: white !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        transform: translateY(-1px) !important;
    }

    /* Active/Selected state */
    select[name*="[product_id]"]:active,
    .enhanced-select:active,
    select.form-control[data-custom-select]:active {
        transform: translateY(0) !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }

    /* Ensure all product dropdown containers have consistent styling */
    .enhanced-product-dropdown {
        position: relative !important;
        width: 100% !important;
        margin-bottom: 8px !important;
    }

    /* Product dropdown options styling */
    select[name*="[product_id]"] option,
    .enhanced-select option,
    select.form-control[data-custom-select] option {
        padding: 8px 12px !important;
        font-size: 14px !important;
        line-height: 1.4 !important;
        color: #1e293b !important;
        background: white !important;
    }

    select[name*="[product_id]"] option:hover,
    .enhanced-select option:hover,
    select.form-control[data-custom-select] option:hover {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%) !important;
        color: #1e40af !important;
    }

    /* Enhanced Product Dropdown Styles */
    .enhanced-product-dropdown {
        position: relative;
    }

    .enhanced-select {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 40px;
    }

    .enhanced-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .enhanced-select:hover {
        border-color: #c7d2fe;
        background: white;
    }

    .product-info-display {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        border: 1px solid #c7d2fe;
        border-radius: 8px;
        padding: 8px 12px;
        margin-top: 8px;
        font-size: 11px;
        animation: slideDown 0.3s ease;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-label {
        color: #64748b;
        font-weight: 500;
    }

    .info-value {
        color: #1e293b;
        font-weight: 600;
    }

    .stock-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #f59e0b;
        border-radius: 6px;
        padding: 6px 10px;
        margin-top: 6px;
        font-size: 11px;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 6px;
        animation: pulse 2s infinite;
    }

    .stock-warning i {
        color: #f59e0b;
    }

    /* Enhanced Input Styles */
    .enhanced-input {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .enhanced-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: white;
    }

    .enhanced-input:hover {
        border-color: #c7d2fe;
        background: white;
    }



    /* Enhanced Total Display */
    .total-display {
        position: relative;
        display: flex;
        align-items: center;
    }

    .total-input {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%) !important;
        border: 2px solid #c7d2fe !important;
        border-radius: 10px;
        padding: 10px 35px 10px 12px !important;
        font-size: 14px;
        font-weight: 700;
        color: #667eea !important;
    }

    .total-currency {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 11px;
        color: #667eea;
        font-weight: 600;
        pointer-events: none;
    }

    /* Enhanced Remove Button */
    .enhanced-remove-btn {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 2px solid #f87171;
        border-radius: 10px;
        padding: 8px;
        color: #dc2626;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }

    .enhanced-remove-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, #fecaca 0%, #f87171 100%);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(248, 113, 113, 0.3);
    }

    .enhanced-remove-btn:disabled {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }

    /* Animation for new rows */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .item-row {
        animation: fadeInUp 0.3s ease;
    }

    /* FOC Row Styling */
    .item-row.foc-row {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-left: 4px solid #10b981;
    }

    .item-row.foc-row .total-input {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%) !important;
        border-color: #10b981 !important;
        color: #059669 !important;
    }

    .item-row.foc-row .total-currency {
        color: #059669;
    }

    .item-row.foc-row .enhanced-input {
        border-color: #a7f3d0;
    }

    .item-row.foc-row .enhanced-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Action Buttons */
    .actions-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        padding: 35px;
        margin-top: 30px;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        align-items: center;
    }

    .btn {
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-family: 'Cairo', sans-serif;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
        color: white;
        text-decoration: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.5);
        color: white;
        text-decoration: none;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid #667eea;
        color: #667eea;
        box-shadow: none;
    }

    .btn-outline:hover {
        background: #667eea;
        color: white;
        text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .invoice-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .sidebar-info {
            position: static;
            order: -1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .nav-container {
            padding: 0 15px;
        }

        .nav-links {
            gap: 10px;
        }

        .nav-link {
            padding: 6px 12px;
            font-size: 14px;
        }

        .invoice-wrapper {
            padding: 0 15px;
        }

        .page-header {
            padding: 25px 20px;
            text-align: center;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-title h1 {
            font-size: 24px;
        }

        .form-section {
            padding: 25px 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .items-section {
            margin: 0 -20px;
            padding: 25px 20px;
        }

        .items-table {
            font-size: 12px;
        }

        .items-table th,
        .items-table td {
            padding: 12px 8px;
        }

        .actions-section {
            padding: 25px 20px;
        }

        .actions-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .btn {
            width: 100%;
            justify-content: center;
            min-height: 50px;
        }

        .sidebar-info {
            grid-template-columns: 1fr;
        }

        .info-card {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .section-title {
            font-size: 16px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .section-icon {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        .form-control {
            padding: 12px 16px;
            min-height: 45px;
        }

        .add-item-btn {
            min-width: 150px;
            padding: 14px 20px;
        }
    }

    /* Loading States */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .loading {
        position: relative;
        overflow: hidden;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Error and Success States */
    .form-control.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .form-control.success {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Enhanced Search Styling */
    .search-highlight {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 2px 4px;
        border-radius: 4px;
        font-weight: 600;
    }

    /* Stock Info Display */
    .stock-info {
        font-size: 11px;
        color: #6b7280;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stock-low {
        color: #ef4444;
    }

    .stock-medium {
        color: #f59e0b;
    }

    .stock-high {
        color: #10b981;
    }

    /* QR Code Preview */
    .qr-preview {
        background: #f8fafc;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-top: 15px;
    }

    .qr-placeholder {
        color: #6b7280;
        font-size: 14px;
    }

    /* Advanced Animations */
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* Enhanced Tooltips */
    .tooltip {
        position: relative;
        cursor: help;
    }

    .tooltip::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        background: #1f2937;
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .tooltip:hover::after {
        opacity: 1;
        visibility: visible;
    }

    /* Print Styles */
    @media print {
        .invoice-header,
        .actions-section,
        .sidebar-info {
            display: none !important;
        }

        .main-form {
            box-shadow: none;
            border: 1px solid #000;
        }

        .items-table {
            border: 1px solid #000;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
        }
    }

    /* Notification Styles */
    .notification {
        font-family: 'Cairo', sans-serif;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 300px;
        max-width: 500px;
        word-wrap: break-word;
    }

    .notification i {
        font-size: 16px;
        flex-shrink: 0;
    }

    /* Enhanced Form Controls */
    .form-control.loading {
        background-image: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Advanced Hover Effects */
    .items-table tr:hover .form-control {
        border-color: #d1d5db;
        transform: translateY(-1px);
    }

    .items-table tr:hover .remove-item-btn {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Focus States */
    .form-control:focus + .stock-info {
        opacity: 1;
        transform: translateY(0);
    }

    /* Smooth Transitions */
    * {
        transition: all 0.2s ease;
    }

    .form-control,
    .btn,
    .items-table tr {
        transition: all 0.3s ease;
    }

    /* Enhanced Visual Feedback */
    .form-control.success {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        background-image: linear-gradient(45deg, transparent 40%, rgba(16, 185, 129, 0.1) 50%, transparent 60%);
    }

    .form-control.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(5px);
    }

    .loading-spinner {
        background: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .loading-spinner i {
        font-size: 24px;
        color: #667eea;
        margin-bottom: 15px;
    }

    /* Enhanced Sidebar */
    .sidebar-info {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e5e7eb;
    }

    .info-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* Enhanced Buttons */
    .btn {
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    /* Responsive Enhancements */
    @media (max-width: 640px) {
        .notification {
            right: 10px;
            left: 10px;
            min-width: auto;
        }

        .invoice-title {
            font-size: 20px;
        }

        .items-table {
            font-size: 11px;
        }

        .form-control {
            padding: 10px 12px;
            font-size: 13px;
        }
    }

    /* Dark Mode Support (Optional) */
    @media (prefers-color-scheme: dark) {
        .invoice-container {
            background: #1f2937;
        }

        .main-form,
        .sidebar-info {
            background: #374151;
            color: #f9fafb;
        }

        .form-control {
            background: #4b5563;
            border-color: #6b7280;
            color: #f9fafb;
        }

        .items-table th {
            background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
        }
    }

        /* Navigation Bar */
        .top-nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            color: white;
            font-size: 20px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-info {
            flex: 1;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .title-icon {
            background: rgba(255,255,255,0.2);
            padding: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .title-icon i {
            font-size: 24px;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-back {
            background: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>



<!-- Navigation -->
<nav class="top-nav">
    <div class="nav-container">
        <a href="{{ route('tenant.dashboard') }}" class="nav-brand">
            <i class="fas fa-cube"></i>
            MaxCon ERP
        </a>
        <div class="nav-links">
            <a href="{{ route('tenant.sales.invoices.index') }}" class="nav-link">
                <i class="fas fa-file-invoice"></i>
                إدارة الفواتير
            </a>
            <a href="{{ route('tenant.sales.orders.index') }}" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                طلبات المبيعات
            </a>
            <a href="{{ route('tenant.dashboard') }}" class="nav-link">
                <i class="fas fa-home"></i>
                الرئيسية
            </a>
        </div>
    </div>
</nav>

<div class="invoice-container" style="margin-top: 120px;">
    <div class="invoice-wrapper">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <div class="header-title">
                        <div class="title-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h1>إنشاء فاتورة احترافية - مع التصميم المحسن</h1>
                    </div>
                    <p class="header-subtitle">نظام إدارة الفواتير المتطور مع QR Code والبحث الذكي (تحديث 2024)</p>

                    <!-- VERIFICATION BANNER -->
                    <div style="background: linear-gradient(45deg, #10b981, #059669); color: white; padding: 20px; border-radius: 15px; margin: 20px 0; text-align: center; font-weight: bold; font-size: 18px; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4); border: 3px solid #ffffff;">
                        <i class="fas fa-check-circle" style="margin-left: 10px; font-size: 24px;"></i>
                        ✅ النسخة المحسنة مع تصميم المنتجات المطور
                        <br>
                        <span style="font-size: 14px; opacity: 0.9;">إذا كنت ترى هذه الرسالة فالتحديث يعمل بنجاح!</span>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('tenant.sales.invoices.index') }}" class="btn-back">
                        <i class="fas fa-arrow-right"></i>
                        <span>عودة للفواتير</span>
                    </a>
                </div>
            </div>
        </div>

        <form id="invoiceForm" method="POST" action="{{ route('tenant.sales.invoices.store') }}">
            @csrf
            
            <div class="invoice-grid">
                <!-- Main Form -->
                <div class="main-form">
                    <!-- Customer Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            معلومات العميل
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">العميل</label>
                                <select name="customer_id" required class="form-control" data-custom-select 
                                        data-placeholder="اختر العميل" data-searchable="true" id="customerSelect">
                                    <option value="">اختر العميل</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                                data-credit-limit="{{ $customer->credit_limit ?? 0 }}"
                                                data-previous-balance="{{ $customer->current_balance ?? ($customer->previous_balance ?? 0) }}"
                                                data-phone="{{ $customer->phone ?? '' }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} 
                                            @if($customer->customer_code)
                                                ({{ $customer->customer_code }})
                                            @endif
                                            @if($customer->phone)
                                                - {{ $customer->phone }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">تاريخ الفاتورة</label>
                                <input type="date" name="invoice_date" class="form-control" 
                                       value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">تاريخ الاستحقاق</label>
                                <input type="date" name="due_date" class="form-control" 
                                       value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">المندوب</label>
                                <input type="text" name="sales_representative" class="form-control" 
                                       placeholder="اسم المندوب" value="{{ old('sales_representative') }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">المستودع</label>
                                <input type="text" name="warehouse_name" class="form-control" 
                                       placeholder="اسم المستودع" value="{{ old('warehouse_name', 'المستودع الرئيسي') }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">نوع الفاتورة</label>
                                <select name="type" class="form-control" data-custom-select>
                                    <option value="sales" {{ old('type', 'sales') == 'sales' ? 'selected' : '' }}>فاتورة مبيعات</option>
                                    <option value="proforma" {{ old('type') == 'proforma' ? 'selected' : '' }}>فاتورة أولية</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="form-section items-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            عناصر الفاتورة
                        </div>
                        
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 35%;">المنتج</th>
                                    <th style="width: 12%;">الكمية</th>
                                    <th style="width: 15%;">السعر</th>
                                    <th style="width: 12%;">الخصم</th>
                                    <th style="width: 10%;">العينات</th>
                                    <th style="width: 10%;">المجموع</th>
                                    <th style="width: 6%;">حذف</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <tr class="item-row">
                                    <td>
                                        <div class="enhanced-product-dropdown">
                                            <select name="items[0][product_id]" required class="form-control enhanced-select" data-custom-select
                                                    data-placeholder="اختر المنتج" data-searchable="true" onchange="simpleUpdateProductInfo(this, 0)">
                                                <option value="">اختر المنتج</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                            data-price="{{ $product->selling_price ?? $product->unit_price ?? 0 }}"
                                                            data-stock="{{ $product->current_stock ?? 0 }}"
                                                            data-unit="{{ $product->unit ?? 'قطعة' }}"
                                                            data-code="{{ $product->product_code ?? '' }}"
                                                            data-company="{{ $product->company ?? '' }}"
                                                            data-category="{{ $product->category ?? '' }}">
                                                        <span class="product-name">{{ $product->name }}</span>
                                                        @if($product->product_code)
                                                            <span class="product-code">({{ $product->product_code }})</span>
                                                        @endif
                                                        @if($product->company)
                                                            <span class="product-company">- {{ $product->company }}</span>
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="product-info-display" id="productInfo0" style="display: none;">
                                                <div class="info-row">
                                                    <span class="info-label">الكود:</span>
                                                    <span class="info-value" id="productCode0">-</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">المخزون:</span>
                                                    <span class="info-value" id="productStock0">-</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">الوحدة:</span>
                                                    <span class="info-value" id="productUnit0">-</span>
                                                </div>
                                            </div>
                                            <div class="stock-warning" id="stockWarning0" style="display: none;">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <span>مخزون منخفض</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" min="1" step="1" required
                                               class="form-control enhanced-input" placeholder="1" value="1"
                                               onchange="simpleCalculateTotal(0)" oninput="validateStock(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                                               class="form-control enhanced-input" placeholder="0.00" value="0"
                                               onchange="simpleCalculateTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][discount_amount]" min="0" step="0.01"
                                               class="form-control enhanced-input" placeholder="0.00" value="0"
                                               onchange="simpleCalculateTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][free_samples]" min="0" step="1"
                                               class="form-control enhanced-input" placeholder="0" value="0"
                                               style="text-align: center;">
                                    </td>
                                    <td>
                                        <div class="total-display">
                                            <input type="number" name="items[0][total_amount]" readonly
                                                   class="form-control total-input" placeholder="0.00" value="0"
                                                   style="background: #f9fafb; text-align: center;">
                                            <div class="total-currency">د.ع</div>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" onclick="removeItem(0)" class="remove-item-btn enhanced-remove-btn" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <button type="button" onclick="addItem()" class="add-item-btn">
                            <i class="fas fa-plus"></i>
                            إضافة منتج
                        </button>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            معلومات إضافية
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" class="form-control" rows="3" 
                                          placeholder="ملاحظات إضافية...">{{ old('notes') }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">شروط الدفع</label>
                                <textarea name="payment_terms" class="form-control" rows="3" 
                                          placeholder="شروط وأحكام الدفع...">{{ old('payment_terms', 'الدفع خلال 30 يوم من تاريخ الفاتورة') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="sidebar-info">
                    <!-- Enhanced Financial Department Section -->
                    <div class="financial-department-section">
                        <!-- Customer Financial Profile -->
                        <div class="financial-card customer-financial-card" id="customerInfo" style="display: none;">
                            <div class="financial-header">
                                <div class="financial-icon-wrapper">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="financial-title-group">
                                    <h3 class="financial-title">الملف المالي للعميل</h3>
                                    <p class="financial-subtitle">تحليل الوضع الائتماني والمديونية</p>
                                </div>
                                <div class="financial-status-indicator" id="creditStatusIndicator">
                                    <span class="status-dot status-good"></span>
                                    <span class="status-text">وضع جيد</span>
                                </div>
                            </div>

                            <div class="financial-metrics-grid">
                                <div class="metric-card credit-limit-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="metric-content">
                                        <span class="metric-label">سقف المديونية</span>
                                        <span class="metric-value" id="creditLimitDisplay">0.00 د.ع</span>
                                        <div class="metric-progress">
                                            <div class="progress-bar">
                                                <div class="progress-fill" id="creditProgressFill" style="width: 0%"></div>
                                            </div>
                                            <span class="progress-text" id="creditProgressText">0% مستخدم</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="metric-card previous-debt-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="metric-content">
                                        <span class="metric-label">المديونية السابقة</span>
                                        <span class="metric-value" id="previousBalanceDisplay">0.00 د.ع</span>
                                        <div class="metric-trend" id="debtTrend">
                                            <i class="fas fa-arrow-down trend-icon"></i>
                                            <span class="trend-text">مستقر</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="metric-card total-debt-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <div class="metric-content">
                                        <span class="metric-label">إجمالي المديونية</span>
                                        <span class="metric-value" id="totalDebtDisplay">0.00 د.ع</span>
                                        <div class="metric-change" id="debtChange">
                                            <span class="change-amount">+0.00 د.ع</span>
                                            <span class="change-label">من هذه الفاتورة</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="metric-card available-credit-card">
                                    <div class="metric-icon">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <div class="metric-content">
                                        <span class="metric-label">الائتمان المتاح</span>
                                        <span class="metric-value" id="availableCreditDisplay">0.00 د.ع</span>
                                        <div class="metric-status" id="creditAvailabilityStatus">
                                            <span class="status-badge status-available">متاح</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Risk Assessment -->
                            <div class="credit-risk-assessment" id="creditRiskAssessment">
                                <div class="risk-header">
                                    <i class="fas fa-chart-line"></i>
                                    <span>تقييم المخاطر الائتمانية</span>
                                </div>
                                <div class="risk-level" id="riskLevel">
                                    <div class="risk-indicator risk-low">
                                        <span class="risk-dot"></span>
                                        <span class="risk-text">مخاطر منخفضة</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Invoice Totals -->
                        <div class="financial-card invoice-totals-card">
                            <div class="financial-header">
                                <div class="financial-icon-wrapper">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="financial-title-group">
                                    <h3 class="financial-title">إجمالي الفاتورة</h3>
                                    <p class="financial-subtitle">تفاصيل المبالغ والحسابات</p>
                                </div>
                                <div class="invoice-status-badge">
                                    <span class="badge-text">مسودة</span>
                                </div>
                            </div>

                            <div class="totals-breakdown">
                                <div class="breakdown-item subtotal-item">
                                    <div class="breakdown-icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="breakdown-content">
                                        <span class="breakdown-label">المجموع الفرعي</span>
                                        <span class="breakdown-value" id="subtotalDisplay">0.00 د.ع</span>
                                    </div>
                                    <div class="breakdown-percentage" id="subtotalPercentage">100%</div>
                                </div>

                                <div class="breakdown-item discount-item">
                                    <div class="breakdown-icon">
                                        <i class="fas fa-minus-circle"></i>
                                    </div>
                                    <div class="breakdown-content">
                                        <span class="breakdown-label">إجمالي الخصومات</span>
                                        <span class="breakdown-value discount-value" id="discountDisplay">0.00 د.ع</span>
                                    </div>
                                    <div class="breakdown-percentage" id="discountPercentage">0%</div>
                                </div>

                                <div class="breakdown-item samples-item">
                                    <div class="breakdown-icon">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                    <div class="breakdown-content">
                                        <span class="breakdown-label">العينات المجانية</span>
                                        <span class="breakdown-value samples-value" id="freeSamplesDisplay">0</span>
                                    </div>
                                    <div class="breakdown-unit">وحدة</div>
                                </div>

                                <div class="breakdown-separator"></div>

                                <div class="breakdown-item total-item">
                                    <div class="breakdown-icon">
                                        <i class="fas fa-equals"></i>
                                    </div>
                                    <div class="breakdown-content">
                                        <span class="breakdown-label">الإجمالي النهائي</span>
                                        <span class="breakdown-value total-value" id="totalDisplay">0.00 د.ع</span>
                                    </div>
                                    <div class="breakdown-currency">IQD</div>
                                </div>
                            </div>

                            <!-- Payment Terms Preview -->
                            <div class="payment-terms-preview">
                                <div class="terms-header">
                                    <i class="fas fa-handshake"></i>
                                    <span>شروط الدفع</span>
                                </div>
                                <div class="terms-content">
                                    <div class="term-item">
                                        <span class="term-label">طريقة الدفع:</span>
                                        <span class="term-value">نقداً / آجل</span>
                                    </div>
                                    <div class="term-item">
                                        <span class="term-label">تاريخ الاستحقاق:</span>
                                        <span class="term-value" id="dueDatePreview">فوري</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Alerts & Warnings -->
                        <div class="financial-alerts-section">
                            <!-- Credit Warning -->
                            <div id="creditWarning" class="financial-alert alert-danger" style="display: none;">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="alert-content">
                                    <div class="alert-title">تحذير ائتماني</div>
                                    <div class="alert-message">المديونية تتجاوز السقف المحدد</div>
                                </div>
                                <div class="alert-action">
                                    <button type="button" class="alert-btn" onclick="showCreditDetails()">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Discount Alert -->
                            <div id="discountAlert" class="financial-alert alert-info" style="display: none;">
                                <div class="alert-icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="alert-content">
                                    <div class="alert-title">خصم كبير</div>
                                    <div class="alert-message">نسبة الخصم تتجاوز 15%</div>
                                </div>
                            </div>

                            <!-- Success Alert -->
                            <div id="financialSuccess" class="financial-alert alert-success" style="display: none;">
                                <div class="alert-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <div class="alert-title">وضع مالي ممتاز</div>
                                    <div class="alert-message">جميع المؤشرات المالية في المعدل الطبيعي</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="subtotal_amount" id="subtotalAmount" value="0">
            <input type="hidden" name="discount_amount" id="discountAmount" value="0">
            <input type="hidden" name="total_amount" id="totalAmount" value="0">
            <input type="hidden" name="previous_balance" id="previousBalance" value="0">
            <input type="hidden" name="credit_limit" id="creditLimit" value="0">
            <input type="hidden" name="free_samples" id="freeSamples" value="0">

            <!-- Action Buttons -->
            <div class="actions-section">
                <div class="actions-grid">
                    <button type="button" onclick="showInvoicePreview()" class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        معاينة الفاتورة
                    </button>
                    <button type="submit" name="action" value="draft" class="btn btn-outline">
                        <i class="fas fa-save"></i>
                        حفظ كمسودة
                    </button>
                    <button type="button" onclick="console.log('Button clicked!'); submitInvoice(this);" class="btn btn-success">
                        <i class="fas fa-check-circle"></i>
                        إنهاء وحفظ الفاتورة
                    </button>
                    <a href="{{ route('tenant.sales.invoices.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للفواتير
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include Preview Modal -->
@include('tenant.sales.invoices.preview-modal')

<script>
let itemIndex = 1;

// Initialize custom selects for existing elements
function initializeCustomSelects() {
    // Initialize all custom selects
    if (window.initCustomSelects) {
        window.initCustomSelects();
    } else if (window.UniversalDropdowns) {
        window.UniversalDropdowns.initializeAllSelects();
    }
}

// Note: Enhanced updateProductInfo function is defined later in the file with visual display support

// Enhanced item total calculation with FOC support and discount handling
function calculateItemTotal(index) {
    const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
    const discountInput = document.querySelector(`input[name="items[${index}][discount_amount]"]`);
    const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);
    const focCheckbox = document.querySelector(`input[name="items[${index}][is_foc]"]`);
    const discountType = document.querySelector(`select[name="items[${index}][discount_type]"]`)?.value || 'amount';

    if (!quantityInput || !priceInput || !totalInput) return;

    const quantity = parseFloat(quantityInput.value || 0);
    const price = parseFloat(priceInput.value || 0);
    const discountAmount = parseFloat(discountInput?.value || 0);
    const isFOC = focCheckbox?.checked || false;

    let total = 0;

    if (isFOC) {
        // FOC items have 0 total
        total = 0;
    } else {
        // Calculate normal total with discount support
        let lineTotal = quantity * price;
        let actualDiscount = 0;

        if (discountType === 'percentage') {
            actualDiscount = (lineTotal * discountAmount) / 100;
        } else {
            actualDiscount = discountAmount;
        }

        total = Math.max(0, lineTotal - actualDiscount);
    }

    // Add visual feedback for calculation
    totalInput.style.transition = 'all 0.3s ease';
    totalInput.style.background = isFOC ? '#f0fdf4' : '#f0f9ff';
    totalInput.value = total.toFixed(2);
    setTimeout(() => {
        totalInput.style.background = isFOC ? '#dcfce7' : '#f9fafb';
    }, 500);

    // Update grand total
    calculateGrandTotal();

    // Validate stock
    validateStock(index);
}

// Validate stock availability
function validateStock(index) {
    const productSelect = document.querySelector(`select[name="items[${index}][product_id]"]`);
    const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
    const errorElement = document.getElementById(`stockError${index}`);

    if (!productSelect || !quantityInput || !errorElement) return;

    const selectedOption = productSelect.options[productSelect.selectedIndex];
    const stock = parseInt(selectedOption.dataset.stock || 0);
    const quantity = parseInt(quantityInput.value || 0);

    if (selectedOption.value && quantity > stock) {
        errorElement.innerHTML = `<i class="fas fa-exclamation-triangle"></i> الكمية المطلوبة (${quantity}) تتجاوز المخزون المتاح (${stock})`;
        errorElement.style.display = 'block';
        quantityInput.classList.add('error');
        return false;
    } else {
        errorElement.style.display = 'none';
        quantityInput.classList.remove('error');
        return true;
    }
}

// Update free samples total
function updateFreeSamplesTotal() {
    let totalFreeSamples = 0;
    document.querySelectorAll('input[name*="[free_samples]"]').forEach(input => {
        totalFreeSamples += parseInt(input.value || 0);
    });

    document.getElementById('freeSamples').value = totalFreeSamples;
    document.getElementById('freeSamplesDisplay').textContent = totalFreeSamples;
}

// Enhanced grand total calculation with financial analysis
function calculateGrandTotal() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalFreeSamples = 0;

    // Sum all item totals
    document.querySelectorAll('input[name*="[total_amount]"]').forEach(input => {
        subtotal += parseFloat(input.value || 0);
    });

    // Sum all discounts
    document.querySelectorAll('input[name*="[discount_amount]"]').forEach(input => {
        totalDiscount += parseFloat(input.value || 0);
    });

    // Sum all free samples
    document.querySelectorAll('input[name*="[free_samples]"]').forEach(input => {
        totalFreeSamples += parseInt(input.value || 0);
    });

    const total = subtotal;

    // Update hidden fields
    document.getElementById('subtotalAmount').value = subtotal.toFixed(2);
    document.getElementById('discountAmount').value = totalDiscount.toFixed(2);
    document.getElementById('totalAmount').value = total.toFixed(2);
    document.getElementById('freeSamples').value = totalFreeSamples;

    // Update basic displays
    document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2) + ' د.ع';
    document.getElementById('discountDisplay').textContent = totalDiscount.toFixed(2) + ' د.ع';
    document.getElementById('freeSamplesDisplay').textContent = totalFreeSamples;
    document.getElementById('totalDisplay').textContent = total.toFixed(2) + ' د.ع';

    // Update enhanced breakdown percentages
    updateBreakdownPercentages(subtotal, totalDiscount, total);

    // Update debt calculation
    updateDebtCalculation(total);

    // Update financial metrics if customer is selected
    const customerSelect = document.getElementById('customerSelect');
    if (customerSelect && customerSelect.value) {
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        const creditLimit = parseFloat(selectedOption.dataset.creditLimit || 0);
        const previousBalance = parseFloat(selectedOption.dataset.previousBalance || 0);
        updateFinancialMetrics(creditLimit, previousBalance);
    }
}

// Update breakdown percentages and visual indicators
function updateBreakdownPercentages(subtotal, totalDiscount, total) {
    // Update subtotal percentage (always 100% as base)
    const subtotalPercentage = document.getElementById('subtotalPercentage');
    if (subtotalPercentage) {
        subtotalPercentage.textContent = '100%';
    }

    // Update discount percentage
    const discountPercentage = document.getElementById('discountPercentage');
    if (discountPercentage && subtotal > 0) {
        const discountPercent = (totalDiscount / subtotal) * 100;
        discountPercentage.textContent = discountPercent.toFixed(1) + '%';

        // Change color based on discount level
        if (discountPercent > 15) {
            discountPercentage.style.background = '#fee2e2';
            discountPercentage.style.color = '#991b1b';
        } else if (discountPercent > 10) {
            discountPercentage.style.background = '#fef3c7';
            discountPercentage.style.color = '#92400e';
        } else {
            discountPercentage.style.background = '#f1f5f9';
            discountPercentage.style.color = '#64748b';
        }
    }
}

// Update debt calculation
function updateDebtCalculation(invoiceTotal) {
    const previousBalance = parseFloat(document.getElementById('previousBalance').value || 0);
    const creditLimit = parseFloat(document.getElementById('creditLimit').value || 0);
    const totalDebt = previousBalance + invoiceTotal;
    
    document.getElementById('totalDebtDisplay').textContent = totalDebt.toFixed(2) + ' د.ع';
    
    // Show/hide credit warning
    const warningElement = document.getElementById('creditWarning');
    if (creditLimit > 0 && totalDebt > creditLimit) {
        warningElement.style.display = 'flex';
        warningElement.classList.add('debt-danger');
    } else {
        warningElement.style.display = 'none';
        warningElement.classList.remove('debt-danger');
    }
}

// Add new item row with enhanced features
function addItem() {
    const tbody = document.getElementById('invoiceItems');
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';

    // Create product options with enhanced design matching first row
    const productOptions = `
        @foreach($products as $product)
            <option value="{{ $product->id }}"
                    data-price="{{ $product->selling_price ?? $product->unit_price ?? 0 }}"
                    data-stock="{{ $product->current_stock ?? 0 }}"
                    data-unit="{{ $product->unit ?? 'قطعة' }}"
                    data-code="{{ $product->product_code ?? '' }}"
                    data-company="{{ $product->company ?? '' }}"
                    data-category="{{ $product->category ?? '' }}">
                <span class="product-name">{{ $product->name }}</span>
                @if($product->product_code)
                    <span class="product-code">({{ $product->product_code }})</span>
                @endif
                @if($product->company)
                    <span class="product-company">- {{ $product->company }}</span>
                @endif
            </option>
        @endforeach
    `;

    newRow.innerHTML = `
        <td>
            <div class="enhanced-product-dropdown">
                <select name="items[${itemIndex}][product_id]" required class="form-control enhanced-select" data-custom-select
                        data-placeholder="اختر المنتج" data-searchable="true" onchange="simpleUpdateProductInfo(this, ${itemIndex})">
                    <option value="">اختر المنتج</option>
                    ${productOptions}
                </select>
                <div class="product-info-display" id="productInfo${itemIndex}" style="display: none;">
                    <div class="info-row">
                        <span class="info-label">الكود:</span>
                        <span class="info-value" id="productCode${itemIndex}">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">المخزون:</span>
                        <span class="info-value" id="productStock${itemIndex}">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الوحدة:</span>
                        <span class="info-value" id="productUnit${itemIndex}">-</span>
                    </div>
                </div>
                <div class="stock-warning" id="stockWarning${itemIndex}" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>مخزون منخفض</span>
                </div>
            </div>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" min="1" step="1" required
                   class="form-control enhanced-input" placeholder="1" value="1"
                   onchange="simpleCalculateTotal(${itemIndex})" oninput="validateStock(${itemIndex})"
                   style="text-align: center;">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" required
                   class="form-control enhanced-input" placeholder="0.00" value="0"
                   onchange="simpleCalculateTotal(${itemIndex})" style="text-align: center;">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][discount_amount]" min="0" step="0.01"
                   class="form-control enhanced-input" placeholder="0.00" value="0"
                   onchange="simpleCalculateTotal(${itemIndex})" style="text-align: center;">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][free_samples]" min="0" step="1"
                   class="form-control enhanced-input" placeholder="0" value="0"
                   style="text-align: center;">
        </td>
        <td>
            <div class="total-display">
                <input type="number" name="items[${itemIndex}][total_amount]" readonly
                       class="form-control total-input" placeholder="0.00" value="0"
                       style="background: #f9fafb; text-align: center;">
                <div class="total-currency">د.ع</div>
            </div>
        </td>
        <td>
            <button type="button" onclick="removeItem(${itemIndex})" class="remove-item-btn enhanced-remove-btn"
                    title="حذف هذا المنتج">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(newRow);

    // Add animation
    newRow.style.opacity = '0';
    newRow.style.transform = 'translateY(-10px)';
    setTimeout(() => {
        newRow.style.transition = 'all 0.3s ease';
        newRow.style.opacity = '1';
        newRow.style.transform = 'translateY(0)';
    }, 10);

    // Initialize custom select for the new dropdown
    if (window.initCustomSelects) {
        window.initCustomSelects(newRow);
    } else if (window.UniversalDropdowns) {
        window.UniversalDropdowns.initializeAllSelects(newRow);
    }

    itemIndex++;
    updateRemoveButtons();

    // Apply enhanced styling to the new dropdown
    setTimeout(() => {
        applyEnhancedStyling(itemIndex - 1);
        // Ensure all dropdowns have consistent styling
        applyEnhancedStylingToAll();
    }, 100);

    // Auto-save draft
    autoSaveDraft();
}

// Remove item row
function removeItem(index) {
    const row = document.querySelector(`tr:has(select[name="items[${index}][product_id]"])`);
    if (row) {
        row.remove();
        calculateGrandTotal();
        updateRemoveButtons();
    }
}

// Apply unified styling to dropdown elements
function applyEnhancedStyling(index) {
    const dropdown = document.querySelector(`select[name="items[${index}][product_id]"]`);
    if (dropdown) {
        applyUnifiedStylingToDropdown(dropdown, index);
    }
}

// Apply unified styling to all existing product dropdowns
function applyEnhancedStylingToAll() {
    // Find all product dropdowns with different selectors
    const selectors = [
        'select[name*="[product_id]"]',
        '.enhanced-select',
        'select.form-control[data-custom-select]',
        'select[data-placeholder]'
    ];

    let totalDropdowns = 0;

    selectors.forEach(selector => {
        const dropdowns = document.querySelectorAll(selector);
        dropdowns.forEach((dropdown, index) => {
            applyUnifiedStylingToDropdown(dropdown, totalDropdowns + index);
            totalDropdowns++;
        });
    });

    console.log(`Unified styling applied to ${totalDropdowns} product dropdowns`);
}

// Apply unified styling to a specific dropdown element
function applyUnifiedStylingToDropdown(dropdown, index) {
    if (!dropdown) return;

    // Add all necessary classes for consistency
    dropdown.classList.add('enhanced-select', 'form-control');
    dropdown.setAttribute('data-custom-select', 'true');
    dropdown.setAttribute('data-placeholder', dropdown.getAttribute('data-placeholder') || 'اختر المنتج');
    dropdown.setAttribute('data-searchable', 'true');

    // Apply unified styling (CSS will handle most of this, but ensure consistency)
    dropdown.style.cssText = `
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 12px 16px 12px 40px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
        color: #1e293b !important;
        width: 100% !important;
        min-height: 48px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
        background-position: left 12px center !important;
        background-repeat: no-repeat !important;
        background-size: 16px 16px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06) !important;
    `;

    // Remove existing event listeners to avoid duplicates
    dropdown.removeEventListener('focus', unifiedFocusHandler);
    dropdown.removeEventListener('blur', unifiedBlurHandler);
    dropdown.removeEventListener('mouseenter', unifiedMouseEnterHandler);
    dropdown.removeEventListener('mouseleave', unifiedMouseLeaveHandler);

    // Add unified event listeners
    dropdown.addEventListener('focus', unifiedFocusHandler);
    dropdown.addEventListener('blur', unifiedBlurHandler);
    dropdown.addEventListener('mouseenter', unifiedMouseEnterHandler);
    dropdown.addEventListener('mouseleave', unifiedMouseLeaveHandler);

    console.log('Unified styling applied to dropdown for index:', index);
}

// Unified event handlers for consistent behavior
function unifiedFocusHandler() {
    this.style.borderColor = '#667eea !important';
    this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important';
    this.style.background = 'white !important';
    this.style.transform = 'translateY(-1px) !important';
}

function unifiedBlurHandler() {
    this.style.borderColor = '#e2e8f0 !important';
    this.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06) !important';
    this.style.background = 'linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important';
    this.style.transform = 'translateY(0) !important';
}

function unifiedMouseEnterHandler() {
    if (document.activeElement !== this) {
        this.style.borderColor = '#c7d2fe !important';
        this.style.background = 'white !important';
        this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important';
        this.style.transform = 'translateY(-1px) !important';
    }
}

function unifiedMouseLeaveHandler() {
    if (document.activeElement !== this) {
        this.style.borderColor = '#e2e8f0 !important';
        this.style.background = 'linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important';
        this.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06) !important';
        this.style.transform = 'translateY(0) !important';
    }
}

// Update remove buttons state
function updateRemoveButtons() {
    const rows = document.querySelectorAll('#invoiceItems tr');
    const removeButtons = document.querySelectorAll('.remove-item-btn');
    
    removeButtons.forEach((btn, index) => {
        btn.disabled = rows.length <= 1;
    });
}

// Enhanced customer information update with financial analysis
function updateCustomerInfo() {
    const customerSelect = document.getElementById('customerSelect');
    const selectedOption = customerSelect.options[customerSelect.selectedIndex];

    if (selectedOption.value) {
        const creditLimit = parseFloat(selectedOption.dataset.creditLimit || 0);
        const previousBalance = parseFloat(selectedOption.dataset.previousBalance || 0);

        // Update hidden fields
        document.getElementById('creditLimit').value = creditLimit.toFixed(2);
        document.getElementById('previousBalance').value = previousBalance.toFixed(2);

        // Update basic displays
        document.getElementById('creditLimitDisplay').textContent = creditLimit.toFixed(2) + ' د.ع';
        document.getElementById('previousBalanceDisplay').textContent = previousBalance.toFixed(2) + ' د.ع';

        // Show customer info section
        document.getElementById('customerInfo').style.display = 'block';

        // Update enhanced financial metrics
        updateFinancialMetrics(creditLimit, previousBalance);

        // Recalculate totals
        calculateGrandTotal();
    } else {
        document.getElementById('customerInfo').style.display = 'none';
        resetFinancialMetrics();
    }
}

// Enhanced financial metrics calculation and display
function updateFinancialMetrics(creditLimit, previousBalance) {
    const currentTotal = parseFloat(document.getElementById('totalAmount').value || 0);
    const totalDebt = previousBalance + currentTotal;
    const availableCredit = Math.max(0, creditLimit - totalDebt);
    const creditUsagePercentage = creditLimit > 0 ? (totalDebt / creditLimit) * 100 : 0;

    // Update displays
    document.getElementById('totalDebtDisplay').textContent = totalDebt.toFixed(2) + ' د.ع';
    document.getElementById('availableCreditDisplay').textContent = availableCredit.toFixed(2) + ' د.ع';

    // Update progress bar
    const progressFill = document.getElementById('creditProgressFill');
    const progressText = document.getElementById('creditProgressText');
    if (progressFill && progressText) {
        const safePercentage = Math.min(100, creditUsagePercentage);
        progressFill.style.width = safePercentage + '%';
        progressText.textContent = safePercentage.toFixed(1) + '% مستخدم';
    }

    // Update debt change indicator
    const debtChange = document.getElementById('debtChange');
    if (debtChange) {
        const changeAmount = debtChange.querySelector('.change-amount');
        if (changeAmount) {
            changeAmount.textContent = '+' + currentTotal.toFixed(2) + ' د.ع';
        }
    }

    // Update credit status indicator
    updateCreditStatusIndicator(creditUsagePercentage, availableCredit);

    // Update risk assessment
    updateRiskAssessment(creditUsagePercentage, totalDebt, creditLimit);

    // Update financial alerts
    updateFinancialAlerts(creditUsagePercentage, availableCredit, currentTotal);
}

// Update credit status indicator
function updateCreditStatusIndicator(usagePercentage, availableCredit) {
    const indicator = document.getElementById('creditStatusIndicator');
    const statusDot = indicator?.querySelector('.status-dot');
    const statusText = indicator?.querySelector('.status-text');

    if (statusDot && statusText) {
        statusDot.className = 'status-dot';

        if (usagePercentage <= 50) {
            statusDot.classList.add('status-good');
            statusText.textContent = 'وضع ممتاز';
        } else if (usagePercentage <= 80) {
            statusDot.classList.add('status-warning');
            statusText.textContent = 'وضع جيد';
        } else {
            statusDot.classList.add('status-danger');
            statusText.textContent = 'وضع حرج';
        }
    }

    // Update availability status
    const availabilityStatus = document.getElementById('creditAvailabilityStatus');
    if (availabilityStatus) {
        const badge = availabilityStatus.querySelector('.status-badge');
        if (badge) {
            badge.className = 'status-badge';

            if (availableCredit > 1000) {
                badge.classList.add('status-available');
                badge.textContent = 'متاح';
            } else if (availableCredit > 0) {
                badge.classList.add('status-limited');
                badge.textContent = 'محدود';
            } else {
                badge.classList.add('status-exceeded');
                badge.textContent = 'متجاوز';
            }
        }
    }
}

// Update risk assessment
function updateRiskAssessment(usagePercentage, totalDebt, creditLimit) {
    const riskLevel = document.getElementById('riskLevel');
    if (riskLevel) {
        const riskIndicator = riskLevel.querySelector('.risk-indicator');
        if (riskIndicator) {
            riskIndicator.className = 'risk-indicator';

            if (usagePercentage <= 60) {
                riskIndicator.classList.add('risk-low');
                riskIndicator.querySelector('.risk-text').textContent = 'مخاطر منخفضة';
            } else if (usagePercentage <= 85) {
                riskIndicator.classList.add('risk-medium');
                riskIndicator.querySelector('.risk-text').textContent = 'مخاطر متوسطة';
            } else {
                riskIndicator.classList.add('risk-high');
                riskIndicator.querySelector('.risk-text').textContent = 'مخاطر عالية';
            }
        }
    }
}

// Update financial alerts
function updateFinancialAlerts(usagePercentage, availableCredit, currentTotal) {
    const creditWarning = document.getElementById('creditWarning');
    const discountAlert = document.getElementById('discountAlert');
    const financialSuccess = document.getElementById('financialSuccess');

    // Hide all alerts first
    if (creditWarning) creditWarning.style.display = 'none';
    if (discountAlert) discountAlert.style.display = 'none';
    if (financialSuccess) financialSuccess.style.display = 'none';

    // Show appropriate alerts
    if (usagePercentage > 100) {
        if (creditWarning) creditWarning.style.display = 'flex';
    } else if (usagePercentage <= 50 && availableCredit > 1000) {
        if (financialSuccess) financialSuccess.style.display = 'flex';
    }

    // Check for high discount
    const discountAmount = parseFloat(document.getElementById('discountAmount').value || 0);
    const subtotalAmount = parseFloat(document.getElementById('subtotalAmount').value || 0);
    if (subtotalAmount > 0 && (discountAmount / subtotalAmount) > 0.15) {
        if (discountAlert) discountAlert.style.display = 'flex';
    }
}

// Reset financial metrics when no customer selected
function resetFinancialMetrics() {
    const elements = [
        'creditLimitDisplay', 'previousBalanceDisplay', 'totalDebtDisplay',
        'availableCreditDisplay', 'creditProgressFill', 'creditProgressText'
    ];

    elements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            if (id === 'creditProgressFill') {
                element.style.width = '0%';
            } else if (id === 'creditProgressText') {
                element.textContent = '0% مستخدم';
            } else {
                element.textContent = '0.00 د.ع';
            }
        }
    });

    // Hide all alerts
    ['creditWarning', 'discountAlert', 'financialSuccess'].forEach(id => {
        const element = document.getElementById(id);
        if (element) element.style.display = 'none';
    });
}

// Show detailed credit information modal
function showCreditDetails() {
    const customerSelect = document.getElementById('customerSelect');
    if (!customerSelect || !customerSelect.value) {
        showNotification('يرجى اختيار عميل أولاً', 'warning');
        return;
    }

    const selectedOption = customerSelect.options[customerSelect.selectedIndex];
    const creditLimit = parseFloat(selectedOption.dataset.creditLimit || 0);
    const previousBalance = parseFloat(selectedOption.dataset.previousBalance || 0);
    const currentTotal = parseFloat(document.getElementById('totalAmount').value || 0);
    const totalDebt = previousBalance + currentTotal;
    const availableCredit = Math.max(0, creditLimit - totalDebt);
    const usagePercentage = creditLimit > 0 ? (totalDebt / creditLimit) * 100 : 0;

    const modalContent = `
        <div style="background: white; border-radius: 20px; padding: 30px; max-width: 500px; margin: 50px auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <div style="text-align: center; margin-bottom: 25px;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 15px; margin-bottom: 20px;">
                    <h3 style="margin: 0; font-size: 20px;">تفاصيل الوضع الائتماني</h3>
                    <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">${selectedOption.textContent}</p>
                </div>
            </div>

            <div style="display: grid; gap: 15px;">
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border-left: 4px solid #667eea;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">سقف المديونية</div>
                    <div style="font-size: 18px; font-weight: 700; color: #1e293b;">${creditLimit.toFixed(2)} د.ع</div>
                </div>

                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border-left: 4px solid #f59e0b;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">المديونية السابقة</div>
                    <div style="font-size: 18px; font-weight: 700; color: #1e293b;">${previousBalance.toFixed(2)} د.ع</div>
                </div>

                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border-left: 4px solid #10b981;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">قيمة الفاتورة الحالية</div>
                    <div style="font-size: 18px; font-weight: 700; color: #1e293b;">${currentTotal.toFixed(2)} د.ع</div>
                </div>

                <div style="background: ${usagePercentage > 100 ? '#fee2e2' : '#f0fdf4'}; padding: 15px; border-radius: 12px; border-left: 4px solid ${usagePercentage > 100 ? '#ef4444' : '#10b981'};">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">إجمالي المديونية</div>
                    <div style="font-size: 18px; font-weight: 700; color: ${usagePercentage > 100 ? '#dc2626' : '#1e293b'};">${totalDebt.toFixed(2)} د.ع</div>
                </div>

                <div style="background: #f8fafc; padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 8px;">نسبة استخدام الائتمان</div>
                    <div style="background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 5px;">
                        <div style="background: linear-gradient(135deg, ${usagePercentage > 100 ? '#ef4444' : usagePercentage > 80 ? '#f59e0b' : '#10b981'} 0%, ${usagePercentage > 100 ? '#dc2626' : usagePercentage > 80 ? '#d97706' : '#059669'} 100%); height: 100%; width: ${Math.min(100, usagePercentage)}%; transition: width 0.5s ease;"></div>
                    </div>
                    <div style="font-size: 14px; font-weight: 600; color: ${usagePercentage > 100 ? '#dc2626' : '#1e293b'};">${usagePercentage.toFixed(1)}%</div>
                </div>

                ${usagePercentage > 100 ? `
                <div style="background: #fee2e2; border: 1px solid #f87171; padding: 15px; border-radius: 12px; text-align: center;">
                    <div style="color: #dc2626; font-weight: 700; margin-bottom: 5px;">⚠️ تحذير</div>
                    <div style="color: #dc2626; font-size: 14px;">المديونية تتجاوز السقف المحدد بمبلغ ${(totalDebt - creditLimit).toFixed(2)} د.ع</div>
                </div>
                ` : `
                <div style="background: #f0fdf4; border: 1px solid #4ade80; padding: 15px; border-radius: 12px; text-align: center;">
                    <div style="color: #16a34a; font-weight: 700; margin-bottom: 5px;">✅ الائتمان المتاح</div>
                    <div style="color: #16a34a; font-size: 16px; font-weight: 600;">${availableCredit.toFixed(2)} د.ع</div>
                </div>
                `}
            </div>

            <div style="text-align: center; margin-top: 25px;">
                <button onclick="closeCreditDetailsModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 30px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    إغلاق
                </button>
            </div>
        </div>
    `;

    // Create modal overlay
    const modalOverlay = document.createElement('div');
    modalOverlay.id = 'creditDetailsModal';
    modalOverlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 10000;
        backdrop-filter: blur(5px);
    `;
    modalOverlay.innerHTML = modalContent;

    document.body.appendChild(modalOverlay);

    // Close modal when clicking overlay
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) {
            closeCreditDetailsModal();
        }
    });
}

// Close credit details modal
function closeCreditDetailsModal() {
    const modal = document.getElementById('creditDetailsModal');
    if (modal) {
        modal.remove();
    }
}

// Enhanced product info update with visual display
function updateProductInfo(selectElement, index) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const productInfoDisplay = document.getElementById(`productInfo${index}`);
    const stockWarning = document.getElementById(`stockWarning${index}`);
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);

    if (selectedOption.value) {
        const price = parseFloat(selectedOption.dataset.price || 0);
        const stock = parseInt(selectedOption.dataset.stock || 0);
        const unit = selectedOption.dataset.unit || 'قطعة';
        const code = selectedOption.dataset.code || '-';
        const company = selectedOption.dataset.company || '-';

        // Update price input
        if (priceInput) {
            priceInput.value = price.toFixed(2);
        }

        // Update product info display
        if (productInfoDisplay) {
            document.getElementById(`productCode${index}`).textContent = code;
            document.getElementById(`productStock${index}`).textContent = stock + ' ' + unit;
            document.getElementById(`productUnit${index}`).textContent = unit;
            productInfoDisplay.style.display = 'block';
        }

        // Show stock warning if low
        if (stockWarning) {
            if (stock <= 10 && stock > 0) {
                stockWarning.style.display = 'flex';
            } else {
                stockWarning.style.display = 'none';
            }
        }

        // Calculate item total
        calculateItemTotal(index);
    } else {
        // Hide info displays when no product selected
        if (productInfoDisplay) {
            productInfoDisplay.style.display = 'none';
        }
        if (stockWarning) {
            stockWarning.style.display = 'none';
        }
        if (priceInput) {
            priceInput.value = '0';
        }
    }
}

// Toggle FOC (Free of Charge) functionality
function toggleFOC(index) {
    const checkbox = document.querySelector(`input[name="items[${index}][is_foc]"]`);
    const row = checkbox.closest('tr');
    const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
    const discountInput = document.querySelector(`input[name="items[${index}][discount_amount]"]`);

    if (checkbox.checked) {
        // Mark as FOC
        row.classList.add('foc-row');

        // Set total to 0 for FOC items
        if (totalInput) {
            totalInput.value = '0.00';
        }

        // Disable price and discount inputs for FOC items
        if (priceInput) {
            priceInput.disabled = true;
            priceInput.style.opacity = '0.5';
        }
        if (discountInput) {
            discountInput.disabled = true;
            discountInput.style.opacity = '0.5';
        }

        showNotification('تم تحديد المنتج كعينة مجانية (FOC)', 'success', 2000);
    } else {
        // Remove FOC
        row.classList.remove('foc-row');

        // Re-enable inputs
        if (priceInput) {
            priceInput.disabled = false;
            priceInput.style.opacity = '1';
        }
        if (discountInput) {
            discountInput.disabled = false;
            discountInput.style.opacity = '1';
        }

        // Recalculate total
        calculateItemTotal(index);
    }

    // Update grand total
    calculateGrandTotal();
}



// Enhanced search functionality for dropdowns
function enhanceDropdownSearch() {
    // Add search functionality to customer dropdown
    const customerSelect = document.getElementById('customerSelect');
    if (customerSelect) {
        // Add data attributes for better search
        Array.from(customerSelect.options).forEach(option => {
            if (option.value) {
                const text = option.textContent.toLowerCase();
                option.setAttribute('data-search', text);
            }
        });
    }

    // Add search functionality to product dropdowns
    document.querySelectorAll('select[name*="[product_id]"]').forEach(select => {
        Array.from(select.options).forEach(option => {
            if (option.value) {
                const text = option.textContent.toLowerCase();
                const code = option.dataset.code || '';
                const company = option.dataset.company || '';
                option.setAttribute('data-search', `${text} ${code} ${company}`.toLowerCase());
            }
        });
    });
}

// Keyboard shortcuts
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl+S or Cmd+S to save as draft
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const draftButton = document.querySelector('button[value="draft"]');
            if (draftButton) {
                draftButton.click();
            }
        }

        // Ctrl+Enter or Cmd+Enter to finalize
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            const finalizeButton = document.querySelector('button[value="finalize"]');
            if (finalizeButton) {
                finalizeButton.click();
            }
        }

        // Ctrl+N or Cmd+N to add new item
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            addItem();
        }

        // Escape to clear current selection
        if (e.key === 'Escape') {
            document.activeElement.blur();
        }
    });
}

// Add tooltips for better UX
function addTooltips() {
    const tooltipElements = [
        { selector: 'button[value="draft"]', text: 'حفظ كمسودة (Ctrl+S)' },
        { selector: 'button[value="finalize"]', text: 'إنهاء وحفظ الفاتورة (Ctrl+Enter)' },
        { selector: '.add-item-btn', text: 'إضافة منتج جديد (Ctrl+N)' },
        { selector: 'input[name*="[free_samples]"]', text: 'عدد الوحدات المجانية' },
        { selector: 'select[name*="[discount_type]"]', text: 'نوع الخصم: مبلغ ثابت أو نسبة مئوية' }
    ];

    tooltipElements.forEach(({ selector, text }) => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(element => {
            element.setAttribute('title', text);
            element.classList.add('tooltip');
            element.setAttribute('data-tooltip', text);
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize custom selects
    initializeCustomSelects();

    // Load draft if available
    loadDraft();

    // Add customer change listener
    document.getElementById('customerSelect').addEventListener('change', updateCustomerInfo);

    // Calculate initial totals
    calculateGrandTotal();

    // Update remove buttons
    updateRemoveButtons();

    // Enhance dropdown search
    enhanceDropdownSearch();

    // Setup keyboard shortcuts
    setupKeyboardShortcuts();

    // Add tooltips
    addTooltips();

    // Add event listeners to existing inputs
    document.querySelectorAll('input[name*="[quantity]"], input[name*="[unit_price]"], input[name*="[discount_amount]"], input[name*="[free_samples]"]').forEach(input => {
        input.addEventListener('input', function() {
            const match = this.name.match(/items\[(\d+)\]/);
            if (match) {
                const index = parseInt(match[1]);
                if (this.name.includes('quantity')) {
                    validateStock(index);
                }
                if (this.name.includes('free_samples')) {
                    updateFreeSamplesTotal();
                } else {
                    calculateItemTotal(index);
                }
            }

            // Auto-save on input
            autoSaveDraft();
        });
    });

    // Add change listeners for discount type selects
    document.querySelectorAll('select[name*="[discount_type]"]').forEach(select => {
        select.addEventListener('change', function() {
            const match = this.name.match(/items\[(\d+)\]/);
            if (match) {
                calculateItemTotal(parseInt(match[1]));
            }
            autoSaveDraft();
        });
    });

    // Show welcome message
    setTimeout(() => {
        showNotification('مرحباً بك في نظام إنشاء الفواتير الاحترافي', 'success', 3000);
    }, 500);
});

// Auto-save draft functionality
let autoSaveTimeout;
function autoSaveDraft() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        const formData = new FormData(document.getElementById('invoiceForm'));
        const draftData = {};

        for (let [key, value] of formData.entries()) {
            draftData[key] = value;
        }

        // Save to localStorage
        localStorage.setItem('invoice_draft', JSON.stringify(draftData));

        // Show auto-save indicator
        showNotification('تم حفظ المسودة تلقائياً', 'success', 2000);
    }, 3000); // Auto-save after 3 seconds of inactivity
}

// Load draft from localStorage
function loadDraft() {
    const draftData = localStorage.getItem('invoice_draft');
    if (draftData) {
        try {
            const data = JSON.parse(draftData);

            // Ask user if they want to restore the draft
            if (confirm('تم العثور على مسودة محفوظة. هل تريد استعادتها؟')) {
                Object.keys(data).forEach(key => {
                    const element = document.querySelector(`[name="${key}"]`);
                    if (element) {
                        element.value = data[key];
                    }
                });

                // Recalculate totals
                calculateGrandTotal();
                updateCustomerInfo();

                showNotification('تم استعادة المسودة بنجاح', 'success');
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
}

// Clear draft
function clearDraft() {
    localStorage.removeItem('invoice_draft');
}

// Simple notification function - no recursion
function showNotification(message, type = 'info', duration = 3000) {
    console.log(`📢 Notification (${type}): ${message}`);

    // Simple alert for now to avoid recursion issues
    alert(message);
}

// Simple submit function
function submitInvoice() {
    console.log('🚀 Submit invoice button clicked!');

    // Validate form
    if (!validateForm()) {
        console.log('❌ Form validation failed');
        return;
    }

    console.log('✅ Form validation passed - submitting...');

    const form = document.getElementById('invoiceForm');
    const formData = new FormData(form);

    // Add finalize action
    formData.append('action', 'finalize');

    console.log('📤 Submitting to:', form.getAttribute('action'));

    fetch(form.getAttribute('action'), {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': formData.get('_token')
        }
    })
    .then(response => {
        console.log('🎯 Response:', response.status, response.statusText);
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.text();
        }
    })
    .then(data => {
        if (data) {
            console.log('📄 Response data:', data);
        }
    })
    .catch(error => {
        console.log('❌ Error:', error);
        alert('حدث خطأ أثناء حفظ الفاتورة');
    });
}

// Simple notification function (removed duplicate)

// Simplified form validation for debugging
function validateForm() {
    console.log('🔍 Starting form validation...');

    // Check customer selection
    const customerSelect = document.getElementById('customerSelect');
    console.log('Customer select element:', customerSelect);
    console.log('Customer value:', customerSelect ? customerSelect.value : 'Element not found');

    if (!customerSelect || !customerSelect.value) {
        console.log('❌ Customer not selected');
        alert('يرجى اختيار العميل');
        return false;
    }

    // Check if at least one product is selected
    const productSelects = document.querySelectorAll('select[name*="[product_id]"]');
    console.log('Product selects found:', productSelects.length);

    let hasValidItems = false;
    productSelects.forEach((select, index) => {
        console.log(`Product ${index + 1} value:`, select.value);
        if (select.value) {
            hasValidItems = true;
        }
    });

    if (!hasValidItems) {
        console.log('❌ No products selected');
        alert('يرجى إضافة منتج واحد على الأقل');
        return false;
    }

    // Check quantities
    const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
    console.log('Quantity inputs found:', quantityInputs.length);

    for (let i = 0; i < quantityInputs.length; i++) {
        const input = quantityInputs[i];
        const value = parseFloat(input.value || 0);
        console.log(`Quantity ${i + 1}:`, value);

        if (value <= 0) {
            console.log('❌ Invalid quantity found');
            alert('يرجى إدخال كمية صحيحة لجميع المنتجات');
            return false;
        }
    }

    console.log('✅ Form validation passed successfully!');
    return true;
}

// Form submission disabled - using onclick instead
console.log('Form submission event listener disabled - using onclick submitInvoice() instead');

// End of script
</script>

<!-- Simple invoice submission script -->
<script src="{{ asset('js/invoice-submit.js') }}?v={{ time() }}" charset="utf-8"></script>

<script>
console.log('🔧 Inline script loaded - checking if submitInvoice exists');
setTimeout(() => {
    if (typeof submitInvoice === 'function') {
        console.log('✅ submitInvoice function is available');
    } else {
        console.log('❌ submitInvoice function NOT available');
        console.log('🔧 Creating fallback submitInvoice function');

        window.submitInvoice = function() {
            console.log('🚀 Fallback submitInvoice called!');

            const form = document.getElementById('invoiceForm');
            if (!form) {
                alert('خطأ: لم يتم العثور على النموذج');
                return;
            }

            console.log('📤 Submitting form directly...');
            form.submit();
        };
    }
}, 1000);
</script>

<!-- jQuery external loading removed to avoid CSP issues -->

<!-- Additional fallback with inline jQuery if CDNs fail -->
<script>
    if (!window.jQuery) {
        console.warn('jQuery failed to load from CDNs, using minimal fallback');
        window.$ = window.jQuery = function(selector) {
            if (typeof selector === 'function') {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', selector);
                } else {
                    selector();
                }
                return;
            }
            return {
                ready: function(fn) {
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', fn);
                    } else {
                        fn();
                    }
                },
                on: function(event, handler) {
                    if (this.length) {
                        for (let i = 0; i < this.length; i++) {
                            this[i].addEventListener(event, handler);
                        }
                    }
                    return this;
                },
                val: function(value) {
                    if (arguments.length === 0) {
                        return this[0] ? this[0].value : '';
                    }
                    if (this.length) {
                        for (let i = 0; i < this.length; i++) {
                            this[i].value = value;
                        }
                    }
                    return this;
                },
                length: selector ? document.querySelectorAll(selector).length : 0
            };
        };
    }
</script>

<!-- Bootstrap disabled to avoid CSP issues -->

<!-- Custom Select JavaScript (with error handling) -->
<script>
    // Load custom scripts with error handling
    function loadScript(src, callback) {
        const script = document.createElement('script');
        script.src = src;
        script.onload = callback || function() {};
        script.onerror = function() {
            console.warn('Failed to load script:', src);
        };
        document.head.appendChild(script);
    }

    // Scripts loading disabled to avoid CSP issues
    console.log('Scripts loading disabled for debugging');
</script>

<!-- Block problematic external scripts and initialize enhanced features -->
<script>
// Block problematic external script loading
(function() {
    const originalCreateElement = document.createElement;
    document.createElement = function(tagName) {
        const element = originalCreateElement.call(this, tagName);
        if (tagName.toLowerCase() === 'script') {
            const originalSetAttribute = element.setAttribute;
            element.setAttribute = function(name, value) {
                if (name === 'src' && (
                    value.includes('code.jquery.com') ||
                    value.includes('select2') ||
                    value.includes('jquery') && !value.includes('cdnjs.cloudflare.com') && !value.includes('cdn.jsdelivr.net')
                )) {
                    console.warn('Blocked problematic script:', value);
                    return;
                }
                return originalSetAttribute.call(this, name, value);
            };
        }
        return element;
    };
})();

document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced invoice features initializing (standalone mode)...');



    // Initialize enhanced dropdowns
    function initializeEnhancedDropdowns() {
        const productSelects = document.querySelectorAll('select[name*="[product_id]"]');
        productSelects.forEach(function(select) {
            select.addEventListener('change', function() {
                const index = this.name.match(/\[(\d+)\]/)[1];
                updateProductInfo(this, index);
            });
        });
    }

    // Initialize enhanced inputs
    function initializeEnhancedInputs() {
        const inputs = document.querySelectorAll('.enhanced-input');
        inputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#667eea';
                this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
            });

            input.addEventListener('blur', function() {
                this.style.borderColor = '#e2e8f0';
                this.style.boxShadow = 'none';
            });
        });
    }

    // Initialize all enhanced features
    try {
        initializeEnhancedDropdowns();
        initializeEnhancedInputs();

        // Apply unified styling to all existing product dropdowns
        setTimeout(() => {
            applyEnhancedStylingToAll();
            // Force re-apply after a short delay to ensure consistency
            setTimeout(() => {
                applyEnhancedStylingToAll();
            }, 500);
        }, 100);

        console.log('Enhanced invoice features with unified styling initialized successfully');
    } catch (error) {
        console.warn('Some enhanced features failed to initialize:', error);
    }

    // Ensure functions exist even if external scripts fail
    if (typeof toggleFOC === 'undefined') {
        window.toggleFOC = function(index) {
            console.log('FOC toggle for index:', index);
            const checkbox = document.querySelector(`input[name="items[${index}][is_foc]"]`);
            const row = checkbox ? checkbox.closest('tr') : null;
            const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);

            if (checkbox && row && totalInput) {
                if (checkbox.checked) {
                    row.classList.add('foc-row');
                    totalInput.value = '0.00';
                } else {
                    row.classList.remove('foc-row');
                    // Recalculate total
                    const quantity = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value || 0);
                    const price = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value || 0);
                    const discount = parseFloat(document.querySelector(`input[name="items[${index}][discount_amount]"]`).value || 0);
                    totalInput.value = Math.max(0, (quantity * price) - discount).toFixed(2);
                }
            }
        };
    }

    if (typeof updateProductInfo === 'undefined') {
        window.updateProductInfo = function(selectElement, index) {
            console.log('Product info update for index:', index);
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);

            if (selectedOption.value && priceInput) {
                const price = parseFloat(selectedOption.dataset.price || 0);
                priceInput.value = price.toFixed(2);

                // Show product info if elements exist
                const productInfo = document.getElementById(`productInfo${index}`);
                if (productInfo) {
                    const code = selectedOption.dataset.code || '-';
                    const stock = selectedOption.dataset.stock || '0';
                    const unit = selectedOption.dataset.unit || 'قطعة';

                    document.getElementById(`productCode${index}`).textContent = code;
                    document.getElementById(`productStock${index}`).textContent = stock + ' ' + unit;
                    document.getElementById(`productUnit${index}`).textContent = unit;
                    productInfo.style.display = 'block';
                }
            }
        };
    }

    if (typeof calculateItemTotal === 'undefined') {
        window.calculateItemTotal = function(index) {
            console.log('Calculate total for index:', index);
            const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
            const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
            const discountInput = document.querySelector(`input[name="items[${index}][discount_amount]"]`);
            const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);
            const focCheckbox = document.querySelector(`input[name="items[${index}][is_foc]"]`);

            if (quantityInput && priceInput && totalInput) {
                const quantity = parseFloat(quantityInput.value || 0);
                const price = parseFloat(priceInput.value || 0);
                const discount = parseFloat(discountInput ? discountInput.value || 0 : 0);
                const isFOC = focCheckbox ? focCheckbox.checked : false;

                const total = isFOC ? 0 : Math.max(0, (quantity * price) - discount);
                totalInput.value = total.toFixed(2);
            }
        };
    }
});



// Enhanced product info update function
function simpleUpdateProductInfo(selectElement, index) {
    console.log('Product changed for index:', index);

    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
    const productInfoDisplay = document.getElementById(`productInfo${index}`);
    const stockWarning = document.getElementById(`stockWarning${index}`);

    if (selectedOption.value) {
        const price = parseFloat(selectedOption.dataset.price || 0);
        const stock = parseInt(selectedOption.dataset.stock || 0);
        const unit = selectedOption.dataset.unit || 'قطعة';
        const code = selectedOption.dataset.code || '-';
        const company = selectedOption.dataset.company || '-';

        // Update price input
        if (priceInput) {
            priceInput.value = price.toFixed(2);

            // Add visual feedback
            priceInput.style.background = '#e6fffa';
            priceInput.style.transition = 'background 0.3s ease';
            setTimeout(() => {
                priceInput.style.background = '';
            }, 1000);
        }

        // Update product info display
        if (productInfoDisplay) {
            const productCodeElement = document.getElementById(`productCode${index}`);
            const productStockElement = document.getElementById(`productStock${index}`);
            const productUnitElement = document.getElementById(`productUnit${index}`);

            if (productCodeElement) productCodeElement.textContent = code;
            if (productStockElement) productStockElement.textContent = stock + ' ' + unit;
            if (productUnitElement) productUnitElement.textContent = unit;

            // Show product info with animation
            productInfoDisplay.style.display = 'block';
            productInfoDisplay.style.opacity = '0';
            productInfoDisplay.style.transform = 'translateY(-10px)';
            productInfoDisplay.style.transition = 'all 0.3s ease';

            setTimeout(() => {
                productInfoDisplay.style.opacity = '1';
                productInfoDisplay.style.transform = 'translateY(0)';
            }, 50);
        }

        // Show/hide stock warning
        if (stockWarning) {
            if (stock <= 10 && stock > 0) {
                stockWarning.style.display = 'flex';
                stockWarning.style.animation = 'pulse 2s infinite';
            } else {
                stockWarning.style.display = 'none';
            }
        }

        console.log('Product info updated:', {
            price: price.toFixed(2),
            stock: stock,
            unit: unit,
            code: code,
            company: company
        });

    } else {
        // Hide info displays when no product selected
        if (productInfoDisplay) {
            productInfoDisplay.style.display = 'none';
        }
        if (stockWarning) {
            stockWarning.style.display = 'none';
        }
        if (priceInput) {
            priceInput.value = '0';
        }

        console.log('Product cleared for index:', index);
    }

    // Recalculate total
    simpleCalculateTotal(index);
}

// Simple calculation function
function simpleCalculateTotal(index) {
    console.log('Calculating total for index:', index);

    const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
    const discountInput = document.querySelector(`input[name="items[${index}][discount_amount]"]`);
    const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);

    if (quantityInput && priceInput && totalInput) {
        const quantity = parseFloat(quantityInput.value || 0);
        const price = parseFloat(priceInput.value || 0);
        const discount = parseFloat(discountInput ? discountInput.value || 0 : 0);

        const total = Math.max(0, (quantity * price) - discount);
        totalInput.value = total.toFixed(2);

        // Add visual feedback
        totalInput.style.background = '#f0f9ff';
        totalInput.style.transition = 'background 0.3s ease';
        setTimeout(() => {
            totalInput.style.background = '';
        }, 500);

        console.log('Total calculated:', total.toFixed(2));
    }
}

// Notification function removed (duplicate)
</script>

</body>
</html>
