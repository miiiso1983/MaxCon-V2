<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>تسجيل الخروج - {{ config('app.name') }}</title>

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
            position: relative;
            overflow-x: hidden;
            margin: 0;
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
    </style>
</head>

<body>
    <div class="floating-shapes"></div>
    
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative; z-index: 2;">
        <div style="max-width: 450px; width: 100%;">
            <!-- Logout Confirmation -->
            <div style="background: white; border-radius: 25px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); padding: 40px; position: relative; overflow: hidden; animation: fadeInUp 0.8s ease-out;">
                <!-- Decorative elements -->
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 50%; opacity: 0.1;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 50%; opacity: 0.1;"></div>
                
                <div style="position: relative; z-index: 2; text-align: center;">
                    <!-- Icon -->
                    <div style="margin-bottom: 30px;">
                        <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 10px 30px rgba(245, 101, 101, 0.3);">
                            <i class="fas fa-sign-out-alt" style="color: white; font-size: 32px;"></i>
                        </div>
                    </div>
                    
                    <h2 style="font-size: 28px; font-weight: 800; color: #2d3748; margin: 0 0 15px 0;">تسجيل الخروج 👋</h2>
                    <p style="color: #718096; font-size: 16px; margin: 0 0 30px 0; line-height: 1.6;">
                        هل أنت متأكد من أنك تريد تسجيل الخروج من النظام؟<br>
                        سيتم إنهاء جلستك الحالية وستحتاج لتسجيل الدخول مرة أخرى.
                    </p>
                    
                    <!-- User Info -->
                    <div style="background: #f7fafc; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; color: #2d3748; margin-bottom: 2px;">{{ auth()->user()->name }}</div>
                                <div style="font-size: 14px; color: #718096;">{{ auth()->user()->email }}</div>
                                <div style="font-size: 12px; color: #667eea; margin-top: 2px;">
                                    @if(auth()->user()->isSuperAdmin())
                                        مدير النظام
                                    @elseif(auth()->user()->hasRole('tenant-admin'))
                                        مدير المؤسسة
                                    @else
                                        مستخدم
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="{{ url()->previous() }}" 
                           style="padding: 15px 25px; border: 2px solid #e2e8f0; border-radius: 12px; color: #4a5568; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;"
                           onmouseover="this.style.background='#f7fafc'; this.style.borderColor='#cbd5e0';"
                           onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0';">
                            <i class="fas fa-arrow-right"></i>
                            البقاء في النظام
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" 
                                    style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 15px 25px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(245, 101, 101, 0.5)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245, 101, 101, 0.4)';">
                                <i class="fas fa-sign-out-alt"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                    
                    <!-- Quick Login Info -->
                    <div style="margin-top: 25px; padding: 15px; background: rgba(102, 126, 234, 0.1); border-radius: 12px;">
                        <p style="font-size: 14px; color: #667eea; margin: 0; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="fas fa-info-circle"></i>
                            يمكنك تسجيل الدخول مرة أخرى في أي وقت
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div style="text-align: center; margin-top: 30px; animation: fadeInUp 0.8s ease-out 0.2s both;">
                <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">
                    © {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript for interactions -->
    <script>
        // Add entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('button, a');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });

        // Add keyboard shortcut (Escape to go back)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                window.history.back();
            }
        });
    </script>
</body>
</html>
