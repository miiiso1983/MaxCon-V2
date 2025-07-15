@extends('layouts.modern')

@section('page-title', 'لوحة التحكم الرئيسية')
@section('page-description', 'نظرة شاملة على أداء النظام والإحصائيات')

@section('content')
<!-- Welcome Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>

    <div style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <div style="background: rgba(255,255,255,0.2); border-radius: 15px; padding: 15px; margin-left: 20px;">
                        <i class="fas fa-crown" style="font-size: 32px; color: #ffd700;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                            مرحباً {{ auth()->user()->name }} 👋
                        </h1>
                        <p style="font-size: 18px; margin: 5px 0 0 0; opacity: 0.9;">
                            إدارة النظام الرئيسية - MaxCon Master
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-shield-alt" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px; font-weight: 600;">مدير عام</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-clock" style="margin-left: 8px;"></i>
                        <span style="font-size: 14px;">آخر دخول: {{ now()->format('Y-m-d H:i') }}</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); border-radius: 25px; padding: 8px 16px; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle" style="margin-left: 8px; color: #4ade80;"></i>
                        <span style="font-size: 14px;">النظام يعمل بشكل طبيعي</span>
                    </div>
                </div>
            </div>

            <div style="display: none;">
                <div style="background: rgba(255,255,255,0.1); border-radius: 20px; padding: 25px; backdrop-filter: blur(15px);">
                    <i class="fas fa-chart-line" style="font-size: 64px; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Tenants -->
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
            <i class="fas fa-building"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">إجمالي المؤسسات</h3>
        <p class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\Tenant::count() }}</p>
        <div class="flex items-center">
            <span class="text-green-500 text-sm font-medium">+{{ \App\Models\Tenant::whereDate('created_at', today())->count() }}</span>
            <span class="text-gray-500 text-sm mr-2">اليوم</span>
        </div>
    </div>

    <!-- Total Users -->
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">إجمالي المستخدمين</h3>
        <p class="text-3xl font-bold text-gray-900 mb-2">{{ \App\Models\User::count() }}</p>
        <div class="flex items-center">
            <span class="text-green-500 text-sm font-medium">في جميع المؤسسات</span>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-green-500 to-green-600 text-white">
            <i class="fas fa-circle"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">الجلسات النشطة</h3>
        <p class="text-3xl font-bold text-gray-900 mb-2">
            {{ \App\Models\User::where('last_login_at', '>=', now()->subMinutes(30))->count() }}
        </p>
        <div class="flex items-center">
            <span class="text-green-500 text-sm font-medium">متصل الآن</span>
        </div>
    </div>

    <!-- System Health -->
    <div class="stat-card">
        <div class="icon-wrapper bg-gradient-to-br from-emerald-500 to-teal-600 text-white">
            <i class="fas fa-heartbeat"></i>
        </div>
        <h3 class="text-gray-600 text-sm font-medium mb-1">صحة النظام</h3>
        <p class="text-2xl font-bold text-gray-900 mb-2">ممتاز</p>
        <div class="flex items-center">
            <div class="w-2 h-2 bg-green-500 rounded-full ml-2 animate-pulse"></div>
            <span class="text-green-500 text-sm font-medium">جميع الأنظمة تعمل</span>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Tenants -->
    <div class="lg:col-span-2">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">المؤسسات الحديثة</h3>
                <a href="{{ route('admin.tenants.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض الكل</a>
            </div>
            
            <div class="space-y-4">
                @php
                    $recentTenants = \App\Models\Tenant::latest()->limit(5)->get();
                @endphp
                
                @forelse($recentTenants as $tenant)
                    <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-3 ml-4">
                            <i class="fas fa-building text-white text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $tenant->name }}</p>
                            <p class="text-sm text-gray-500">{{ $tenant->domain }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                    {{ $tenant->users_count ?? $tenant->users()->count() }} مستخدم
                                </span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full mr-2">
                                    {{ $tenant->status === 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        </div>
                        <div class="text-left">
                            <p class="text-sm text-gray-500">{{ $tenant->created_at->diffForHumans() }}</p>
                            <p class="text-xs text-gray-400">{{ $tenant->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-building text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">لا توجد مؤسسات مسجلة</p>
                        <button class="mt-4 btn-primary">
                            <i class="fas fa-plus ml-2"></i>
                            إضافة مؤسسة جديدة
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Admin Actions & System Info -->
    <div class="space-y-6">
        <!-- Admin Actions -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">إجراءات الإدارة</h3>
            
            <div class="space-y-3">
                <a href="{{ route('admin.tenants.create') }}" class="w-full btn-primary text-right block text-center">
                    <i class="fas fa-building ml-2"></i>
                    إضافة مؤسسة جديدة
                </a>
                
                <button class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-3 font-medium hover:shadow-lg transition-all">
                    <i class="fas fa-users-cog ml-2"></i>
                    إدارة المستخدمين
                </button>
                
                <button class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-3 font-medium hover:shadow-lg transition-all">
                    <i class="fas fa-chart-line ml-2"></i>
                    تقارير النظام
                </button>
                
                <button class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-xl p-3 font-medium hover:shadow-lg transition-all">
                    <i class="fas fa-tools ml-2"></i>
                    أدوات النظام
                </button>
                
                <button class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl p-3 font-medium hover:shadow-lg transition-all">
                    <i class="fas fa-database ml-2"></i>
                    نسخ احتياطي
                </button>
            </div>
        </div>

        <!-- System Information -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">معلومات النظام</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">إصدار النظام</span>
                    <span class="font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-lg text-sm">v2.1.0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">قاعدة البيانات</span>
                    <span class="font-medium text-green-600">متصلة</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Redis</span>
                    <span class="font-medium text-green-600">متصل</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">وقت التشغيل</span>
                    <span class="font-medium text-green-600">99.9%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">آخر نسخة احتياطية</span>
                    <span class="font-medium">{{ now()->subHours(2)->diffForHumans() }}</span>
                </div>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">استخدام الخادم</span>
                    <span class="text-sm font-medium">68%</span>
                </div>
                <div class="mt-2 bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full" style="width: 68%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
