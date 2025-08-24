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

        /* Floating background circles */
        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 8s ease-in-out infinite;
        }

        .floating-circle:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-circle:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 15%;
            animation-delay: -3s;
        }

        .floating-circle:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: -6s;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) scale(1);
                opacity: 0.1;
            }
            50% { 
                transform: translateY(-30px) scale(1.05);
                opacity: 0.2;
            }
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

        /* Main container */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .login-wrapper {
            max-width: 500px;
            width: 100%;
        }

        /* Logo section */
        .logo-section {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out;
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

        .welcome-text {
            color: white;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 500;
        }

        /* Login card */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.25);
            padding: 40px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .login-card::before {
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

        .login-card::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: -30px;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border-radius: 50%;
            opacity: 0.1;
        }

        .card-content {
            position: relative;
            z-index: 2;
        }

        .card-title {
            font-size: 24px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 8px;
            text-align: center;
        }

        .card-subtitle {
            color: #718096;
            font-size: 16px;
            margin-bottom: 30px;
            text-align: center;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 18px 55px 18px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        .form-input.error {
            border-color: #f56565;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            right: 18px;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .error-message {
            margin-top: 10px;
            font-size: 14px;
            color: #f56565;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .remember-checkbox {
            width: 20px;
            height: 20px;
            accent-color: #667eea;
            border-radius: 6px;
            cursor: pointer;
        }

        .remember-label {
            font-size: 15px;
            color: #2d3748;
            font-weight: 500;
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            font-size: 15px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 8px;
        }

        .forgot-link:hover {
            color: #5a67d8;
            background: rgba(102, 126, 234, 0.1);
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border: none;
            border-radius: 15px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        /* Alert styles */
        .alert {
            margin-bottom: 25px;
            padding: 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .alert-error {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .login-container {
                padding: 15px;
            }
            
            .login-card {
                padding: 30px 25px;
            }
            
            .welcome-text {
                font-size: 24px;
            }
            
            .card-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating background circles -->
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>

    <div class="login-container">
        <div class="login-wrapper">
            <!-- Logo and Welcome Section -->
            <div class="logo-section">
                <div style="display:flex; justify-content:center; margin-bottom: 16px;">
                    <img src="{{ asset('images/maxcon-logo.svg') }}" alt="MaxCon Logo" style="max-width: 200px; width: 60%; height: auto;" />
                </div>
                <h1 class="welcome-text">مرحباً بك في MaxCon</h1>
                <p class="welcome-subtitle">نظام إدارة الأعمال المتكامل والمتطور</p>
            </div>

            <!-- Login Card -->
            <div class="login-card">
                <div class="card-content">
                    <h2 class="card-title">تسجيل الدخول 🔐</h2>
                    <p class="card-subtitle">أدخل بياناتك للوصول إلى حسابك</p>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle" style="font-size: 18px;"></i>
                            <span style="font-weight: 600;">{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-error">
                            <div>
                                <i class="fas fa-exclamation-triangle" style="font-size: 18px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 10px;">
                                    يرجى تصحيح الأخطاء التالية:
                                </h3>
                                <ul style="margin: 0; padding: 0; list-style: none;">
                                    @foreach ($errors->all() as $error)
                                        <li style="display: flex; align-items: center; margin-bottom: 5px; gap: 10px;">
                                            <i class="fas fa-circle" style="font-size: 6px; opacity: 0.8;"></i>
                                            <span style="font-size: 14px;">{{ $error }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope" style="margin-left: 10px; color: #667eea;"></i>
                                البريد الإلكتروني
                            </label>
                            <div class="input-wrapper">
                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                                       placeholder="أدخل بريدك الإلكتروني">
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock" style="margin-left: 10px; color: #667eea;"></i>
                                كلمة المرور
                            </label>
                            <div class="input-wrapper">
                                <input id="password"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                                       placeholder="أدخل كلمة المرور">
                                <i class="fas fa-lock input-icon"></i>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i id="password-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="remember-forgot">
                            <div class="remember-me">
                                <input id="remember_me" type="checkbox" name="remember" class="remember-checkbox">
                                <label for="remember_me" class="remember-label">تذكرني</label>
                            </div>
                            <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-sign-in-alt" style="font-size: 18px;"></i>
                            تسجيل الدخول
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>
