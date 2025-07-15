<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>تسجيل الدخول - {{ config('app.name') }}</title>

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
    </style>
</head>
<body style="font-family: 'Cairo', sans-serif; min-height: 100vh; margin: 0; position: relative;">
    <div class="floating-shapes"></div>

    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative; z-index: 2;">
        <div style="max-width: 450px; width: 100%;">
            <!-- Logo and Header -->
            <div style="text-align: center; margin-bottom: 40px; animation: fadeInUp 0.8s ease-out;">
                <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 30px;">
                    <div style="background: rgba(255,255,255,0.2); padding: 20px; border-radius: 50%; backdrop-filter: blur(10px); box-shadow: 0 8px 32px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-building" style="color: white; font-size: 48px;"></i>
                    </div>
                </div>
                <h1 style="font-size: 36px; font-weight: 800; color: white; margin: 0 0 15px 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    مرحباً بك في {{ config('app.name') }} 🏢
                </h1>
                <p style="color: rgba(255,255,255,0.9); font-size: 18px; margin: 0; font-weight: 500;">
                    نظام إدارة الأعمال المتكامل والمتطور
                </p>

                <!-- Decorative badges -->
                <div style="display: flex; justify-content: center; gap: 15px; margin-top: 20px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                        <i class="fas fa-shield-alt" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: white;">آمن ومحمي</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                        <i class="fas fa-rocket" style="margin-left: 8px; color: #fbbf24;"></i>
                        <span style="font-size: 14px; font-weight: 600; color: white;">سريع وفعال</span>
                    </div>
                </div>
            </div>

            <!-- Tenant Info -->
            @if(is_tenant_context())
                <div style="margin-bottom: 30px; text-align: center; animation: fadeInUp 0.8s ease-out 0.2s both;">
                    <div style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); border-radius: 15px; padding: 20px;">
                        <p style="color: white; font-size: 16px; font-weight: 600; margin: 0;">
                            <i class="fas fa-building" style="margin-left: 10px; color: #4ade80;"></i>
                            تسجيل الدخول إلى <strong>{{ tenant()->name }}</strong>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <div style="background: white; border-radius: 25px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); padding: 40px; position: relative; overflow: hidden; animation: fadeInUp 0.8s ease-out 0.4s both;">
                <!-- Decorative elements -->
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; opacity: 0.1;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 50%; opacity: 0.1;"></div>

                <div style="position: relative; z-index: 2;">
                    <div style="margin-bottom: 30px; text-align: center;">
                        <h2 style="font-size: 28px; font-weight: 800; color: #2d3748; margin: 0 0 10px 0;">تسجيل الدخول 🔐</h2>
                        <p style="color: #718096; font-size: 16px; margin: 0;">أدخل بياناتك للوصول إلى حسابك</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div style="margin-bottom: 25px; padding: 15px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 12px; color: white;">
                            <div style="display: flex; align-items: center;">
                                <i class="fas fa-check-circle" style="margin-left: 10px; font-size: 18px;"></i>
                                <span style="font-weight: 600;">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div style="margin-bottom: 25px;">
                            <div style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border-radius: 12px; padding: 20px; color: white;">
                                <div style="display: flex; align-items: flex-start;">
                                    <div style="flex-shrink: 0;">
                                        <i class="fas fa-exclamation-triangle" style="font-size: 18px;"></i>
                                    </div>
                                    <div style="margin-right: 15px;">
                                        <h3 style="font-size: 16px; font-weight: 700; margin: 0 0 10px 0;">
                                            يرجى تصحيح الأخطاء التالية:
                                        </h3>
                                        <ul style="margin: 0; padding: 0; list-style: none;">
                                            @foreach ($errors->all() as $error)
                                                <li style="display: flex; align-items: center; margin-bottom: 5px;">
                                                    <i class="fas fa-circle" style="font-size: 6px; margin-left: 10px; opacity: 0.8;"></i>
                                                    <span style="font-size: 14px;">{{ $error }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" style="display: grid; gap: 25px;">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" style="display: block; font-size: 15px; font-weight: 600; color: #2d3748; margin-bottom: 10px;">
                                <i class="fas fa-envelope" style="margin-left: 10px; color: #667eea;"></i>
                                البريد الإلكتروني
                            </label>
                            <div style="position: relative;">
                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       style="width: 100%; padding: 18px 55px 18px 20px; border: 2px solid {{ $errors->has('email') ? '#f56565' : '#e2e8f0' }}; border-radius: 15px; font-size: 16px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                       placeholder="أدخل بريدك الإلكتروني"
                                       onfocus="this.style.borderColor='#667eea'; this.style.background='#ffffff'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.15)';"
                                       onblur="this.style.borderColor='#e2e8f0'; this.style.background='#ffffff'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';">
                                <div style="position: absolute; top: 50%; right: 18px; transform: translateY(-50%); pointer-events: none;">
                                    <i class="fas fa-envelope" style="color: #a0aec0; font-size: 16px;"></i>
                                </div>
                            </div>
                            @error('email')
                                <p style="margin-top: 10px; font-size: 14px; color: #f56565; display: flex; align-items: center;">
                                    <i class="fas fa-exclamation-circle" style="margin-left: 8px;"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" style="display: block; font-size: 15px; font-weight: 600; color: #2d3748; margin-bottom: 10px;">
                                <i class="fas fa-lock" style="margin-left: 10px; color: #667eea;"></i>
                                كلمة المرور
                            </label>
                            <div style="position: relative;">
                                <input id="password"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       style="width: 100%; padding: 18px 85px 18px 20px; border: 2px solid {{ $errors->has('password') ? '#f56565' : '#e2e8f0' }}; border-radius: 15px; font-size: 16px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);"
                                       placeholder="أدخل كلمة المرور"
                                       onfocus="this.style.borderColor='#667eea'; this.style.background='#ffffff'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.15)';"
                                       onblur="this.style.borderColor='#e2e8f0'; this.style.background='#ffffff'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';">
                                <div style="position: absolute; top: 50%; right: 55px; transform: translateY(-50%); pointer-events: none;">
                                    <i class="fas fa-lock" style="color: #a0aec0; font-size: 16px;"></i>
                                </div>
                                <button type="button"
                                        onclick="togglePassword()"
                                        style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #a0aec0; cursor: pointer; padding: 8px; border-radius: 8px; transition: all 0.3s ease; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                                        onmouseover="this.style.color='#667eea'; this.style.background='rgba(102, 126, 234, 0.1)';"
                                        onmouseout="this.style.color='#a0aec0'; this.style.background='none';">
                                    <i id="password-icon" class="fas fa-eye" style="font-size: 16px;"></i>
                                </button>
                            </div>
                            @error('password')
                                <p style="margin-top: 10px; font-size: 14px; color: #f56565; display: flex; align-items: center;">
                                    <i class="fas fa-exclamation-circle" style="margin-left: 8px;"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin: 10px 0;">
                            <div style="display: flex; align-items: center;">
                                <input id="remember_me"
                                       type="checkbox"
                                       name="remember"
                                       style="width: 20px; height: 20px; accent-color: #667eea; border-radius: 6px; margin-left: 12px; cursor: pointer;">
                                <label for="remember_me" style="font-size: 15px; color: #2d3748; font-weight: 500; cursor: pointer; user-select: none;">
                                    تذكرني
                                </label>
                            </div>

                            <div>
                                <a href="#" style="font-size: 15px; color: #667eea; text-decoration: none; font-weight: 600; transition: all 0.3s ease; padding: 5px 10px; border-radius: 8px;"
                                   onmouseover="this.style.color='#5a67d8'; this.style.background='rgba(102, 126, 234, 0.1)';"
                                   onmouseout="this.style.color='#667eea'; this.style.background='transparent';">
                                    نسيت كلمة المرور؟
                                </a>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border: none; border-radius: 15px; font-size: 17px; font-weight: 700; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; justify-content: center; gap: 12px; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4); position: relative; overflow: hidden;"
                                    onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.5)'; this.style.background='linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.4)'; this.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)';">
                                <i class="fas fa-sign-in-alt" style="font-size: 18px;"></i>
                                تسجيل الدخول
                            </button>
                        </div>
                </form>

                    <!-- Demo Accounts Info - Hidden for security -->
                    @if(false)
                    <div style="margin-top: 30px; padding: 20px; background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); border-radius: 15px; border: 1px solid #e2e8f0; display: none;">
                        <h4 style="font-size: 16px; font-weight: 700; color: #2d3748; margin: 0 0 15px 0; text-align: center; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-cog" style="margin-left: 8px; color: #667eea;"></i>
                            حسابات تجريبية
                        </h4>
                        <div style="display: grid; gap: 10px; font-size: 14px; color: #4a5568;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: white; border-radius: 8px;">
                                <span style="font-weight: 600;">السوبر أدمن:</span>
                                <span style="color: #667eea; font-family: monospace;">admin@maxcon.com</span>
                            </div>
                            @if(is_tenant_context())
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px; background: white; border-radius: 8px;">
                                <span style="font-weight: 600;">أدمن المستأجر:</span>
                                <span style="color: #667eea; font-family: monospace;">admin@demo.com</span>
                            </div>
                            @endif
                            <div style="text-align: center; margin-top: 10px; padding: 8px; background: rgba(102, 126, 234, 0.1); border-radius: 8px;">
                                <span style="font-weight: 600; color: #667eea;">كلمة المرور:</span>
                                <span style="font-family: monospace; color: #2d3748;">password123</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Footer -->
                <div style="margin-top: 30px; text-align: center; animation: fadeInUp 0.8s ease-out 0.6s both;">
                    <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin: 0;">
                        © {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
                    </p>
                    <div style="margin-top: 10px; display: flex; justify-content: center; gap: 20px;">
                        <span style="color: rgba(255,255,255,0.6); font-size: 12px;">نسخة 2.0</span>
                        <span style="color: rgba(255,255,255,0.6); font-size: 12px;">•</span>
                        <span style="color: rgba(255,255,255,0.6); font-size: 12px;">تطوير MaxCon</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for interactions -->
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Add floating animation keyframes
        const style = document.createElement('style');
        style.textContent = `
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

            .floating-shapes::before,
            .floating-shapes::after {
                animation: float 15s ease-in-out infinite;
            }

            /* Enhanced input focus effects */
            input:focus {
                outline: none !important;
            }

            /* Custom checkbox styling */
            input[type="checkbox"] {
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                border: 2px solid #e2e8f0;
                background: white;
                transition: all 0.3s ease;
            }

            input[type="checkbox"]:checked {
                background: #667eea;
                border-color: #667eea;
                background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='m13.854 3.646-7.5 7.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6 10.293l7.146-7.147a.5.5 0 0 1 .708.708z'/%3e%3c/svg%3e");
                background-size: 14px;
                background-position: center;
                background-repeat: no-repeat;
            }

            input[type="checkbox"]:hover {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }

            /* Enhanced button effects */
            button[type="submit"]::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s;
            }

            button[type="submit"]:hover::before {
                left: 100%;
            }

            /* Loading animation for submit button */
            .btn-loading {
                position: relative;
                color: transparent !important;
            }

            .btn-loading::after {
                content: '';
                position: absolute;
                width: 20px;
                height: 20px;
                top: 50%;
                left: 50%;
                margin-left: -10px;
                margin-top: -10px;
                border: 2px solid transparent;
                border-top-color: #ffffff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Add form submission loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');
            submitBtn.style.pointerEvents = 'none';
        });

        // Add smooth scroll and entrance animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add entrance animation to form elements
            const elements = document.querySelectorAll('input, button, label');
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
    </script>
</body>
</html>
