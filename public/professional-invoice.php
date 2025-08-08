<?php
/**
 * Professional Invoice Creation - Direct Access
 * حل مؤقت للوصول المباشر لصفحة الفواتير الاحترافية
 */

// Redirect to Laravel route
$redirect_url = '/tenant/sales/invoices/create';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفواتير الاحترافية - MaxCon</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="card">
        <div class="mb-6">
            <i class="fas fa-file-invoice text-6xl text-blue-500 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">نظام الفواتير الاحترافي</h1>
            <p class="text-gray-600">جاري تحضير الصفحة...</p>
        </div>
        
        <div id="status" class="mb-6">
            <div class="loading mx-auto mb-4"></div>
            <p class="text-gray-500">يتم التحقق من توفر النظام...</p>
        </div>
        
        <div id="options" style="display: none;">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">اختر طريقة الوصول:</h3>
            <div class="space-y-3">
                <a href="/tenant/sales/invoices/create" class="btn w-full justify-center">
                    <i class="fas fa-file-alt"></i>
                    الفاتورة العادية (متوفرة الآن)
                </a>
                <button onclick="tryProfessional()" class="btn w-full justify-center bg-green-500">
                    <i class="fas fa-star"></i>
                    المحاولة مرة أخرى - الفاتورة الاحترافية
                </button>
                <a href="/tenant/sales/invoices" class="btn w-full justify-center bg-gray-500">
                    <i class="fas fa-list"></i>
                    العودة لقائمة الفواتير
                </a>
            </div>
        </div>
        
        <div id="error" style="display: none;" class="text-red-600">
            <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">عذراً، النظام الاحترافي غير متوفر حالياً</h3>
            <p class="mb-4">يمكنك استخدام النظام العادي أو المحاولة لاحقاً</p>
        </div>
    </div>

    <script>
        // Check if professional route is available
        async function checkProfessionalRoute() {
            try {
                const response = await fetch('/tenant/sales/invoices/professional', {
                    method: 'HEAD'
                });
                
                if (response.ok) {
                    // Route is available, redirect
                    window.location.href = '/tenant/sales/invoices/professional';
                    return true;
                } else {
                    throw new Error('Route not available');
                }
            } catch (error) {
                return false;
            }
        }
        
        async function tryProfessional() {
            document.getElementById('status').style.display = 'block';
            document.getElementById('options').style.display = 'none';
            document.getElementById('error').style.display = 'none';
            
            const available = await checkProfessionalRoute();
            
            if (!available) {
                document.getElementById('status').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                document.getElementById('options').style.display = 'block';
            }
        }
        
        // Initial check
        setTimeout(async () => {
            const available = await checkProfessionalRoute();
            
            if (!available) {
                document.getElementById('status').style.display = 'none';
                document.getElementById('options').style.display = 'block';
            }
        }, 2000);
        
        // Alternative routes to try
        const alternativeRoutes = [
            '/tenant/sales/invoices/professional',
            '/tenant/sales/invoices/create-professional',
            '/sales/invoices/professional'
        ];
        
        async function tryAlternatives() {
            for (const route of alternativeRoutes) {
                try {
                    const response = await fetch(route, { method: 'HEAD' });
                    if (response.ok) {
                        window.location.href = route;
                        return;
                    }
                } catch (error) {
                    continue;
                }
            }
        }
    </script>
</body>
</html>
