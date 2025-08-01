<?php
// Direct access tenant guide - works without Laravel routing
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل المستأجر الجديد - MaxCon ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f7fafc;
            line-height: 1.6;
            color: #2d3748;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            color: white;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .progress-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .progress-bar {
            background: #e2e8f0;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .progress-fill {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 10px;
        }

        .step-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 24px;
            color: white;
        }

        .step-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .step-description {
            color: #718096;
            font-size: 14px;
        }

        .task-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .task-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-checkbox {
            width: 20px;
            height: 20px;
            margin-left: 12px;
            accent-color: #667eea;
            cursor: pointer;
        }

        .task-text {
            flex: 1;
            color: #4a5568;
            font-size: 14px;
        }

        .task-item.completed .task-text {
            text-decoration: line-through;
            color: #a0aec0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #718096;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .alert-success {
            background: #f0fff4;
            border: 1px solid #68d391;
            color: #22543d;
        }

        .alert-info {
            background: #e6fffa;
            border: 1px solid #81e6d9;
            color: #234e52;
        }

        @media (max-width: 768px) {
            .header {
                padding: 25px;
            }
            
            .step-card {
                padding: 20px;
            }
            
            .step-header {
                flex-direction: column;
                text-align: center;
            }
            
            .step-icon {
                margin: 0 0 15px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-rocket" style="margin-left: 15px;"></i>
                دليل المستأجر الجديد
            </h1>
            <p>دليل شامل للاستفادة القصوى من نظام MaxCon ERP</p>
        </div>

        <!-- Success Alert -->
        <div class="alert alert-success">
            <h4 style="margin-bottom: 10px;">🎉 مرحباً بك في MaxCon ERP!</h4>
            <p>هذا الدليل سيساعدك في إعداد وتفعيل جميع وحدات النظام خلال 30 يوم.</p>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <h3 style="margin-bottom: 20px; color: #2d3748; font-weight: 700;">
                <i class="fas fa-chart-line" style="color: #667eea; margin-left: 10px;"></i>
                تقدم التنفيذ
            </h3>
            
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 0%;"></div>
            </div>
            
            <div style="text-align: center; color: #4a5568; font-weight: 600;" id="progressText">
                0% مكتمل - ابدأ رحلتك مع MaxCon ERP
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid" style="margin-top: 25px;">
                <div class="stat-card">
                    <div class="stat-number" id="completedTasks">0</div>
                    <div class="stat-label">مهام مكتملة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="totalTasks">16</div>
                    <div class="stat-label">إجمالي المهام</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="currentWeek">1</div>
                    <div class="stat-label">الأسبوع الحالي</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="daysLeft">30</div>
                    <div class="stat-label">يوم متبقي</div>
                </div>
            </div>
        </div>

        <!-- Setup Steps -->
        <div style="margin-bottom: 30px;">
            <h2 style="color: #2d3748; font-weight: 700; margin-bottom: 25px; text-align: center;">
                <i class="fas fa-list-ol" style="color: #667eea; margin-left: 10px;"></i>
                خطوات الإعداد الأساسية
            </h2>
            
            <!-- Step 1 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-icon" style="background: #667eea;">
                        <i class="fas fa-building"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="step-title">إعداد معلومات الشركة</div>
                        <div class="step-description">إدخال البيانات الأساسية للشركة والإعدادات الأولية</div>
                    </div>
                    <div style="background: #f7fafc; color: #4a5568; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">30 دقيقة</div>
                </div>
                
                <ul class="task-list">
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task1" onchange="updateProgress()">
                        <label for="task1" class="task-text">إدخال اسم الشركة والعنوان</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task2" onchange="updateProgress()">
                        <label for="task2" class="task-text">رفع شعار الشركة</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task3" onchange="updateProgress()">
                        <label for="task3" class="task-text">تحديد العملة والمنطقة الزمنية</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task4" onchange="updateProgress()">
                        <label for="task4" class="task-text">إعداد إعدادات الأمان</label>
                    </li>
                </ul>
            </div>

            <!-- Step 2 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-icon" style="background: #10b981;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="step-title">إدارة المستخدمين</div>
                        <div class="step-description">إضافة المستخدمين وتعيين الأدوار والصلاحيات</div>
                    </div>
                    <div style="background: #f7fafc; color: #4a5568; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">45 دقيقة</div>
                </div>
                
                <ul class="task-list">
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task5" onchange="updateProgress()">
                        <label for="task5" class="task-text">إضافة المستخدمين الأساسيين</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task6" onchange="updateProgress()">
                        <label for="task6" class="task-text">تعيين الأدوار والصلاحيات</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task7" onchange="updateProgress()">
                        <label for="task7" class="task-text">إعداد كلمات مرور قوية</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task8" onchange="updateProgress()">
                        <label for="task8" class="task-text">اختبار تسجيل الدخول</label>
                    </li>
                </ul>
            </div>

            <!-- Step 3 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-icon" style="background: #f59e0b;">
                        <i class="fas fa-database"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="step-title">إعداد البيانات الأساسية</div>
                        <div class="step-description">إدخال بيانات العملاء والمنتجات الأساسية</div>
                    </div>
                    <div style="background: #f7fafc; color: #4a5568; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">2 ساعة</div>
                </div>
                
                <ul class="task-list">
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task9" onchange="updateProgress()">
                        <label for="task9" class="task-text">إدخال بيانات العملاء الرئيسيين</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task10" onchange="updateProgress()">
                        <label for="task10" class="task-text">إعداد كتالوج المنتجات</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task11" onchange="updateProgress()">
                        <label for="task11" class="task-text">تحديد أسعار البيع والشراء</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task12" onchange="updateProgress()">
                        <label for="task12" class="task-text">إعداد فئات المنتجات</label>
                    </li>
                </ul>
            </div>

            <!-- Step 4 -->
            <div class="step-card">
                <div class="step-header">
                    <div class="step-icon" style="background: #8b5cf6;">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="step-title">تفعيل الوحدات</div>
                        <div class="step-description">تفعيل واختبار جميع وحدات النظام</div>
                    </div>
                    <div style="background: #f7fafc; color: #4a5568; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">3 ساعات</div>
                </div>
                
                <ul class="task-list">
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task13" onchange="updateProgress()">
                        <label for="task13" class="task-text">تفعيل وحدة المبيعات</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task14" onchange="updateProgress()">
                        <label for="task14" class="task-text">تفعيل وحدة المخزون</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task15" onchange="updateProgress()">
                        <label for="task15" class="task-text">تفعيل وحدة المحاسبة</label>
                    </li>
                    <li class="task-item">
                        <input type="checkbox" class="task-checkbox" id="task16" onchange="updateProgress()">
                        <label for="task16" class="task-text">تفعيل وحدة الموارد البشرية</label>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="/دليل_المستأجر_التفاعلي.html" target="_blank" class="btn btn-primary">
                <i class="fas fa-external-link-alt"></i>
                الدليل التفاعلي الكامل
            </a>
            <a href="/دليل_المستأجر_للطباعة.html" target="_blank" class="btn btn-info">
                <i class="fas fa-print"></i>
                نسخة للطباعة
            </a>
            <a href="https://www.maxcon.app" class="btn btn-success">
                <i class="fas fa-home"></i>
                العودة للنظام
            </a>
        </div>

        <!-- Info Alert -->
        <div class="alert alert-info">
            <h4 style="margin-bottom: 10px;">💡 نصيحة مهمة:</h4>
            <p>هذا الدليل يحفظ تقدمك تلقائياً في المتصفح. يمكنك العودة إليه في أي وقت لمتابعة من حيث توقفت.</p>
        </div>
    </div>

    <script>
        // Initialize progress tracking
        let totalTasks = 16;
        let completedTasks = 0;

        // Load saved progress from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            loadProgress();
            updateProgress();
        });

        function updateProgress() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            completedTasks = Array.from(checkboxes).filter(cb => cb.checked).length;
            
            const percentage = Math.round((completedTasks / totalTasks) * 100);
            
            // Update progress bar
            document.getElementById('progressFill').style.width = percentage + '%';
            document.getElementById('progressText').textContent = percentage + '% مكتمل';
            
            // Update stats
            document.getElementById('completedTasks').textContent = completedTasks;
            
            // Update current week based on progress
            const currentWeek = Math.ceil((completedTasks / totalTasks) * 4) || 1;
            document.getElementById('currentWeek').textContent = currentWeek;
            
            // Update days left
            const daysLeft = Math.max(0, 30 - Math.floor((completedTasks / totalTasks) * 30));
            document.getElementById('daysLeft').textContent = daysLeft;
            
            // Update visual state of completed tasks
            checkboxes.forEach(checkbox => {
                const taskItem = checkbox.closest('.task-item');
                if (checkbox.checked) {
                    taskItem.classList.add('completed');
                } else {
                    taskItem.classList.remove('completed');
                }
            });
            
            // Save progress to localStorage
            saveProgress();
            
            // Show congratulations if all tasks completed
            if (percentage === 100) {
                showCongratulations();
            }
        }

        function saveProgress() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const progress = {};
            
            checkboxes.forEach(checkbox => {
                progress[checkbox.id] = checkbox.checked;
            });
            
            localStorage.setItem('maxconTenantGuideProgress', JSON.stringify(progress));
        }

        function loadProgress() {
            const savedProgress = localStorage.getItem('maxconTenantGuideProgress');
            
            if (savedProgress) {
                const progress = JSON.parse(savedProgress);
                
                Object.keys(progress).forEach(checkboxId => {
                    const checkbox = document.getElementById(checkboxId);
                    if (checkbox) {
                        checkbox.checked = progress[checkboxId];
                    }
                });
            }
        }

        function showCongratulations() {
            setTimeout(() => {
                alert('🎉 تهانينا! لقد أكملت جميع خطوات إعداد النظام بنجاح. أنت الآن جاهز لاستخدام MaxCon ERP بكامل إمكانياته!');
            }, 500);
        }
    </script>
</body>
</html>
