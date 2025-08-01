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
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            animation: float 20s ease-in-out infinite;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .floating-shapes::before,
        .floating-shapes::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 15s ease-in-out infinite;
        }

        .floating-shapes::before {
            width: 300px;
            height: 300px;
            top: 10%;
            right: -150px;
            animation-delay: -5s;
        }

        .floating-shapes::after {
            width: 200px;
            height: 200px;
            bottom: 10%;
            left: -100px;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(1deg); }
            66% { transform: translateY(-10px) rotate(-1deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
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

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: 0.1;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        .nav-header {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            z-index: 10;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .nav-btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #2d3748;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>
<body>
    <div class="floating-shapes"></div>

    <!-- Navigation Header -->
    @if (Route::has('login'))
        <div class="nav-header">
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-btn">
                    <i class="fas fa-tachometer-alt" style="margin-left: 8px;"></i>
                    لوحة التحكم
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-btn">
                    <i class="fas fa-sign-in-alt" style="margin-left: 8px;"></i>
                    تسجيل الدخول
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-btn">
                        <i class="fas fa-user-plus" style="margin-left: 8px;"></i>
                        إنشاء حساب
                    </a>
                @endif
            @endauth
        </div>
    @endif

    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative; z-index: 2;">
        <div style="max-width: 1400px; width: 100%;">

            <!-- Hero Section -->
            <div class="hero-card" style="padding: 60px 40px; text-align: center; margin-bottom: 60px; animation: fadeInUp 0.8s ease-out;">
                <div style="position: relative; z-index: 2;">
                    <!-- Logo -->
                    <div style="margin-bottom: 30px;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
                            <i class="fas fa-pills" style="color: white; font-size: 56px;"></i>
                        </div>
                        <h1 style="font-size: 52px; font-weight: 800; color: #2d3748; margin: 0 0 15px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            {{ config('app.name') }}
                        </h1>
                        <p style="font-size: 28px; color: #4a5568; margin: 0 0 20px 0; font-weight: 600;">
                            نظام إدارة الأعمال الصيدلاني المتكامل
                        </p>
                        <p style="font-size: 18px; color: #718096; margin: 0 0 30px 0; line-height: 1.6;">
                            حلول متطورة لإدارة الصيدليات والشركات الدوائية مع أحدث التقنيات والذكاء الاصطناعي
                        </p>
                    </div>

                    <!-- Key Features badges -->
                    <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 40px; flex-wrap: wrap;">
                        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 25px; padding: 12px 24px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);">
                            <i class="fas fa-building" style="margin-left: 8px;"></i>
                            إدارة متعددة المؤسسات
                        </div>
                        <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 25px; padding: 12px 24px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3);">
                            <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                            أمان متقدم
                        </div>
                        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 25px; padding: 12px 24px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);">
                            <i class="fas fa-brain" style="margin-left: 8px;"></i>
                            ذكاء اصطناعي
                        </div>
                        <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); color: white; border-radius: 25px; padding: 12px 24px; font-size: 14px; font-weight: 600; box-shadow: 0 4px 15px rgba(159, 122, 234, 0.3);">
                            <i class="fas fa-mobile-alt" style="margin-left: 8px;"></i>
                            تصميم متجاوب
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                        <a href="{{ route('login') }}" class="btn-primary" style="font-size: 18px; padding: 18px 36px;">
                            <i class="fas fa-sign-in-alt"></i>
                            تسجيل الدخول للنظام
                        </a>
                        <a href="#features" class="btn-primary" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); box-shadow: 0 4px 15px rgba(72, 187, 120, 0.4); font-size: 18px; padding: 18px 36px;">
                            <i class="fas fa-arrow-down"></i>
                            استكشف المميزات
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" style="margin-bottom: 60px;">
                <div style="text-align: center; margin-bottom: 50px;">
                    <h2 style="font-size: 42px; font-weight: 800; color: #2d3748; margin: 0 0 15px 0;">
                        مميزات النظام الشاملة
                    </h2>
                    <p style="font-size: 18px; color: #718096; margin: 0; line-height: 1.6;">
                        نظام متكامل يغطي جميع احتياجات الشركات الصيدلانية مع أحدث التقنيات
                    </p>
                </div>

                <!-- Core Modules Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; margin-bottom: 50px;">

                    <!-- Sales Management -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.1s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-shopping-bag" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">إدارة المبيعات</h3>
                                <p style="color: #10b981; margin: 5px 0 0 0;">نظام مبيعات متكامل</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #10b981; margin-left: 10px;"></i>
                                إدارة العملاء والموردين
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #10b981; margin-left: 10px;"></i>
                                إصدار الفواتير الإلكترونية مع QR
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #10b981; margin-left: 10px;"></i>
                                معالجة المرتجعات والمبادلات
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #10b981; margin-left: 10px;"></i>
                                تتبع المدفوعات والديون
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #10b981; margin-left: 10px;"></i>
                                تكامل واتساب لإرسال الفواتير
                            </li>
                        </ul>
                    </div>

                    <!-- Inventory Management -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.2s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-warehouse" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">إدارة المخزون</h3>
                                <p style="color: #3b82f6; margin: 5px 0 0 0;">تتبع ذكي للمخزون</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #3b82f6; margin-left: 10px;"></i>
                                كتالوج المنتجات مع الباركود
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #3b82f6; margin-left: 10px;"></i>
                                إدارة المستودعات المتعددة
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #3b82f6; margin-left: 10px;"></i>
                                تتبع تواريخ الانتهاء
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #3b82f6; margin-left: 10px;"></i>
                                تنبيهات المخزون المنخفض
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #3b82f6; margin-left: 10px;"></i>
                                الجرد الدوري والتلقائي
                            </li>
                        </ul>
                    </div>

                    <!-- Accounting System -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.3s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-calculator" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">النظام المحاسبي</h3>
                                <p style="color: #ef4444; margin: 5px 0 0 0;">محاسبة متقدمة</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ef4444; margin-left: 10px;"></i>
                                دليل الحسابات الهرمي
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ef4444; margin-left: 10px;"></i>
                                القيود المحاسبية التلقائية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ef4444; margin-left: 10px;"></i>
                                التقارير المالية الشاملة
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ef4444; margin-left: 10px;"></i>
                                مراكز التكلفة والميزانيات
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ef4444; margin-left: 10px;"></i>
                                دعم العملات المتعددة
                            </li>
                        </ul>
                    </div>

                    <!-- Human Resources -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.4s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-users" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">الموارد البشرية</h3>
                                <p style="color: #8b5cf6; margin: 5px 0 0 0;">إدارة شاملة للموظفين</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #8b5cf6; margin-left: 10px;"></i>
                                ملفات الموظفين الشاملة
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #8b5cf6; margin-left: 10px;"></i>
                                نظام الحضور والانصراف
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #8b5cf6; margin-left: 10px;"></i>
                                إدارة الإجازات والعطل
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #8b5cf6; margin-left: 10px;"></i>
                                نظام الرواتب والمكافآت
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #8b5cf6; margin-left: 10px;"></i>
                                تقييم الأداء والترقيات
                            </li>
                        </ul>
                    </div>

                    <!-- Procurement Management -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.5s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-truck" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">إدارة المشتريات</h3>
                                <p style="color: #f59e0b; margin: 5px 0 0 0;">مشتريات ذكية</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #f59e0b; margin-left: 10px;"></i>
                                إدارة الموردين والعقود
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #f59e0b; margin-left: 10px;"></i>
                                طلبات الشراء الداخلية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #f59e0b; margin-left: 10px;"></i>
                                مقارنة الأسعار والعروض
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #f59e0b; margin-left: 10px;"></i>
                                أوامر الشراء الرسمية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #f59e0b; margin-left: 10px;"></i>
                                استلام البضائع والفحص
                            </li>
                        </ul>
                    </div>

                    <!-- Regulatory Affairs -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.6s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-shield-alt" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">الشؤون التنظيمية</h3>
                                <p style="color: #06b6d4; margin: 5px 0 0 0;">امتثال تنظيمي</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #06b6d4; margin-left: 10px;"></i>
                                تسجيل الشركات والتراخيص
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #06b6d4; margin-left: 10px;"></i>
                                تصنيف المنتجات الدوائية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #06b6d4; margin-left: 10px;"></i>
                                إدارة التفتيش والمراجعة
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #06b6d4; margin-left: 10px;"></i>
                                شهادات الجودة والانتهاء
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #06b6d4; margin-left: 10px;"></i>
                                أرشيف الوثائق القانونية
                            </li>
                        </ul>
                    </div>

                    <!-- AI & Business Intelligence -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.7s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-brain" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">الذكاء الاصطناعي</h3>
                                <p style="color: #ec4899; margin: 5px 0 0 0;">تحليلات ذكية</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ec4899; margin-left: 10px;"></i>
                                لوحات تحكم تفاعلية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ec4899; margin-left: 10px;"></i>
                                تحليل اتجاهات السوق
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ec4899; margin-left: 10px;"></i>
                                توقعات المبيعات الذكية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ec4899; margin-left: 10px;"></i>
                                تحليل الربحية والمخاطر
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #ec4899; margin-left: 10px;"></i>
                                تقارير بصرية للإدارة
                            </li>
                        </ul>
                    </div>

                    <!-- Dynamic Reporting -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.8s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-chart-bar" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">التقارير الديناميكية</h3>
                                <p style="color: #84cc16; margin: 5px 0 0 0;">تقارير شاملة</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #84cc16; margin-left: 10px;"></i>
                                تقارير المبيعات والعملاء
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #84cc16; margin-left: 10px;"></i>
                                التقارير المالية والتدفق النقدي
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #84cc16; margin-left: 10px;"></i>
                                تقارير المخزون والحركة
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #84cc16; margin-left: 10px;"></i>
                                تصدير Excel/PDF/طباعة
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #84cc16; margin-left: 10px;"></i>
                                إرسال التقارير بالبريد
                            </li>
                        </ul>
                    </div>

                    <!-- Iraqi Market Localization -->
                    <div class="feature-card" style="animation: slideInRight 0.8s ease-out 0.9s both;">
                        <div class="feature-card::before" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);"></div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-flag" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 20px; font-weight: 700; color: #1e3a8a; margin: 0;">التوطين العراقي</h3>
                                <p style="color: #dc2626; margin: 5px 0 0 0;">متوافق مع السوق العراقي</p>
                            </div>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #dc2626; margin-left: 10px;"></i>
                                دعم كامل للغة العربية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #dc2626; margin-left: 10px;"></i>
                                الدينار العراقي كعملة أساسية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #dc2626; margin-left: 10px;"></i>
                                النظام الضريبي العراقي
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #dc2626; margin-left: 10px;"></i>
                                التقارير الحكومية الرسمية
                            </li>
                            <li style="padding: 8px 0; color: #4a5568; display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="color: #dc2626; margin-left: 10px;"></i>
                                تكامل البنوك العراقية
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div style="background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 100%); border-radius: 25px; padding: 50px 40px; margin: 60px 0; color: white; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; opacity: 0.5;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255, 255, 255, 0.05); border-radius: 50%;"></div>

                    <div style="text-align: center; margin-bottom: 40px; position: relative; z-index: 2;">
                        <h2 style="font-size: 36px; font-weight: 800; margin: 0 0 15px 0;">إحصائيات النظام</h2>
                        <p style="font-size: 18px; opacity: 0.9; margin: 0;">أرقام تعكس قوة وشمولية النظام</p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; position: relative; z-index: 2;">
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 48px; font-weight: 800; margin-bottom: 10px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                9+
                            </div>
                            <h4 style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">وحدات أساسية</h4>
                            <p style="font-size: 14px; opacity: 0.8; margin: 0;">أنظمة متكاملة شاملة</p>
                        </div>
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 48px; font-weight: 800; margin-bottom: 10px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                50+
                            </div>
                            <h4 style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">ميزة متقدمة</h4>
                            <p style="font-size: 14px; opacity: 0.8; margin: 0;">وظائف احترافية متطورة</p>
                        </div>
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 48px; font-weight: 800; margin-bottom: 10px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                100%
                            </div>
                            <h4 style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">متوافق مع العراق</h4>
                            <p style="font-size: 14px; opacity: 0.8; margin: 0;">مصمم للسوق العراقي</p>
                        </div>
                        <div style="text-align: center; padding: 20px;">
                            <div style="font-size: 48px; font-weight: 800; margin-bottom: 10px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                24/7
                            </div>
                            <h4 style="font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">دعم فني</h4>
                            <p style="font-size: 14px; opacity: 0.8; margin: 0;">مساعدة مستمرة</p>
                        </div>
                    </div>
                </div>

                <!-- Technology Stack -->
                <div style="margin: 60px 0;">
                    <div style="text-align: center; margin-bottom: 40px;">
                        <h2 style="font-size: 36px; font-weight: 800; color: #2d3748; margin: 0 0 15px 0;">التقنيات المستخدمة</h2>
                        <p style="font-size: 18px; color: #718096; margin: 0;">أحدث التقنيات لضمان الأداء والأمان</p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); text-align: center; border: 2px solid #f1f5f9; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#3b82f6';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#f1f5f9';">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: white; font-size: 24px;">
                                <i class="fab fa-laravel"></i>
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 10px 0;">Laravel 10+</h4>
                            <p style="font-size: 14px; color: #718096; margin: 0;">إطار عمل PHP متقدم</p>
                        </div>
                        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); text-align: center; border: 2px solid #f1f5f9; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#3b82f6';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#f1f5f9';">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4285f4 0%, #3367d6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: white; font-size: 24px;">
                                <i class="fas fa-database"></i>
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 10px 0;">MySQL/MariaDB</h4>
                            <p style="font-size: 14px; color: #718096; margin: 0;">قاعدة بيانات موثوقة</p>
                        </div>
                        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); text-align: center; border: 2px solid #f1f5f9; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#3b82f6';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#f1f5f9';">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: white; font-size: 24px;">
                                <i class="fab fa-css3-alt"></i>
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 10px 0;">Tailwind CSS</h4>
                            <p style="font-size: 14px; color: #718096; margin: 0;">تصميم حديث ومرن</p>
                        </div>
                        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); text-align: center; border: 2px solid #f1f5f9; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='#3b82f6';" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#f1f5f9';">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: white; font-size: 24px;">
                                <i class="fab fa-js-square"></i>
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: #2d3748; margin: 0 0 10px 0;">Alpine.js & Livewire</h4>
                            <p style="font-size: 14px; color: #718096; margin: 0;">تفاعل ديناميكي</p>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 25px; padding: 60px 40px; text-align: center; color: white; position: relative; overflow: hidden; margin: 60px 0;">
                    <div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; opacity: 0.3;"></div>
                    <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255, 255, 255, 0.05); border-radius: 50%;"></div>

                    <div style="position: relative; z-index: 2;">
                        <h2 style="font-size: 42px; font-weight: 800; margin: 0 0 20px 0;">ابدأ رحلتك الرقمية اليوم</h2>
                        <p style="font-size: 20px; opacity: 0.9; margin: 0 0 40px 0; line-height: 1.6;">
                            انضم إلى مئات الشركات الصيدلانية التي تثق في نظام MaxCon لإدارة أعمالها
                        </p>

                        <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                            <a href="{{ route('login') }}" class="btn-primary" style="font-size: 20px; padding: 20px 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);">
                                <i class="fas fa-rocket"></i>
                                ابدأ الآن
                            </a>
                            <a href="#features" class="btn-primary" style="font-size: 20px; padding: 20px 40px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 2px solid rgba(255, 255, 255, 0.3); box-shadow: 0 8px 25px rgba(255, 255, 255, 0.1);">
                                <i class="fas fa-play-circle"></i>
                                شاهد العرض التوضيحي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); border-radius: 25px; padding: 50px 40px 30px; color: white; margin-top: 60px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
                    <!-- Company Info -->
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 15px;">
                                <i class="fas fa-pills" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 24px; font-weight: 800; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ config('app.name') }}</h3>
                                <p style="font-size: 14px; color: #9ca3af; margin: 0;">نظام إدارة الأعمال الصيدلاني</p>
                            </div>
                        </div>
                        <p style="color: #d1d5db; line-height: 1.6; margin: 0;">
                            الحل الأمثل لإدارة الشركات الصيدلانية بأحدث التقنيات والذكاء الاصطناعي، مصمم خصيصاً للسوق العراقي.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 style="font-size: 18px; font-weight: 700; margin: 0 0 20px 0; color: #f9fafb;">روابط سريعة</h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 12px;">
                                <a href="{{ route('login') }}" style="color: #d1d5db; text-decoration: none; display: flex; align-items: center; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#d1d5db'">
                                    <i class="fas fa-sign-in-alt" style="margin-left: 10px; width: 16px;"></i>
                                    تسجيل الدخول
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="#features" style="color: #d1d5db; text-decoration: none; display: flex; align-items: center; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#d1d5db'">
                                    <i class="fas fa-star" style="margin-left: 10px; width: 16px;"></i>
                                    المميزات
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="#" style="color: #d1d5db; text-decoration: none; display: flex; align-items: center; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#d1d5db'">
                                    <i class="fas fa-headset" style="margin-left: 10px; width: 16px;"></i>
                                    الدعم الفني
                                </a>
                            </li>
                            <li style="margin-bottom: 12px;">
                                <a href="#" style="color: #d1d5db; text-decoration: none; display: flex; align-items: center; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#d1d5db'">
                                    <i class="fas fa-book" style="margin-left: 10px; width: 16px;"></i>
                                    دليل المستخدم
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h4 style="font-size: 18px; font-weight: 700; margin: 0 0 20px 0; color: #f9fafb;">معلومات التواصل</h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 12px; display: flex; align-items: center; color: #d1d5db;">
                                <i class="fas fa-envelope" style="margin-left: 10px; width: 16px; color: #667eea;"></i>
                                info@maxcon.app
                            </li>
                            <li style="margin-bottom: 12px; display: flex; align-items: center; color: #d1d5db;">
                                <i class="fas fa-phone" style="margin-left: 10px; width: 16px; color: #667eea;"></i>
                                +964 XXX XXX XXXX
                            </li>
                            <li style="margin-bottom: 12px; display: flex; align-items: center; color: #d1d5db;">
                                <i class="fas fa-map-marker-alt" style="margin-left: 10px; width: 16px; color: #667eea;"></i>
                                العراق - بغداد
                            </li>
                            <li style="margin-bottom: 12px; display: flex; align-items: center; color: #d1d5db;">
                                <i class="fab fa-whatsapp" style="margin-left: 10px; width: 16px; color: #25d366;"></i>
                                واتساب: +964 XXX XXX XXXX
                            </li>
                        </ul>
                    </div>

                    <!-- Social Media -->
                    <div>
                        <h4 style="font-size: 18px; font-weight: 700; margin: 0 0 20px 0; color: #f9fafb;">تابعنا</h4>
                        <div style="display: flex; gap: 15px;">
                            <a href="#" style="width: 45px; height: 45px; background: linear-gradient(135deg, #1877f2 0%, #166fe5 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" style="width: 45px; height: 45px; background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" style="width: 45px; height: 45px; background: linear-gradient(135deg, #0077b5 0%, #005885 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" style="width: 45px; height: 45px; background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>

                        <div style="margin-top: 25px;">
                            <p style="color: #9ca3af; font-size: 14px; margin: 0 0 10px 0;">اشترك في النشرة الإخبارية</p>
                            <div style="display: flex; gap: 10px;">
                                <input type="email" placeholder="البريد الإلكتروني" style="flex: 1; padding: 12px 15px; border: 1px solid #374151; border-radius: 8px; background: #374151; color: white; font-size: 14px;" />
                                <button style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 12px 20px; cursor: pointer; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div style="border-top: 1px solid #374151; padding-top: 30px; text-align: center;">
                    <p style="color: #9ca3af; margin: 0; font-size: 14px;">
                        © {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة. | مصمم بـ ❤️ للسوق العراقي
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Smooth Scrolling Script -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease-in-out';
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all feature cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
