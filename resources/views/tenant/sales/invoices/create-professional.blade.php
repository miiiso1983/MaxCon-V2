<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إنشاء فاتورة احترافية - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">

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

<div class="invoice-container">
    <div class="invoice-wrapper">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <div class="header-title">
                        <div class="title-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h1>إنشاء فاتورة احترافية</h1>
                    </div>
                    <p class="header-subtitle">نظام إدارة الفواتير المتطور مع QR Code والبحث الذكي</p>
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
                                    <th style="width: 30%;">المنتج</th>
                                    <th style="width: 12%;">الكمية</th>
                                    <th style="width: 15%;">السعر</th>
                                    <th style="width: 12%;">الخصم</th>
                                    <th style="width: 10%;">العينات</th>
                                    <th style="width: 15%;">المجموع</th>
                                    <th style="width: 6%;">حذف</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][product_id]" required class="form-control" data-custom-select
                                                data-placeholder="اختر المنتج" data-searchable="true" onchange="updateProductInfo(this, 0)">
                                            <option value="">اختر المنتج</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-price="{{ $product->selling_price ?? $product->unit_price ?? 0 }}"
                                                        data-stock="{{ $product->current_stock ?? 0 }}"
                                                        data-unit="{{ $product->unit ?? 'قطعة' }}"
                                                        data-code="{{ $product->product_code ?? '' }}">
                                                    {{ $product->name }}
                                                    @if($product->product_code)
                                                        ({{ $product->product_code }})
                                                    @endif
                                                    @if($product->company)
                                                        - {{ $product->company }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" min="1" step="1" required
                                               class="form-control" placeholder="1" value="1" 
                                               onchange="calculateItemTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                                               class="form-control" placeholder="0.00" value="0" 
                                               onchange="calculateItemTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][discount_amount]" min="0" step="0.01"
                                               class="form-control" placeholder="0.00" value="0" 
                                               onchange="calculateItemTotal(0)" style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][free_samples]" min="0" step="1"
                                               class="form-control" placeholder="0" value="0" 
                                               style="text-align: center;">
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][total_amount]" readonly
                                               class="form-control" placeholder="0.00" value="0" 
                                               style="background: #f9fafb; text-align: center;">
                                    </td>
                                    <td>
                                        <button type="button" onclick="removeItem(0)" class="remove-item-btn" disabled>
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
                    <button type="submit" name="action" value="finalize" class="btn btn-success">
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

// Update product information when product is selected
function updateProductInfo(selectElement, index) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const price = parseFloat(selectedOption.dataset.price || 0);
    const stock = parseInt(selectedOption.dataset.stock || 0);
    const unit = selectedOption.dataset.unit || 'قطعة';
    const code = selectedOption.dataset.code || '';
    const company = selectedOption.dataset.company || '';

    // Update price field with animation
    const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
    if (priceInput) {
        priceInput.style.transition = 'all 0.3s ease';
        priceInput.style.background = '#e6fffa';
        priceInput.value = price.toFixed(2);
        setTimeout(() => {
            priceInput.style.background = '#ffffff';
        }, 1000);
    }

    // Show stock information
    const stockInfoElement = document.getElementById(`stockInfo${index}`);
    if (stockInfoElement && selectedOption.value) {
        let stockClass = 'stock-high';
        let stockIcon = 'fas fa-check-circle';

        if (stock <= 0) {
            stockClass = 'stock-low';
            stockIcon = 'fas fa-exclamation-triangle';
        } else if (stock <= 10) {
            stockClass = 'stock-medium';
            stockIcon = 'fas fa-exclamation-circle';
        }

        stockInfoElement.innerHTML = `
            <i class="${stockIcon}"></i>
            المخزون المتاح: <span class="${stockClass}">${stock} ${unit}</span>
            ${code ? `| الكود: ${code}` : ''}
            ${company ? `| الشركة: ${company}` : ''}
        `;
        stockInfoElement.style.display = 'block';
        stockInfoElement.className = `stock-info ${stockClass}`;
    } else if (stockInfoElement) {
        stockInfoElement.style.display = 'none';
    }

    // Validate stock immediately
    validateStock(index);

    // Recalculate totals
    calculateItemTotal(index);

    // Auto-save draft
    autoSaveDraft();
}

