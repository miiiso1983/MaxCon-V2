<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - نظام إدارة الأعمال الصيدلاني المتكامل والمتطور</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Floating background circle */
        .floating-circle {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateX(-50%) translateY(0px); 
                opacity: 0.15;
            }
            50% { 
                transform: translateX(-50%) translateY(-30px); 
                opacity: 0.25;
            }
        }

        /* Main container */
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            z-index: 2;
        }

        /* Hero card */
        .hero-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
        }

        /* Logo and title */
        .logo-section {
            margin-bottom: 35px;
            position: relative;
            z-index: 2;
        }

        .logo-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .main-title {
            font-size: 42px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 12px;
        }

        .subtitle {
            font-size: 20px;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 35px;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 35px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 20px;
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

        .btn-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }

        .btn-orange:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
        }

        .btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        }

        .btn-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .btn-green:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
        }

        /* Login button */
        .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 35px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            margin-bottom: 40px;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
        }

        /* Features section */
        .features-section {
            position: relative;
            z-index: 2;
        }

        .features-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-size: 16px;
        }

        .feature-name {
            font-size: 12px;
            font-weight: 600;
            color: #2d3748;
            line-height: 1.3;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .hero-card {
                padding: 35px 25px;
                margin: 15px;
            }
            
            .main-title {
                font-size: 32px;
            }
            
            .subtitle {
                font-size: 18px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
            
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }

        @media (max-width: 480px) {
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons .btn {
                width: 200px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Floating background circle -->
    <div class="floating-circle"></div>

    <div class="main-container">
        <div class="hero-card">
            <!-- Logo and Title Section -->
            <div class="logo-section">
                <div style="display:flex; justify-content:center; margin-bottom: 16px;">
                    <img src="{{ file_exists(public_path('images/maxcon-logo.png')) ? asset('images/maxcon-logo.png') : asset('images/maxcon-logo.svg') }}?v=20250824" alt="MaxCon Logo" style="max-width: 220px; width: 60%; height: auto;" />
                </div>
                <h1 class="main-title">MaxCon ERP</h1>
                <p class="subtitle">نظام إدارة الأعمال المتكامل والمتطور</p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="#" class="btn btn-orange">
                    <i class="fas fa-tools"></i>
                    أداة عمل
                </a>
                <a href="#" class="btn btn-blue">
                    <i class="fas fa-shield-alt"></i>
                    أمان متقدم
                </a>
                <a href="#" class="btn btn-green">
                    <i class="fas fa-building"></i>
                    إدارة متعددة المؤسسات
                </a>
            </div>

            <!-- Login Button -->
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    تسجيل الدخول
                </a>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <h2 class="features-title">
                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                    مميزات النظام
                </h2>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="feature-name">إدارة المبيعات</div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="feature-name">إدارة المخزون</div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="feature-name">النظام المحاسبي</div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-name">إدارة الموارد البشرية</div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="feature-name">إدارة المشتريات</div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="feature-name">التقارير والتحليلات</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
