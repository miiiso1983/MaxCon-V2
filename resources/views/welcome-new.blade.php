<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaxCon - Pharmaceutical ERP System</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(-50px) translateY(-50px); }
        }
        
        .feature-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-color: #667eea;
        }
        
        .login-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        .animate-delay-4 { animation-delay: 0.4s; }
        .animate-delay-5 { animation-delay: 0.5s; }
        .animate-delay-6 { animation-delay: 0.6s; }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Hero Section -->
    <section class="hero-section text-white py-20 relative">
        <div class="container mx-auto px-6 text-center relative z-10">
            <!-- Project Name & Subtitle -->
            <div class="animate-fadeInUp animate-delay-1">
                <div class="mb-6 flex justify-center">
                    <img src="{{ file_exists(public_path('images/maxcon-logo.png')) ? asset('images/maxcon-logo.png') : asset('images/maxcon-logo.svg') }}" alt="MaxCon Logo" class="w-48 md:w-56 h-auto"/>
                </div>
                <h1 class="text-6xl md:text-7xl font-bold mb-6">
                    Pharmaceutical ERP System
                </p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <!-- Features Title -->
            <div class="text-center mb-16 animate-fadeInUp animate-delay-2">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    أهم مميزات النظام
                </h2>
                <p class="text-xl text-gray-600">
                    نظام إدارة شامل للشركات الدوائية مع أحدث التقنيات
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Feature 1 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg animate-fadeInUp animate-delay-3">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                        إدارة المبيعات والمشتريات
                    </h3>
                    <p class="text-gray-600 text-center">
                        نظام متكامل لإدارة دورة المبيعات والمشتريات مع تتبع شامل للعمليات
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg animate-fadeInUp animate-delay-4">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                        إدارة المخزون المتقدمة
                    </h3>
                    <p class="text-gray-600 text-center">
                        تتبع المخزون في الوقت الفعلي مع تنبيهات انتهاء الصلاحية والنفاد
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg animate-fadeInUp animate-delay-5">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                        تتبع الذمم المالية
                    </h3>
                    <p class="text-gray-600 text-center">
                        إدارة شاملة للذمم المدينة والدائنة للعملاء والموردين
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg animate-fadeInUp animate-delay-6">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                        تقارير وتحليلات شاملة
                    </h3>
                    <p class="text-gray-600 text-center">
                        تقارير بيانية تفاعلية وتحليلات متقدمة لاتخاذ قرارات مدروسة
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg animate-fadeInUp animate-delay-6">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                        نظام صلاحيات مرن
                    </h3>
                    <p class="text-gray-600 text-center">
                        إدارة متقدمة للمستخدمين والصلاحيات مع أمان عالي
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg animate-fadeInUp animate-delay-6">
                    <div class="feature-icon mx-auto">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
                        لوحة تحكم ذكية
                    </h3>
                    <p class="text-gray-600 text-center">
                        واجهة إدارية متطورة مع مؤشرات أداء فورية للفريق الإداري
                    </p>
                </div>
            </div>

            <!-- Login Button -->
            <div class="text-center animate-fadeInUp animate-delay-6">
                <a href="/login" class="login-btn inline-flex items-center px-12 py-4 text-white font-bold text-lg rounded-2xl relative overflow-hidden">
                    <i class="fas fa-sign-in-alt ml-3"></i>
                    Login to Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gray-400">
                © 2024 MaxCon - Pharmaceutical ERP System. جميع الحقوق محفوظة.
            </p>
        </div>
    </footer>

    <script>
        // Add entrance animations on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);

            // Observe all animated elements
            document.querySelectorAll('.animate-fadeInUp').forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });

            console.log('✅ MaxCon Welcome Page loaded successfully!');
        });
    </script>
</body>
</html>
