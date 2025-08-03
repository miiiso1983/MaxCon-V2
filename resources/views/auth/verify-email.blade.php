<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني - MaxCon</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- Logo -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">تأكيد البريد الإلكتروني</h1>
            </div>

            <!-- Message -->
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    تم إرسال رابط التأكيد إلى بريدك الإلكتروني. يرجى فحص صندوق الوارد والنقر على الرابط لتأكيد حسابك.
                </p>
                
                @if (session('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <!-- Resend Button -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-700 hover:to-blue-700 transition duration-300">
                        <i class="fas fa-paper-plane ml-2"></i>
                        إعادة إرسال رابط التأكيد
                    </button>
                </form>

                <!-- Back to Dashboard -->
                <a href="{{ route('dashboard') }}" class="block w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition duration-300">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للوحة التحكم
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-red-600 hover:text-red-800 py-2 transition duration-300">
                        <i class="fas fa-sign-out-alt ml-2"></i>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-500 text-sm">
            <p>© 2024 MaxCon - نظام إدارة الصيدليات</p>
        </div>
    </div>
</body>
</html>
