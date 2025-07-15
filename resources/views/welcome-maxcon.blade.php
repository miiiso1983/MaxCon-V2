<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - نظام إدارة الأعمال المتكامل</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- MaxCon Enhanced Styles -->
    <link rel="stylesheet" href="{{ asset('css/maxcon-enhancements.css') }}">

    <!-- Scripts -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->

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
        

    </style>
</head>

<body>
    <div class="floating-shapes"></div>
    
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative; z-index: 2;">
        <div style="max-width: 1200px; width: 100%;">
            
            <!-- Hero Section -->
            <div class="hero-card" style="padding: 60px 40px; text-align: center; margin-bottom: 60px; animation: fadeInUp 0.8s ease-out;">
                <div style="position: relative; z-index: 2;">
                    <!-- Logo -->
                    <div style="margin-bottom: 30px;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
                            <i class="fas fa-building" style="color: white; font-size: 48px;"></i>
                        </div>
                        <h1 style="font-size: 48px; font-weight: 800; color: #2d3748; margin: 0 0 15px 0;">
                            {{ config('app.name') }} 🏢
                        </h1>
                        <p style="font-size: 24px; color: #4a5568; margin: 0 0 30px 0; font-weight: 500;">
                            نظام إدارة الأعمال المتكامل والمتطور
                        </p>
                    </div>
                    
                    <!-- Features badges -->
                    <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 40px; flex-wrap: wrap;">
                        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border-radius: 25px; padding: 10px 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-users" style="margin-left: 8px;"></i>
                            إدارة متعددة المؤسسات
                        </div>
                        <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border-radius: 25px; padding: 10px 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                            أمان متقدم
                        </div>
                        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 25px; padding: 10px 20px; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-rocket" style="margin-left: 8px;"></i>
                            أداء عالي
                        </div>
                    </div>
                    
                    <!-- Login Button -->
                    <div style="display: flex; justify-content: center;">
                        <a href="{{ route('login') }}" class="btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            تسجيل الدخول
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Features Section -->
            <div id="features" style="animation: fadeInUp 0.8s ease-out 0.2s both;">
                <h2 style="text-align: center; font-size: 36px; font-weight: 800; color: white; margin: 0 0 50px 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    مميزات النظام ✨
                </h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
                    <!-- Feature 1 -->
                    <div class="feature-card">
                        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-building" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0;">إدارة المؤسسات</h3>
                        <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                            نظام متطور لإدارة عدة مؤسسات بشكل منفصل وآمن مع صلاحيات مخصصة لكل مؤسسة.
                        </p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="feature-card">
                        <div style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-users" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0;">إدارة المستخدمين</h3>
                        <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                            نظام شامل لإدارة المستخدمين مع أدوار وصلاحيات متقدمة وأمان عالي.
                        </p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="feature-card">
                        <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-chart-line" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0;">التقارير والإحصائيات</h3>
                        <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                            تقارير مفصلة وإحصائيات شاملة لمتابعة أداء المؤسسة والمستخدمين.
                        </p>
                    </div>
                    
                    <!-- Feature 4 -->
                    <div class="feature-card">
                        <div style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-shield-alt" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0;">الأمان المتقدم</h3>
                        <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                            حماية متقدمة للبيانات مع تشفير عالي وعزل كامل بين المؤسسات.
                        </p>
                    </div>
                    
                    <!-- Feature 5 -->
                    <div class="feature-card">
                        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-mobile-alt" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0;">تصميم متجاوب</h3>
                        <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                            واجهة متجاوبة تعمل بسلاسة على جميع الأجهزة والشاشات.
                        </p>
                    </div>
                    
                    <!-- Feature 6 -->
                    <div class="feature-card">
                        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas fa-cogs" style="color: white; font-size: 24px;"></i>
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0;">قابلية التخصيص</h3>
                        <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                            نظام مرن قابل للتخصيص حسب احتياجات كل مؤسسة ومتطلباتها الخاصة.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div style="text-align: center; margin-top: 80px; padding: 40px; animation: fadeInUp 0.8s ease-out 0.4s both;">
                <p style="color: rgba(255,255,255,0.8); font-size: 16px; margin: 0 0 10px 0;">
                    © {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
                </p>
                <div style="display: flex; justify-content: center; gap: 30px; margin-top: 20px;">
                    <span style="color: rgba(255,255,255,0.6); font-size: 14px;">نسخة 2.0</span>
                    <span style="color: rgba(255,255,255,0.6); font-size: 14px;">•</span>
                    <span style="color: rgba(255,255,255,0.6); font-size: 14px;">تطوير MaxCon</span>
                    <span style="color: rgba(255,255,255,0.6); font-size: 14px;">•</span>
                    <span style="color: rgba(255,255,255,0.6); font-size: 14px;">Laravel {{ app()->version() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for smooth scrolling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Add entrance animations
            const elements = document.querySelectorAll('.feature-card');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 200 * index);
            });
        });
    </script>
</body>
</html>
