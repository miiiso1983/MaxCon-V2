<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - نظام إدارة الأعمال الصيدلاني المتكامل</title>

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
            top: 15%;
            right: 50%;
            transform: translateX(50%);
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateX(50%) translateY(0px); }
            50% { transform: translateX(50%) translateY(-20px); }
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 60px 50px;
            text-align: center;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
            max-width: 800px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            opacity: 0.1;
        }

        /* Logo and title */
        .logo-section {
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }

        .logo-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
        }

        .main-title {
            font-size: 48px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 24px;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 30px;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(72, 187, 120, 0.5);
        }

        .btn-tertiary {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.4);
        }

        .btn-tertiary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(237, 137, 54, 0.5);
        }

        /* Login button special styling */
        .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 40px;
            border-radius: 30px;
            font-size: 18px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }

        /* Features preview section */
        .features-preview {
            margin-top: 50px;
            position: relative;
            z-index: 2;
        }

        .features-title {
            font-size: 32px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            border-color: rgba(102, 126, 234, 0.3);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 20px;
        }

        .feature-name {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .hero-card {
                padding: 40px 30px;
                margin: 20px;
            }

            .main-title {
                font-size: 36px;
            }

            .subtitle {
                font-size: 20px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .features-grid {
                grid-template-columns: 1fr;
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
                <div class="logo-icon">
                    <i class="fas fa-building" style="color: white; font-size: 40px;"></i>
                </div>
                <h1 class="main-title">MaxCon ERP</h1>
                <p class="subtitle">نظام إدارة الأعمال المتكامل والمتطور</p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="#" class="btn btn-tertiary">
                    <i class="fas fa-play"></i>
                    أداة عمل
                </a>
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-user-tie"></i>
                    أمان متقدم
                </a>
                <a href="#" class="btn btn-secondary">
                    <i class="fas fa-cogs"></i>
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

            <!-- Features Preview -->
            <div class="features-preview">
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

