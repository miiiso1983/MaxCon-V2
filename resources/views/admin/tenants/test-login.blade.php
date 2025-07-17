@extends('layouts.modern')

@section('page-title', 'اختبار تسجيل الدخول')
@section('page-description', 'صفحة اختبار تسجيل الدخول للمستأجرين')

@section('content')
<div class="content-card">
    <h2 style="color: #2d3748; margin-bottom: 30px; text-align: center;">🔐 اختبار تسجيل الدخول</h2>

    @if(session('success'))
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle" style="margin-left: 8px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle" style="margin-left: 8px;"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Test Login Form -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; padding: 30px; margin-bottom: 30px; color: white;">
        <h3 style="margin-bottom: 20px; text-align: center;">🧪 اختبار تسجيل الدخول</h3>
        
        <form action="{{ route('admin.test-login.attempt') }}" method="POST" style="max-width: 400px; margin: 0 auto;">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">البريد الإلكتروني</label>
                <input type="email" name="email" value="admin@alnoor-pharmacy.com" required
                       style="width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 16px; color: #333;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">كلمة المرور</label>
                <input type="password" name="password" value="AdminPass123!" required
                       style="width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 16px; color: #333;">
            </div>

            <button type="submit" style="width: 100%; background: rgba(255,255,255,0.2); color: white; border: 2px solid white; padding: 12px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                🔐 اختبار تسجيل الدخول
            </button>
        </form>
    </div>

    <!-- Available Users -->
    <div style="background: #f8f9fa; border-radius: 12px; padding: 25px; margin-bottom: 25px;">
        <h3 style="color: #2d3748; margin-bottom: 20px; display: flex; align-items: center;">
            <i class="fas fa-users" style="margin-left: 10px;"></i>
            المستخدمين المتاحين للاختبار
        </h3>

        @php
            $testUsers = \App\Models\User::whereHas('roles', function($query) {
                $query->where('name', 'tenant-admin');
            })->get();
        @endphp

        @if($testUsers->count() > 0)
            <div style="display: grid; gap: 15px;">
                @foreach($testUsers as $testUser)
                    <div style="background: white; border-radius: 8px; padding: 20px; border: 1px solid #e2e8f0;">
                        <div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: center;">
                            <div>
                                <h4 style="margin: 0 0 8px 0; color: #2d3748;">{{ $testUser->name }}</h4>
                                <p style="margin: 0 0 5px 0; color: #718096; font-size: 14px;">
                                    📧 {{ $testUser->email }}
                                </p>
                                <p style="margin: 0; color: #718096; font-size: 14px;">
                                    🏢 مؤسسة #{{ $testUser->tenant_id }} - 
                                    <span style="color: {{ $testUser->is_active ? '#48bb78' : '#f56565' }};">
                                        {{ $testUser->is_active ? 'نشط' : 'معطل' }}
                                    </span>
                                </p>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button onclick="fillLoginForm('{{ $testUser->email }}')" 
                                        style="background: #4299e1; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                    استخدام
                                </button>
                                <form action="{{ route('admin.test-login.attempt') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $testUser->email }}">
                                    <input type="hidden" name="password" value="AdminPass123!">
                                    <button type="submit" style="background: #48bb78; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                        تسجيل دخول
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px; color: #718096;">
                <i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>لا توجد مستخدمين متاحين للاختبار</p>
            </div>
        @endif
    </div>

    <!-- Debug Info -->
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 12px; padding: 20px;">
        <h3 style="color: #856404; margin-bottom: 15px; display: flex; align-items: center;">
            <i class="fas fa-bug" style="margin-left: 8px;"></i>
            معلومات التشخيص
        </h3>
        
        <div style="color: #856404; font-family: monospace; font-size: 14px; line-height: 1.6;">
            <p><strong>Current User:</strong> {{ Auth::check() ? Auth::user()->name . ' (' . Auth::user()->email . ')' : 'Not logged in' }}</p>
            <p><strong>Tenant Context:</strong> {{ tenant() ? 'Tenant ID: ' . tenant()->id : 'No tenant context' }}</p>
            <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
            <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
            <p><strong>Environment:</strong> {{ app()->environment() }}</p>
        </div>
    </div>
</div>

<script>
function fillLoginForm(email) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = 'AdminPass123!';
    
    // Show success message
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #48bb78;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 9999;
        font-weight: 600;
    `;
    toast.textContent = '✅ تم ملء النموذج!';
    document.body.appendChild(toast);
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 2000);
}
</script>
@endsection