// Calculate item total with enhanced discount handling
function calculateItemTotal(index) {
    const quantity = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value || 0);
    const unitPrice = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value || 0);
    const discountAmount = parseFloat(document.querySelector(`input[name="items[${index}][discount_amount]"]`).value || 0);
    const discountType = document.querySelector(`select[name="items[${index}][discount_type]"]`)?.value || 'amount';

    let lineTotal = quantity * unitPrice;
    let actualDiscount = 0;

    if (discountType === 'percentage') {
        actualDiscount = (lineTotal * discountAmount) / 100;
    } else {
        actualDiscount = discountAmount;
    }

    lineTotal = Math.max(0, lineTotal - actualDiscount);

    const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);
    if (totalInput) {
        // Add visual feedback for calculation
        totalInput.style.transition = 'all 0.3s ease';
        totalInput.style.background = '#f0f9ff';
        totalInput.value = lineTotal.toFixed(2);
        setTimeout(() => {
            totalInput.style.background = '#f9fafb';
        }, 500);
    }

    calculateGrandTotal();
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

    // Create product options with enhanced search data
    const productOptions = `
        <option value="">اختر المنتج</option>
        @foreach($products as $product)
            <option value="{{ $product->id }}"
                    data-price="{{ $product->selling_price ?? $product->unit_price ?? 0 }}"
                    data-stock="{{ $product->current_stock ?? 0 }}"
                    data-unit="{{ $product->unit ?? 'قطعة' }}"
                    data-code="{{ $product->product_code ?? '' }}"
                    data-company="{{ $product->company ?? '' }}"
                    data-category="{{ $product->category ?? '' }}">
                {{ $product->name }}
                @if($product->product_code)
                    ({{ $product->product_code }})
                @endif
                @if($product->company)
                    - {{ $product->company }}
                @endif
                <span class="stock-info" style="color: #6b7280; font-size: 11px;">
                    (المخزون: {{ $product->current_stock ?? 0 }} {{ $product->unit ?? 'قطعة' }})
                </span>
            </option>
        @endforeach
    `;

    newRow.innerHTML = `
        <td>
            <select name="items[${itemIndex}][product_id]" required class="form-control" data-custom-select
                    data-placeholder="اختر المنتج" data-searchable="true" onchange="updateProductInfo(this, ${itemIndex})">
                ${productOptions}
            </select>
            <div class="stock-info" id="stockInfo${itemIndex}" style="display: none; margin-top: 5px; font-size: 11px;"></div>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][quantity]" min="1" step="1" required
                   class="form-control" placeholder="1" value="1"
                   onchange="calculateItemTotal(${itemIndex})" oninput="validateStock(${itemIndex})"
                   style="text-align: center;">
            <div class="error-message" id="stockError${itemIndex}" style="display: none;"></div>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" required
                   class="form-control" placeholder="0.00" value="0"
                   onchange="calculateItemTotal(${itemIndex})" style="text-align: center;">
        </td>
        <td>
            <div style="display: flex; align-items: center; gap: 5px;">
                <input type="number" name="items[${itemIndex}][discount_amount]" min="0" step="0.01"
                       class="form-control" placeholder="0.00" value="0"
                       onchange="calculateItemTotal(${itemIndex})" style="text-align: center; flex: 1;">
                <select name="items[${itemIndex}][discount_type]" class="form-control" style="width: 60px; font-size: 12px;"
                        onchange="calculateItemTotal(${itemIndex})">
                    <option value="amount">د.ع</option>
                    <option value="percentage">%</option>
                </select>
            </div>
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][free_samples]" min="0" step="1"
                   class="form-control" placeholder="0" value="0"
                   onchange="updateFreeSamplesTotal()" style="text-align: center;">
        </td>
        <td>
            <input type="number" name="items[${itemIndex}][total_amount]" readonly
                   class="form-control" placeholder="0.00" value="0"
                   style="background: #f9fafb; text-align: center; font-weight: 600;">
        </td>
        <td>
            <button type="button" onclick="removeItem(${itemIndex})" class="remove-item-btn"
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

// Show notification
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 10000;
        font-weight: 600;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i> ${message}`;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, duration);
}

// Enhanced form validation using the validation class
function validateForm() {
    if (window.invoiceValidator) {
        const validation = window.invoiceValidator.validateInvoice();

        if (!validation.isValid) {
            // Show errors
            const errorMessage = validation.errors.join('<br>');
            showNotification(errorMessage, 'error', 8000);
        }

        if (validation.warnings.length > 0) {
            // Show warnings
            const warningMessage = validation.warnings.join('<br>');
            showNotification(warningMessage, 'warning', 6000);
        }

        return validation.isValid;
    }

    // Fallback validation if validator is not available
    const customerSelect = document.getElementById('customerSelect');
    const productSelects = document.querySelectorAll('select[name*="[product_id]"]');

    if (!customerSelect.value) {
        showNotification('يرجى اختيار العميل', 'error', 3000);
        return false;
    }

    let hasValidItems = false;
    productSelects.forEach(select => {
        if (select.value) {
            hasValidItems = true;
        }
    });

    if (!hasValidItems) {
        showNotification('يرجى إضافة منتج واحد على الأقل', 'error', 3000);
        return false;
    }

    return true;
}

// Form submission with enhanced validation
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault();
        return false;
    }

    // Clear draft on successful submission
    clearDraft();

    // Show loading state
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    const clickedButton = document.activeElement;

    submitButtons.forEach(btn => {
        btn.disabled = true;
        if (btn === clickedButton) {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.dataset.originalText = originalText;
        }
    });

    // Show progress notification
    showNotification('جاري حفظ الفاتورة...', 'info', 10000);
});
</script>

<!-- Bootstrap Modal (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom Select JavaScript -->
<script src="{{ asset('js/custom-select.js') }}"></script>
<script src="{{ asset('js/universal-dropdowns.js') }}"></script>
<script src="{{ asset('js/dropdown-initializer.js') }}"></script>
<script src="{{ asset('js/invoice-validation.js') }}"></script>
<script src="{{ asset('js/professional-invoice.js') }}"></script>

</body>
</html>
